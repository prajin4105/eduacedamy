<?php

namespace App\Filament\Admin\Resources\InstructorApplications\Pages;

use App\Filament\Admin\Resources\InstructorApplications\InstructorApplicationResource;
use App\Models\InstructorApplication;
use App\Notifications\InstructorApprovedNotification;
use App\Notifications\InstructorRejectedNotification;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewInstructorApplication extends ViewRecord
{
    protected static string $resource = InstructorApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Instructor Application')
                ->modalDescription('Are you sure you want to approve this instructor application? The user will be granted instructor role.')
                ->action(function (InstructorApplication $record) {
                    $record->update([
                        'status' => 'approved',
                        'reviewed_at' => now(),
                        'reviewed_by' => auth()->id(),
                    ]);

                    // Update user role to instructor
                    $user = $record->user;
                    $user->role = 'instructor';
                    $user->save();

                    // Send notification
                    $user->notify(new InstructorApprovedNotification());

                    Notification::make()
                        ->title('Application Approved')
                        ->success()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('index'));
                })
                ->visible(fn (InstructorApplication $record) => $record->status === 'pending'),

            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Rejection Reason')
                        ->required()
                        ->maxLength(1000)
                        ->rows(4),
                ])
                ->requiresConfirmation()
                ->modalHeading('Reject Instructor Application')
                ->modalDescription('Please provide a reason for rejection. The applicant will be notified.')
                ->action(function (InstructorApplication $record, array $data) {
                    $record->update([
                        'status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                        'reviewed_at' => now(),
                        'reviewed_by' => auth()->id(),
                    ]);

                    // Send notification
                    $record->user->notify(new InstructorRejectedNotification($record));

                    Notification::make()
                        ->title('Application Rejected')
                        ->success()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('index'));
                })
                ->visible(fn (InstructorApplication $record) => $record->status === 'pending'),

            Action::make('download_document')
                ->label('Download Document')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (InstructorApplication $record) => 
                    $record->document_path 
                        ? route('instructor-applications.download', $record->id)
                        : null
                )
                ->openUrlInNewTab()
                ->visible(fn (InstructorApplication $record) => !empty($record->document_path)),
        ];
    }
}

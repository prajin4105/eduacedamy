<?php

namespace App\Filament\Admin\Resources\Courses\Pages;

use App\Filament\Admin\Resources\Courses\CourseResource;
use App\Models\Course;
use App\Notifications\CourseApprovedNotification;
use App\Notifications\CourseRejectedNotification;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve Course')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Course')
                ->modalDescription('Are you sure you want to approve this course? It will be published and visible to students.')
                ->action(function (Course $record) {
                    $record->update([
                        'approval_status' => 'approved',
                        'is_published' => true,
                        'approved_at' => now(),
                        'approved_by' => auth()->id(),
                        'published_at' => now(),
                    ]);

                    // Send notification to instructor
                    $record->instructor->notify(new CourseApprovedNotification($record));

                    Notification::make()
                        ->title('Course Approved')
                        ->success()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('index'));
                })
                ->visible(fn (Course $record) => $record->approval_status !== 'approved'),

            Action::make('reject')
                ->label('Reject Course')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Textarea::make('approval_reason')
                        ->label('Rejection Reason')
                        ->required()
                        ->maxLength(1000)
                        ->rows(4),
                ])
                ->requiresConfirmation()
                ->modalHeading('Reject Course')
                ->modalDescription('Please provide a reason for rejection. The instructor will be notified.')
                ->action(function (Course $record, array $data) {
                    $record->update([
                        'approval_status' => 'rejected',
                        'approval_reason' => $data['approval_reason'],
                        'is_published' => false,
                        'approved_at' => now(),
                        'approved_by' => auth()->id(),
                    ]);

                    // Send notification to instructor
                    $record->instructor->notify(new CourseRejectedNotification($record));

                    Notification::make()
                        ->title('Course Rejected')
                        ->success()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('index'));
                })
                ->visible(fn (Course $record) => $record->approval_status !== 'rejected'),

            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}


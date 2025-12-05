<?php

namespace App\Filament\Admin\Resources\Courses\Pages;

use App\Filament\Admin\Resources\Courses\PendingCourseResource;
use App\Models\Course;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Forms;

class ViewPendingCourse extends ViewRecord
{
    protected static string $resource = PendingCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
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
                    $record->instructor->notify(new \App\Notifications\CourseApprovedNotification($record));

                    \Filament\Notifications\Notification::make()
                        ->title('Course Approved')
                        ->body("'{$record->title}' has been approved and published.")
                        ->success()
                        ->send();

                    return redirect()->to(PendingCourseResource::getUrl('index'));
                })
                ->visible(fn (Course $record) => $record->approval_status === 'pending'),
            Actions\Action::make('reject')
                ->label('Reject Course')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Forms\Components\Textarea::make('approval_reason')
                        ->label('Rejection Reason')
                        ->required()
                        ->maxLength(1000)
                        ->rows(4)
                        ->placeholder('Please explain why this course is being rejected...'),
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
                    $record->instructor->notify(new \App\Notifications\CourseRejectedNotification($record));

                    \Filament\Notifications\Notification::make()
                        ->title('Course Rejected')
                        ->body("'{$record->title}' has been rejected.")
                        ->success()
                        ->send();

                    return redirect()->to(PendingCourseResource::getUrl('index'));
                })
                ->visible(fn (Course $record) => $record->approval_status === 'pending'),
        ];
    }

    public function getTitle(): string
    {
        return 'Review Pending Course';
    }
}

<?php

namespace App\Filament\Instructor\Resources\CourseResource\Pages;

use App\Filament\Instructor\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function afterCreate(): void
    {
        // Any logic to run after course creation
    }
    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
{
    return \Filament\Notifications\Notification::make()
        ->title('Course created successfully')
        ->body('Your shiny new course is now waiting for approval.')
        ->success();
}

}

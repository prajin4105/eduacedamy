<?php

namespace App\Filament\Admin\Resources\InstructorApplications\Pages;

use App\Filament\Admin\Resources\InstructorApplications\InstructorApplicationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInstructorApplication extends EditRecord
{
    protected static string $resource = InstructorApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

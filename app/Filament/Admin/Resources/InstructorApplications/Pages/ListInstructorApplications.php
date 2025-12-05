<?php

namespace App\Filament\Admin\Resources\InstructorApplications\Pages;

use App\Filament\Admin\Resources\InstructorApplications\InstructorApplicationResource;
use Filament\Resources\Pages\ListRecords;

class ListInstructorApplications extends ListRecords
{
    protected static string $resource = InstructorApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Remove create action - applications are created by users
        ];
    }
}

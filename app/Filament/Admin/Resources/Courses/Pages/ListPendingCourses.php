<?php

namespace App\Filament\Admin\Resources\Courses\Pages;

use App\Filament\Admin\Resources\Courses\PendingCourseResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListPendingCourses extends ListRecords
{
    protected static string $resource = PendingCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Pending Courses';
    }

    public function getHeading(): string
    {
        return 'Pending Courses for Approval';
    }

    public function getSubheading(): ?string
    {
        $count = $this->getFilteredTableQuery()->count();
        return $count > 0 ? "{$count} course(s) waiting for approval" : null;
    }
}

<?php

namespace App\Filament\Instructor\Resources\UserTests\Pages;

use App\Filament\Instructor\Resources\UserTests\UserTestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserTests extends ListRecords
{
    protected static string $resource = UserTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

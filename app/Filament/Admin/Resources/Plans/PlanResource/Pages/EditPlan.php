<?php

namespace App\Filament\Admin\Resources\Plans\PlanResource\Pages;

use App\Filament\Admin\Resources\Plans\PlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlan extends EditRecord
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}



<?php

namespace App\Filament\Admin\Resources\Subscriptions\SubscriptionResource\Pages;

use App\Filament\Admin\Resources\Subscriptions\SubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubscription extends EditRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}



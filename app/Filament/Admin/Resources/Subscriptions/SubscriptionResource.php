<?php

namespace App\Filament\Admin\Resources\Subscriptions;

use App\Filament\Admin\Resources\Subscriptions\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    // protected static ?string $navigationGroup = 'Catalog';
    // protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Subscription';
    protected static ?string $pluralModelLabel = 'Subscriptions';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('plan.name')->label('Plan')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('starts_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('ends_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'canceled' => 'Canceled',
                    'expired' => 'Expired',
                ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}



<?php

namespace App\Filament\Admin\Resources\Plans;

use App\Filament\Admin\Resources\Plans\PlanResource\Pages;
use App\Filament\Admin\Resources\Plans\PlanResource\RelationManagers\CoursesRelationManager;
use App\Models\Plan;
use Filament\Schemas\Components\Forms\Components\TextInput;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;

use Filament\Tables\Table;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Forms;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    // protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    // protected static string|BackedEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 40;

    protected static ?string $modelLabel = 'Plan';

    protected static ?string $pluralModelLabel = 'Plans';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
               Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                Forms\Components\Textarea::make('description')->rows(3),
               Forms\Components\TextInput::make('price')->numeric()->prefix('$')->required(),
                Forms\Components\Select::make('currency')
                    ->options([
                        'USD' => 'USD ($)',
                        'EUR' => 'EUR (€)',
                        'INR' => 'INR (₹)',
                    ])->default('USD')->required(),
                Forms\Components\Select::make('interval')
                    ->options([
                        'day' => 'Day',
                        'week' => 'Week',
                        'month' => 'Month',
                        'year' => 'Year',
                    ])->default('month')->required(),
                Forms\Components\TextInput::make('interval_count')->numeric()->default(1)->minValue(1)->required(),
                Forms\Components\Toggle::make('is_active')->default(true),
                Forms\Components\Select::make('courses')
                    ->label('Included Courses')
                    ->relationship('courses', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->helperText('Select which courses are accessible with this plan.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->toggleable(),
                TextColumn::make('price')->money(fn($record) => $record->currency)->sortable(),
                TextColumn::make('interval')->label('Every')->formatStateUsing(fn($state, $record) => $record->interval_count . ' ' . $state . ($record->interval_count > 1 ? 's' : '')),
                IconColumn::make('is_active')->boolean()->label('Active'),
                TextColumn::make('courses_count')->counts('courses')->label('Courses'),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
    
}

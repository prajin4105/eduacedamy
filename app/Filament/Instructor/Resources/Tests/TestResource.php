<?php

namespace App\Filament\Instructor\Resources\Tests;

use App\Filament\Instructor\Resources\Tests\Pages\CreateTest;
use App\Filament\Instructor\Resources\Tests\Pages\EditTest;
use App\Filament\Instructor\Resources\Tests\Pages\ListTests;
use App\Models\Test;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Admin\Resources\TestResource\RelationManagers\QuestionsRelationManager;
use UnitEnum;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('course_id')
                ->relationship('course', 'title', fn ($query) =>
                    $query->where('instructor_id', auth()->id())
                )
                ->required(),

            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('title')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->since(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTests::route('/'),
            'create' => CreateTest::route('/create'),
            'edit' => EditTest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('course', function ($query) {
                $query->where('instructor_id', auth()->id());
            });
    }
}

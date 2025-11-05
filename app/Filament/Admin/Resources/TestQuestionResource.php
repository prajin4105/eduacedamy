<?php

namespace App\Filament\Admin\Resources;

use App\Models\TestQuestion;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Filament\Admin\Resources\TestQuestionResource\Pages;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Actions\Action;

class TestQuestionResource extends Resource
{
    protected static ?string $model = TestQuestion::class;
    protected static \UnitEnum|string|null $navigationGroup = 'Courses';
    protected static ?string $navigationLabel = 'Test Questions';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('test_id')
                ->relationship('test', 'title')
                ->required()
                ->searchable()
                ->preload()
                ->columnSpanFull(),

            Repeater::make('questions')
                ->schema([
                    Forms\Components\Textarea::make('question_text')
                        ->label('Question')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('option_a')
                        ->label('Option A')
                        ->required(),

                    Forms\Components\TextInput::make('option_b')
                        ->label('Option B')
                        ->required(),

                    Forms\Components\TextInput::make('option_c')
                        ->label('Option C')
                        ->required(),

                    Forms\Components\TextInput::make('option_d')
                        ->label('Option D')
                        ->required(),

                    Forms\Components\Select::make('correct_option')
                        ->label('Correct Answer')
                        ->options([
                            'a' => 'Option A',
                            'b' => 'Option B',
                            'c' => 'Option C',
                            'd' => 'Option D',
                        ])
                        ->required()
                        ->columnSpanFull(),
                ])
                ->grid(2)
                ->defaultItems(1)
                ->addActionLabel('Add Another Question')
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? 'New Question')
                ->columnSpanFull()
                ->minItems(1)
                ->reorderable()
                ->cloneable()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID'),
                Tables\Columns\TextColumn::make('test.title')
                    ->label('Test')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Question')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\BadgeColumn::make('correct_option')
                    ->label('Answer')
                    ->colors([
                        'success' => fn ($state) => in_array($state, ['a', 'b', 'c', 'd']),
                    ])
                    ->formatStateUsing(fn (string $state): string => 'Option ' . strtoupper($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('test')
                    ->relationship('test', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('test_id', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestQuestions::route('/'),
            'create' => Pages\CreateTestQuestion::route('/create'),
            'edit' => Pages\EditTestQuestion::route('/{record}/edit'),
            'view-test' => Pages\ViewTestQuestions::route('/test/{test}'),
        ];
    }
}

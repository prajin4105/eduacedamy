<?php
namespace App\Filament\Admin\Resources\TestResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\CreateAction;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'question_text';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Textarea::make('question_text')
                    ->label('Question')
                    ->required(),
                Forms\Components\TextInput::make('option_a')->required(),
                Forms\Components\TextInput::make('option_b')->required(),
                Forms\Components\TextInput::make('option_c')->required(),
                Forms\Components\TextInput::make('option_d')->required(),
                Forms\Components\Select::make('correct_option')
                    ->options([
                        'A' => 'Option A',
                        'B' => 'Option B',
                        'C' => 'Option C',
                        'D' => 'Option D',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_text')->limit(80),
                Tables\Columns\TextColumn::make('correct_option'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('id', 'asc');
    }
}

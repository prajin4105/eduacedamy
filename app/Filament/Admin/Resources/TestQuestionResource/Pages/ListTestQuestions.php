<?php

namespace App\Filament\Admin\Resources\TestQuestionResource\Pages;

use App\Filament\Admin\Resources\TestQuestionResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Test;
use Filament\Actions ;


class ListTestQuestions extends ListRecords
{
    protected static string $resource = TestQuestionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->query(Test::query()->withCount('questions'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Test ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Test Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('questions_count')
                    ->label('Total Questions')
                    ->colors([
                        'danger' => 0,
                        'warning' => fn ($state) => $state > 0 && $state < 5,
                        'success' => fn ($state) => $state >= 5,
                    ]),
                Tables\Columns\TextColumn::make('passing_score')
                    ->label('Passing Score')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add course filter instead of test filter
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Actions\Action::make('view_questions')
                    ->label('View Questions')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Test $record): string => static::$resource::getUrl('view-test', ['test' => $record->id]))
                    ->color('primary'),
                Actions\Action::make('add_question')
                    ->label('Add Question')
                    ->icon('heroicon-o-plus')
                    ->url(fn (Test $record): string => static::$resource::getUrl('create') . '?test_id=' . $record->id)
                    ->color('success'),
            ])
            ->bulkActions([
                // You can add bulk actions here if needed
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('No tests found')
            ->emptyStateDescription('Create a test first, then add questions to it.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Optionally add a button to create tests from the Test resource
        ];
    }
}

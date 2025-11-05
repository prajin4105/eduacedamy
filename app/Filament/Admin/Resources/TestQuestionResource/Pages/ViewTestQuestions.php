<?php

namespace App\Filament\Admin\Resources\TestQuestionResource\Pages;

use App\Filament\Admin\Resources\TestQuestionResource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use App\Models\Test;
use App\Models\TestQuestion;
use Filament\Actions;
use Filament\Actions\Action;

class ViewTestQuestions extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = TestQuestionResource::class;

    // non-static, correct declaration
    protected string $view = 'filament.admin.resources.test-question-resource.pages.view-test-questions';

    public Test $test;

    public function mount($test): void
    {
        $this->test = Test::with('questions')->findOrFail($test);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(TestQuestion::query()->where('test_id', $this->test->id))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Q#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Question')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('option_a')
                    ->label('Option A')
                    ->limit(30),
                Tables\Columns\TextColumn::make('option_b')
                    ->label('Option B')
                    ->limit(30),
                Tables\Columns\TextColumn::make('option_c')
                    ->label('Option C')
                    ->limit(30),
                Tables\Columns\TextColumn::make('option_d')
                    ->label('Option D')
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('correct_option')
                    ->label('Correct Answer')
                    ->colors([
                        'success' => fn ($state) => in_array($state, ['a', 'b', 'c', 'd']),
                    ])
                    ->formatStateUsing(fn (string $state): string => 'Option ' . strtoupper($state)),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->label('Edit')
                    // redirect to your custom ViewTestQuestions page
                    ->url(fn (TestQuestion $record): string =>
                        TestQuestionResource::getUrl('view-questions', ['test' => $record->test_id])
                    ),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                Actions\Action::make('add_question')
                    ->label('Add New Question')
                    ->icon('heroicon-o-plus')
                    ->url(fn (): string => static::$resource::getUrl('create') . '?test_id=' . $this->test->id)
                    ->color('success'),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Tests')
                ->url(static::$resource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    public function getTitle(): string
    {
        return $this->test->title . ' - Questions';
    }

    public function getHeading(): string
    {
        return $this->test->title;
    }

    public function getSubheading(): string
    {
        return 'Total Questions: ' . $this->test->questions->count() . ' | Passing Score: ' . $this->test->passing_score . '%';
    }
}

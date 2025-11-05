<?php

namespace App\Filament\Admin\Resources\TestQuestionResource\Pages;

use App\Filament\Admin\Resources\TestQuestionResource;
use App\Models\TestQuestion;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditTestQuestion extends EditRecord
{
    protected static string $resource = TestQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Test Questions')
                ->url(fn (): string => static::getResource()::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            \Filament\Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    /**
     * Fetch all questions sharing the same test_id.
     */
    public function getAllQuestionsProperty()
    {
        return TestQuestion::where('test_id', $this->record->test_id)
            ->orderBy('id')
            ->get();
    }

    /**
     * Filament 4 uses getContent() for extra output inside the page body.
     */
    public function getContent(): ?string
    {
        $questions = $this->allQuestions;

        if ($questions->isEmpty()) {
            return '<div class="text-gray-600 font-medium">No other questions in this test.</div>';
        }

        $html = '<div class="space-y-3">';
        $html .= '<h2 class="text-lg font-bold">All Questions in this Test</h2>';
        $html .= '<div class="border border-gray-200 rounded-lg divide-y">';

        foreach ($questions as $q) {
            $html .= '
                <div class="p-3">
                    <div class="font-semibold">#' . e($q->id) . ' ' . e($q->question) . '</div>
                    <div class="text-sm text-gray-600">Correct Answer: ' . e($q->correct_answer) . '</div>
                </div>
            ';
        }

        $html .= '</div></div>';

        // Filament will render this *above* the edit form by default
        return $html;
    }
}

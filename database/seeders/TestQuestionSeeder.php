<?php

namespace Database\Seeders;

use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Database\Seeder;

class TestQuestionSeeder extends Seeder
{
    private array $questionTemplates = [
        [
            'templates' => [
                'What is the primary purpose of [concept]?',
                'Which of the following best describes [concept]?',
                'What is the correct syntax for [code_snippet]?',
                'Which [language] keyword is used for [purpose]?',
                'What is the output of the following code? [code_block]',
                'Which of these is NOT a valid [concept]?',
                'What does [acronym] stand for?',
                'Which method would you use to [action]?',
                'What is the time complexity of [algorithm]?',
                'Which design pattern is best suited for [situation]?',
            ],
            'type' => 'multiple_choice'
        ],
        [
            'templates' => [
                'True or False: [Statement]',
                'Is the following statement correct? [Statement]',
                '[Statement] is true.',
                'Is [concept] the same as [similar_concept]?',
                'Does [language] support [feature]?',
            ],
            'type' => 'true_false'
        ],
        [
            'templates' => [
                'What will be the output of this code? [code_block]',
                'Which code snippet correctly implements [functionality]?',
                'What is wrong with this code? [code_block]',
                'How would you optimize this code? [code_block]',
                'Which code follows [best_practice]?',
            ],
            'type' => 'code'
        ],
    ];

    private array $concepts = [
        'inheritance','polymorphism','encapsulation','abstraction','recursion',
        'asynchronous programming','REST API','MVC pattern','dependency injection',
        'unit testing','SQL injection','responsive design','state management',
        'virtual DOM','garbage collection','event loop','hoisting','closure'
    ];

    private array $languages = [
        'JavaScript','Python','Java','C#','PHP','Ruby','Go','TypeScript',
        'Swift','Kotlin','Rust','C++','C','Dart','Scala','R'
    ];

    private array $codeSnippets = [
        'a for loop','a while loop','an if-else statement','a function declaration',
        'a class definition','a try-catch block','an array method','a promise',
        'an async function','a database query','a regex expression','a sorting algorithm'
    ];

    public function run(): void
    {
        $tests = Test::all();

        foreach ($tests as $test) {
            $questionCount = $test->title === 'Final Exam' ? 20 : 10;

            for ($i = 1; $i <= $questionCount; $i++) {
                $this->createQuestion($test, $i);
            }
        }
    }

    private function createQuestion(Test $test, int $num): void
    {
        $type = $this->getQuestionType($test);
        $template = $this->getTemplate($type);
        $text = $this->fillPlaceholders($template);

        // Generate 4 options
        [$optionA, $optionB, $optionC, $optionD, $correctLetter] =
            $this->generateOptions($type, $text);

        TestQuestion::create([
            'test_id' => $test->id,
            'question_text' => $text,
            'option_a' => $optionA,
            'option_b' => $optionB,
            'option_c' => $optionC,
            'option_d' => $optionD,
            'correct_option' => $correctLetter,
            'created_at' => $test->created_at->copy()->addMinutes($num * 3),
            'updated_at' => now(),
        ]);
    }

    private function getQuestionType(Test $test): string
    {
        $weights = str_contains(strtolower($test->title), 'final')
            ? ['multiple_choice' => 50, 'true_false' => 10, 'code' => 40]
            : ['multiple_choice' => 60, 'true_false' => 20, 'code' => 20];

        $rand = mt_rand(1, array_sum($weights));
        $total = 0;

        foreach ($weights as $type => $weight) {
            $total += $weight;
            if ($rand <= $total) return $type;
        }

        return 'multiple_choice';
    }

    private function getTemplate(string $type): string
    {
        foreach ($this->questionTemplates as $t) {
            if ($t['type'] === $type) {
                return $t['templates'][array_rand($t['templates'])];
            }
        }
        return $this->questionTemplates[0]['templates'][0];
    }

    private function fillPlaceholders(string $t): string
    {
        $repl = [
            '[concept]' => $this->concepts[array_rand($this->concepts)],
            '[language]' => $this->languages[array_rand($this->languages)],
            '[code_snippet]' => $this->codeSnippets[array_rand($this->codeSnippets)],
            '[acronym]' => $this->randomAcronym(),
            '[Statement]' => $this->randomStatement(),
            '[code_block]' => $this->randomCode(),
            '[purpose]' => $this->randomPurpose(),
            '[action]' => $this->randomAction(),
            '[algorithm]' => $this->randomAlgorithm(),
            '[situation]' => $this->randomSituation(),
            '[similar_concept]' => $this->concepts[array_rand($this->concepts)],
            '[feature]' => $this->randomFeature(),
            '[functionality]' => $this->randomFunctionality(),
            '[best_practice]' => $this->randomBestPractice(),
        ];

        return str_replace(array_keys($repl), array_values($repl), $t);
    }

    private function generateOptions(string $type, string $question): array
    {
        $correct = $this->generateCorrect($question);

        $incorrects = [];
        while (count($incorrects) < 3) {
            $wrong = $this->generateIncorrect($correct);
            if (!in_array($wrong, $incorrects)) $incorrects[] = $wrong;
        }

        $all = [$correct, ...$incorrects];
        shuffle($all);

        $correctLetter = ['A','B','C','D'][array_search($correct, $all)];

        return [
            $all[0],
            $all[1],
            $all[2],
            $all[3],
            $correctLetter
        ];
    }

    private function generateCorrect(string $q): string
    {
        if (str_contains($q, 'output')) return '42';
        if (str_contains($q, 'time complexity')) {
            return ['O(1)','O(log n)','O(n)','O(n log n)','O(n²)'][array_rand([0,1,2,3,4])];
        }

        return 'Correct answer for this question';
    }

    private function generateIncorrect(string $correct): string
    {
        $bad = [
            'Incorrect option',
            'Wrong answer',
            'Not valid',
            'Makes no sense',
            'Completely wrong',
            'Invalid choice'
        ];

        $opt = $bad[array_rand($bad)];
        return $opt === $correct ? $opt.' extra' : $opt;
    }

    private function randomAcronym() { return 'API'; }
    private function randomStatement() { return 'JavaScript is statically typed'; }
    private function randomCode() { return 'console.log(2 + 2);'; }
    private function randomPurpose() { return 'declare a variable'; }
    private function randomAction() { return 'sort an array'; }
    private function randomAlgorithm() { return 'binary search'; }
    private function randomSituation() { return 'when enforcing single-instance behavior'; }
    private function randomFeature() { return 'pattern matching'; }
    private function randomFunctionality() { return 'a REST API'; }
    private function randomBestPractice() { return 'DRY principle'; }
}

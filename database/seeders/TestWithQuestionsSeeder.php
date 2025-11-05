<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestWithQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Insert only ONE Test
        $testId = DB::table('tests')->insertGetId([
            'course_id' => 2,
            'title' => 'Programming Fundamentals Quiz',
            'description' => 'Comprehensive test covering programming basics, data structures, and OOP concepts.',
            'passing_score' => 75,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Insert 15 Questions for this single test
        $questions = [
            [
                'test_id' => $testId,
                'question_text' => 'What is a variable in programming?',
                'option_a' => 'A fixed value that cannot be changed',
                'option_b' => 'A container for storing data values',
                'option_c' => 'A type of loop',
                'option_d' => 'A function that returns multiple values',
                'correct_option' => 'b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'Which of the following is NOT a primitive data type?',
                'option_a' => 'Integer',
                'option_b' => 'String',
                'option_c' => 'Array',
                'option_d' => 'Boolean',
                'correct_option' => 'c',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'What does the "==" operator do in most programming languages?',
                'option_a' => 'Assigns a value to a variable',
                'option_b' => 'Compares two values for equality',
                'option_c' => 'Multiplies two numbers',
                'option_d' => 'Divides two numbers',
                'correct_option' => 'b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'Which keyword is used to define a function in Python?',
                'option_a' => 'function',
                'option_b' => 'def',
                'option_c' => 'func',
                'option_d' => 'define',
                'correct_option' => 'b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'What is the purpose of comments in code?',
                'option_a' => 'To execute additional code',
                'option_b' => 'To make the code run faster',
                'option_c' => 'To explain code and improve readability',
                'option_d' => 'To store data',
                'correct_option' => 'c',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'What is the time complexity of accessing an element in an array by index?',
                'option_a' => 'O(n)',
                'option_b' => 'O(log n)',
                'option_c' => 'O(1)',
                'option_d' => 'O(n²)',
                'correct_option' => 'c',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'Which data structure follows the LIFO (Last In First Out) principle?',
                'option_a' => 'Queue',
                'option_b' => 'Stack',
                'option_c' => 'Array',
                'option_d' => 'Linked List',
                'correct_option' => 'b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'In a singly linked list, what does each node contain?',
                'option_a' => 'Only data',
                'option_b' => 'Only a pointer to the next node',
                'option_c' => 'Data and a pointer to the next node',
                'option_d' => 'Data and pointers to both previous and next nodes',
                'correct_option' => 'c',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'Which operation is NOT typically performed on a queue?',
                'option_a' => 'Enqueue',
                'option_b' => 'Dequeue',
                'option_c' => 'Push',
                'option_d' => 'Peek',
                'correct_option' => 'c',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'What is the main advantage of a linked list over an array?',
                'option_a' => 'Faster access time',
                'option_b' => 'Dynamic size allocation',
                'option_c' => 'Better cache performance',
                'option_d' => 'Less memory usage',
                'correct_option' => 'b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'What is encapsulation in OOP?',
                'option_a' => 'Creating multiple instances of a class',
                'option_b' => 'Bundling data and methods that operate on that data within a single unit',
                'option_c' => 'Inheriting properties from a parent class',
                'option_d' => 'Overriding methods in a subclass',
                'correct_option' => 'b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'Which keyword is used to inherit a class in Java?',
                'option_a' => 'inherits',
                'option_b' => 'extends',
                'option_c' => 'implements',
                'option_d' => 'super',
                'correct_option' => 'b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'What is polymorphism?',
                'option_a' => 'Having multiple constructors in a class',
                'option_b' => 'The ability of different objects to respond to the same message in different ways',
                'option_c' => 'Creating multiple classes with the same name',
                'option_d' => 'Using multiple inheritance',
                'correct_option' => 'b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'What is a constructor in OOP?',
                'option_a' => 'A method that destroys an object',
                'option_b' => 'A special method called when an object is instantiated',
                'option_c' => 'A method that returns multiple values',
                'option_d' => 'A method that cannot be overridden',
                'correct_option' => 'b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'test_id' => $testId,
                'question_text' => 'Which access modifier makes a member accessible only within the same class?',
                'option_a' => 'public',
                'option_b' => 'protected',
                'option_c' => 'private',
                'option_d' => 'internal',
                'correct_option' => 'c',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('test_questions')->insert($questions);

        $this->command->info('Successfully created 1 test with 15 questions for course_id 2');
    }
}

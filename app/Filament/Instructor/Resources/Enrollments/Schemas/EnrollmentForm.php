<?php

namespace App\Filament\Instructor\Resources\Enrollments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Student')
                    ->options(User::where('role', 'student')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('course_id')
                    ->label('Course')
                    ->options(Course::where('instructor_id', Auth::id())->where('is_published', true)->pluck('title', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('amount_paid')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('completed')
                    ->required(),

                DateTimePicker::make('enrolled_at')
                    ->label('Enrollment Date')
                    ->default(now()),
            ]);
    }
}

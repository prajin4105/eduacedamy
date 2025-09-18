<?php

namespace App\Filament\Admin\Resources\Enrollments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;


use App\Models\User;
use App\Models\Course;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
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
                    ->options(Course::where('is_published', true)->pluck('title', 'id'))
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

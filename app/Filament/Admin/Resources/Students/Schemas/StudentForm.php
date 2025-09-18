<?php

namespace App\Filament\Admin\Resources\Students\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\DateTimePicker;
use Illuminate\Support\Facades\Hash;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                    
                Select::make('role')
                    ->options([
                        'student' => 'Student',
                        'instructor' => 'Instructor',
                        'admin' => 'Admin',
                    ])
                    ->default('student')
                    ->required(),
                    
                DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At'),
            ]);
    }
}

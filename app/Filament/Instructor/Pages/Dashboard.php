<?php

namespace App\Filament\Instructor\Pages;

use Filament\Pages\Dashboard as BasePage;
use BackedEnum; // optional, only needed if you plan to use enums

class Dashboard extends BasePage
{
    protected static string $routePath = 'dashboard';
    
    // protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'Dashboard';
    
    protected static ?int $navigationSort = -2;
    
    protected function getHeaderWidgets(): array
    {
        return [];
    }
}

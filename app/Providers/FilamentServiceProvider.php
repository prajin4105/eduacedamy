<?php

namespace App\Providers;

use Filament\Panel;
use Filament\FilamentServiceProvider as BaseServiceProvider;

class FilamentServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        // Register a default panel
        $this->registerPanels();
    }

    protected function registerPanels(): void
    {
        Panel::make('admin')   // Unique panel ID
            ->default();       // Mark as default
    }
}

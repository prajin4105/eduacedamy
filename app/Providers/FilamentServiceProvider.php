<?php

namespace App\Providers;

use Filament\Panel;
use Filament\FilamentServiceProvider as BaseServiceProvider;

class FilamentServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        // Register a default panel
        // Panels are provided via dedicated PanelProviders.
    }

    protected function registerPanels(): void
    {
        // No-op. Using PanelProviders (AdminPanelProvider, InstructorPanelProvider).
    }
}

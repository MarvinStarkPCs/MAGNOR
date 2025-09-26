<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home'; // ícono en el menú
    protected static string $view = 'filament.pages.dashboard';   // usa tu vista
    protected static ?string $title = 'Dashboard';                // título arriba

    public static function shouldRegisterNavigation(): bool
    {
        return true; // asegúrate de que salga en el menú lateral
    }
}

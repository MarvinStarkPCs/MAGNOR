<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?string $navigationLabel = 'Inicio';

    protected static ?int $navigationSort = -1;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}

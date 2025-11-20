<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GestionExtras extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.gestion-extras';
    protected static ?string $title = 'Gestión de Extras';
    protected static ?string $navigationLabel = 'Extras';
    protected static ?string $navigationGroup = 'Administración';

    protected static ?int $navigationSort = 2;
}

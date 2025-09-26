<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GestionExtras extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack'; // ícono
    protected static string $view = 'filament.pages.gestion-extras';
    protected static ?string $title = 'Gestión de Extras'; // título en el menú
    protected static ?string $navigationGroup = 'Administración'; // opcional, agrupa en sidebar
}

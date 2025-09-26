<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.user-profile';

    // 🔹 ocultar en el menú lateral
    protected static bool $shouldRegisterNavigation = false;

    public $name;
    public $email;

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('Nombre')->required(),
            TextInput::make('email')->label('Correo')->email()->required(),
        ])->statePath('data');
    }

    public function save(): void
    {
        $user = Auth::user();
        $user->update($this->form->getState());

        $this->notify('success', 'Perfil actualizado ✅');
    }
}

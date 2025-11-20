<?php

namespace App\Filament\Pages;

use App\Models\Material;
use App\Models\CajaDiaria;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class PreciosDelDia extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string $view = 'filament.pages.precios-del-dia';
    protected static ?string $title = 'Precios del Día';
    protected static ?string $navigationLabel = 'Precios del Día';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 0;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getDefaultData());
    }

    protected function getDefaultData(): array
    {
        $materiales = Material::all();
        $cajaHoy = CajaDiaria::whereDate('fecha', now())->first();

        $data = [
            'fecha' => now()->format('Y-m-d'),
            'monto_inicial' => $cajaHoy?->monto_inicial ?? 0,
            'observaciones_caja' => $cajaHoy?->observaciones ?? '',
        ];

        foreach ($materiales as $material) {
            $data['material_' . $material->id . '_compra'] = $material->precio_dia_compra ?? $material->precio_compra;
            $data['material_' . $material->id . '_venta'] = $material->precio_dia_venta ?? $material->precio_venta;
        }

        return $data;
    }

    public function form(Form $form): Form
    {
        $materiales = Material::orderBy('nombre')->get();
        $schema = [
            Section::make('Fecha de Actualización')
                ->schema([
                    DatePicker::make('fecha')
                        ->label('Fecha')
                        ->default(now())
                        ->required()
                        ->native(false)
                        ->displayFormat('d/m/Y'),
                ])
                ->columns(1),

            Section::make('💰 Caja Menor del Día')
                ->description('Dinero disponible para iniciar el día')
                ->schema([
                    TextInput::make('monto_inicial')
                        ->label('Monto Inicial (Caja Menor)')
                        ->numeric()
                        ->prefix('$')
                        ->step(0.01)
                        ->minValue(0)
                        ->required()
                        ->default(0)
                        ->helperText('Dinero con el que inicias el día para gastos menores y pagos'),

                    Textarea::make('observaciones_caja')
                        ->label('Observaciones de Caja')
                        ->rows(2)
                        ->placeholder('Ej: Faltaron $50,000 de ayer, se agregaron desde caja fuerte'),
                ])
                ->columns(2)
                ->collapsible()
                ->collapsed(false),
        ];

        foreach ($materiales as $material) {
            $unidad = match($material->unidad_medida) {
                'kg' => 'kg',
                'unidad' => 'unid.',
                'tonelada' => 'ton.',
                'metro' => 'm',
                'litro' => 'L',
                default => $material->unidad_medida,
            };

            $schema[] = Section::make($material->nombre . ' (' . $unidad . ')')
                ->schema([
                    TextInput::make('material_' . $material->id . '_compra')
                        ->label('Precio de COMPRA por ' . $unidad)
                        ->numeric()
                        ->prefix('$')
                        ->step(0.01)
                        ->minValue(0)
                        ->required()
                        ->helperText('Precio base: $' . number_format($material->precio_compra, 0)),

                    TextInput::make('material_' . $material->id . '_venta')
                        ->label('Precio de VENTA por ' . $unidad)
                        ->numeric()
                        ->prefix('$')
                        ->step(0.01)
                        ->minValue(0)
                        ->required()
                        ->helperText('Precio base: $' . number_format($material->precio_venta, 0)),
                ])
                ->columns(2)
                ->collapsible();
        }

        return $form->schema($schema)->statePath('data');
    }

    public function actualizarPrecios(): void
    {
        $data = $this->form->getState();
        $fecha = $data['fecha'];

        DB::beginTransaction();
        try {
            // Actualizar o crear la caja del día
            CajaDiaria::updateOrCreate(
                ['fecha' => $fecha],
                [
                    'monto_inicial' => $data['monto_inicial'] ?? 0,
                    'observaciones' => $data['observaciones_caja'] ?? null,
                ]
            );

            // Actualizar precios de materiales
            $materiales = Material::all();
            foreach ($materiales as $material) {
                $precioCompra = $data['material_' . $material->id . '_compra'] ?? null;
                $precioVenta = $data['material_' . $material->id . '_venta'] ?? null;

                if ($precioCompra !== null && $precioVenta !== null) {
                    $material->update([
                        'precio_dia_compra' => $precioCompra,
                        'precio_dia_venta' => $precioVenta,
                        'fecha_actualizacion_precio' => $fecha,
                    ]);
                }
            }

            DB::commit();

            Notification::make()
                ->title('Datos actualizados correctamente')
                ->success()
                ->body('Los precios del día y la caja menor se han actualizado.')
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error al actualizar datos')
                ->danger()
                ->body('Ocurrió un error: ' . $e->getMessage())
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('actualizar')
                ->label('Actualizar Precios del Día')
                ->action('actualizarPrecios')
                ->requiresConfirmation()
                ->modalHeading('¿Actualizar precios?')
                ->modalDescription('Esta acción actualizará los precios del día para todos los materiales.')
                ->modalSubmitActionLabel('Sí, actualizar')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class InventarioTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Material::query())
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Material')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('unidad_medida')
                    ->label('Unidad')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'kg' => 'success',
                        'unidad' => 'info',
                        'tonelada' => 'warning',
                        'metro' => 'primary',
                        'litro' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'kg' => 'kg',
                        'unidad' => 'Unid.',
                        'tonelada' => 'Ton.',
                        'metro' => 'm',
                        'litro' => 'L',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock Actual')
                    ->numeric(decimalPlaces: 2)
                    ->suffix(fn ($record) => ' ' . match($record->unidad_medida) {
                        'kg' => 'kg',
                        'unidad' => 'unid.',
                        'tonelada' => 'ton.',
                        'metro' => 'm',
                        'litro' => 'L',
                        default => $record->unidad_medida,
                    })
                    ->sortable()
                    ->color(fn ($state) => match(true) {
                        $state <= 0 => 'danger',
                        $state < 50 => 'warning',
                        default => 'success'
                    })
                    ->icon(fn ($state) => match(true) {
                        $state <= 0 => 'heroicon-o-x-circle',
                        $state < 50 => 'heroicon-o-exclamation-triangle',
                        default => 'heroicon-o-check-circle'
                    }),

                Tables\Columns\TextColumn::make('precio_compra')
                    ->label('Precio Compra')
                    ->money('COP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('precio_venta')
                    ->label('Precio Venta')
                    ->money('COP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('valor_stock')
                    ->label('Valor en Stock')
                    ->money('COP')
                    ->state(function (Material $record): float {
                        return $record->stock * $record->precio_venta;
                    })
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('costo_stock')
                    ->label('Costo en Stock')
                    ->money('COP')
                    ->state(function (Material $record): float {
                        return $record->stock * $record->precio_compra;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('margen')
                    ->label('Margen')
                    ->state(function (Material $record): string {
                        if ($record->precio_compra == 0) return 'N/A';
                        $margen = (($record->precio_venta - $record->precio_compra) / $record->precio_compra) * 100;
                        return number_format($margen, 1) . '%';
                    })
                    ->color(fn ($state) => str_contains($state, 'N/A') ? 'gray' : 'info')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unidad_medida')
                    ->label('Unidad de Medida')
                    ->options([
                        'kg' => 'Kilogramo (kg)',
                        'unidad' => 'Unidad',
                        'tonelada' => 'Tonelada',
                        'metro' => 'Metro',
                        'litro' => 'Litro',
                    ]),

                Tables\Filters\Filter::make('sin_stock')
                    ->label('Sin Stock')
                    ->query(fn ($query) => $query->where('stock', '<=', 0)),

                Tables\Filters\Filter::make('stock_bajo')
                    ->label('Stock Bajo (< 50)')
                    ->query(fn ($query) => $query->where('stock', '>', 0)->where('stock', '<', 50)),

                Tables\Filters\Filter::make('con_stock')
                    ->label('Con Stock')
                    ->query(fn ($query) => $query->where('stock', '>', 0)),
            ])
            ->defaultSort('stock', 'desc');
    }
}

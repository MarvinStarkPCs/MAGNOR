<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompraResource\Pages;
use App\Filament\Resources\CompraResource\RelationManagers;
use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\Material;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Compras';

    protected static ?string $modelLabel = 'Compra';

    protected static ?string $pluralModelLabel = 'Compras';

    protected static ?string $navigationGroup = 'Operaciones';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Compra')
                    ->schema([
                        Forms\Components\Select::make('proveedor_id')
                            ->label('Proveedor / Reciclador')
                            ->relationship('proveedor', 'nombre')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(150),
                                Forms\Components\TextInput::make('documento')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('telefono')
                                    ->tel()
                                    ->maxLength(30),
                                Forms\Components\Textarea::make('direccion')
                                    ->maxLength(200),
                                Forms\Components\Toggle::make('es_reciclador')
                                    ->label('Es Reciclador')
                                    ->default(true),
                            ]),

                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Materiales')
                    ->schema([
                        Forms\Components\Repeater::make('detalles')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('material_id')
                                    ->label('Material')
                                    ->options(Material::all()->mapWithKeys(function ($material) {
                                        $unidad = match($material->unidad_medida) {
                                            'kg' => 'kg',
                                            'unidad' => 'unid.',
                                            'tonelada' => 'ton.',
                                            'metro' => 'm',
                                            'litro' => 'L',
                                            default => $material->unidad_medida,
                                        };
                                        return [$material->id => $material->nombre . ' (' . $unidad . ')'];
                                    }))
                                    ->required()
                                    ->live()
                                    ->searchable()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $material = Material::find($state);
                                            $set('_unidad_medida', $material?->unidad_medida ?? 'kg');
                                            // Usar precio del día si existe, sino usar precio base
                                            $precioCompra = $material?->precio_dia_compra ?? $material?->precio_compra ?? 0;
                                            $set('precio_unitario', $precioCompra);
                                        }
                                    }),

                                Forms\Components\Hidden::make('_unidad_medida')
                                    ->default('kg'),

                                Forms\Components\TextInput::make('cantidad')
                                    ->label(fn (Forms\Get $get) => 'Cantidad (' . match($get('_unidad_medida') ?? 'kg') {
                                        'kg' => 'kg',
                                        'unidad' => 'unidades',
                                        'tonelada' => 'toneladas',
                                        'metro' => 'metros',
                                        'litro' => 'litros',
                                        default => $get('_unidad_medida'),
                                    } . ')')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        $precio = $get('precio_unitario') ?? 0;
                                        $set('subtotal', $state * $precio);
                                    }),

                                Forms\Components\TextInput::make('precio_unitario')
                                    ->label(fn (Forms\Get $get) => 'Precio por ' . match($get('_unidad_medida') ?? 'kg') {
                                        'kg' => 'kg',
                                        'unidad' => 'unidad',
                                        'tonelada' => 'tonelada',
                                        'metro' => 'metro',
                                        'litro' => 'litro',
                                        default => $get('_unidad_medida'),
                                    })
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->prefix('$')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        $cantidad = $get('cantidad') ?? 0;
                                        $set('subtotal', $cantidad * $state);
                                    }),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->addActionLabel('Agregar Material')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('proveedor.nombre')
                    ->label('Proveedor')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('proveedor.es_reciclador')
                    ->label('Reciclador')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('fecha_compra')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('COP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('detalles_count')
                    ->label('Materiales')
                    ->counts('detalles')
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('fecha_compra', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('proveedor_id')
                    ->label('Proveedor')
                    ->relationship('proveedor', 'nombre')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('es_reciclador')
                    ->label('Solo Recicladores')
                    ->query(fn (Builder $query): Builder => $query->whereHas('proveedor', fn ($q) => $q->where('es_reciclador', true))),

                Tables\Filters\Filter::make('fecha_compra')
                    ->form([
                        Forms\Components\DatePicker::make('desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_compra', '>=', $date),
                            )
                            ->when(
                                $data['hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_compra', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label('Imprimir')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function (Compra $record) {
                        $compra = $record->load(['proveedor', 'detalles.material']);

                        $pdf = Pdf::loadView('pdf.compra', ['compra' => $compra]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'factura-compra-' . $record->id . '.pdf'
                        );
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información de la Compra')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('ID de Compra')
                            ->badge()
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('fecha_compra')
                            ->label('Fecha de Compra')
                            ->date('d/m/Y')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('proveedor.nombre')
                            ->label('Proveedor')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\IconEntry::make('proveedor.es_reciclador')
                            ->label('Reciclador')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('gray'),

                        Infolists\Components\TextEntry::make('proveedor.documento')
                            ->label('Documento'),

                        Infolists\Components\TextEntry::make('proveedor.telefono')
                            ->label('Teléfono')
                            ->icon('heroicon-o-phone'),

                        Infolists\Components\TextEntry::make('total')
                            ->label('Total de la Compra')
                            ->money('COP')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('observaciones')
                            ->label('Observaciones')
                            ->placeholder('Sin observaciones')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Detalle de Materiales')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('detalles')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('material.nombre')
                                    ->label('Material')
                                    ->badge()
                                    ->color('info'),

                                Infolists\Components\TextEntry::make('cantidad')
                                    ->label('Cantidad (kg)')
                                    ->suffix(' kg')
                                    ->numeric(decimalPlaces: 2),

                                Infolists\Components\TextEntry::make('precio_unitario')
                                    ->label('Precio por kg')
                                    ->money('COP'),

                                Infolists\Components\TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('COP')
                                    ->weight('bold'),
                            ])
                            ->columns(4)
                            ->contained(false),
                    ]),

                Infolists\Components\Section::make('Información del Sistema')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Creado el')
                            ->dateTime('d/m/Y H:i:s'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Última actualización')
                            ->dateTime('d/m/Y H:i:s'),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompras::route('/'),
            'create' => Pages\CreateCompra::route('/create'),
            'view' => Pages\ViewCompra::route('/{record}'),
            'edit' => Pages\EditCompra::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;



use App\Filament\Resources\MaterialResource\Pages;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Materiales';
    protected static ?string $pluralModelLabel = 'Materiales';
    protected static ?string $modelLabel = 'Material';

    protected static ?string $navigationGroup = 'Catálogos';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Select::make('unidad_medida')
                    ->label('Unidad de Medida')
                    ->options([
                        'kg' => 'Kilogramo (kg)',
                        'unidad' => 'Unidad',
                        'tonelada' => 'Tonelada',
                        'metro' => 'Metro',
                        'litro' => 'Litro',
                    ])
                    ->required()
                    ->default('kg'),

                Forms\Components\TextInput::make('precio_compra')
                    ->label('Precio de Compra')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('precio_venta')
                    ->label('Precio de Venta')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

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
                    ->label('Stock')
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
                    })
                    ->description(fn ($state) => match(true) {
                        $state <= 0 => 'Sin stock',
                        $state < 50 => 'Stock bajo',
                        default => null
                    }),

                Tables\Columns\TextColumn::make('precio_compra')
                    ->label('Precio Compra')
                    ->money('COP')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('precio_venta')
                    ->label('Precio Venta')
                    ->money('COP')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
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
                    ->query(fn ($query) => $query->where('stock', '<=', 0))
                    ->toggle(),

                Tables\Filters\Filter::make('stock_bajo')
                    ->label('Stock Bajo (< 50)')
                    ->query(fn ($query) => $query->where('stock', '>', 0)->where('stock', '<', 50))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}

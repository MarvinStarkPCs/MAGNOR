<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProveedoresResource\Pages;
use App\Filament\Resources\ProveedoresResource\RelationManagers;
use App\Models\Proveedor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProveedoresResource extends Resource
{
    protected static ?string $model = Proveedor::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Proveedores';

    protected static ?string $modelLabel = 'Proveedor';

    protected static ?string $pluralModelLabel = 'Proveedores';

    protected static ?string $navigationGroup = 'Catálogos';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Proveedor')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(150)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('documento')
                            ->label('Documento (CC/NIT)')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('telefono')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(30),

                        Forms\Components\Textarea::make('direccion')
                            ->label('Dirección')
                            ->maxLength(200)
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('es_reciclador')
                            ->label('Es Reciclador')
                            ->helperText('Marque si este proveedor es un reciclador de materiales')
                            ->default(false)
                            ->inline(false),
                    ])
                    ->columns(2),
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

                Tables\Columns\TextColumn::make('documento')
                    ->label('Documento')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),

                Tables\Columns\IconColumn::make('es_reciclador')
                    ->label('Reciclador')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('compras_count')
                    ->label('Compras')
                    ->counts('compras')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('es_reciclador')
                    ->label('Reciclador')
                    ->placeholder('Todos')
                    ->trueLabel('Solo recicladores')
                    ->falseLabel('Solo no recicladores'),
            ])
            ->actions([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProveedores::route('/'),
            'create' => Pages\CreateProveedores::route('/create'),
            'edit' => Pages\EditProveedores::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Productos\Schemas;

use App\Models\Producto;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Artículo')
                    ->description('Datos que se muestran en la tarjeta de venta de la web.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(80)
                            ->placeholder('Camiseta oficial 2026')
                            ->columnSpanFull(),
                        Select::make('categoria_id')
                            ->label('Categoría')
                            ->relationship('categoria', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->label('Nombre de la categoría')
                                    ->required()
                                    ->maxLength(60),
                            ])
                            ->createOptionModalHeading('Nueva categoría')
                            ->helperText('Sirve para agrupar y filtrar los artículos en la sección de ventas.'),
                        FileUpload::make('imagen')
                            ->label('Imagen')
                            ->image()
                            ->disk('productos')
                            ->directory('')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->dehydrateStateUsing(fn ($state): ?string => is_array($state) ? ($state[0] ?? null) : $state)
                            ->helperText('Opcional. Si no subes una foto, se usa una imagen de ejemplo.')
                            ->columnSpan(1),
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->required()
                            ->rows(3)
                            ->maxLength(300)
                            ->helperText('Texto breve que acompaña al nombre y al precio.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Precio')
                    ->description('El formato se adapta a la moneda elegida.')
                    ->columns(2)
                    ->schema([
                        Select::make('moneda')
                            ->label('Moneda')
                            ->options(Producto::MONEDAS)
                            ->default('CLP')
                            ->required()
                            ->live(),
                        TextInput::make('precio')
                            ->label('Precio')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix(fn (Get $get): string => Producto::simboloPara((string) $get('moneda')))
                            ->helperText('Se muestra como precio principal del artículo.'),
                        TextInput::make('precio_anterior')
                            ->label('Precio anterior')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix(fn (Get $get): string => Producto::simboloPara((string) $get('moneda')))
                            ->helperText('Opcional. Solo se muestra si es mayor que el precio actual.'),
                        TextInput::make('nota_precio')
                            ->label('Nota del precio')
                            ->maxLength(60)
                            ->placeholder('pago único, envío incluido, retiro en el club…')
                            ->helperText('Opcional. Aparece bajo el precio.'),
                        TextInput::make('etiqueta')
                            ->label('Distintivo')
                            ->maxLength(30)
                            ->placeholder('Oferta, Nuevo, Evento…')
                            ->helperText('Opcional. Se muestra sobre la imagen.'),
                    ]),
                Section::make('Publicación y botón')
                    ->columns(2)
                    ->schema([
                        TextInput::make('boton_texto')
                            ->label('Texto del botón')
                            ->required()
                            ->maxLength(40)
                            ->default('Comprar ahora'),
                        TextInput::make('enlace_pago')
                            ->label('Enlace de pago')
                            ->url()
                            ->maxLength(2048)
                            ->placeholder('https://www.mercadopago.cl/...')
                            ->helperText('Mercado Pago, Webpay u otra pasarela. Si queda vacío, el botón se muestra desactivado.'),
                        Toggle::make('publicado')
                            ->label('Publicado')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('Solo los artículos publicados aparecen en la web.'),
                        TextInput::make('orden')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Menor número aparece primero. También puedes arrastrar las filas en la tabla.'),
                    ]),
            ]);
    }
}

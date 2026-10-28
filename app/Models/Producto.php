<?php

namespace App\Models;

use Database\Factories\ProductoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'categoria_id',
    'nombre',
    'descripcion',
    'imagen',
    'moneda',
    'precio',
    'precio_anterior',
    'nota_precio',
    'etiqueta',
    'boton_texto',
    'enlace_pago',
    'publicado',
    'orden',
])]
class Producto extends Model
{
    /** @use HasFactory<ProductoFactory> */
    use HasFactory;

    /**
     * Monedas disponibles en la tienda y su etiqueta para el panel.
     *
     * @var array<string, string>
     */
    public const MONEDAS = [
        'CLP' => 'Peso chileno (CLP)',
        'USD' => 'Dólar estadounidense (USD)',
        'EUR' => 'Euro (EUR)',
        'ARS' => 'Peso argentino (ARS)',
        'PEN' => 'Sol peruano (PEN)',
        'MXN' => 'Peso mexicano (MXN)',
        'COP' => 'Peso colombiano (COP)',
        'UYU' => 'Peso uruguayo (UYU)',
        'BOB' => 'Boliviano (BOB)',
        'BRL' => 'Real brasileño (BRL)',
    ];

    /**
     * Símbolo y decimales con los que se muestra cada moneda.
     *
     * @var array<string, array{simbolo: string, decimales: int}>
     */
    private const FORMATOS = [
        'CLP' => ['simbolo' => '$', 'decimales' => 0],
        'USD' => ['simbolo' => 'US$', 'decimales' => 2],
        'EUR' => ['simbolo' => '€', 'decimales' => 2],
        'ARS' => ['simbolo' => '$', 'decimales' => 2],
        'PEN' => ['simbolo' => 'S/', 'decimales' => 2],
        'MXN' => ['simbolo' => '$', 'decimales' => 2],
        'COP' => ['simbolo' => '$', 'decimales' => 0],
        'UYU' => ['simbolo' => '$', 'decimales' => 2],
        'BOB' => ['simbolo' => 'Bs', 'decimales' => 2],
        'BRL' => ['simbolo' => 'R$', 'decimales' => 2],
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'precio_anterior' => 'decimal:2',
            'publicado' => 'boolean',
            'orden' => 'integer',
        ];
    }

    /**
     * Categoría a la que pertenece el artículo.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }

    /**
     * Solo los artículos publicados en la tienda.
     */
    public function scopePublicados(Builder $query): Builder
    {
        return $query->where('publicado', true);
    }

    /**
     * Productos que pueden verse en la web: publicados y con categoría visible
     * (los artículos sin categoría también se muestran).
     */
    public function scopeVisibles(Builder $query): Builder
    {
        return $query->publicados()->where(function (Builder $query): void {
            $query->whereNull('categoria_id')
                ->orWhereHas('categoria', fn (Builder $categoria): Builder => $categoria->where('publicada', true));
        });
    }

    /**
     * Precio principal con símbolo y separadores locales.
     */
    public function precioFormateado(): string
    {
        return $this->formatearMonto($this->precio);
    }

    /**
     * Precio anterior, solo si realmente representa un descuento.
     */
    public function precioAnteriorFormateado(): ?string
    {
        if ($this->precio_anterior === null || (float) $this->precio_anterior <= (float) $this->precio) {
            return null;
        }

        return $this->formatearMonto($this->precio_anterior);
    }

    public function simboloMoneda(): string
    {
        return self::simboloPara($this->moneda);
    }

    /**
     * Símbolo de una moneda concreta, útil en formularios antes de guardar.
     */
    public static function simboloPara(string $moneda): string
    {
        return self::FORMATOS[$moneda]['simbolo'] ?? $moneda;
    }

    /**
     * Etiqueta legible de la moneda para tablas y formularios.
     */
    public function monedaEtiqueta(): string
    {
        return self::MONEDAS[$this->moneda] ?? $this->moneda;
    }

    /**
     * URL de la imagen subida o un marcador de posición estable.
     */
    public function imagenUrl(): string
    {
        $imagen = trim((string) $this->imagen);

        if ($imagen === '') {
            return 'https://picsum.photos/seed/cielo-producto-'.$this->getKey().'/800/600';
        }

        if (Str::startsWith($imagen, ['http://', 'https://'])) {
            return $imagen;
        }

        return asset('img/productos/'.$imagen);
    }

    private function formatearMonto(float|string|null $monto): string
    {
        $formato = self::FORMATOS[$this->moneda] ?? ['simbolo' => $this->moneda, 'decimales' => 2];

        return $formato['simbolo'].number_format((float) $monto, $formato['decimales'], ',', '.');
    }
}

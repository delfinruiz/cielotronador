<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Marca;
use App\Models\Testimonio;
use Illuminate\Database\Seeder;

/**
 * Contenido de ejemplo para los módulos dinámicos del constructor.
 *
 * Los textos son neutrales para que sirvan a cualquier plantilla (agencia,
 * estudio, fotografía) y se puedan reemplazar desde el panel.
 */
class ContenidoDinamicoSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => '¿Cómo empieza un proyecto?',
                'answer' => 'Con una reunión breve para entender qué necesitas, a quién le hablas y para cuándo. Después enviamos una propuesta con alcance, plazos y precio antes de empezar.',
            ],
            [
                'question' => '¿Cuánto demora un sitio web?',
                'answer' => 'Una página de presentación toma entre dos y cuatro semanas. Un sitio con catálogo, blog o reservas puede tomar entre cuatro y ocho, según el contenido y las integraciones.',
            ],
            [
                'question' => '¿Qué incluye la entrega?',
                'answer' => 'Archivos editables, el sitio publicado, una guía rápida de uso y una sesión de traspaso. También podemos dejar el contenido cargado y el dominio configurado.',
            ],
            [
                'question' => '¿Puedo pedir cambios después de publicar?',
                'answer' => 'Sí. Incluimos un período de ajustes sin costo después del lanzamiento. Pasado ese plazo, trabajamos por hora o con un plan mensual de mantenimiento.',
            ],
            [
                'question' => '¿Trabajan con marcas de otras ciudades?',
                'answer' => 'Sí. Trabajamos en remoto con reuniones por videollamada y entregas en línea. Cuando el proyecto lo pide, coordinamos una visita o una sesión presencial.',
            ],
            [
                'question' => '¿Cómo se paga?',
                'answer' => 'Con un 50 % al aprobar la propuesta y el resto al publicar. Para proyectos largos dividimos el pago en tres hitos y emitimos boleta o factura.',
            ],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::query()->firstOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }

        $marcas = [
            ['name' => 'Nube', 'logo' => 'nube.svg'],
            ['name' => 'Faro', 'logo' => 'faro.svg'],
            ['name' => 'Raíz', 'logo' => 'raiz.svg'],
            ['name' => 'Lumen', 'logo' => 'lumen.svg'],
            ['name' => 'Alba', 'logo' => 'alba.svg'],
            ['name' => 'Norte', 'logo' => 'norte.svg'],
        ];

        foreach ($marcas as $index => $marca) {
            Marca::query()->firstOrCreate(
                ['name' => $marca['name']],
                [
                    'logo' => $marca['logo'],
                    'url' => null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }

        $testimonios = [
            [
                'name' => 'Camila Rojas',
                'role' => 'Fundadora de Nube Café',
                'quote' => 'Entendieron nuestra marca mejor que nosotros. En tres semanas teníamos un sitio claro, rápido y con una identidad que nos representa.',
                'rating' => 5,
            ],
            [
                'name' => 'Ignacio Fuentes',
                'role' => 'Tienda de diseño',
                'quote' => 'La propuesta fue directa, sin vueltas. Nos ayudaron a ordenar el catálogo y las fotos quedaron impecables.',
                'rating' => 5,
            ],
            [
                'name' => 'María José Herrera',
                'role' => 'Directora de Lumen Festival',
                'quote' => 'Cumplieron con los plazos en un proyecto difícil, con muchos cambios y decisiones sobre la marcha.',
                'rating' => 4,
            ],
            [
                'name' => 'Tomás Vidal',
                'role' => 'Estudio Raíz',
                'quote' => 'Trabajar con un equipo pequeño se nota: responden rápido, escuchan y cuidan los detalles que uno no ve.',
                'rating' => 5,
            ],
        ];

        foreach ($testimonios as $index => $testimonio) {
            Testimonio::query()->firstOrCreate(
                ['name' => $testimonio['name']],
                [
                    'role' => $testimonio['role'],
                    'quote' => $testimonio['quote'],
                    'rating' => $testimonio['rating'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}

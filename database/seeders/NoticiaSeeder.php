<?php

namespace Database\Seeders;

use App\Models\Noticia;
use Illuminate\Database\Seeder;

class NoticiaSeeder extends Seeder
{
    /**
     * Siembra las noticias publicadas actualmente en la página principal.
     */
    public function run(): void
    {
        $noticias = [
            [
                'titulo' => '“Jugando Aprendo 2025”: minibásquet con sentido formativo en Talcahuano',
                'fecha' => '2025-10-25',
                'extracto' => 'Con más de 500 niños en cancha, 2 días de acción y 7 partidos por equipo, el Torneo Nacional de Minibásquet “Jugando Aprendo” volvió a llenar de energía los gimnasios de Talcahuano.',
                'imagen' => 'jugando-aprendo-2025.jpg',
                'cuerpo' => [
                    'Con más de 500 niños en cancha, 2 días de acción y 7 partidos por equipo, el Torneo Nacional de Minibásquet “Jugando Aprendo” volvió a llenar de energía los gimnasios de Talcahuano. El evento para series Sub 9 y 12 fue organizado por Cielo Tronador y logró reunir a 23 clubes de todo Chile en La Tortuga y el Polideportivo Parque Tumbes.',
                    '“Jugando Aprendo lleva un año prepararlo”, explica Jacqueline Reyes, directora del club. “Empezamos con la solicitud de espacios, en marzo enviamos las invitaciones abiertas y en agosto cerramos inscripciones. Desde ahí, reuniones y coordinación con los clubes”. La edición 2025 contó con delegaciones de Punta Arenas, Copiapó y Huasco, entre otras ciudades, más otros créditos del Biobío como Huachipato, Lobos y Lord Cochrane. “Es un honor que hayan atravesado la mitad de Chile para participar”, destacó.',
                    'Para Reyes, el valor de estas instancias es invaluable. “Es el primer paso para que los niños hagan esta disciplina encantados y felices. Cuando tú les das esa herramienta, ellos no se van saltando etapas, sino que van creciendo seguros ante los procesos que vienen. Vimos niños contentos y comentarios de los profes muy agradecidos, esperando el próximo año para volver.”',
                    'La directora destacó el apoyo de estudiantes de la Universidad Católica y del Departamento de Kinesiología de la UdeC, además de instituciones como la Cruz Roja, Defensa Civil y la Dirección de Salud. “Si se suman más entidades desde el principio, el trabajo no se vuelve tan desgastante”, afirma Reyes. “Este evento costó más de 11 millones, pero lo importante es que logramos el objetivo: niños felices”.',
                    'Cielo Tronador se define como un club cultural y social sin fines de lucro. “Formamos jugadores, pero sobre todo personas. Somos su segunda casa, un lugar seguro porque se sacan sus mochilas de los problemas que pasan afuera y acá pueden ser niños. Por eso nuestro lema es: un club es una familia”, concluye Reyes, emocionada. “Hemos sobrevivido terremotos, pandemias, y seguimos aquí, trabajando los 12 meses del año. Porque esto es de todos”.',
                ],
            ],
            [
                'titulo' => 'Jugando Aprendo 2024',
                'fecha' => '2024-09-12',
                'extracto' => 'Un mes de agosto lleno de actividades y con el corazón rebosante de gratitud. Gracias, Dios, por estar presente en cada una de nuestras acciones. Agradecemos a todos los que se sumaron nuevamente.',
                'imagen' => 'jugando-aprendo-2024.jpg',
                'cuerpo' => [
                    'Un mes de agosto lleno de actividades y con el corazón rebosante de gratitud. Gracias, Dios, por estar presente en cada una de nuestras acciones.',
                    'Agradecemos a todos los que se sumaron nuevamente para que el evento “Jugando Aprendo V” brillara. Este es un esfuerzo colectivo, y sin el apoyo de todos y todas no sería lo mismo. Gracias por creer en este proyecto y convertirlo en un referente del básquetbol formativo.',
                    'En esta actividad, los niños y niñas, de 6 a 13 años, son los verdaderos protagonistas.',
                    'Gracias a la Ilustre Municipalidad de Talcahuano, a nuestro alcalde Henry Campos y a los concejales.',
                    'Gracias a la Corporación de Deporte de Talcahuano y a todo su equipo, al Gobierno Regional (GORE), Cementos Bío Bío, Aguas Agustina, Globos Cecilia Larson, a nuestra madrina Paola Erices, y al kinesiólogo Enrique Maldonado.',
                    'También extendemos un agradecimiento especial a nuestro staff de apoderados y jugadores por el constante apoyo a nuestro club.',
                    'A los profesores y apoderados, gracias por su compromiso con los niños y niñas, acompañándolos en todo momento.',
                    'A los clubes que participaron: Lord, Caupolicán, Siembra Buin, Huachipato, California, CAB Pucón, Atlético, Lobos, JSU, El Constructor y Cielo Tronador, reuniendo a más de 2,000 personas entre jugadores, cuerpo técnico y apoderados.',
                    'Estamos orgullosos de tener a dos de nuestros jugadores en la preselección de la liga Bío Bío: Josué Aray (sub 13) y Sandra Contreras (sub 17). Su esfuerzo y constancia están dando frutos. Además, nuestra categoría sub 13 varones ha llegado a los playoffs de la Liga Bío Bío.',
                    'Agradecemos también al equipo Los Lobos por invitarnos a un encuentro con todas las categorías formativas en San Pedro, y a Wolf de Laja por invitarnos a celebrar su aniversario con cinco categorías, donde más de 50 jugadores experimentaron, para algunos, su primer viaje en tren.',
                    'Solo podemos decir que todos los sacrificios valen la pena cuando vemos las sonrisas de los niños y sentimos que estamos formando buenas personas.',
                ],
            ],
            [
                'titulo' => 'Premiación del aniversario número 18',
                'fecha' => '2024-06-28',
                'extracto' => 'Premiación del aniversario número 18 en categorías formativas y juveniles. En el campeonato que se llevó a cabo todo el mes de mayo participaron más de 450 jóvenes de diferentes clubes.',
                'imagen' => 'premiacion-aniversario-18.jpg',
                'cuerpo' => [
                    'Premiación del aniversario número 18 en categorías formativas y juveniles. En el campeonato que se llevó a cabo todo el mes de mayo participaron más de 450 jóvenes de diferentes comunas del Gran Concepción y alrededores.',
                    'Varones Sub 13, Sub 15, Sub 17 y Sub 19, y Damas Sub 18.',
                ],
            ],
            [
                'titulo' => 'Aniversario 2024',
                'fecha' => '2024-04-15',
                'extracto' => 'Hoy se dio inicio en la Tortuga de Talcahuano nuestro Campeonato de Aniversario Número 18. El encargado de abrir la celebración fue la categoría U13. ¡Muy felices de celebrar 18 años!',
                'imagen' => 'aniversario-2024.jpg',
                'cuerpo' => [
                    'Hoy se dio inicio en la Tortuga de Talcahuano nuestro Campeonato de Aniversario Número 18. El encargado de abrir la celebración fue la categoría U13. ¡Muy felices de celebrar 18 años!',
                ],
            ],
            [
                'titulo' => 'Jugando Aprendo',
                'fecha' => '2023-08-28',
                'extracto' => 'Conversamos con la señora Jacqueline Reyes, Presidenta del Club de Básquetbol Cielo Tronador de la ciudad de Talcahuano, organizadores de este gran evento del minibásquet en su región.',
                'imagen' => 'jugando-aprendo.jpg',
                'cuerpo' => [
                    'Conversamos con la señora Jacqueline Reyes, Presidenta del Club de Básquetbol Cielo Tronador de la ciudad de Talcahuano, quienes son los organizadores de este gran evento del minibásquet en su segunda versión denominado “Jugando Aprendo”. ¡Felicitaciones por este gran evento!',
                ],
            ],
        ];

        foreach ($noticias as $noticia) {
            Noticia::updateOrCreate(
                ['titulo' => $noticia['titulo']],
                [
                    ...$noticia,
                    'cuerpo' => array_map(
                        fn (string $parrafo): array => ['parrafo' => $parrafo],
                        $noticia['cuerpo'],
                    ),
                    'published' => true,
                ],
            );
        }
    }
}

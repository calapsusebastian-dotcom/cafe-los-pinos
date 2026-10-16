<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Starter articles; the administrator can edit or replace them from the panel.
     */
    public function run(): void
    {
        $posts = [
            [
                'slug' => 'como-preparar-cafe-en-v60',
                'title' => 'Cómo preparar café en V60 paso a paso',
                'excerpt' => 'Una guía sencilla para preparar en casa una taza limpia y aromática con el método V60, usando café molido del Huila.',
                'published_at' => now()->subDays(14),
                'body' => <<<'MD'
El V60 es uno de los métodos de filtrado más queridos porque resalta la dulzura y la acidez del café. Con un poco de práctica, lograrás una taza limpia y aromática en casa.

## Lo que necesitas

- 15 g de café molido medio (como sal gruesa).
- 250 ml de agua caliente, entre 92 °C y 96 °C.
- Un filtro de papel y un cono V60.
- Una balanza y un cronómetro (opcionales, pero ayudan).

## Paso a paso

1. Enjuaga el filtro con agua caliente para quitar el sabor a papel y precalentar la taza.
2. Agrega el café y nivela la superficie con un pequeño golpe.
3. Vierte 30 ml de agua en círculos y espera 30 segundos: es la *floración*, donde el café libera gases.
4. Sigue vertiendo el agua despacio, en espiral, hasta llegar a 250 ml.
5. El tiempo total ideal está entre 2:30 y 3:00 minutos.

## Ajusta a tu gusto

Si tu café sabe amargo, muele más grueso o baja la temperatura del agua. Si lo sientes ácido o aguado, muele más fino. Cambia una sola cosa a la vez para entender qué mejora.
MD,
            ],
            [
                'slug' => 'como-guardar-el-cafe-para-que-dure-mas',
                'title' => 'Cómo guardar el café para conservar su aroma',
                'excerpt' => 'Luz, humedad y aire son los enemigos del café. Aprende a guardarlo para disfrutar su sabor hasta la última taza.',
                'published_at' => now()->subDays(7),
                'body' => <<<'MD'
Un buen café puede perder su aroma en pocas semanas si se guarda mal. Estos consejos te ayudan a conservarlo.

## Los tres enemigos del café

- **El aire:** oxida los aceites y apaga el aroma.
- **La luz:** acelera el deterioro del grano.
- **La humedad:** apaga el sabor y puede dañar el café.

## Cómo guardarlo

1. Cierra bien la bolsa después de cada uso o pásalo a un frasco hermético opaco.
2. Déjalo en un lugar fresco y seco, lejos de la estufa y del sol.
3. Evita la nevera: absorbe olores y humedad.
4. Si puedes, compra el grano entero y muélelo justo antes de preparar.

## ¿Cuánto dura?

Lo ideal es consumirlo dentro de las 4 a 6 semanas posteriores al tostado. Por eso es mejor comprar cantidades que termines pronto.
MD,
            ],
            [
                'slug' => 'cafe-del-huila-por-que-es-tan-apreciado',
                'title' => 'Café del Huila: por qué es tan apreciado',
                'excerpt' => 'Altura, clima y tradición cafetera: conoce lo que hace especial al café que nace en las montañas del Huila.',
                'published_at' => now()->subDays(2),
                'body' => <<<'MD'
El Huila es una de las regiones cafeteras más reconocidas de Colombia. Sus montañas, su clima y la dedicación de sus familias caficultoras dan origen a tazas con carácter propio.

## Altura y clima

Los cafetales crecen en las laderas de la cordillera, donde los días cálidos y las noches frescas hacen que el grano madure despacio y desarrolle más dulzura y complejidad.

## Una tradición que se hereda

El café aquí se cultiva con paciencia y se recoge a mano, grano a grano. Esa experiencia pasa de generación en generación y se nota en cada cosecha.

## Qué esperar en la taza

Es común encontrar notas dulces como panela, caramelo o cacao, con una acidez agradable y buen cuerpo. Cada lote tiene su personalidad, y descubrirla es parte del placer.

Si quieres probarlo, conoce nuestros cafés tostados con dedicación para resaltar todo ese origen.
MD,
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['slug' => $post['slug']],
                $post + ['is_published' => true],
            );
        }
    }
}

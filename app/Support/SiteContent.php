<?php

namespace App\Support;

use App\Models\SiteSetting;

/**
 * Editable texts of the public site. Each field falls back to its default
 * until the administrator saves a value for it.
 */
class SiteContent
{
    /**
     * @return array<string, array{label: string, fields: array<string, array{label: string, default: string, type: string}>}>
     */
    public static function sections(): array
    {
        $features = [
            ['Envíos a todo el país', 'Rápidos y seguros'],
            ['Compra segura', 'Tus datos están protegidos'],
            ['Soporte personalizado', 'Estamos para ayudarte'],
            ['Café que conecta', 'Disfruta, comparte, regala'],
        ];

        $featureFields = [];
        foreach ($features as $index => [$title, $text]) {
            $number = $index + 1;
            $featureFields["feature_{$number}_title"] = ['label' => "Beneficio {$number}: título", 'default' => $title, 'type' => 'text'];
            $featureFields["feature_{$number}_text"] = ['label' => "Beneficio {$number}: subtítulo", 'default' => $text, 'type' => 'text'];
        }

        $chapters = [
            ['El origen', 'Nacimos entre montañas', 'Café Los Pinos nace en el Huila, una tierra de clima generoso, suelos fértiles y gente que trabaja el campo con paciencia. Aquí el café no es solo un cultivo: es una tradición que pasa de generación en generación y une a las familias alrededor de la mesa.'],
            ['Nuestra gente', 'Caficultores que cuidan cada grano', 'Trabajamos de la mano con caficultores del Huila que conocen cada cafetal. Su esfuerzo diario, desde la siembra hasta la cosecha, es el verdadero secreto de nuestro café. Apoyarlos es apoyar el desarrollo de nuestras comunidades.'],
            ['El proceso', 'Tostado con pasión y precisión', 'Seleccionamos los mejores granos y los tostamos con dedicación para resaltar su aroma, cuerpo y sabor. Cada lote se prepara con cuidado para que lo que llega a tu taza sea fiel a su origen.'],
            ['Hoy', 'Del campo a tu taza', 'Hoy llevamos el sabor de nuestras montañas a hogares de todo el país. Cada bolsa de Café Los Pinos es una invitación a disfrutar, compartir y regalar una tradición que se vive en cada sorbo.'],
        ];

        $values = [
            ['Origen', 'Café 100% colombiano, cultivado en las montañas del Huila.'],
            ['Comunidad', 'Trabajamos junto a caficultores y apoyamos a sus familias.'],
            ['Calidad', 'Granos seleccionados y tostados con precisión en cada lote.'],
        ];

        $storyPageFields = [];
        foreach ($chapters as $index => [$eyebrow, $title, $text]) {
            $number = $index + 1;
            $storyPageFields["chapter_{$number}_eyebrow"] = ['label' => "Capítulo {$number}: texto pequeño", 'default' => $eyebrow, 'type' => 'text'];
            $storyPageFields["chapter_{$number}_title"] = ['label' => "Capítulo {$number}: título", 'default' => $title, 'type' => 'text'];
            $storyPageFields["chapter_{$number}_text"] = ['label' => "Capítulo {$number}: texto", 'default' => $text, 'type' => 'textarea'];
        }
        foreach ($values as $index => [$title, $text]) {
            $number = $index + 1;
            $storyPageFields["value_{$number}_title"] = ['label' => "Valor {$number}: título", 'default' => $title, 'type' => 'text'];
            $storyPageFields["value_{$number}_text"] = ['label' => "Valor {$number}: descripción", 'default' => $text, 'type' => 'textarea'];
        }
        $storyPageFields['story_cta'] = ['label' => 'Frase final', 'default' => 'Prueba la tierra del Huila en tu taza', 'type' => 'text'];

        return [
            'hero' => [
                'label' => 'Portada',
                'fields' => [
                    'hero_eyebrow' => ['label' => 'Texto pequeño superior', 'default' => 'Café tostado Los Pinos', 'type' => 'text'],
                    'hero_title' => ['label' => 'Título', 'default' => 'El verdadero sabor del Huila en cada taza', 'type' => 'text'],
                    'hero_text' => ['label' => 'Descripción', 'default' => 'Café de origen, tostado con pasión, cultivado por nuestra gente. Disfruta la esencia de nuestras montañas en cada sorbo.', 'type' => 'textarea'],
                    'hero_button' => ['label' => 'Texto del botón', 'default' => 'Comprar ahora', 'type' => 'text'],
                    'badge_1' => ['label' => 'Insignia 1 (Enter = salto de línea)', 'default' => "100% Café\nColombiano", 'type' => 'textarea'],
                    'badge_2' => ['label' => 'Insignia 2', 'default' => "Origen\nHuila", 'type' => 'textarea'],
                    'badge_3' => ['label' => 'Insignia 3', 'default' => "Calidad\ngarantizada", 'type' => 'textarea'],
                ],
            ],
            'products' => [
                'label' => 'Sección de productos',
                'fields' => [
                    'products_eyebrow' => ['label' => 'Texto pequeño superior', 'default' => 'Nuestros productos', 'type' => 'text'],
                    'products_title' => ['label' => 'Título', 'default' => 'Café tostado de alta calidad', 'type' => 'text'],
                    'products_text' => ['label' => 'Descripción', 'default' => 'Seleccionamos los mejores granos, los tostamos con precisión y te llevamos un café con todo su aroma, cuerpo y sabor.', 'type' => 'textarea'],
                    'products_button' => ['label' => 'Texto del botón', 'default' => 'Ver todos los productos', 'type' => 'text'],
                ],
            ],
            'story' => [
                'label' => 'Historia',
                'fields' => [
                    'story_quote' => ['label' => 'Frase destacada (Enter = salto de línea)', 'default' => "Más que café,\nes nuestra tierra", 'type' => 'textarea'],
                    'story_title' => ['label' => 'Título', 'default' => 'Del campo a tu taza', 'type' => 'text'],
                    'story_text' => ['label' => 'Descripción', 'default' => 'En Café Los Pinos trabajamos de la mano con caficultores del Huila, apoyando el desarrollo de nuestras comunidades y llevando lo mejor de nuestra tierra a tu mesa.', 'type' => 'textarea'],
                    'story_button' => ['label' => 'Texto del botón', 'default' => 'Conoce nuestra historia', 'type' => 'text'],
                ],
            ],
            'story_page' => [
                'label' => 'Página Nuestra historia',
                'fields' => $storyPageFields,
            ],
            'features' => [
                'label' => 'Beneficios',
                'fields' => $featureFields,
            ],
            'shipping' => [
                'label' => 'Envíos',
                'fields' => [
                    'shipping_cost' => ['label' => 'Costo de envío en COP (0 = se confirma por WhatsApp)', 'default' => '0', 'type' => 'text'],
                    'free_shipping_from' => ['label' => 'Envío gratis desde este valor en COP (0 = nunca)', 'default' => '0', 'type' => 'text'],
                ],
            ],
            'seo' => [
                'label' => 'Buscadores (Google)',
                'fields' => [
                    'seo_description' => ['label' => 'Descripción que aparece en Google', 'default' => 'Café de origen del Huila, tostado con pasión y cultivado por nuestra gente. Compra Café Los Pinos con envíos a todo el país.', 'type' => 'textarea'],
                ],
            ],
            'footer' => [
                'label' => 'Pie de página y redes',
                'fields' => [
                    'newsletter_label' => ['label' => 'Texto del boletín', 'default' => 'Suscríbete y recibe nuestras novedades', 'type' => 'text'],
                    'instagram_url' => ['label' => 'Enlace de Instagram', 'default' => '#', 'type' => 'text'],
                    'facebook_url' => ['label' => 'Enlace de Facebook', 'default' => '#', 'type' => 'text'],
                    'whatsapp_number' => ['label' => 'Número de WhatsApp (indicativo + número, solo dígitos)', 'default' => '573208332844', 'type' => 'text'],
                    'whatsapp_message' => ['label' => 'Mensaje inicial de WhatsApp', 'default' => 'Hola, quiero información sobre el café Los Pinos.', 'type' => 'textarea'],
                    'footer_copy' => ['label' => 'Texto de derechos', 'default' => '© 2025 Café Los Pinos. Todos los derechos reservados.', 'type' => 'text'],
                ],
            ],
        ];
    }

    /**
     * @return array<string, array{label: string, default: string, type: string}>
     */
    public static function fields(): array
    {
        return array_merge(...array_column(self::sections(), 'fields'));
    }

    /**
     * Current value of every field, using the default when none is saved.
     *
     * @return array<string, string>
     */
    public static function all(): array
    {
        $saved = SiteSetting::query()->pluck('value', 'key');

        return collect(self::fields())
            ->map(fn (array $field, string $key): string => $saved[$key] ?? $field['default'])
            ->all();
    }

    /**
     * Link that opens a WhatsApp chat with the saved number and message.
     *
     * @param  array<string, string>  $content
     * @param  string|null  $message  Overrides the saved initial message.
     */
    public static function whatsappLink(array $content, ?string $message = null): string
    {
        $number = preg_replace('/\D/', '', $content['whatsapp_number']);

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message ?? $content['whatsapp_message']);
    }

    /**
     * Shipping charge for an order subtotal, following the saved settings.
     *
     * @param  array<string, string>  $content
     */
    public static function shippingFor(int $subtotal, array $content): int
    {
        $cost = (int) $content['shipping_cost'];
        $freeFrom = (int) $content['free_shipping_from'];

        return $freeFrom > 0 && $subtotal >= $freeFrom ? 0 : $cost;
    }

    /**
     * @param  array<string, string|null>  $values
     */
    public static function save(array $values): void
    {
        $known = self::fields();

        foreach ($values as $key => $value) {
            if (! isset($known[$key])) {
                continue;
            }

            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }
    }
}

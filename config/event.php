<?php

/*
|--------------------------------------------------------------------------
| Datos de la invitación
|--------------------------------------------------------------------------
| Todo el contenido editable de la invitación vive aquí. Cambia estos valores
| (o sus equivalentes en el archivo .env) para personalizar la invitación sin
| tocar el código de las vistas.
*/

return [

    // Nombre del festejado y título principal
    'host_name' => env('EVENT_HOST_NAME', 'Kevin'),
    'title' => env('EVENT_TITLE', '¡Estás invitado!'),
    'subtitle' => env('EVENT_SUBTITLE', 'A mi fiesta de cumpleaños 🎉'),
    'tagline' => env('EVENT_TAGLINE', 'Edición Mundialista'),

    // Anfitriones (firma "Con cariño, ...") y título corto para los correos
    'hosts' => env('EVENT_HOSTS', 'Kevin y Marinés'),
    'short_title' => env('EVENT_SHORT_TITLE', 'El cumpleaños de Kevin'),

    // Fecha y hora del evento (ISO 8601). Se usa para la cuenta regresiva.
    'datetime' => env('EVENT_DATETIME', '2026-06-20 13:00:00'),
    'timezone' => env('EVENT_TIMEZONE', 'America/Guatemala'),

    // Texto legible de fecha y hora (lo que se muestra al invitado)
    'date_label' => env('EVENT_DATE_LABEL', 'Sábado 20 de junio de 2026'),
    'time_label' => env('EVENT_TIME_LABEL', '1:00 PM'),

    // Ubicación
    'venue_name' => env('EVENT_VENUE_NAME', 'Edificio Real de las Américas'),
    'address' => env('EVENT_ADDRESS', '4ta Avenida 23-55, Zona 14, Edificio Real de las Américas'),
    'map_lat' => (float) env('EVENT_MAP_LAT', 14.571603970040183),
    'map_lng' => (float) env('EVENT_MAP_LNG', -90.5240814777078),
    'map_zoom' => (int) env('EVENT_MAP_ZOOM', 17),
    // Link directo para "Cómo llegar" (Google Maps). Si está vacío se genera con lat/lng.
    'maps_url' => env('EVENT_MAPS_URL', 'https://www.google.com/maps/dir/?api=1&destination=14.571603970040183%2C-90.5240814777078'),
    // Imagen del edificio donde será (en /public). Reemplázala por la tuya.
    'venue_image' => env('EVENT_VENUE_IMAGE', '/images/venue.png'),

    // Parqueo
    'parking' => [
        'title' => env('EVENT_PARKING_TITLE', '¿Dónde parqueo?'),
        'description' => env('EVENT_PARKING_DESC', 'Tienes dos opciones para dejar tu carro tranquilo:'),
        'options' => [
            'Enfrente del edificio Real de las Américas.',
            'En el parqueo que está a la par del edificio SEKKEI.',
        ],
        // Imagen del parqueo junto al edificio SEKKEI (en /public). Reemplázala por la tuya.
        'image' => env('EVENT_PARKING_IMAGE', '/images/parking.png'),
        'image_caption' => env('EVENT_PARKING_CAPTION', 'Parqueo a un lado del edificio SEKKEI'),
    ],

    // Dress code
    'dress_code' => [
        'title' => env('EVENT_DRESSCODE_TITLE', 'Dress Code'),
        'description' => env('EVENT_DRESSCODE_DESC', 'Ven con la camiseta de tu selección favorita. ¡Que se sienta el ambiente mundialista!'),
        // Imagen del dress code (en /public). Reemplázala por la tuya.
        'image' => env('EVENT_DRESSCODE_IMAGE', '/images/dresscode.png'),
    ],

    // Comida
    'food' => [
        'title' => env('EVENT_FOOD_TITLE', 'La comida 🍽️'),
        'description' => env('EVENT_FOOD_DESC', 'Nosotros ponemos el almuerzo, así que tú solo llega con hambre y ganas de celebrar. 😄'),
        'note' => env('EVENT_FOOD_NOTE', '¿Quieres quedarte al partido de la noche? ¡Quédate el tiempo que quieras! Si te da hambre más tarde, puedes pedir comida a domicilio a esta misma dirección.'),
    ],

    // Mensaje de bienvenida / descripción larga
    'description' => env('EVENT_DESCRIPTION', 'Lo vamos a celebrar a lo grande con pura temática mundialista. Buena comida, buen ambiente y mucho fútbol. ¡No puedes faltar!'),

    // El partido principal del día
    'match' => [
        'title' => env('EVENT_MATCH_TITLE', 'El partidazo del día'),
        'home' => env('EVENT_MATCH_HOME', 'Alemania'),
        'home_flag' => env('EVENT_MATCH_HOME_FLAG', '🇩🇪'),
        'away' => env('EVENT_MATCH_AWAY', 'Costa de Marfil'),
        'away_flag' => env('EVENT_MATCH_AWAY_FLAG', '🇨🇮'),
        'note' => env('EVENT_MATCH_NOTE', '¿Tienes miedo de perderte el juego por venir a la fiesta? ¡Tranquilo! Lo estaremos viendo todos juntos aquí en mi cumpleaños. Así que no te pierdes nada: ¡fiesta y fútbol en el mismo lugar!'),
    ],

    // Textos del formulario RSVP
    'rsvp' => [
        'deadline_label' => env('EVENT_RSVP_DEADLINE', 'Confirma antes del 15 de junio'),
        'max_guests' => (int) env('EVENT_MAX_GUESTS', 5),
    ],

    // Hashtag o frase del pie de página
    'footer_note' => env('EVENT_FOOTER_NOTE', 'Te espero para celebrar juntos ⚽🏆'),
];

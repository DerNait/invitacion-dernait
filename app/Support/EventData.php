<?php

namespace App\Support;

use Carbon\Carbon;

class EventData
{
    /**
     * Devuelve todos los datos del evento listos para usar en vistas y correos.
     */
    public static function all(): array
    {
        $event = config('event');

        $tz = $event['timezone'] ?? config('app.timezone');
        $datetime = Carbon::parse($event['datetime'], $tz);

        $mapsUrl = $event['maps_url']
            ?: 'https://www.google.com/maps/search/?api=1&query='
                . $event['map_lat'] . ',' . $event['map_lng'];

        return [
            'hostName' => $event['host_name'],
            'hosts' => $event['hosts'],
            'shortTitle' => $event['short_title'],
            'title' => $event['title'],
            'subtitle' => $event['subtitle'],
            'tagline' => $event['tagline'],
            'description' => $event['description'],

            // ISO con offset para que el contador en el navegador sea exacto
            'datetimeIso' => $datetime->toIso8601String(),
            'dateLabel' => $event['date_label'],
            'timeLabel' => $event['time_label'],

            'venueName' => $event['venue_name'],
            'address' => $event['address'],
            'mapLat' => $event['map_lat'],
            'mapLng' => $event['map_lng'],
            'mapZoom' => $event['map_zoom'],
            'mapsUrl' => $mapsUrl,
            'venueImage' => $event['venue_image'],

            'parking' => [
                'title' => $event['parking']['title'],
                'description' => $event['parking']['description'],
                'options' => $event['parking']['options'],
                'image' => $event['parking']['image'],
                'imageCaption' => $event['parking']['image_caption'],
            ],

            'food' => [
                'title' => $event['food']['title'],
                'description' => $event['food']['description'],
                'note' => $event['food']['note'],
            ],

            'match' => [
                'title' => $event['match']['title'],
                'home' => $event['match']['home'],
                'homeFlag' => $event['match']['home_flag'],
                'away' => $event['match']['away'],
                'awayFlag' => $event['match']['away_flag'],
                'note' => $event['match']['note'],
            ],

            'dressCode' => [
                'title' => $event['dress_code']['title'],
                'description' => $event['dress_code']['description'],
                'image' => $event['dress_code']['image'],
            ],

            'rsvp' => [
                'deadlineLabel' => $event['rsvp']['deadline_label'],
                'maxGuests' => $event['rsvp']['max_guests'],
            ],

            'footerNote' => $event['footer_note'],
        ];
    }
}

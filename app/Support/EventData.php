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
        $end = isset($event['end_datetime'])
            ? Carbon::parse($event['end_datetime'], $tz)
            : $datetime->copy()->addHours(6);

        $mapsUrl = $event['maps_url']
            ?: 'https://www.google.com/maps/search/?api=1&query='
                . $event['map_lat'] . ',' . $event['map_lng'];

        // Link "Agregar a Google Calendar" (prellenado, sin autenticación).
        $googleCalendarUrl = 'https://calendar.google.com/calendar/render?' . http_build_query([
            'action' => 'TEMPLATE',
            'text' => $event['short_title'],
            'dates' => $datetime->copy()->utc()->format('Ymd\THis\Z')
                . '/' . $end->copy()->utc()->format('Ymd\THis\Z'),
            'details' => $event['description'],
            'location' => $event['venue_name'] . ', ' . $event['address'],
        ]);

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

            // Agregar al calendario
            'googleCalendarUrl' => $googleCalendarUrl,
            'icsUrl' => '/invitacion.ics',

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

    /**
     * Genera el contenido del archivo .ics (iCalendar) del evento, compatible con
     * Google Calendar, Apple Calendar y Outlook.
     */
    public static function ics(): string
    {
        $event = config('event');
        $tz = $event['timezone'] ?? config('app.timezone');

        $start = Carbon::parse($event['datetime'], $tz)->utc();
        $end = isset($event['end_datetime'])
            ? Carbon::parse($event['end_datetime'], $tz)->utc()
            : $start->copy()->addHours(6);

        // Escapa caracteres especiales según RFC 5545.
        $esc = fn (string $s): string => addcslashes($s, ",;\\");

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//invitacion.dernait.com//ES',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:cumple-kevin-2026@invitacion.dernait.com',
            'DTSTAMP:' . now()->utc()->format('Ymd\THis\Z'),
            'DTSTART:' . $start->format('Ymd\THis\Z'),
            'DTEND:' . $end->format('Ymd\THis\Z'),
            'SUMMARY:' . $esc($event['short_title']),
            'DESCRIPTION:' . $esc($event['description']),
            'LOCATION:' . $esc($event['venue_name'] . ', ' . $event['address']),
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return implode("\r\n", $lines) . "\r\n";
    }
}

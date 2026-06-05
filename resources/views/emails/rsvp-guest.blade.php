@component('mail::message')
# {{ $rsvp->attending ? '¡Nos vemos pronto, ' . $rsvp->name . '! ⚽' : 'Gracias por avisarnos, ' . $rsvp->name }}

@if ($rsvp->attending)
Tu asistencia a **{{ $event['shortTitle'] }}** quedó confirmada.

Estos son los detalles para que no se te pase nada:

@component('mail::panel')
🗓️ **Fecha:** {{ $event['dateLabel'] }}
🕖 **Hora:** {{ $event['timeLabel'] }}
📍 **Lugar:** {{ $event['venueName'] }}
🏟️ **Dirección:** {{ $event['address'] }}
👕 **Dress code:** {{ $event['dressCode']['description'] }}
@endcomponent

@component('mail::button', ['url' => $event['mapsUrl']])
Cómo llegar
@endcomponent

¡Prepara tu camiseta y nos vemos en la cancha! 🏆
@else
Lamentamos que no puedas acompañarnos esta vez. ¡Gracias por tomarte el tiempo de avisar!

Si cambias de planes, puedes volver a confirmar desde la invitación.
@endif

Con cariño,<br>
{{ $event['hosts'] }}
@endcomponent

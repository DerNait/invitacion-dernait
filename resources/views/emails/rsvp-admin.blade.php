@component('mail::message')
# Nueva confirmación recibida

**{{ $rsvp->name }}** acaba de responder tu invitación.

@component('mail::panel')
👤 **Nombre:** {{ $rsvp->name }}
✉️ **Correo:** {{ $rsvp->email }}
{{ $rsvp->attending ? '✅ **Asiste:** Sí' : '❌ **Asiste:** No' }}
@if ($rsvp->attending)
👥 **Acompañantes:** {{ $rsvp->guests_count }} (total {{ $rsvp->total_people }} personas)
@if (!empty($rsvp->guest_names))
🧑‍🤝‍🧑 **Vienen con:** {{ implode(', ', $rsvp->guest_names) }}
@endif
@endif
@if ($rsvp->message)
💬 **Mensaje:** {{ $rsvp->message }}
@endif
@endcomponent

@component('mail::button', ['url' => url('/admin')])
Ver panel de confirmaciones
@endcomponent
@endcomponent

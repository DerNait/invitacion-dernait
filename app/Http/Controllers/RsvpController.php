<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRsvpRequest;
use App\Mail\RsvpAdminNotification;
use App\Mail\RsvpGuestConfirmation;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RsvpController extends Controller
{
    public function store(StoreRsvpRequest $request)
    {
        $data = $request->validated();

        // Upsert por correo: si ya confirmó, actualizamos su respuesta.
        $rsvp = Rsvp::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'attending' => $data['attending'],
                'guests_count' => $data['guests_count'] ?? 0,
                'guest_names' => $data['guest_names'] ?? [],
                'message' => $data['message'] ?? null,
            ]
        );

        $this->sendNotifications($rsvp);

        return back()->with('success', $rsvp->attending
            ? '¡Confirmación recibida! Te esperamos. ⚽'
            : 'Gracias por avisarnos. ¡Te vamos a extrañar!');
    }

    /**
     * Envía el correo de confirmación al invitado y el aviso al anfitrión.
     * No interrumpe la respuesta si el correo falla (por ej. sin API key todavía).
     */
    protected function sendNotifications(Rsvp $rsvp): void
    {
        try {
            // Correo de confirmación al invitado.
            Mail::to($rsvp->email)->send(new RsvpGuestConfirmation($rsvp));

            // Aviso a todos los admins de que alguien confirmó.
            $adminEmails = User::query()->pluck('email')->filter()->all();
            if (! empty($adminEmails)) {
                Mail::to($adminEmails)->send(new RsvpAdminNotification($rsvp));
            }
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar el correo de RSVP: ' . $e->getMessage());
        }
    }
}

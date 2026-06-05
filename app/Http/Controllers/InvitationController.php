<?php

namespace App\Http\Controllers;

use App\Support\EventData;
use Inertia\Inertia;
use Inertia\Response;

class InvitationController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Invitation', [
            'event' => EventData::all(),
        ]);
    }

    /**
     * Descarga el evento como archivo .ics (Apple Calendar, Outlook, etc.).
     */
    public function calendar()
    {
        return response(EventData::ics(), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="cumple-kevin.ics"',
        ]);
    }
}

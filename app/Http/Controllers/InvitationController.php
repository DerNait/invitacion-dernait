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
}

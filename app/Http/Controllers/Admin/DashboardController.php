<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rsvp;
use App\Support\EventData;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $rsvps = Rsvp::latest()->get();
        $trashed = Rsvp::onlyTrashed()->latest('deleted_at')->get();

        $attending = $rsvps->where('attending', true);
        $declined = $rsvps->where('attending', false);

        return Inertia::render('Admin/Dashboard', [
            'event' => EventData::all(),
            'rsvps' => $rsvps->map(fn (Rsvp $r) => $this->present($r)),
            'trashed' => $trashed->map(fn (Rsvp $r) => $this->present($r)),
            'stats' => [
                'responses' => $rsvps->count(),
                'attending' => $attending->count(),
                'declined' => $declined->count(),
                'total_people' => $attending->sum(fn (Rsvp $r) => $r->total_people),
                'hidden' => $trashed->count(),
            ],
        ]);
    }

    /**
     * Da formato a un RSVP para enviarlo al panel.
     */
    protected function present(Rsvp $r): array
    {
        return [
            'id' => $r->id,
            'name' => $r->name,
            'email' => $r->email,
            'attending' => $r->attending,
            'guests_count' => $r->guests_count,
            'guest_names' => $r->guest_names ?? [],
            'total_people' => $r->total_people,
            'message' => $r->message,
            'created_at_label' => $r->created_at->timezone(config('event.timezone'))->format('d/m/Y H:i'),
        ];
    }
}

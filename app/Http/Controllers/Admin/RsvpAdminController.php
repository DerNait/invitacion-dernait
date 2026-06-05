<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rsvp;
use Illuminate\Http\RedirectResponse;

class RsvpAdminController extends Controller
{
    /**
     * Oculta (soft-delete) una confirmación: deja de aparecer pero no se borra de la BD.
     */
    public function destroy(Rsvp $rsvp): RedirectResponse
    {
        $rsvp->delete();

        return back()->with('success', "Se ocultó la confirmación de {$rsvp->name}.");
    }

    /**
     * Restaura una confirmación ocultada.
     */
    public function restore(int $rsvp): RedirectResponse
    {
        $record = Rsvp::onlyTrashed()->findOrFail($rsvp);
        $record->restore();

        return back()->with('success', "Se restauró la confirmación de {$record->name}.");
    }
}

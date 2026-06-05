<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rsvp extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'attending',
        'guests_count',
        'guest_names',
        'message',
    ];

    protected $casts = [
        'attending' => 'boolean',
        'guests_count' => 'integer',
        'guest_names' => 'array',
    ];

    /**
     * Total de personas que representa este RSVP (el invitado + acompañantes),
     * solo si confirmó asistencia.
     */
    public function getTotalPeopleAttribute(): int
    {
        return $this->attending ? 1 + (int) $this->guests_count : 0;
    }
}

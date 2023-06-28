<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $table = 'event_participants';
    protected $fillable = [
        'id',
        'user_id',
        'event_id',
    ];
}

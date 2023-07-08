<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'detail',
        'category',
        'tag',
        'participation_condition',
        'external_link',
        'date',
        'deadline_date',
        'place',
        'number_of_recruits',
        'image_path',
        'organizer_id',
    ];
}

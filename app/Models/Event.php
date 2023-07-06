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
        'conditions_of_participation',
        'estarnal_link',
        'datetime',
        'place',
        'number_of_people',
        'product_image',
        'creator_id',
    ];
}

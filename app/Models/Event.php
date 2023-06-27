<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'details',
        'category',
        'tag',
        'conditions_of_participation',
        'extarnal_links',
        'datetime',
        'place',
        'number_of_people',
        'product_image',
    ];
}

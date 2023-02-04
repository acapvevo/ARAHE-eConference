<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'participant_id',
        'lineOne',
        'lineTwo',
        'lineThree',
        'city',
        'postcode',
        'state',
        'country',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'participant_id',
        'phoneNumber',
        'faxNumber'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];
}

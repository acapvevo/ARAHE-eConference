<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupancy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'locality',
        'type',
        'number',
        'bookBefore',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'bookBefore' => 'date',
    ];

    /**
     * Get the Form that owns the Occupancy.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the Rates associated with the Occupancy.
     */
    public function Rate()
    {
        return $this->hasMany(Rate::class);
    }
}

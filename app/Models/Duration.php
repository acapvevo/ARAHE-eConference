<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Duration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'locality',
        'start',
        'end',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'date',
        'end' => 'date'
    ];

    /**
     * Get the Form that owns the Duration.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the Fees associated with the Duration.
     */
    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
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
        'description',
        'code',
        'checkIn',
        'checkOut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'checkIn' => 'date',
        'checkOut' => 'date',
    ];

    /**
     * Get the Form that owns the Hotel.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the Rates associated with the Hotel.
     */
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function getDaysAndNights()
    {
        $nights = $this->checkIn->diffInDays($this->checkOut) . 'N';
        $days = ($this->checkIn->diffInDays($this->checkOut) + 1) . 'D';

        return $days . $nights;
    }
}

<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getLocality()
    {
        return DB::table('locality')->where('code', $this->locality)->first();
    }

    public function getInputDateFormat($point)
    {
        return $this->{$point}->translatedFormat('Y-m-d');
    }

    public function getLongDateFormat($point)
    {
        return $this->{$point}->translatedFormat('j F Y');
    }

    public function getShortDateFormat($point)
    {
        return $this->{$point}->translatedFormat('d/m/Y');
    }

    public function updateFeesInStripe()
    {
        foreach($this->fees as $fee) {
            $fee->updateStripe();
        }
    }
}

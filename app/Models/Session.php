<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Session extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'year',
        'submission',
        'registration',
        'congress',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'submission' => AsCollection::class,
        'registration' => AsCollection::class,
        'congress' => AsCollection::class,
    ];

    /**
     * Get the Form that owns the Session.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function returnDateString($attribute, $point)
    {
        return Carbon::parse($this->{$attribute}[$point])->translatedFormat('j F Y');
    }
}

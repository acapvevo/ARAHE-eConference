<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'code',
        'description',
        'date',
        'options',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'options' => AsCollection::class,
    ];

    /**
     * Get the Form that owns the Extra.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get all of the Extra's fees.
     */
    public function fees()
    {
        return $this->morphMany(Fee::class, 'parent');
    }

    public function updateFeesInStripe()
    {
        foreach($this->fees as $fee) {
            $fee->updateStripe();
        }
    }
}

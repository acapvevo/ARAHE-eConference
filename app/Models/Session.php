<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'review',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'submission' => AsCollection::class,
        'review' => AsCollection::class,
    ];

    /**
     * Get the Form that owns the Session.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}

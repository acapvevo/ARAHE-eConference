<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'locality',
        'needProof'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Form that owns the Category.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the Packages associated with the Category.
     */
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Get the Extras associated with the Category.
     */
    public function extras()
    {
        return $this->hasMany(Extra::class);
    }
}

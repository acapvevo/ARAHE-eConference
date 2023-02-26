<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'code',
        'description',
        'fullPackage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Get the Category that owns the Package.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all of the Package's fees.
     */
    public function fees()
    {
        return $this->morphMany(Fee::class, 'parent');
    }
}

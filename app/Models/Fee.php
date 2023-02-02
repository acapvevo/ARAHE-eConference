<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'duration_id',
        'amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Category that owns the Session.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the Duration that owns the Session.
     */
    public function duration()
    {
        return $this->belongsTo(Duration::class);
    }
}

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
        'parent_id',
        'parent_type',
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
     * Get the parent model (Extra or Package).
     */
    public function parent()
    {
        return $this->morphTo();
    }


    /**
     * Get the Duration that owns the Fee.
     */
    public function duration()
    {
        return $this->belongsTo(Duration::class);
    }
}

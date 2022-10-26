<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rubric_id',
        'submission_id',
        'pass',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Rubric that owns the Mark.
     */
    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }

    /**
     * Get the Submission that owns the Mark.
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}

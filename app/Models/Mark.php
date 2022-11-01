<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'scale_code',
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

    public function getScale()
    {
        return DB::table('scale')->where('code', $this->scale_code)->first();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Session associated with the Form.
     */
    public function session()
    {
        return $this->hasOne(Session::class);
    }

    /**
     * Get the Rubrics for the Form.
     */
    public function rubrics()
    {
        return $this->hasMany(Rubric::class);
    }

    /**
     * Get the Submissions that use the Form.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function calculateFullMark()
    {
        $fullMark = 0;

        foreach ($this->rubrics as $rubric) {
            $fullMark += $rubric->mark;
        }

        return $fullMark;
    }
}

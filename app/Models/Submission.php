<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'participant_id',
        'abstract',
        'title',
        'file',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Form that owns the Submission.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the Participant that owns the Submission.
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get the Marks for the Form.
     */
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}

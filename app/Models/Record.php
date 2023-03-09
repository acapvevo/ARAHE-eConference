<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_id',
        'reviewer_id',
        'accept',
        'reject',
        'return',
        'assign',
        'reviewing',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Reviewer that owns the Record.
     */
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }

    /**
     * Get the Form that owns the Record.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}

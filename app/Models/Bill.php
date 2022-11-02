<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'submission_id',
        'amount',
        'code',
        'pay_attempt_at',
        'pay_complete_at',
        'status',
        'reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Submission that owns the Bill.
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function getPayAttemptAt()
    {
        return Carbon::parse($this->pay_attempt_at)->translatedFormat('j F Y');
    }

    public function getPayCompleteAt()
    {
        return Carbon::parse($this->pay_complete_at)->translatedFormat('j F Y');
    }
}

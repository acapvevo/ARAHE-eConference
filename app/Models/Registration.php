<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
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
        'code',
        'register_as',
        'category_id',
        'proof',
        'status_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    public function generateCode()
    {
        $currentYearSubmissions = DB::table('submissions')->where('registration_id', 'LIKE', '%ARAHE-' . Carbon::now()->year . '-%')->get();

        if ($currentYearSubmissions->isEmpty()) {
            $latestIndex = 0;
        } else {
            $latestIndex = (int) explode('-', $currentYearSubmissions->last()->registration_id)[2];
        }

        $this->registration_id = 'ARAHE-' . Carbon::now()->year . '-' . ($latestIndex + 1);
    }

    public function getStatusLabel()
    {
        return DB::table('status')->where('code', $this->status_code)->first()->label;
    }

    public function getStatusDescription()
    {
        return DB::table('status')->where('code', $this->status_code)->first()->description;
    }

    /**
     * Get the Form that use by the Submission.
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
     * Get the Bill associated with the Submission.
     */
    public function bill()
    {
        return $this->hasOne(Bill::class);
    }
}

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
    protected $casts = [
        'created_at' => 'date'
    ];

    public function generateCode()
    {
        $currentYearSubmissions = DB::table('registrations')->where('code', 'LIKE', '%ARAHE-' . Carbon::now()->year . '-%')->get();

        if ($currentYearSubmissions->isEmpty()) {
            $latestIndex = 0;
        } else {
            $latestIndex = (int) explode('-', $currentYearSubmissions->last()->code)[2];
        }

        $this->code = 'ARAHE-' . Carbon::now()->year . '-' . ($latestIndex + 1);
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
     * Get the Form that use by the Registration.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the Participant that owns the Registration.
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get the Category associated with the Registration.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the Submission associated with the Registration.
     */
    public function submission()
    {
        return $this->hasOne(Submission::class);
    }

    /**
     * Get the Bill associated with the Registration.
     */
    public function bill()
    {
        return $this->hasOne(Bill::class);
    }
}
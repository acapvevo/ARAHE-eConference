<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_id',
        'abstract',
        'title',
        'paper',
        'reviewer_id',
        'totalMark',
        'comment',
        'correction',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Reviewer that review the Submission.
     */
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }

    /**
     * Get the Marks for the Submission.
     */
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function checkEnableSubmit()
    {
        return $this->status_code === 'N' || $this->status_code === 'C';
    }

    public function calculatePercentage()
    {
        return ($this->totalMark / $this->form->calculateFullMark()) * 100;
    }

    public function uploadPaper($type, $request)
    {
        $fileName = preg_replace('/\s+/', '_', '[' . strtoupper($type) . '] ' . $this->title);
        $request->file($type)->storeAs('ARAHE' . $this->form->session->year . '/' . $type, $fileName);

        $this->{$type} = $fileName;
    }

    public function deletePaper($type)
    {
        Storage::delete('ARAHE' . $this->form->session->year . '/' . $type . '/' . $this->{$type});
        $this->correction = null;
    }
}

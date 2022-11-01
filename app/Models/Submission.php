<?php

namespace App\Models;

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
        'form_id',
        'participant_id',
        'abstract',
        'title',
        'paper',
        'status_code',
        'reviewer_id',
        'mark',
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

    public function getStatusLabel()
    {
        return DB::table('status')->where('code', $this->status_code)->first()->label;
    }

    public function getStatusDescription()
    {
        return DB::table('status')->where('code', $this->status_code)->first()->description;
    }

    public function checkEnableSubmit()
    {
        return $this->status_code === 'N' || $this->status_code === 'C';
    }

    public function calculatePercentage()
    {
        return ($this->mark / $this->form->calculateFullMark()) * 100;
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

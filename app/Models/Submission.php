<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
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
        'title',
        'authors',
        'coAuthors',
        'presenter',
        'abstract',
        'abstractFile',
        'keywords',
        'paperFile',
        'reviewer_id',
        'totalMark',
        'comment',
        'correctionFile',
        'status_code',
        'submitDate',
        'acceptedDate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'submitDate' => 'date',
        'acceptedDate' => 'date',
        'authors' => AsCollection::class,
        'coAuthors' => AsCollection::class,
    ];

    public function getStatusLabel()
    {
        return DB::table('status')->where('code', $this->status_code)->first()->label;
    }

    public function getStatusDescription()
    {
        return DB::table('status')->where('code', $this->status_code)->first()->description;
    }

    /**
     * Get the Registration that review the Submission.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
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

    public function setSubmitDate()
    {
        $this->submitDate = Carbon::now();
    }

    public function setAcceptedDate()
    {
        $this->acceptedDate = Carbon::now();
    }

    public function checkEnableSubmit()
    {
        return $this->status_code === 'N' || $this->status_code === 'C';
    }

    public function calculatePercentage()
    {
        return ($this->totalMark / $this->registration->form->calculateFullMark()) * 100;
    }

    public function uploadPaper($type, $request)
    {
        $fileName = preg_replace('/\s+/', '_', '[' . strtoupper($type) . '] ' . $this->title . '.' . $request->file($type)->getClientOriginalExtension());
        $request->file($type)->storeAs('ARAHE' . $this->form->session->year . '/' . $type, $fileName);

        $this->{$type} = $fileName;
    }

    public function saveFile($type, $attribute, $file)
    {
        $fileName = preg_replace('/\s+/', '_', '[' . strtoupper($type) . '] ' . $this->title . '.' . $file->getClientOriginalExtension());
        $file->storeAs('ARAHE' . $this->registration->form->session->year . '/paper/' . str_replace('-', '_', $this->registration->code), $fileName);

        $this->{$attribute} = $fileName;
    }

    public function deletePaper($type)
    {
        Storage::delete('ARAHE' . $this->form->session->year . '/' . $type . '/' . $this->{$type});
        $this->correction = null;
    }
}

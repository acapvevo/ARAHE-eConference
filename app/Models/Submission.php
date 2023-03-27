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
        'submitDate' => 'datetime',
        'acceptedDate' => 'datetime',
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
     * Get the Registration that owns the Submission.
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

    public function getSubmitDate()
    {
        return $this->submitDate ? Carbon::parse($this->submitDate)->toRfc2822String() : '';
    }

    public function getAcceptedDate()
    {
        return $this->acceptedDate ? Carbon::parse($this->acceptedDate)->toRfc2822String() : '';
    }

    public function checkEnableSubmit()
    {
        return $this->status_code === 'N' || $this->status_code === 'C';
    }

    public function calculatePercentage()
    {
        return ($this->totalMark / $this->registration->form->calculateFullMark()) * 100;
    }

    public function saveFile($type, $attribute, $file)
    {
        $fileName = preg_replace('/\s+/', '_', '[' . strtoupper($type) . '] ' . $this->title . '.' . $file->getClientOriginalExtension());
        $file->storeAs('ARAHE' . $this->registration->form->session->year . '/submission/' . str_replace('-', '_', $this->registration->code), $fileName);

        $this->{$attribute} = $fileName;
    }

    public function deleteFile($attribute)
    {
        Storage::delete('ARAHE' . $this->registration->form->session->year . '/submission/' . str_replace('-', '_', $this->registration->code) . '/' . $this->{$attribute});

        $this->{$attribute} = null;
    }

    public function getFilePath($attribute)
    {
        return 'ARAHE' . $this->registration->form->session->year . '/submission/' . str_replace('-', '_', $this->registration->code) . '/' . $this->{$attribute};
    }
}

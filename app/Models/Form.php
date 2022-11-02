<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    /**
     * Get the Category associated with the Form.
     */
    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function calculateFullMark()
    {
        $countScale = DB::table('scale')->get()->count();
        return $this->rubrics->count() * $countScale;
    }
}

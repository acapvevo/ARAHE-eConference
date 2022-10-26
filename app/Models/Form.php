<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

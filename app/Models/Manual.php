<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'guard',
        'file',
    ];

    public function formatNameWithoutSpace()
    {
        return str_replace(' ', '', $this->nama);
    }
}

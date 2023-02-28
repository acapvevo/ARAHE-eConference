<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Summary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_id',
        'duration_id',
        'package_id',
        'extras',
        'hotel_id',
        'occupancy_id',
        'total',
        'locality',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'extras' => AsCollection::class,
    ];

    /**
     * Get the Registration that owns the Summary.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function getDuration()
    {
        return Duration::find($this->duration_id);
    }

    public function getPackage()
    {
        return Package::find($this->package_id);
    }

    public function getExtras()
    {
        return Extra::find($this->extras->pluck(['id']));
    }

    public function getHotel()
    {
        return Hotel::find($this->hotel_id);
    }

    public function getOccupancy()
    {
        return Occupancy::find($this->occupancy_id);
    }

    public function getLocality()
    {
        return DB::table('locality')->where('code', $this->locality)->first();
    }
}

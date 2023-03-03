<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

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
     * Get the Registrations that use the Form.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the Categories associated with the Form.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the Durations associated with the Form.
     */
    public function durations()
    {
        return $this->hasMany(Duration::class);
    }

    /**
     * Get the Extras associated with the Form.
     */
    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

    /**
     * Get the Hotels associated with the Form.
     */
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    /**
     * Get the Occupancies associated with the Form.
     */
    public function occupancies()
    {
        return $this->hasMany(Occupancy::class);
    }

    public function getPackages()
    {
        return Package::whereIn('category_id', $this->categories->pluck('id'))->get();
    }

    public function getRates()
    {
        return Rate::whereIn('hotel_id', $this->hotels->pluck('id'))->get();
    }

    public function calculateFullMark()
    {
        $countScale = DB::table('scale')->get()->count();
        return $this->rubrics->count() * $countScale;
    }

    public function getDurationBasedCurrentDate($locality_code)
    {
        $currentDate = Carbon::now();

        return $this->durations->where('locality', $locality_code)->first(function($duration) use ($currentDate){
            return $currentDate->between($duration->start, $duration->end);
        });
    }

    public function getOccupanciesByLocality($locality_code)
    {
        return $this->occupancies->where('locality', $locality_code);
    }

    public function getHotelsByLocality($locality_code)
    {
        return $this->hotels->where('locality', $locality_code);
    }

    public function updateDurationsInStripe()
    {
        foreach($this->durations as $duration)
        {
            $duration->updateFeesInStripe();
        }
    }

    public function updateCategoiesInStripe()
    {
        foreach($this->getPackages() as $package)
        {
            $package->updateFeesInStripe();
        }
    }

    public function updateHotelsInStripe()
    {
        foreach($this->hotels as $hotel)
        {
            $hotel->updateRatesInStripe();
        }
    }

    public function updateOccupansiesInStripe()
    {
        foreach($this->occupancies as $occupancy)
        {
            $occupancy->updateRatesInStripe();
        }
    }
}

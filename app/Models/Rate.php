<?php

namespace App\Models;

use App\Plugins\Stripes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hotel_id',
        'duration_id',
        'amount',
        'product_id',
        'price_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the Hotel that owns the Rate.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the Occupancy that owns the Rate.
     */
    public function occupancy()
    {
        return $this->belongsTo(Occupancy::class);
    }

    public function getLocality()
    {
        return DB::table('locality')->where('code', $this->occupancy->locality)->first();
    }

    public function updateStripe()
    {
        if ($this->product_id) {
            $name = '[' . $this->getLocality()->name . '] HotelAccommodation_' . $this->hotel->code . '-Occupancy_' . $this->occupancy->type;
            $description = 'Including ' . $this->hotel->description . ' (' . $this->occupancy->type . ') as accommodation for ARAHE conference';

            Stripes::updateProduct($this->product_id, $name, $description);
        }
    }
}

<?php

namespace App\Models;

use PDO;
use App\Plugins\Stripes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'parent_type',
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
     * Get the parent model (Extra or Package).
     */
    public function parent()
    {
        return $this->morphTo();
    }

    /**
     * Get the Duration that owns the Fee.
     */
    public function duration()
    {
        return $this->belongsTo(Duration::class);
    }

    public function updateStripe()
    {
        if ($this->product_id) {
            $name = '[' . $this->duration->getLocality()->name . '] Package_' . $this->parent->code . '-Duration_' . $this->duration->name;
            $description = $this->parent->parent_type == 'App\Models\Package' ? $this->parent->description : 'Including ' . $this->parent->description . ' in overall trip for ARAHE conference';

            Stripes::updateProduct($this->product_id, $name, $description);
        }
    }
}

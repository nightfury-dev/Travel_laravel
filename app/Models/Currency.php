<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    
    protected $table = 'currency';
    protected $primaryKey = 'id';

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $currency_id = $obj->id;

            $itinerary_daily = ItineraryDaily::where('product_price_currency', $currency_id)->get();
            if (!empty($itinerary_daily)) {
                $itinerary_daily->each(function ($iti_dai) {
                    $iti_dai->delete();
                });
            }

            $product_pricing = ProductPricing::where('currency', $currency_id)->get();
            if (!empty($product_pricing)) {
                $product_pricing->each(function ($pro_pri) {
                    $pro_pri->delete();
                });
            }
        });
    }
}

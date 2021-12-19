<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItineraryDaily extends Model
{
    // use SoftDeletes;
    
    protected $table = 'itinerary_daily';
    protected $primaryKey = 'id';

    protected $fillable = ["id", "itinerary_id", "product_id", "product_price_tag", "product_price_season", "product_price_currency", "product_price_id", "itinerary_margin_type", "itinerary_margin_price", "date", "start_time", "end_time", "adults_num", "children_num"];

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $itinerary_daily_id = $obj->id;

            $task_detail = TaskDetail::where('daily_id', $itinerary_daily_id)->get();
            if (!empty($task_detail)) {
                $task_detail->each(function ($con_chk) {
                    $con_chk->delete();
                });
            }
        });
    }

    public function get_product()
    {
        $product = Product::find($this->product_id);
        return $product;
    }

    public function get_time()
    {
        $time = $this->date . ' ' . $this->start_time . ' ~ ' . $this->end_time;
        return $time;

    }

    public function get_product_prices()
    {
        $price_ids = explode(':', $this->product_price_id);
        $product_prices = collect([]);
        foreach ($price_ids as $price_id) {
            $product_prices->push(ProductPricing::find($price_id));
        }

        return $product_prices;
    }

    // public function confirm_check()
    // {
    //     $confirmation = Contact::where('itinerary_daily_id', $this->id)->where('flag', 1)->first();
    //     return $confirmation;
    // }
}

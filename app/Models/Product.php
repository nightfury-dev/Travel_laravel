<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $table = 'product';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'title', 'category', 'zip', 'country', 'city', 'state', 'position', 'street_address', 'start_time', 'end_time', 'supplier'];

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $product_id = $obj->id;

			$itinerary_daily = ItineraryDaily::where('product_id', $product_id)->get();
			if(!empty($itinerary_daily)) {
				$itinerary_daily->each(function($iti_dai) { 
                    $iti_dai->delete(); 
                });
			}

			$itinerary_template = ItineraryTemplate::where('product_id', $product_id)->get();
			if(!empty($itinerary_template)) {
				$itinerary_template->each(function($iti_tem) { 
                    $iti_tem->delete(); 
                });
			}
        });
    }

    public function get_supplier()
    {
        $supplier = User::find($this->supplier);
        return $supplier;
    }
    
    public function getDescription()
    {
        return $this->hasMany('App\Models\ProductDescription', 'product_id', 'id');
    }

    public function getFirstImage()
    {
        return $this->hasOne('App\Models\ProductGallery', 'product_id', 'id');
    }

    public function getGallery()
    {
        return $this->hasMany('App\Models\ProductGallery', 'product_id', 'id');
    }

    public function getPrice()
    {
        return $this->hasMany('App\Models\ProductPricing', 'product_id', 'id');
    }

    public function getCategory()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category');
    }
    public function get_category()
    {
        $category = Category::find($this->category);
        return $category;
    }

    public function getCountry()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country');
    }
    public function get_country()
    {
        $country = Country::find($this->country);
        return $country;
    }

    public function getCity()
    {
        return $this->hasOne('App\Models\City', 'id', 'city');
    }
    public function get_city()
    {
        $city = City::find($this->city);
        return $city;
    }
}

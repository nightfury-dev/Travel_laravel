<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskDetail extends Model
{
    
    // use SoftDeletes;
    
    protected $table = 'task_detail';
    protected $primaryKey = 'id';

    protected $fillable = ["id", "task_id", "daily_id"];

    public function get_ItineraryDaily() 
    {
        $itinerary_daily = ItineraryDaily::find($this->daily_id);
        return $itinerary_daily;
    }
    
    public function get_product_title() {
        $product_title = Product::find($this->get_ItineraryDaily()->product_id)->title;
        return $product_title;
    }

    public function get_product_main_gallery() {
        $product = Product::find($this->get_ItineraryDaily()->product_id);
        $gallery = ProductGallery::where('product_id', $product->id)->first()->path;
        return $gallery;
    }

    public function get_product_seasons(){
        $price_seasons = explode(':', $this->get_ItineraryDaily()->product_price_season);
        $html = '';
        for($i=0; $i<count($price_seasons); $i++) {
            $html .= $price_seasons[$i] . '<br/>';
        }
        return $html;
    }

    public function get_Datetime() {
        $time = $this->get_ItineraryDaily()->date . ' ' . $this->get_ItineraryDaily()->start_time . ' ~ ' . $this->get_ItineraryDaily()->end_time;
        return $time;
    }

    public function get_product_prices(){
        $price_ids = explode(':', $this->get_ItineraryDaily()->product_price_id);
        $price_currs = explode(':', $this->get_ItineraryDaily()->product_price_currency);
        $price_tags = explode(':', $this->get_ItineraryDaily()->product_price_tag);
        
        $html = '';
        for($i=0; $i<count($price_ids); $i++) {
            $tag = CategoryTag::find($price_tags[$i])->title;
            $currency = Currency::find($price_currs[$i])->title;

            $html .= $tag .' - '. $price_ids[$i] .'('. $currency .') <br />';
        }
        return $html;
    }

    public function get_margin() {
        $margin_price = $this->get_ItineraryDaily()->itinerary_margin_price;
        $margin_type = $this->get_ItineraryDaily()->itinerary_margin_type;
        $html = '';
        if($margin_type == 1) {
            $html = $margin_price . '(%)';
        }
        else {
            $html = $margin_price . '(Fixed)';
        } 
        return $html;
    }

    public function get_persons() {
        $adults_num = $this->get_ItineraryDaily()->adults_num;
        $children_num = $this->get_ItineraryDaily()->children_num;

        return 'Adults: ' . $adults_num . ' Childrens: ' . $children_num;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\Enquiry;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductDescription;
use App\Models\ProductPricing;
use App\Models\Currency;
use App\Models\CategoryTag;
use App\Models\Category;
use App\Models\Language;
use App\Models\ItineraryDaily;
use App\Models\ItineraryTemplate;
use App\Models\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DB;
use Mail;
use PDF;

use Config;

class ShowCaseController extends Controller
{
    //ecommerce
    public function index(Request $request) {
        $itinerary_id = $request->itinerary_id;
        $created_id = Itinerary::find($itinerary_id)->created_id;
    
        $email = Account::find($created_id)->main_email;
        $street_address = Account::find($created_id)->main_street_address;
        $city = Account::find($created_id)->main_city;
        $postal_code = Account::find($created_id)->main_postal_code;
        $region = Account::find($created_id)->main_region_state;
        $country = Account::find($created_id)->main_country;
        $phone = Account::find($created_id)->main_office_phone;
    
        $account_info = array(
            'email' => $email,
            'street_address' => $street_address,
            'city' => $city,
            'postal_code' => $postal_code,
            'region' => $region,
            'country' => $country,
            'phone' => $phone
        );
    
        $schedule_date = DB::table('itinerary_daily')
            ->select('date')
            ->where('itinerary_id', $itinerary_id)
            ->groupBy('date')
            ->get();
    
        $itinerary_daily = DB::table('itinerary_daily')
          ->select('product.id as product_id', 'product.title as product_title', 'category.title as category_title', 'product.country as country_title', 'product.city as city_title', 'itinerary_daily.date', 'itinerary_daily.start_time', 'itinerary_daily.end_time', 'itinerary_daily.adults_num', 'itinerary_daily.children_num')
          ->join('product', 'product.id', '=', 'itinerary_daily.product_id')
          ->join('category', 'product.category', '=', 'category.id')
          ->where('itinerary_daily.itinerary_id', $itinerary_id)
          ->orderBy('itinerary_daily.start_time')
          ->get();
    
        $itinerary_schedule_data = array();
    
        for($i = 0; $i<count($schedule_date); $i++) {
          $temp = array();
          for($j=0; $j<count($itinerary_daily); $j++) {
            if($schedule_date[$i]->date == $itinerary_daily[$j]->date) {
              $product_id = $itinerary_daily[$j]->product_id;
    
              $product_gallery = ProductGallery::where('product_id', $product_id)->get();
    
              $schedule_record = $itinerary_daily[$j];
              $schedule_record->product_gallery = $product_gallery;
    
              array_push($temp, $schedule_record);
            }
          }
    
          $itinerary_schedule_data[$schedule_date[$i]->date] = $temp;
        }
    
        $itinerary_currency = Itinerary::find($itinerary_id)->currency;
        $itinerary_budget = Itinerary::find($itinerary_id)->budget;
    
        $itinerary_info = array(
          'itinerary_currency' => $itinerary_currency,
          'itinerary_budget' => $itinerary_budget
        );
    
        return view('pages.showcase', array(
          'account_info' => $account_info,
          'itinerary_schedule_info' => $itinerary_schedule_data,
          'itinerary_info' => $itinerary_info
        ));
    }
}

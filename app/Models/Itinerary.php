<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itinerary extends Model
{
    use SoftDeletes;
    
    protected $table = 'itinerary';
    protected $primaryKey = 'id';

    protected $fillable = ["id", "title", "reference_number", "enquiry_id", "created_id", "updated_id", "travel_number", "budget", "currency", "from_date", "to_date", "adult_number", "children_number", "single_count", "double_count", "twin_count", "triple_count", "family_count", "note", "status"];

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $itinerary_id = $obj->id;

            $itinerary_daily = ItineraryDaily::where('itinerary_id', $itinerary_id)->get();
            if (!empty($itinerary_daily)) {
				$itinerary_daily->each(function($iti_dai) { 
                    $iti_dai->delete();
                });
            }

            $task = Task::where('itinerary_id', $itinerary_id)->get();
            if (!empty($task)) {
				$task->each(function($ta) { 
                    $ta->delete();
                });
            }
        });
    }

    public function get_enquiry() {
        return $this->hasOne('App\Models\Enquiry', 'id', 'enquiry_id');
    }

    public function get_currency() {
        return $this->hasOne('App\Models\Currency', 'id', 'currency');
    }

    public function get_customer() {
        $customer_id = Enquiry::find($this->enquiry_id)->customer_id;
        $fullname = User::find($customer_id) ->first_name. ' ' .User::find($customer_id) ->last_name;
        return $fullname;
    }
}

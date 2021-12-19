<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use SoftDeletes;
    
    protected $table = 'enquiry';
    protected $primaryKey = 'id';

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $enquiry_id = $obj->id;

            $itinerary = Itinerary::where('enquiry_id', $enquiry_id)->get();
            if (!empty($itinerary)) {
				$itinerary->each(function($iti) { 
                    $iti->delete();
                });
            }

            $enquiry_message = Message::where('link_id', $enquiry_id)->where('message_type', 1)->get();
            if (!empty($enquiry_message)) {
				$enquiry_message->each(function($iti) { 
                    $iti->delete();
                });
            }
        });
    }


    public function get_account() {
        return $this->hasOne('App\Models\User', 'id', 'customer_id');
    }

    public function get_staff() {
        return $this->hasOne('App\Models\User', 'id', 'staff_id');
    }
}
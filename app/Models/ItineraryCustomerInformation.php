<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryCustomerInformation extends Model
{
    protected $table = 'itinerary_customer_information';
    protected $primaryKey = 'id';

    protected $fillable = ["id", "itinerary_id", "customer_infomation"];
}

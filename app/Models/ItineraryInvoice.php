<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryInvoice extends Model
{
    protected $table = 'itinerary_invoice';
    protected $primaryKey = 'id';

    protected $fillable = ["id", "itinerary_id", "title", "invoice_number", "issue_date", "due_date", "quantitiy", "price", "tax", "payment_type", "status", "voucher"];

    public function get_Itinerary() {
        $currency = Itinerary::find($this->itinerary_id)->currency;
        $currency_symbol = Currency::find($currency)->title;
        return $currency_symbol;
    }

    public function get_iti() {
        return $this->hasOne('App\Models\Itinerary', 'id', 'itinerary_id');
    }
}
<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\AccountType;
use App\Models\Enquiry;
use App\Models\User;
use App\Models\Itinerary;
use App\Models\ItineraryInvoice;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;


class BookingController extends Controller
{
    public function index(){
        $pageConfigs = ['pageHeader' => true];
        $breadcrumbs = [
            ["link" => "/", "name" => "Home"],["name" => "Booking"]
        ];

        $bookings = ItineraryInvoice::all();

        return view('pages.admin.booking.index',compact('pageConfigs','breadcrumbs', 'bookings'));
    }
    
    public function preview(Request $request) {
        $booking_id = $request->booking_id;
        $itinerary_invoice = ItineraryInvoice::find($booking_id);
        $itinerary_id = $itinerary_invoice->itinerary_id;
        $itinerary = Itinerary::find($itinerary_id);
        
        return view('pages.admin.booking.preview', compact('itinerary_id', 'itinerary_invoice', 'booking_id', 'itinerary'));
    }
}
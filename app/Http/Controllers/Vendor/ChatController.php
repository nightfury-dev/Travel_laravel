<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DB;
use Mail;
use PDF;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\Enquiry;
use App\Models\Task;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductDescription;
use App\Models\ProductPricing;
use App\Models\Currency;
use App\Models\CategoryTag;
use App\Models\Category;
use App\Models\Language;


class ChatController extends Controller
{
    // chat App
    public function index(){
        $pageConfigs = ['isContentSidebar' => true, 'bodyCustomClass' => 'chat-application'];

        $session_user_id = Account::where('user_id', Auth::user()->id)->first()->id;
        $session_user_name = Account::where('user_id', Auth::user()->id)->first()->first_name . ' ' . Account::where('user_id', Auth::user()->id)->first()->last_name;
        $session_user_avatar = Account::where('user_id', Auth::user()->id)->first()->avatar_path;

        return view('pages.vendor.chat', [
            'pageConfigs' => $pageConfigs,
            'session_user_id' => $session_user_id,
            'session_user_name' => $session_user_name,
            'session_user_avatar' => $session_user_avatar
        ]);
    }
    
}

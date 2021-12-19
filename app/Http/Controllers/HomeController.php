<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //ecommerce
    public function index(){
        return view('pages.home');
    }
    
}

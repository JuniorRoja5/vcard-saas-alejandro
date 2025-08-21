<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessCardController extends Controller
{
    public function index()
    {
        return view('user.business_card');
    }
}

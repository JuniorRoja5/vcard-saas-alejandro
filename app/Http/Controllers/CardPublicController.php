<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CardPublicController extends Controller
{
    public function show(string $slug)
    {
        $card = DB::table('cards')->where('slug',$slug)->first();
        if (!$card) abort(404);
        return view('public.card', ['slug'=>$slug]);
    }
}

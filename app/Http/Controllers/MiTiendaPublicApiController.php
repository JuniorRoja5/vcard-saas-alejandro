<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MiTiendaPublicApiController extends Controller
{
    public function state(string $slug)
    {
        $card = DB::table('cards')->where('slug',$slug)->first();
        if (!$card) abort(404);

        $payload = [
            'card' => [
                'id'=>$card->id, 'user_id'=>$card->user_id, 'title'=>$card->title, 'slug'=>$card->slug,
                'status'=>$card->status ?? null, 'name'=>$card->name ?? null, 'job_title'=>$card->job_title ?? null,
                'company'=>$card->company ?? null, 'phone'=>$card->phone ?? null, 'email'=>$card->email ?? null,
                'website'=>$card->website ?? null, 'bio'=>$card->bio ?? null, 'avatar_path'=>$card->avatar_path ?? null,
                'cover_path'=>$card->cover_path ?? null, 'theme'=>$card->theme ?? null, 'is_published'=>$card->is_published ?? 0,
                'views'=>$card->views ?? 0, 'data'=>json_decode($card->data ?? "{}", true), 'social_links'=>json_decode($card->social_links ?? "[]", true),
            ],
            'links'        => DB::table('card_links')->where('card_id',$card->id)->orderBy('sort_order')->get(),
            'products'     => DB::table('card_products')->where('card_id',$card->id)->orderByDesc('id')->get(),
            'galleries'    => DB::table('card_galleries')->where('card_id',$card->id)->orderBy('sort_order')->get(),
            'hours'        => DB::table('card_hours')->where('card_id',$card->id)->orderBy('weekday')->get(),
            'testimonials' => DB::table('card_testimonials')->where('card_id',$card->id)->orderByDesc('id')->get(),
        ];

        return response()->json($payload);
    }
}

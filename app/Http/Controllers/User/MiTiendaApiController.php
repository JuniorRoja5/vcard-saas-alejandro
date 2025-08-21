<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\{Card};

class MiTiendaApiController extends Controller
{
    public function ping(){ return response()->json(['ok'=>true,'ts'=>now()]); }

    public function stateGet(Request $r){
        $r->validate(['card_id'=>'required|integer|min:1']);
        $card = Card::where('id', $r->card_id)->where('user_id', Auth::id())->firstOrFail();

        $payload = [
            'card'         => $card->only(['id','user_id','title','slug','status','name','job_title','company','phone','email','website','bio','avatar_path','cover_path','theme','is_published','views','data','social_links']),
            'links'        => DB::table('card_links')->where('card_id',$card->id)->orderBy('sort_order')->get(),
            'products'     => DB::table('card_products')->where('card_id',$card->id)->orderByDesc('id')->get(),
            'galleries'    => DB::table('card_galleries')->where('card_id',$card->id)->orderBy('sort_order')->get(),
            'hours'        => DB::table('card_hours')->where('card_id',$card->id)->orderBy('weekday')->get(),
            'testimonials' => DB::table('card_testimonials')->where('card_id',$card->id)->orderByDesc('id')->get(),
        ];
        return response()->json($payload);
    }

    public function statePost(Request $r){
        $r->validate(['card_id'=>'required|integer|min:1']);
        $card = Card::where('id', $r->card_id)->where('user_id', Auth::id())->firstOrFail();

        DB::transaction(function() use ($card, $r){
            if($r->has('card') && is_array($r->card)){
                $allowed = ['title','name','job_title','company','phone','email','website','bio','avatar_path','cover_path','theme','is_published','data','social_links'];
                $card->fill(array_intersect_key($r->card, array_flip($allowed)));
                $card->save();
            }
            if($r->has('links')) DB::table('card_links')->where('card_id',$card->id)->delete();
            if($r->has('products')) DB::table('card_products')->where('card_id',$card->id)->delete();
            if($r->has('galleries')) DB::table('card_galleries')->where('card_id',$card->id)->delete();
            if($r->has('hours')) DB::table('card_hours')->where('card_id',$card->id)->delete();
            if($r->has('testimonials')) DB::table('card_testimonials')->where('card_id',$card->id)->delete();
            if($r->has('links')){
                $rows=[]; foreach ((array)$r->links as $x){
                    $rows[]=['card_id'=>$card->id,'label'=>$x['label']??'','url'=>$x['url']??'','icon'=>$x['icon']??None,'type'=>$x['type']??None,'sort_order'=>$x['sort_order']??0];
                }
                if($rows) DB::table('card_links')->insert($rows);
            }
            if($r->has('products')){
                $rows=[]; foreach ((array)$r->products as $x){
                    $rows[]=['card_id'=>$card->id,'name'=>$x['name']??'','description'=>$x['description']??None,'price'=>$x['price']??None,'currency'=>$x['currency']??'USD','sku'=>$x['sku']??None,'image_path'=>$x['image_path']??None,'meta'=>isset($x['meta'])?json_encode($x['meta']):None];
                }
                if($rows) DB::table('card_products')->insert($rows);
            }
            if($r->has('galleries')){
                $rows=[]; foreach ((array)$r->galleries as $x){
                    $rows[]=['card_id'=>$card->id,'title'=>$x['title']??None,'image_path'=>$x['image_path']??'','meta'=>isset($x['meta'])?json_encode($x['meta']):None,'sort_order'=>$x['sort_order']??0];
                }
                if($rows) DB::table('card_galleries')->insert($rows);
            }
            if($r->has('hours')){
                $rows=[]; foreach ((array)$r->hours as $x){
                    $rows[]=['card_id'=>$card->id,'weekday'=>(int)($x['weekday']??0),'open_time'=>$x['open_time']??None,'close_time'=>$x['close_time']??None,'is_closed'=>!empty($x['is_closed'])];
                }
                if($rows) DB::table('card_hours')->insert($rows);
            }
            if($r->has('testimonials')){
                $rows=[]; foreach ((array)$r->testimonials as $x){
                    $rows[]=['card_id'=>$card->id,'author'=>$x['author']??'','role'=>$x['role']??None,'content'=>$x['content']??None,'rating'=>$x['rating']??None];
                }
                if($rows) DB::table('card_testimonials')->insert($rows);
            }
        });

        return response()->json(['ok'=>true]);
    }

    public function inventory(Request $r){
        $r->validate(['card_id'=>'required|integer|min:1']);
        $card = Card::where('id', $r->card_id)->where('user_id', Auth::id())->firstOrFail();
        return response()->json(['products'=>DB::table('card_products')->where('card_id',$card->id)->orderByDesc('id')->get()]);
    }
}

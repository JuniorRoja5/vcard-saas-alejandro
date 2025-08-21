<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CardController extends Controller
{
 // app/Http/Controllers/CardController.php
public function index(Request $req) {
  $q = \App\BusinessCard::query();
  if ($term = $req->q) $q->where(fn($w)=>$w->where('title','like',"%$term%")->orWhere('card_url','like',"%$term%"));
  if ($s = $req->status) $q->where('card_status',$s);
  $cards = $q->latest()->paginate(12);
  return view('cards.index', compact('cards'));
}
public function create(){ return view('cards.create'); }
public function store(Request $r){
  $data = $r->validate([
    'title'=>'required','sub_title'=>'nullable','type'=>'required',
    'card_lang'=>'nullable','description'=>'nullable','cover'=>'nullable',
    'card_url'=>'nullable','card_status'=>'required'
  ]);
  $card = \App\BusinessCard::create($data);
  return redirect()->route('cards.index')->with('success','Card created.');
}
public function edit($id){ $card=\App\BusinessCard::findOrFail($id); return view('cards.edit',compact('card')); }
public function update(Request $r,$id){
  $card=\App\BusinessCard::findOrFail($id);
  $card->update($r->except('_token','_method'));
  return redirect()->route('cards.index')->with('success','Card updated.');
}
public function destroy($id){
  \App\BusinessCard::findOrFail($id)->delete();
  return back()->with('success','Card deleted.');
}

}

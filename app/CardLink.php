<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class CardLink extends Model {
    protected $fillable = ['card_id','label','url','icon','type','sort_order'];
    public function card(){ return $this->belongsTo(Card::class); }
}
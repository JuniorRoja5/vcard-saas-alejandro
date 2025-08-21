<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class CardProduct extends Model {
    protected $fillable = ['card_id','name','description','price','currency','sku','image_path','meta'];
    protected $casts = ['meta'=>'array','price'=>'decimal:2'];
    public function card(){ return $this->belongsTo(Card::class); }
}
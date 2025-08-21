<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class CardTestimonial extends Model {
    protected $fillable = ['card_id','author','role','content','rating'];
    public function card(){ return $this->belongsTo(Card::class); }
}
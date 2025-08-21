<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardTestimonial extends Model
{
    protected $fillable = ['card_id','author','role','content','rating'];
    protected $casts = ['rating'=>'integer'];
    public function card(){ return $this->belongsTo(Card::class); }
}

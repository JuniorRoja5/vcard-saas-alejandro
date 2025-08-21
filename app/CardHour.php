<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class CardHour extends Model {
    protected $fillable = ['card_id','weekday','open_time','close_time','is_closed'];
    protected $casts = ['is_closed'=>'boolean'];
    public function card(){ return $this->belongsTo(Card::class); }
}
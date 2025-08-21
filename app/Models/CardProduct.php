<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardProduct extends Model
{
    protected $fillable = ['card_id','name','description','price','currency','sku','image_path','meta'];
    protected $casts = ['price' => 'decimal:2', 'meta' => 'array'];
    public function card(){ return $this->belongsTo(Card::class); }
}

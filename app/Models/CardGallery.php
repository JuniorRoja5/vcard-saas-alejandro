<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardGallery extends Model
{
    protected $fillable = ['card_id','title','image_path','meta','sort_order'];
    protected $casts = ['meta' => 'array', 'sort_order' => 'integer'];
    public function card(){ return $this->belongsTo(Card::class); }
}

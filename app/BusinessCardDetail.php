<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class BusinessCardDetail extends Model {
    protected $table = 'business_card_details';
    protected $guarded = [];
    public function business_card() {
        return $this->belongsTo(BusinessCard::class, 'card_id', 'id');
    }
}
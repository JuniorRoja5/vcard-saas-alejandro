<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class BusinessCard extends Model {
    protected $table = 'business_cards';
    protected $guarded = [];
    public function business_card_details() {
        return $this->hasMany(BusinessCardDetail::class, 'card_id', 'id');
    }
}
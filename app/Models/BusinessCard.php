<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCard extends Model
{
    protected $table = 'business_cards';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $casts = [
        'custom_styles' => 'array',
        'is_newsletter_pop_active' => 'boolean',
        'is_info_pop_active' => 'boolean',
        'expiry_time' => 'datetime',
    ];

    protected $fillable = [
        'card_id', 'user_id', 'type', 'theme_id', 'card_lang', 'cover', 'cover_type',
        'profile', 'card_url', 'custom_domain', 'card_type', 'title', 'sub_title',
        'description', 'enquiry_email', 'appointment_receive_email',
        'is_newsletter_pop_active', 'is_info_pop_active', 'custom_styles', 'custom_css',
        'custom_js', 'password', 'expiry_time', 'delivery_options', 'seo_configurations',
        'card_status', 'status',
    ];
}

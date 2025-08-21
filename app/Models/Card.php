<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','title','slug','status','name','job_title','company','phone','email',
        'website','bio','avatar_path','cover_path','theme','is_published','views',
        'data','social_links'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'views'        => 'integer',
        'data'         => 'array',
        'social_links' => 'array',
    ];

    // Relations
    public function links()        { return $this->hasMany(CardLink::class); }
    public function products()     { return $this->hasMany(CardProduct::class); }
    public function galleries()    { return $this->hasMany(CardGallery::class); }
    public function hours()        { return $this->hasMany(CardHour::class); }
    public function testimonials() { return $this->hasMany(CardTestimonial::class); }

    // Scopes
    public function scopeOwnedBy($q, $userId) { return $q->where('user_id', $userId); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'cart_token',
        'service_id',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service that belongs to the cart.
     */
    public function service()
    {
        return $this->belongsTo(OurService::class, 'service_id');
    }
}

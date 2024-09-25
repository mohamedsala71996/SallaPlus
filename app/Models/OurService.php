<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurService extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'photo', 'price'];


    public function carts()
    {
        return $this->hasMany(Cart::class, 'service_id');
    }
}

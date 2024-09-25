<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'business_email',
        'phone_number',
        'company_name',
        'job_title',
        'average_online_orders',
        'has_store',
        'hear_about',
    ];


    protected $casts = [
        'hear_about' => 'array', // This will automatically convert the JSON field to an array
    ];
}

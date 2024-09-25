<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    // Specify the table if it's not following Laravel's naming convention
    protected $table = 'features';

    // Define the fillable properties
    protected $fillable = [
        'name',
        'category_id',
        'basic_package',
        'advanced_package',
        'professional_package',
    ];

    // Define the relationship with the Category model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

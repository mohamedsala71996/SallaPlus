<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionItem extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'title', 'description', 'icon', 'photo', 'order','link','statistics'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}

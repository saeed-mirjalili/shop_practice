<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'categories';
    protected $guarded = [];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
    
    public function product() {
        return $this->hasMany(Product::class);
    }

}

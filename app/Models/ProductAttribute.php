<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $table = 'product_attributes';
    protected $fillable = ['product_id','attribute_id','attribute_value','status'];
    public $timestamps = false;
    function  product(){
        return $this->belongsTo(Product::class);
    }

    function  attribute(){
        return $this->belongsTo(Attribute::class);
    }
}

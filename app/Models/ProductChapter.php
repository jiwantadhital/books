<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductChapter extends Model
{
    use HasFactory;
    protected $table = 'product_chapters';
    protected $fillable = ['product_id','chapter_id','attribute_value','status'];
    public $timestamps = false;
    function  product(){
        return $this->belongsTo(Product::class);
    }

    function  chapter(){
        return $this->belongsTo(Chapter::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'products';
    protected $fillable = ['title','image','description','status','created_by','updated_by','feature_product','flash_product',];

    function  createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    function  updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }

    function  attributes(){
        return $this->belongsToMany(Attribute::class,'product_attributes','product_id','attribute_id');
    }
    function  chapters(){
        return $this->hasMany(Chapter::class);
    }
    function  comments(){
        return $this->hasMany(Comments::class)->with('user');
    }
    function  favourites(){
        return $this->hasMany(Get::class);
    }
}

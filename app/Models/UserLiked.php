<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLiked extends Model
{
    use HasFactory;
    protected $table = 'user_like';
    protected $fillable = ['user_id','product_id'];

    

    // function  attributes(){
    //     return $this->belongsToMany(Attribute::class,'product_attributes','product_id','attribute_id');
    // }
    function  products(){
        return $this->belongsTo(Product::class,'product_id')->with('chapters','createdBy','attributes','comments');
    }
    function  comments(){
        return $this->hasMany(Comments::class);
    }
    
}

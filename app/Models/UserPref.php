<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPref extends Model
{
    use HasFactory;
    protected $table = 'user_pref';
    protected $fillable = ['user_id','product_id','attribute_id'];

    

    // function  attributes(){
    //     return $this->belongsToMany(Attribute::class,'product_attributes','product_id','attribute_id');
    // }
    function  products(){
        return $this->belongsTo(Product::class,'product_id');
    }
    function  comments(){
        return $this->hasMany(Comments::class);
    }
    
}

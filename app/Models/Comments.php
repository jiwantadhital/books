<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'comments';
    protected $fillable = ['user_id','product_id','likes','comments','created_by','updated_by'];

    function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }
    function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }
    function  novel(){
        return $this->belongsTo(Product::class);
    }
}

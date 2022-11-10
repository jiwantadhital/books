<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'chapters';
    protected $fillable = ['product_id','name','slug','number','description','status','created_by','updated_by'];

    function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }
    function  novel(){
        return $this->belongsTo(Product::class);
    }
}

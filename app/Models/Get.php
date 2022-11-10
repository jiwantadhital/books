<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Get extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'favourites';
    protected $fillable = ['product_id','user','created_by','updated_by'];

    function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class onboard extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'onboards';
    protected $fillable = ['title','description','image','status','created_by','updated_by'];

    function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }
}

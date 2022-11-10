<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'modules';
    protected $fillable = ['name','route','status','created_by','updated_by'];

    function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }
    function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }

    function  permissions(){
        return $this->hasMany(Permission::class);
    }
}

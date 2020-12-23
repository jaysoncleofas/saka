<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breakfast extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function transactions()
    {
    	return $this->belongsToMany('App\Models\Transaction');
    }
}

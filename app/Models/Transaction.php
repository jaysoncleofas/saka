<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $dates = ['checkIn_at', 'checkOut_at'];

    public function cottage()
    {
    	return $this->belongsTo('App\Models\Cottage');
    }

    public function room()
    {
    	return $this->belongsTo('App\Models\Room');
    }

    public function client()
    {
    	return $this->belongsTo('App\Models\Client');
    }

    public function breakfasts()
    {
    	return $this->belongsToMany('App\Models\Breakfast');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $dates = ['checkIn_at', 'checkOut_at'];

    public function cottages()
    {
    	return $this->belongsToMany('App\Models\Cottage');
    }

    public function type()
    {
    	return $this->belongsTo('App\Models\Type');
    }

    public function rooms()
    {
    	return $this->belongsToMany('App\Models\Room');
    }

    public function client()
    {
    	return $this->belongsTo('App\Models\Client');
    }
}

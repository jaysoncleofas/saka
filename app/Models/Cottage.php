<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cottage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function transaction()
    {
    	return $this->hasOne('App\Models\Transaction');
    }

    public function images()
    {
        return $this->hasMany('App\Models\CottageImage');
    }

    public function coverimage()
    {
        return $this->images()->where('is_cover', 1)->first(); 
    }
}

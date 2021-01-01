<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CottageImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'cottage_images';

    public function cottage()
    {
    	return $this->belongsTo('App\Models\Cottage');
    }
}

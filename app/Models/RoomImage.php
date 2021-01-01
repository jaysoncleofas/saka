<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'room_images';

    public function room()
    {
    	return $this->belongsTo('App\Models\Room');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Guest extends Model
{
    use Notifiable;
    use HasFactory;

    protected $guarded = ['id'];

    public function getFullNameAttribute() 
    {
		  return ucfirst($this->firstName) . ' ' . ucfirst($this->lastName);
	  }
}

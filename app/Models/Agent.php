<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = ['agent', 'total_refered', 'total_activated', 'earnings'];

    public function duser(){
    	return $this->belongsTo('App\Models\User', 'agent');
    }
}

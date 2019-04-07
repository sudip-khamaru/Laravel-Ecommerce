<?php

namespace App;

use App\City;
use App\Country;
use App\State;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $dates = [ 'deleted_at' ];

	public function user() {

		return $this->belongsTo( User::class );
	
	}

	public function country() {

		return $this->belongsTo( Country::class );
	
	}

	public function state() {

		return $this->belongsTo( State::class );
	
	}

	public function city() {

		return $this->belongsTo( City::class );
	
	}

	public function getRouteKeyName() {
	
		return "slug";
	
	}
}

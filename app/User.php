<?php

namespace App;

use App\Profile;
use App\Role;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = [ 'deleted_at' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        
        return $this->belongsTo( Role::class );

    }

    public function profile() {

        return $this->hasOne( Profile::class );
        
    }

    public function getUserCountry() {

        return $this->profile->country->name;
    
    }

    // public function getUserState() {

    //     return $this->profile->state->name;
    
    // }

    // public function getUserCity() {

    //     return $this->profile->city->name;
    
    // }

    public function getRouteKeyName() {
    
        return "slug";
    
    }
}

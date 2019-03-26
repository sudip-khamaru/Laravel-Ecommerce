<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $dates = [ 'deleted_at' ];

    public function users()
    {

    	return $this->hasMany( User::class );

    }
}

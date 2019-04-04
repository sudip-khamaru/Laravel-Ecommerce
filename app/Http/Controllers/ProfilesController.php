<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\Http\Requests\ValidateProfile;
use App\Profile;
use App\Role;
use App\State;
use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $customers = User::with( 'role', 'profile' )->paginate( 5 );

        return view( 'admin.customers.index', compact( 'customers' ) );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $roles = Role::all();
        $countries = Country::all();
        return view( 'admin.customers.create', compact( 'roles', 'countries' ) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateProfile $request)
    {
        
        $user = User::create( [

            'email'     =>  $request->email,
            'password'  =>  bcrypt( $request->password ),
            'status'    =>  $request->status,

        ] );

        $path = 'images/no-thumbnail.jpeg';
        
        if( $request->has( 'thumbnail' ) ) {

            $extension = "." . $request->thumbnail->getClientOriginalExtension();
            $name = basename( $request->thumbnail->getClientOriginalName(), $extension ) . time();
            $thumbnail = $name . $extension;
            // $path = $request->thumbnail->store( 'images' );
            $path = $request->thumbnail->storeAs( 'images/profiles', $thumbnail, 'public' );

        }

        if( $user ) {

            $profile = Profile::create( [
            
                'user_id'       => $user->id,
                'name'          => $request->name,
                'slug'          => $request->slug,
                'address'       => $request->address,
                'country_id'    => $request->country_id,
                'state_id'      => $request->state_id,
                'city_id'       => $request->city_id,
                'phone'         => $request->phone,
                'thumbnail'     => $path,

            ] );

        }

        if( $profile ) {

            // redirect
            return redirect( route( 'admin.profiles.index' ) )->with( 'message', "User Created Successfully!" );
        
        } else {

            return back()->with( 'message', "Error Creating User!" );

        } 

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        
        $customer = User::find( $profile )->first();
        $roles = Role::all();
        $countries = Country::all();
        return view( 'admin.customers.create', compact( 'customer', 'roles', 'countries' ) );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }

    public function getStates( Request $request, $id )
    {

        if( $request->ajax() ) {

            return State::where( 'country_id', $id )->get();

        } else {

            return 0;
        }

    }

    public function getCities( Request $request, $id )
    {

        if( $request->ajax() ) {

            return City::where( 'state_id', $id )->get();

        } else {

            return 0;
        }

    }
}

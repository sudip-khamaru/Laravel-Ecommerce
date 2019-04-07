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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $customers = Profile::with( 'user', 'user.role' )->paginate( 5 );

        return view( 'admin.customers.index', compact( 'customers' ) );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $countries = Country::all();
        return view( 'admin.customers.create', compact( 'countries' ) );

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
            'password'  =>  Hash::make( $request->password ),
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
        
        $customer = Profile::with( 'user', 'user.role' )->findOrFail( $profile->id );
        $roles = Role::all();
        $countries = Country::all();
        $states = State::where( 'country_id', $profile->country_id )->get();
        $cities = City::where( 'state_id', $profile->state_id )->get();

        return view( 'admin.customers.create', compact( 'customer', 'roles', 'countries', 'states', 'cities' ) );

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
        
        $user = User::where( 'id', $profile->user_id )->first();

        if( $request->password !== $user->password ) {

            // validate inputs
            $request->validate( [

                'password'          =>  'same:password_confirm',
                'password_confirm'  =>  'required',

            ] );

            $user->password = Hash::make( $request->password );

        } 

        $user->status = $request->status;
        $user->save();

        if( $request->has( 'thumbnail' ) ) {

            Storage::disk( 'public' )->delete( $profile->thumbnail );

            $extension = "." . $request->thumbnail->getClientOriginalExtension();
            $name = basename( $request->thumbnail->getClientOriginalName(), $extension ) . time();
            $thumbnail = $name . $extension;
            // $path = $request->thumbnail->store( 'images' );
            $path = $request->thumbnail->storeAs( 'images/profiles', $thumbnail, 'public' );

            $profile->thumbnail = $path;

        }

        $profile->name = $request->name;
        $profile->address = $request->address;
        $profile->country_id = $request->country_id;
        $profile->state_id = $request->state_id;
        $profile->city_id = $request->city_id;
        $profile->phone = $request->phone;

        if( $profile->save() ) {

            // redirect
            return back()->with( 'message', "User Updated Successfully!" );

        } else {

            return back()->with( 'message', "Error Updating User!" );

        } 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        
        $profile->user()->forceDelete();

        if( $profile->forceDelete() ) {

            Storage::disk( 'public' )->delete( $profile->thumbnail );

            return back()->with( 'message', "User Deleted Successfully!" );

        } else {

            return back()->with( 'message', "Error Deleting User!" );

        }

    }

    public function remove( Profile $profile )
    {
        
        if( $profile->delete() ) {

            return back()->with( 'message', "User Trashed Successfully!" );

        } else {

            return back()->with( 'message', "Error Trashing User!" );

        }

    }

    public function trash()
    {
        
        $customers = Profile::with( 'user', 'user.role' )->onlyTrashed()->paginate( 5 );
        // dd( $customers );
        $trash = 1;

        return view( 'admin.customers.index', compact( 'customers', 'trash' ) );

    }

    public function recover( $id )
    {

        // $profile = Profile::withTrashed()->findOrFail( $id );
        $profile = Profile::onlyTrashed()->findOrFail( $id );
        if( $profile->restore() ) {

            return back()->with( 'message', 'User Restored Successfully!' );

        } else {

            return back()->with( 'message', 'Error Restoring User!' );

        }

    }

    public function destroyFromTrash( $id )
    {
        
        $profile = Profile::onlyTrashed()->where( 'id', $id )->first();
        $profile->user()->forceDelete();

        if( $profile->forceDelete() ) {

            Storage::disk( 'public' )->delete( $profile->thumbnail );

            return back()->with( 'message', "User Deleted Successfully!" );

        } else {

            return back()->with( 'message', "Error Deleting User!" );

        }

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

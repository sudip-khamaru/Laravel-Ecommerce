<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryParent;
use App\Http\Requests\ValidateProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $products = Product::with( 'categories' )->paginate( 5 );

        return view( 'admin.products.index', compact( 'products' ) );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $categories = Category::all();
        // $categories = Category::with( 'childrens' )->get();

        return view( 'admin.products.create', compact( 'categories' ) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateProduct $request)
    {
        
        $path = 'images/no-thumbnail.jpeg';
        
        if( $request->has( 'thumbnail' ) ) {

            $extension = "." . $request->thumbnail->getClientOriginalExtension();
            $name = basename( $request->thumbnail->getClientOriginalName(), $extension ) . time();
            $thumbnail = $name . $extension;
            // $path = $request->thumbnail->store( 'images' );
            $path = $request->thumbnail->storeAs( 'images', $thumbnail, 'public' );

        }

        // if success, store into products table
        $product = Product::create( [

            'title'             =>  $request->title,
            'slug'              =>  $request->slug,
            'description'       =>  $request->description,
            'price'             =>  $request->price,
            'discount_price'    =>  ( $request->discount_price ) ? $request->discount_price : 0,
            'discount'          =>  ( $request->discount ) ? $request->discount : 0,
            'featured'          =>  ( $request->featured ) ? $request->featured : 0,
            'options'           =>  isset( $request->extras ) ? json_encode( $request->extras ) : NULL,
            'status'            =>  $request->status,
            'thumbnail'         =>  $path,

        ] );

        if( $product ) {

            // store category in category_product table
            $product->categories()->attach( $request->category_id, [ 'created_at' => now(), 'updated_at' => now() ] );

            // redirect
            return back()->with( 'message', "Product Added Successfully!" );
        
        } else {

            return back()->with( 'message', "Error Adding Product!" );

        } 

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        
        $categories = Category::all();
        // $categories = Category::with( 'childrens' )->get();

        return view( 'admin.products.create', compact( 'product', 'categories' ) );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        
        if( $request->has( 'thumbnail' ) ) {

            Storage::disk( 'public' )->delete( $product->thumbnail );

            $extension = "." . $request->thumbnail->getClientOriginalExtension();
            $name = basename( $request->thumbnail->getClientOriginalName(), $extension ) . time();
            $thumbnail = $name . $extension;
            // $path = $request->thumbnail->store( 'images' );
            $path = $request->thumbnail->storeAs( 'images', $thumbnail, 'public' );

            $product->thumbnail = $path;

        }

        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = ( $request->discount_price ) ? $request->discount_price : 0;
        $product->discount = ( $request->discount ) ? $request->discount : 0;
        $product->featured = ( $request->featured ) ? $request->featured : 0;
        $product->status = $request->status;

        $product->categories()->detach();

        if( $product->save() ) {

            // store category in category_product table
            $product->categories()->attach( $request->category_id, [ 'created_at' => now(), 'updated_at' => now() ] );

            // redirect
            return back()->with( 'message', "Product Updated Successfully!" );
        
        } else {

            return back()->with( 'message', "Error Updating Product!" );

        } 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        
        if( $product->categories()->count() > 0 ) {

            $product->categories()->detach(); 

        }

        if( $product->forceDelete() ) {

            Storage::disk( 'public' )->delete( $product->thumbnail );

            return back()->with( 'message', "Product Deleted Successfully!" );

        } else {

            return back()->with( 'message', "Error Deleting Product!" );

        }

    }

    public function remove( Product $product )
    {
        
        if( $product->delete() ) {

            return back()->with( 'message', "Product Trashed Successfully!" );

        } else {

            return back()->with( 'message', "Error Trashing Product!" );

        }

    }

    public function trash()
    {
        
        $products = Product::onlyTrashed()->paginate( 5 );
        $trash = 1;

        return view( 'admin.products.index', compact( 'products', 'trash' ) );

    }

    public function recover( $id )
    {

        // $product = Product::withTrashed()->findOrFail( $id );
        $product = Product::onlyTrashed()->findOrFail( $id );
        if( $product->restore() ) {

            return back()->with( 'message', 'Product Restored Successfully!' );

        } else {

            return back()->with( 'message', 'Error Restoring Product!' );

        }

    }

    public function destroyFromTrash( $id )
    {
        
        $product = Product::onlyTrashed()->where( 'id', $id )->first();
        if( $product->categories()->count() > 0 ) {

            $product->categories()->detach(); 

        }

        if( $product->forceDelete() ) {

            Storage::disk( 'public' )->delete( $product->thumbnail );

            return back()->with( 'message', "Product Deleted Successfully!" );

        } else {

            return back()->with( 'message', "Error Deleting Product!" );

        }

    }

    public function showAllProduct()
    {

        $categories = Category::all();
        $products = Product::all();

        return view( 'products.show-all', compact( 'categories', 'products' ) );
    
    }
}

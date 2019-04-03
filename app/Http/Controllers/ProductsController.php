<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ValidateProduct;
use App\Product;
use Illuminate\Http\Request;

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
        
        // dd( $request->all() );
        $extension = "." . $request->thumbnail->getClientOriginalExtension();
        $name = basename( $request->thumbnail->getClientOriginalName(), $extension ) . time();
        $thumbnail = $name . $extension;
        // $path = $request->thumbnail->store( 'images' );
        $path = $request->thumbnail->storeAs( 'images', $thumbnail );

        // if success, store into products table
        $product = Product::create( [

            'title'             =>  $request->title,
            'slug'              =>  $request->slug,
            'description'       =>  $request->description,
            'price'             =>  $request->price,
            'discount_price'    =>  ( $request->discount_price ) ? $request->discount_price : 0,
            'discount'          =>  ( $request->discount ) ? $request->discount : 0,
            'featured'          =>  ( $request->featured ) ? $request->featured : 0,
            'status'            =>  $request->status,
            'thumbnail'         =>  $path,

        ] );

        if( $product ) {

            // store category in category_product table
            $product->categories()->attach( $request->category_id, [ 'created_at' => now(), 'updated_at' => now() ] );

            // redirect
            return back()->with( 'message', "Product Added Successfully!" );
        
        } else {

            return back()->with( 'message', "Error Adding Product" );

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Category;
use App\CategoryParent;
use App\Http\Requests\ValidateProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
            return back()->with( 'message', "Product added successfully!" );
        
        } else {

            return back()->with( 'message', "Error adding product!" );

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
        
        // $categories = Category::all();
        
        // return view( 'products.show-single', compact( 'product', 'categories' ) );

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
            return back()->with( 'message', "Product updated successfully!" );
        
        } else {

            return back()->with( 'message', "Error updating product!" );

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

            return back()->with( 'message', "Product deleted successfully!" );

        } else {

            return back()->with( 'message', "Error deleting product!" );

        }

    }

    public function remove( Product $product )
    {
        
        if( $product->delete() ) {

            return back()->with( 'message', "Product trashed successfully!" );

        } else {

            return back()->with( 'message', "Error trashing product!" );

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

            return back()->with( 'message', 'Product restored successfully!' );

        } else {

            return back()->with( 'message', 'Error restoring product!' );

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

            return back()->with( 'message', "Product deleted successfully!" );

        } else {

            return back()->with( 'message', "Error deleting product!" );

        }

    }

    public function showAllProduct()
    {

        $categories = Category::with( 'childrens' )->get();
        $products = Product::with( 'categories' )->paginate( 5 );

        return view( 'products.show-all', compact( 'categories', 'products' ) );
    
    }

    public function showSingleProduct( Product $product )
    {

        $categories = Category::all();
        
        return view( 'products.show-single', compact( 'product', 'categories' ) );
    
    }

    public function addToCart( Request $request, Product $product )
    {
        
        $old_cart = Session::has( 'cart' ) ? Session::get( 'cart' ) : null;

        $quantity = $request->qty ? $request->qty : 1;

        $new_cart = new Cart( $old_cart );
        $new_cart->addProductToCart( $product, $quantity );

        Session::put( 'cart', $new_cart );

        $message = $product->title .' has been successfully added to cart! <span class="view-cart-button"><a href="'. url( "/cart" ) .'"><strong>View Cart</strong><span data-feather="arrow-right"></span></a></span>';

        return back()->with( 'message', $message );

    }

    public function viewCart()
    {

        if( Session::has( 'cart' ) ) {

            $cart = Session::get( 'cart' );

            return view( 'cart.show', compact( 'cart' ) );

        }

        return view( 'cart.show' );

    }

    public function removeSingleProductFromCart( Product $product )
    {

        $old_cart = Session::has( 'cart' ) ? Session::get( 'cart' ) : null;

        $new_cart = new Cart( $old_cart );
        $new_cart->removeProductFromCart( $product );

        Session::put( 'cart', $new_cart );

        return back()->with( 'message', "$product->title has been successfully removed from cart!" );
    
    }

    public function updateSingleProductInCart( Request $request, Product $product )
    {

        dd( $request->all() );

        $old_cart = Session::has( 'cart' ) ? Session::get( 'cart' ) : null;

        $quantity = $request->qty;

        $new_cart = new Cart( $old_cart );
        $new_cart->updateProductInCart( $product, $quantity );

        Session::put( 'cart', $new_cart );

        return back()->with( 'message', "$product->title has been successfully updated in cart!" );
    
    }
}

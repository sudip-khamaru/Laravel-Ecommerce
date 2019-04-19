<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryParent;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $categories = Category::all();
        $categories = Category::paginate( 5 );

        return view( 'admin.categories.index', compact( 'categories' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $categories = Category::all();
        
        return view( 'admin.categories.create', compact( 'categories' ) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // validate inputs
        $request->validate( [

            'title' =>  'required|min:5',
            'slug'  =>  'required|min:5|unique:categories',

        ] );

        // if success, store into categories table
        $category = Category::create( $request->only( 'title', 'description', 'slug' ) );

        if( $category ) {

            // store parent in category_parent table
            $category->childrens()->attach( $request->parent_category_id, [ 'created_at' => now(), 'updated_at' => now() ] );

            // redirect
            return back()->with( 'message', "Category added successfully!" );

        } else {

            return back()->with( 'message', "Error adding category!" );

        } 

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        
        // $categories = Category::all();
        $categories = Category::where( 'id', '!=', $category->id )->get();

        $select_category = $category;

        return view( 'admin.categories.create', compact( 'categories', 'select_category' ) );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        
        $category->title = $request->title;
        $category->description = $request->description;
        // $category->slug = $request->slug;

        $category->childrens()->detach();

        if( $category->save() ) {

            // store parent in category_parent table
            $category->childrens()->attach( $request->parent_category_id, [ 'created_at' => now(), 'updated_at' => now() ] );

            // redirect
            return back()->with( 'message', "Category updated successfully!" );

        } else {

            return back()->with( 'message', "Error updating category!" );

        } 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        
        if( $category->childrens()->count() > 0 ) {

            $category->childrens()->detach(); 

        }

        if( $category->forceDelete() ) {

            return back()->with( 'message', "Category deleted successfully!" );

        } else {

            return back()->with( 'message', "Error deleting category!" );

        }

    }

    public function remove( Category $category )
    {
        
        if( $category->delete() ) {

            return back()->with( 'message', "Category trashed successfully!" );

        } else {

            return back()->with( 'message', "Error trashing category!" );

        }

    }

    public function trash()
    {
        
        $categories = Category::onlyTrashed()->paginate( 5 );
        $trash = 1;

        return view( 'admin.categories.index', compact( 'categories', 'trash' ) );

    }

    public function recover( $id )
    {

        // $category = Category::withTrashed()->findOrFail( $id );
        $category = Category::onlyTrashed()->findOrFail( $id );
        if( $category->restore() ) {

            return back()->with( 'message', 'Category restored successfully!' );

        } else {

            return back()->with( 'message', 'Error restoring category!' );

        }

    }

    public function destroyFromTrash( $id )
    {
        
        $category = Category::onlyTrashed()->where( 'id', $id )->first();
        if( $category->childrens()->count() > 0 ) {

            $category->childrens()->detach(); 

        }

        if( $category->forceDelete() ) {

            return back()->with( 'message', "Category deleted successfully!" );

        } else {

            return back()->with( 'message', "Error deleting category!" );

        }

    }
}

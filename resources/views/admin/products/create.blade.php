@extends( 'admin.app' )

@section( 'breadcrumbs' )
<li class="breadcrumb-item"><a href="{{ route( 'admin.dashboard' ) }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route( 'admin.products.index' ) }}">Products</a></li>
<li class="breadcrumb-item active" aria-current="page">
	@if( isset( $product ) && $product->count() > 0 )
		Edit Product
	@else
		Add Product
	@endif
</li>
@endsection

@section( 'content' )
<h2 class="modal-title">
	@if( isset( $product ) && $product->count() > 0 )
		Edit Product
	@else
		Add Product
	@endif
</h2>
<form method="POST" action="@if( isset( $product ) && $product->count() > 0 ) {{ route( 'admin.products.update', $product->slug ) }} @else {{ route( 'admin.products.store' ) }} @endif" accept-charset="UTF-8" enctype="multipart/form-data">
	@csrf
	@if( isset( $product ) && $product->count() > 0 )
		@method( 'PUT' )
	@endif
	<div class="row">
		<div class="col-lg-9">
			<div class="form-group row">
				<div class="col-sm-12">
					@if( $errors->any() )
					<div class="alert alert-danger">
						<ul>
						@foreach( $errors->all() as $error )
							<li>{{ $error }}</li>
						@endforeach
						</ul>
					</div>
					@endif
				</div>

				<div class="col-sm-12">
					@if( session()->has( 'message' ) )
					<div class="alert alert-success">
						{{ session( 'message' ) }}
					</div>
					@endif
				</div>	
			
				<div class="col-sm-12">
					<label for="txturl" class="form-control-label">Title:</label>
					<input type="text" id="txturl" name="title" class="form-control" value="{{ @$product->title }}">
					<p class="small">{{ config( 'app.url' ) }}/<span id="url">{{ @$product->slug }}</span>
						<input type="hidden" name="slug" id="slug" value="{{ @$product->slug }}">
					</p>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-12">
					<label for="editor" class="form-control-label">Description:</label>
					<textarea id="editor" name="description" class="form-control" rows="10" cols="80">{!! @$product->description !!}</textarea>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-6">
					<label for="basic-addon1-price" class="form-control-label">Price: </label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">$</span>
						</div>
						<input type="text" class="form-control" placeholder="0.00" aria-label="Username" aria-describedby="basic-addon1" name="price" value="{{ @$product->price }}" id="basic-addon1-price"/>
					</div>
				</div>
				<div class="col-6">
					<label for="basic-addon2-price" class="form-control-label">Discount: </label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon2">$</span>
						</div>
						<input type="text" class="form-control" name="discount_price" placeholder="0.00" aria-label="discount_price" aria-describedby="discount" value="{{ @$product->discount_price }}" id="basic-addon2-price"/>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<div class="card col-sm-12 p-0 mb-2">
					<div class="card-header align-items-center">
						<h5 class="card-title float-left">Extra Options</h5>
						<div class="float-right" >
							<button type="button" id="btn-add" class="btn btn-primary btn-sm"><span data-feather="plus"></span></button>
							<button type="button" id="btn-remove" class="btn btn-danger btn-sm"><span data-feather="minus"></span></button>
						</div>
						
					</div>
					<div class="card-body" id="extras">

					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-3">
			<ul class="list-group row">
				<li class="list-group-item active"><h5><label for="status">Status</label></h5></li>
				<li class="list-group-item">
					<div class="form-group row">
						<select class="form-control" id="status" name="status">
							<option value="0" @if( isset( $product ) && $product->status == 0 ) {{ "selected" }} @endif>Pending</option>
							<option value="1" @if( isset( $product ) && $product->status == 1 ) {{ "selected" }} @endif>Publish</option>
						</select>
					</div>
					<div class="form-group row">
						<div class="col-lg-12">
						@if( isset( $product ) )
							<input type="submit" name="submit" class="btn btn-primary btn-block" value="Update Product" />
						@else
							<input type="submit" name="submit" class="btn btn-primary btn-block" value="Add Product" />
						@endif
						</div>
					</div>
				</li>
				<li class="list-group-item active"><h5>Feaured Image</h5></li>
				<li class="list-group-item">
					<div class="input-group mb-3">
						<div class="custom-file ">
							<input type="file"  class="custom-file-input" name="thumbnail" id="thumbnail">
							<label class="custom-file-label" for="thumbnail">Choose file</label>
						</div>
					</div>
					<div class="img-thumbnail text-center">
						<img src="@if( isset( $product )) {{ asset( 'storage/' . $product->thumbnail ) }} @else {{ asset( 'images/no-thumbnail.jpeg' ) }} @endif" id="imgthumbnail" class="img-fluid" alt="">
					</div>
				</li>
				<li class="list-group-item">
					<div class="col-12">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text" ><input id="featured" type="checkbox" name="featured" value="@if( isset( $product ) ) {{ @$product->featured }} @else{{ 0 }} @endif" @if( isset( $product ) && $product->featured == 1 ) {{ "checked" }} @endif /></span>
							</div>
							<p type="text" class="form-control" name="featured" placeholder="0.00" aria-label="featured" aria-describedby="featured" >Featured Product</p>
						</div>
					</div>
				</li>
				@php
					$selected_ids = ( isset( $product ) && $product->categories->count() > 0 ) ? array_pluck( $product->categories->toArray(), 'id' ) : null;
				@endphp
				<li class="list-group-item active"><h5><label for="select2">Select Categories</label></h5></li>
				<li class="list-group-item ">
					<select name="category_id[]" id="select2" class="form-control" multiple>
					@if( $categories->count() > 0 )
						@foreach( $categories as $category )
							<option value="{{ $category->id }}" @if( !is_null( $selected_ids ) && in_array( $category->id, $selected_ids ) ) {{ "selected" }} @endif >{{ $category->title }}</option>
						@endforeach
					@endif
					</select>
				</li>
			</ul>
		</div>
	</div>
</form>
@endsection

@section( 'scripts' )
<script type="text/javascript">
$( function() {

	ClassicEditor.create( document.querySelector( '#editor' ), {

		toolbar: [ 'Heading', 'Link', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote','undo', 'redo' ],

	} ).then( editor => {

		// console.log( editor );

	} ).catch( error => {

		// console.error( error );

	} );

	@php
	if( !isset( $product ) ) {
	@endphp

		$( '#txturl' ).on( 'keyup', function() {

			var getText = slugify( $( this ).val() );
			$( '#url' ).html( getText );
			$( '#slug' ).val( getText );

		} );

	@php
	}
	@endphp	

	$( '#select2' ).select2( {

		placeholder: "Select multiple categories",
		allowClear: true,

	} );

	$( '#status' ).select2( {

		placeholder: "Select a status",
		allowClear: true,
		minimumResultsForSearch: Infinity
	
	} );

	$( '#thumbnail' ).on( 'change', function() {

		var file = $( this ).get( 0 ).files;
		var reader = new FileReader();
		reader.readAsDataURL( file[ 0 ] );
		reader.addEventListener( "load", function( e ) {

			var image = e.target.result;
			$( '#imgthumbnail' ).attr( 'src', image );
		
		} );
	
	} );

	$( '#btn-add' ).on( 'click', function( e ) {

		var count = $( '.options' ).length + 1;
		$( '#extras' ).append( 

			'<div class="row align-items-center options">\
				<div class="col-sm-12">\
					<h5 class="pt-2 pb-2 bg-primary text-center" style="color:#fff;">Extras</h5>\
				</div>\
				<div class="col-sm-4">\
					<label class="form-control-label" for="option-' + count + '">Option' + count + '</label>\
					<input type="text" name="option[]" class="form-control" id="option-' + count + '" value="" placeholder="size">\
				</div>\
				<div class="col-sm-8">\
					<label class="form-control-label" for="values-' + count + '">Values</label>\
					<input type="text" name="values[]" class="form-control" id="values-' + count + '" placeholder="options1 | option2 | option3" />\
					<label class="form-control-label" for="additional-price-' + count + '">Additional Prices</label>\
					<input type="text" name="prices[]" class="form-control" id="additional-price-' + count + '" placeholder="price1 | price2 | price3" />\
				</div>\
			</div>' 

		);

	} );
	
	$( '#btn-remove' ).on( 'click', function( e ) {

		$( '.options:last' ).remove();
	
	} );

	$( '#featured' ).on( 'change', function() {

		if( $( this ).is( ":checked" ) ) {
	
			$( this ).val( 1 );
	
		} else {
	
			$( this ).val( 0 );
	
		}
	
	} );

} )
</script>
@endsection
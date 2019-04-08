@extends( 'admin.app' )

@section( 'breadcrumbs' )
<li class="breadcrumb-item"><a href="{{ route( 'admin.dashboard' ) }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route( 'admin.categories.index' ) }}">Categories</a></li>
<li class="breadcrumb-item active" aria-current="page">
	@if( isset( $select_category ) && $select_category->count() > 0 )
		Edit Category
	@else
		Add Category
	@endif
</li>
@endsection

@section( 'content' )
<h2 class="modal-title">
	@if( isset( $select_category ) && $select_category->count() > 0 )
		Edit Category
	@else
		Add Category
	@endif
</h2>
<form method="POST" action="@if( isset( $select_category ) && $select_category->count() > 0 ) {{ route( 'admin.categories.update', $select_category->slug ) }} @else {{ route( 'admin.categories.store' ) }} @endif" accept-charset="UTF-8">
	@csrf
	@if( isset( $select_category ) && $select_category->count() > 0 )
		@method( 'PUT' )
	@endif
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
	</div>

	<div class="form-group row">
		<div class="col-sm-12">
			<label for="txturl" class="form-control-label">Title:</label>
			<input type="text" id="txturl" name="title" class="form-control" value="{{ @$select_category->title }}">
			<p class="small">{{ route( 'admin.categories.index' ) }}/<span id="url">{{ @$select_category->slug }}</span>
				<input type="hidden" name="slug" id="slug" value="{{ @$select_category->slug }}">
			</p>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-sm-12">
			<label for="editor" class="form-control-label">Description:</label>
			<textarea id="editor" name="description" class="form-control" rows="10" cols="80">{!! @$select_category->description !!}</textarea>
		</div>
	</div>

	<div class="form-group row">
		@php
			$selected_ids = ( isset( $select_category->childrens ) && $select_category->childrens->count() > 0 ) ? array_pluck( $select_category->childrens, 'id' ) : null;
		@endphp
		<div class="col-sm-12">
			<label for="parent-category-id" class="form-control-label">Category:</label>
			<select id="parent-category-id" name="parent_category_id[]" class="form-control" multiple>
			@if( isset( $categories ) && $categories->count() > 0 )
				<option value="0">Select a parent category</option>
				@foreach( $categories as $category )
					<option value="{{ $category->id }}" @if( isset( $selected_ids ) && in_array( $category->id, $selected_ids ) ) {{ "selected" }} @endif>{{ $category->title }}</option>
				@endforeach
			@endif
			</select>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-sm-12">
		@if( isset( $select_category ) && $select_category->count() > 0 )
			<input type="submit" name="submit" class="btn btn-primary" value="Update Category">
		@else
			<input type="submit" name="submit" class="btn btn-primary" value="Add Category">
		@endif
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
	if( !isset( $select_category ) ) {
	@endphp

		$( '#txturl' ).on( 'keyup', function() {

			var getText = slugify( $( this ).val() );
			$( '#url' ).html( getText );
			$( '#slug' ).val( getText );

		} );

	@php
	}
	@endphp

	$( '#parent-category-id' ).select2( {

		placeholder: "Select a parent category",
		allowClear: true,
		minimumResultsForSearch: Infinity

	} );

} )
</script>
@endsection
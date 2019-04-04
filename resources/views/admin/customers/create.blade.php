@extends( 'admin.app' )

@section( 'breadcrumbs' )
<li class="breadcrumb-item"><a href="{{ route( 'admin.dashboard' ) }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route( 'admin.profiles.index' ) }}">Customers</a></li>
<li class="breadcrumb-item active" aria-current="page">
	@if( isset( $customer ) && $customer->count() > 0 )
		Edit Customer
	@else
		Add Customer
	@endif
</li>
@endsection

@section( 'content' )
<h2 class="modal-title">
	@if( isset( $customer ) && $customer->count() > 0 )
		Edit Customer
	@else
		Add Customer
	@endif
</h2>
<form method="POST" action="@if( isset( $customer ) && $customer->count() > 0 ) {{ route( 'admin.profiles.update', $customer->profile->slug ) }} @else {{ route( 'admin.profiles.store' ) }} @endif" accept-charset="UTF-8" enctype="multipart/form-data">
	@csrf
	@if( isset( $customer ) && $customer->count() > 0 )
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
			
				<div class="col-sm-12 col-md-6">
					<label class="form-control-label">Name: </label>
					<input type="text" id="txturl" name="name" class="form-control" value="{{ @$customer->profile->name }}">
					<p class="small">{{ route( 'admin.profiles.index' ) }}/<span id="url">{{ @$customer->profile->slug }}</span>
						<input type="hidden" name="slug" id="slug" value="{{ @$customer->profile->slug }}">
					</p>
				</div>

				<div class="col-sm-12 col-md-6">
					<label class="form-control-label">Email: </label>
					<input type="text" id="email" name="email" class="form-control" value="{{ @$customer->email }}">
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-12 col-md-6">
					<label class="form-control-label">Password: </label>
					<input type="password" id="password" name="password" class="form-control" value="{{ @$customer->profile->name }}">
				</div>
				<div class="col-sm-12 col-md-6">
					<label class="form-control-label">Confirm Password: </label>
					<input type="password" id="password_confirm" name="password_confirm" class="form-control " value="">
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-6">
					<label class="form-control-label">Status: </label>
					<div class="input-group mb-3">
						<select class="form-control" id="status" name="status">
							<option value="0" @if( isset( $customer ) && $customer->status == 0 ) {{ "selected" }} @endif >Blocked</option>
							<option value="1" @if( isset( $customer ) && $customer->status == 1 ) {{ "selected" }} @endif>Active</option>
						</select>
					</div>
				</div>
				@php
					$selected_ids = ( isset( $customer->role ) && $customer->role->count() > 0 ) ? array_pluck( $customer->role->toArray(), 'id' ) : null;
				@endphp
				{{-- <div class="col-sm-6">
					<label class="form-control-label">Select Role: </label>
					<div class="input-group mb-3">
						<select name="role_id" id="role" class="form-control">
						@if( $roles->count() > 0 )
							@foreach( $roles as $role )
								<option value="{{ $role->id }}"	@if( !is_null( $selected_ids ) && in_array( $role->id, $selected_ids ) ) {{ "selected" }} @endif>{{ $role->name }}</option>
							@endforeach
						@endif
						</select>
					</div>
				</div> --}}
			</div>

			{{-- <div class="row">
				<h4 class="title">Address</h4>
			</div> --}}

			<div class="form-group row">
				<div class="col-sm-12">
					<label class="form-control-label">Address: </label>
					<div class="input-group mb-3">
						<textarea type="text" name="address" class="form-control" rows="3" cols="4">{{ @$customer->addrress }}</textarea>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-6 col-md-3">
					<label class="form-control-label">Country: </label>
					<div class="input-group mb-3">
						<select name="country_id" class="form-control" id="countries">
						@if( $countries->count() > 0 )
							<option value="0">Select a country</option>
							@foreach( $countries as $country )
								<option value="{{ $country->id }}">{{ $country->name }}</option>
							@endforeach
						@endif
						</select>
					</div>
				</div>

				<div class="col-sm-6 col-md-3">
					<label class="form-control-label">State: </label>
					<div class="input-group mb-3">
						<select name="state_id" class="form-control" id="states">
							<option value="0">Select a state</option>
						</select>
					</div>
				</div>

				<div class="col-sm-6 col-md-3">
					<label class="form-control-label">City: </label>
					<div class="input-group mb-3">
						<select name="city_id" class="form-control" id="cities">
							<option value="0">Select a city</option>
						</select>
					</div>
				</div>

				<div class="col-sm-6 col-md-3">
					<label class="form-control-label">Phone: </label>
					<div class="input-group mb-3">
						<input type="text" class="form-control" name="phone" placeholder="Phone" value="{{ @$customer->phone }}" />
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-3">
			<ul class="list-group row">
				<li class="list-group-item">
					<div class="form-group row">
						<div class="col-lg-12">
						@if( isset( $customer ) )
							<input type="submit" name="submit" class="btn btn-primary btn-block" value="Update Customer" />
						@else
							<input type="submit" name="submit" class="btn btn-primary btn-block" value="Add Customer" />
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
						<img src="@if( isset( $customer )) {{ asset( 'storage/' . $customer->thumbnail ) }} @else {{ asset( 'images/no-thumbnail.jpeg' ) }} @endif" id="imgthumbnail" class="img-fluid" alt="">
					</div>
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
	if( !isset( $customer ) ) {
	@endphp

		$( '#txturl' ).on( 'keyup', function() {

			var getText = slugify( $( this ).val() );
			$( '#url' ).html( getText );
			$( '#slug' ).val( getText );

		} );

	@php
	}
	@endphp	

	$( '#thumbnail' ).on( 'change', function() {

		var file = $( this ).get( 0 ).files;
		var reader = new FileReader();
		reader.readAsDataURL( file[ 0 ] );
		reader.addEventListener( 'load', function( e ) {

			var image = e.target.result;
			$( '#imgthumbnail' ).attr( 'src', image );
		
		} );
	
	} );

	$( '#countries' ).select2().trigger( 'change' );
	$( '#countries' ).on( 'change', function() {

		var countryId = $( this ).select2( 'data' )[ 0 ].id;
		
		$( '#states' ).val( null );
		$( '#states option' ).remove();
		
		var stateSelect = $( '#states' );
		$.ajax( {
		    type: 'GET',
		    url: "{{ route( 'admin.profiles.states' ) }}/" + countryId
		} ).then( function( data ) {

		    // create the option and append to Select2
		    for( i = 0; i < data.length; i++ ) {
		    	
				var item = data[ i ];
		    	var option = new Option( item.name, item.id, true, true );
		    	stateSelect.append( option );

		    }

		    stateSelect.trigger( 'change' );

		} );

	} );

	$( '#states' ).select2();
	$( '#states' ).on( 'change', function() {

		var stateId = $( this ).select2( 'data' )[ 0 ].id;
		
		$( '#cities' ).val( null );
		$( '#cities option' ).remove();
		
		var citySelect = $( '#cities' );
		$.ajax( {
		    type: 'GET',
		    url: "{{ route( 'admin.profiles.cities' ) }}/" + stateId
		} ).then( function( data ) {

		    // create the option and append to Select2
		    for( i = 0; i < data.length; i++ ) {
		    	
				var item = data[ i ];
		    	var option = new Option( item.name, item.id, false, false );
		    	citySelect.append( option );

		    }

			citySelect.trigger( 'change' );

		} );

	} );

	$( '#cities' ).select2();

} )
</script>
@endsection
@extends( 'layouts.app' )

@section( 'content' )
<h2>My Cart</h2>

@if( session()->has( 'message' ) )
	<div class="alert alert-success">
		{!! session( 'message' ) !!}
	</div>
@endif

@if( isset( $cart ) && $cart->getItems() )
<div class="card table-responsive">
	<table class="table table-hover shopping-cart-wrap">
		<thead class="text-muted">
			<tr>
				<th scope="col">Product</th>
				<th scope="col" width="120">Quantity</th>
				<th scope="col" width="120">Price</th>
				<th scope="col" width="200" class="text-right">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach( $cart->getItems() as $slug => $data )
			<tr>
				<td>
					<figure class="media">
						<div class="img-wrap"><img src="@if( !empty( $data[ 'product' ]->thumbnail ) ) {{ asset( 'storage/' . $data[ 'product' ]->thumbnail ) }} @else {{ asset( 'images/no-thumbnail.jpeg' ) }} @endif" class="img-thumbnail img-sm" width="50px" height="50px"></div>
						<figcaption class="media-body">
							<h6 class="title text-truncate">{{ $data[ 'product' ]->title }}</h6>
							<dl class="param param-inline small">
								<dt>Size: </dt>
								<dd>XXL</dd>
							</dl>

							<dl class="param param-inline small">
								<dt>Color: </dt>
								<dd>Orange color</dd>
							</dl>
						</figcaption>
					</figure>
				</td>
				
				<td>
					<form id="update-quantity">
						@csrf
						<input type="number" name="qty" id="qty" class="form-control text-center" min="0" max="99" value="{{ $data[ 'qty' ] }}">
						<input type="hidden" name="product-slug" value="{{ $slug }}">
						<input type="submit" name="update" value="Update" class="btn btn-block btn-success btn-round">
					</form>
				</td>
				
				<td>
					<div class="price-wrap">
						<span class="price">&#8377;{{ $data[ 'price' ] }}</span>
						<small class="text-muted">&#8377;{{ $data[ 'product' ]->price }} x {{ $data[ 'qty' ] }}</small>
					</div> <!-- price-wrap .// -->
				</td>

				<td class="text-right">
					<form method="POST" action="{{ route( 'cart.removeSingleProductFromCart', $slug ) }}" accept-charset="utf-8">
						@csrf
						<input type="submit" name="remove" value="Remove" class="btn btn-danger"/>
					</form>
				</td>
			</tr>
			@endforeach

			<tr>
				<th colspan="2">Total Qty: </th>
				<td>{{ $cart->getTotalQuantity() }}</td>
			</tr>

			<tr>
				<th colspan="2">Total Price: </th>
				<td>&#8377;{{ $cart->getTotalPrice() }}</td>
			</tr>
		</tbody>
	</table>
</div> <!-- card.// -->

@section( 'scripts' )
<script type="text/javascript">
$( function() {

	$.ajaxSetup( {
        
        headers: { 
        
        	'X-CSRF-TOKEN': $( '[name="_token"]' ).attr( 'value' ) 

        }

    } );

	$( '#qty' ).on( 'change', function( e ) {

		e.preventDefault();
		// console.log( $( this ).val() );

		// $.post( url ).done( function( data ) {

		// 	$( 'form#update-quantity' ).submit();

		// } );

		$.ajax({
		    url: "{{ route( 'cart.updateSingleProductInCart' ) }}/" + slug,
		    type: 'POST',
		    success: function(response){
		        $( 'form#update-quantity' ).submit();
		    },
		    error: function(response){
		    }
		});

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

} )
</script>
@endsection

@else
<p class="alert alert-info">No product found in cart! <span class="all-product-button"><a href="{{ route( 'products.showAllProduct' ) }}">Grab something?</a></span></p>
@endif
@endsection
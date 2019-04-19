@extends( 'layouts.app' )

@section( 'content' )
@if( isset( $cart ) && $cart->getItems() )
<div class="row">
	<div class="col-md-4 order-md-2 mb-4">
		<h4 class="d-flex justify-content-between align-items-center mb-3">
			<span class="text-muted">Your Cart</span>
			<span class="badge badge-secondary badge-pill">{{ $cart->getTotalQuantity() }}</span>
		</h4>
		<ul class="list-group mb-3">
		@foreach( $cart->getItems() as $slug => $data )
			<li class="list-group-item d-flex justify-content-between lh-condensed">
				<div>
					<h6 class="my-0">{{ $data[ 'product' ]->title }}</h6>
					<small class="text-muted">&#8377;{{ $data[ 'product' ]->price }} x {{ $data[ 'qty' ] }}</small>
				</div>
				<span class="text-muted">&#8377;{{ $data[ 'price' ] }}</span>
			</li>
		@endforeach
			{{-- <li class="list-group-item d-flex justify-content-between bg-light">
				<div class="text-success">
					<h6 class="my-0">Promo code</h6>
					<small>EXAMPLECODE</small>
				</div>
				<span class="text-success">-$5</span>
			</li> --}}
			<li class="list-group-item d-flex justify-content-between">
				<span>Total (Rupee)</span>
				<strong>&#8377;{{ $cart->getTotalPrice() }}</strong>
			</li>
		</ul>

		{{-- <form class="card p-2">
			@csrf
			<div class="input-group">
				<input type="text" class="form-control" placeholder="Promo code">
				<div class="input-group-append">
					<button type="submit" class="btn btn-secondary">Redeem</button>
				</div>
			</div>
		</form> --}}
	</div>
	<div class="col-md-8 order-md-1" id="billing-address">
		<h4 class="mb-3">Billing Address</h4>
		<!-- <form method="POST" action="{{ route( 'checkout.store' ) }}" class="needs-validation" novalidate="" id="payment-form"> -->
		<form method="POST" action="{{ route( 'payment.paypalPayment' ) }}" class="needs-validation" novalidate="" id="payment-form">
			@csrf
			<div class="row">
				<div class="col-md-6 mb-3">
					<label for="firstName">First Name</label>
					<input type="text" name="billing_first_name" class="form-control" id="firstName" placeholder="" value="" required="">
					<div class="invalid-feedback">
						Valid first name is required.
					</div>
					@if( $errors->has( 'billing_first_name' ) )
	                  	<div class="alert alert-danger">
	                    	{{ $errors->first( 'billing_first_name' ) }}
	                  	</div>
	                @endif
				</div>
				<div class="col-md-6 mb-3">
					<label for="lastName">Last Name</label>
					<input type="text" name="billing_last_name" class="form-control" id="lastName" placeholder="" value="" required="">
					<div class="invalid-feedback">
						Valid last name is required.
					</div>
					@if( $errors->has( 'billing_last_name' ) )
	                  	<div class="alert alert-danger">
	                    	{{ $errors->first( 'billing_last_name' ) }}
	                  	</div>
	                @endif
				</div>
			</div>

			<div class="mb-3">
				<label for="username">Username</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">@</span>
					</div>
					<input type="text" name="billing_username" class="form-control" id="username" placeholder="Username" required="">
					<div class="invalid-feedback" style="width: 100%;">
						Your username is required.
					</div>
					@if( $errors->has( 'billing_username' ) )
	                  	<div class="alert alert-danger">
	                    	{{ $errors->first( 'billing_username' ) }}
	                  	</div>
	                @endif
				</div>
			</div>

			<div class="mb-3">
				<label for="email">Email Address <span class="text-muted">(Optional)</span></label>
				<input type="email" name="billing_email" class="form-control" id="email" placeholder="you@example.com">
				<div class="invalid-feedback">
					Please enter a valid email address for shipping updates.
				</div>
				@if( $errors->has( 'billing_email' ) )
                  	<div class="alert alert-danger">
                    	{{ $errors->first( 'billing_email' ) }}
                  	</div>
                @endif
			</div>

			<div class="mb-3">
				<label for="address">Address Line 1</label>
				<input type="text" name="billing_address1" class="form-control" id="address" placeholder="1234 Main St" required="">
				<div class="invalid-feedback">
					Please enter your shipping address.
				</div>
				@if( $errors->has( 'billing_address1' ) )
                  	<div class="alert alert-danger">
                    	{{ $errors->first( 'billing_address1' ) }}
                  	</div>
                @endif
			</div>

			<div class="mb-3">
				<label for="address2">Address Line 2 <span class="text-muted">(Optional)</span></label>
				<input type="text" name="billing_address2" class="form-control" id="address2" placeholder="Apartment or Suite">
				@if( $errors->has( 'billing_address2' ) )
                  	<div class="alert alert-danger">
                    	{{ $errors->first( 'billing_address2' ) }}
                  	</div>
                @endif
			</div>

			<div class="row">
				<div class="col-md-5 mb-3">
					<label for="country">Country</label>
					<select name="billing_country" class="custom-select d-block w-100" id="country" required="">
						<option value="">Choose...</option>
						<option>United States</option>
					</select>
					<div class="invalid-feedback">
						Please select a valid country.
					</div>
					@if( $errors->has( 'billing_country' ) )
	                  	<div class="alert alert-danger">
	                    	{{ $errors->first( 'billing_country' ) }}
	                  	</div>
	                @endif
				</div>
				<div class="col-md-4 mb-3">
					<label for="state">State</label>
					<select name="billing_state" class="custom-select d-block w-100" id="state" required="">
						<option value="">Choose...</option>
						<option>California</option>
					</select>
					<div class="invalid-feedback">
						Please provide a valid state.
					</div>
					@if( $errors->has( 'billing_state' ) )
	                  	<div class="alert alert-danger">
	                    	{{ $errors->first( 'billing_state' ) }}
	                  	</div>
	                @endif
				</div>
				<div class="col-md-3 mb-3">
					<label for="zip">Zip</label>
					<input type="text" name="billing_zip" class="form-control" id="zip" placeholder="" required="">
					<div class="invalid-feedback">
						Zip code required.
					</div>
					@if( $errors->has( 'billing_zip' ) )
	                  	<div class="alert alert-danger">
	                    	{{ $errors->first( 'billing_zip' ) }}
	                  	</div>
	                @endif
				</div>
			</div>

			<hr class="mb-4">
			
			<div class="custom-control custom-checkbox">
				<input type="checkbox" name="same_address" class="custom-control-input" id="same-address">
				<label class="custom-control-label" for="same-address">Shipping Address is the same as my Billing Address</label>
			</div>
			<div class="custom-control custom-checkbox">
				<input type="checkbox" name="guest_checkout" class="custom-control-input" id="guest-checkout">
				<label class="custom-control-label" for="guest-checkout">Checkout as Guest</label>
			</div>
			
			<hr class="mb-4">

			<div class="col-md-12 order-md-1" id="shipping-address">
				<h4 class="mb-3">Shipping Address</h4>
				<div class="row">
					<div class="col-md-6 mb-3">
						<label for="firstName">First name</label>
						<input type="text" name="shipping_first_name" class="form-control" id="firstName" placeholder="" value="" required="">
						<div class="invalid-feedback">
							Valid first name is required.
						</div>
					</div>
					<div class="col-md-6 mb-3">
						<label for="lastName">Last Name</label>
						<input type="text" name="shipping_last_name" class="form-control" id="lastName" placeholder="" value="" required="">
						<div class="invalid-feedback">
							Valid last name is required.
						</div>
					</div>
				</div>

				<div class="mb-3">
					<label for="address">Address Line 1</label>
					<input type="text" name="shipping_address1" class="form-control" id="address" placeholder="1234 Main St" required="">
					<div class="invalid-feedback">
						Please enter your shipping address.
					</div>
				</div>

				<div class="mb-3">
					<label for="address2">Address Line 2 <span class="text-muted">(Optional)</span></label>
					<input type="text" name="shipping_address2" class="form-control" id="address2" placeholder="Apartment or Suite">
				</div>

				<div class="row">
					<div class="col-md-5 mb-3">
						<label for="country">Country</label>
						<select name="shipping_country" class="custom-select d-block w-100" id="country" required="">
							<option value="">Choose...</option>
							<option>United States</option>
						</select>
						<div class="invalid-feedback">
							Please select a valid country.
						</div>
					</div>
					<div class="col-md-4 mb-3">
						<label for="state">State</label>
						<select name="shipping_state" class="custom-select d-block w-100" id="state" required="">
							<option value="">Choose...</option>
							<option>California</option>
						</select>
						<div class="invalid-feedback">
							Please provide a valid state.
						</div>
					</div>
					<div class="col-md-3 mb-3">
						<label for="zip">Zip</label>
						<input type="text" name="shipping_zip" class="form-control" id="zip" placeholder="" required="">
						<div class="invalid-feedback">
							Zip code required.
						</div>
					</div>
				</div>
				<hr class="mb-4">
			</div>

			{{-- <hr class="mb-4"> --}}

			<!-- <div class="col-md-12 order-md-1" id="payment-gateway">
				<script src="https://js.stripe.com/v3/"></script>
				<div class="form-row">
				    <label for="card-element">
				      Credit or Debit card
				    </label>
				    <div id="card-element">
				    </div>

				    <div id="card-errors" role="alert"></div>
			  	</div>
			</div> -->
			<button class="btn btn-primary btn-lg btn-block" type="submit">Continue to Checkout</button>
		</form>
	</div>
</div>
@else
<p class="alert alert-info">No product found for checkout! <span class="all-product-button"><a href="{{ route( 'products.showAllProduct' ) }}">Grab something?</a></span></p>
@endif

@endsection

@section( 'scripts' )
<script type="text/javascript">
$( function() {

	$( '#same-address' ).on( 'change', function() {

		$( '#shipping-address' ).slideToggle( !this.checked );

	} );

} )	
</script>
@endsection
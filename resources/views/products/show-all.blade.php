@extends( 'layouts.app' )

@section( 'sidebar' )
<div class="m-md-5">
	@parent
</div>
@endsection

@section( 'content' )
<div class="col-sm-12">
@if( session()->has( 'message' ) )
    <div class="alert alert-success">
        {!! session( 'message' ) !!}
    </div>
@endif
</div>

<div class="album py-5 bg-light">
	<div class="container">
		<div class="row">
		@if( isset( $products ) && $products->count() > 0 )
			@foreach( $products as $product )
			<div class="col-md-4">	            
				<div class="card mb-4 shadow-sm">
					<img class="card-img-top img-thumbnail" src="@if( !empty( $product->thumbnail ) ) {{ asset( 'storage/' . $product->thumbnail ) }} @else {{ asset( 'images/no-thumbnail.jpeg' ) }} @endif">
					<div class="card-body">
						<h3 class="card-title text-center"><strong>{{ $product->title }}</strong></h3>
						{{-- <p class="card-text">{!! substr( $product->description, 0, 30 ) !!}</p> --}}
						<div class="d-flex justify-content-between align-items-center">
							<div class="btn-group">
								<a href="{{ route( 'products.showSingleProduct', $product->slug ) }}" class="btn btn-sm btn-primary">
									<span data-feather="eye"></span>
									<strong>View</strong>
								</a>
								&nbsp;&nbsp;
								<a href="{{ route( 'products.addToCart', $product->slug ) }}" class="btn btn-sm btn-warning">
									<span data-feather="shopping-cart"></span>
									<strong>Add to Cart</strong>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		@endif
		</div>

		<div class="row">
			<div class="col-md-12">
				{{ $products->links() }}
			</div>
		</div>
	</div>
</div> 
@endsection

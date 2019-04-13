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
			<div class="col-md-12">
				<div class="mb-4">
					<div class="row">
						<div class="col-md-4">
							<img class="img-thumbnail" src="@if( !empty( $product->thumbnail ) ) {{ asset( 'storage/' . $product->thumbnail ) }} @else {{ asset( 'images/no-thumbnail.jpeg' ) }} @endif">
						</div>
						<div class="col-md-8">        
							<h3><strong>{{ $product->title }}</strong></h3>
							<p>{!! $product->description  !!}</p>
							<div class="d-block justify-content-between align-items-center">
								<div class="btn-group">
									<a href="{{ route( 'products.addToCart', $product->slug ) }}" class="btn btn-sm btn-warning">
										<span data-feather="shopping-cart"></span>
										<strong>Add to Cart</strong>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 
@endsection

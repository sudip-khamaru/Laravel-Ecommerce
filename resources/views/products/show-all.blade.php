@extends( 'layouts.app' )

@section( 'sidebar' )
<div class="m-md-5">
	@parent
</div>
@endsection

@section( 'content' )
<div class="album py-5 bg-light">
	<div class="container">
		<div class="row">
			@if( isset( $products ) && $products->count() > 0 )
			@foreach( $products as $product )
			<div class="col-md-4">
				<div class="card mb-4 shadow-sm">
					<img class="card-img-top img-thumbnail" src="@if( isset( $product )) {{ asset( 'storage/' . $product->thumbnail ) }} @else {{ asset( 'images/no-thumbnail.jpeg' ) }} @endif">
					<div class="card-body">
						<h4 class="card-title">{{ $product->title }}</h4>
						<p class="card-text">{!! $product->description !!}</p>
						<div class="d-flex justify-content-between align-items-center">
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-outline-secondary"><span data-feather="eye"></span>View Product</button>
								<button type="button" class="btn btn-sm btn-outline-secondary"><span data-feather="shopping-bag"></span>Add to Cart</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endforeach
			@endif
		</div>
	</div>
</div> 
@endsection

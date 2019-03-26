@extends( 'admin.app' )

@section( 'breadcrumbs' )
<li class="breadcrumb-item"><a href="{{ route( 'admin.dashboard' ) }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Products</li>
@endsection

@section( 'content' )
<div class="row d-block">
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

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h2 class="h2">Products List</h2>
	<div class="btn-toolbar mb-2 mb-md-0">
		<a href="{{route('admin.products.create')}}" class="btn btn-sm btn-outline-primary">
			<span data-feather="plus"></span>
			Add Product
		</a>
	</div>
</div>
<div class="table-responsive">
	<table class="table table-striped table-sm">
		<thead>
			<tr>
				<th>#</th>
				<th>Title</th>
				<th>Description</th>
				<th>Slug</th>
				<th>Products</th>
				@if( isset( $trash ) )
				<th>Date Deleted</th>
				@else
				<th>Date Created</th>
				@endif
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		@if( isset( $products ) && $products->count() > 0 )
			@foreach( $products as $product )
			<tr>
				<td>{{ $product->id }}</td>
				<td>{{ $product->title }}</td>
				<td>{!! $product->description !!}</td>
				<td>{{ $product->slug }}</td>
				<td>
				@if( isset( $product->childrens ) && $product->childrens->count() > 0 )
					@foreach( $product->childrens as $children )
						{{ $children->title }},
					@endforeach
				@else
					{{ "----" }}
				@endif
				</td>
				@if( $product->trashed() )
				<td>{{ $product->deleted_at->toDateString() }}</td>
				<td>
					<a href="{{ route( 'admin.products.recover', $product->id ) }}" class="btn btn-success btn-sm">
						<span data-feather="rotate-ccw"></span>
						Restore
					</a>
					&nbsp;&nbsp;
					<a href="javascript:;" onClick="deleteProduct( '{{ $product->id }}' )" class="btn btn-danger btn-sm">
						<span data-feather="trash-2"></span>
						Delete
					</a>
					<form method="POST" action="{{ route( 'admin.products.destroyFromTrash', $product->id ) }}" id="delete-product-{{ $product->id }}" style="display: none;">
						@csrf
						@method( 'DELETE' )
						<!-- <input type="hidden" name="product_id" value="{{ $product->id }}"> -->
					</form>
				</td>
				@else
				<td>{{ $product->created_at->toDateString() }}</td>
				<td>
					<a href="{{ route( 'admin.products.edit', $product->id ) }}" class="btn btn-info btn-sm">
						<span data-feather="edit"></span>
						Edit
					</a>
					&nbsp;&nbsp;
					<a href="{{ route( 'admin.products.remove', $product->id ) }}" class="btn btn-warning btn-sm" id="trash-product-{{ $product->id }}">
						<span data-feather="trash"></span>
						Trash
					</a>
					&nbsp;&nbsp;
					<a href="javascript:;" onClick="deleteProduct( '{{ $product->id }}' )" class="btn btn-danger btn-sm">
						<span data-feather="trash-2"></span>
						Delete
					</a>
					<form method="POST" action="{{ route( 'admin.products.destroy', $product->id ) }}" id="delete-product-{{ $product->id }}" style="display: none;">
						@csrf
						@method( 'DELETE' )
						<!-- <input type="hidden" name="product_id" value="{{ $product->id }}"> -->
					</form>
				</td>
				@endif
			</tr>
			@endforeach
		@else
		<tr>
			<td colspan="8" style="text-align: center;">No product found!</td>
		</tr>
		@endif
		</tbody>
	</table>
</div>

<div class="row">
	<div class="col-md-12">
		{{ $products->links() }}
	</div>
</div>
@endsection

@section( 'scripts' )
<script type="text/javascript">
function deleteProduct( id )
{
	
	let choice = confirm( "Are you sure, you want to delete this product?" );
	if( choice ) {

		document.getElementById( 'delete-product-' + id ).submit();

	}

}
</script>
@endsection

@extends( 'admin.app' )

@section( 'breadcrumbs' )
<li class="breadcrumb-item"><a href="{{ route( 'admin.dashboard' ) }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Categories</li>
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
	<h2 class="h2">@if( isset( $trash ) ) Trashed Categories List @else Categories List @endif</h2>
	<div class="btn-toolbar mb-2 mb-md-0">
		<a href="{{route('admin.categories.create')}}" class="btn btn-sm btn-outline-primary">
			<span data-feather="plus"></span>
			Add Category
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
				<th>Categories</th>
				@if( isset( $trash ) )
				<th>Date Deleted</th>
				@else
				<th>Date Created</th>
				@endif
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		@if( isset( $categories ) && $categories->count() > 0 )
			@foreach( $categories as $category )
			<tr>
				<td>{{ $category->id }}</td>
				<td>{{ $category->title }}</td>
				<td>{!! $category->description !!}</td>
				<td>{{ $category->slug }}</td>
				<td>
				@if( isset( $category->childrens ) && $category->childrens->count() > 0 )
					@foreach( $category->childrens as $children )
						{{ $children->title }},
					@endforeach
				@else
					{{ "----" }}
				@endif
				</td>
				@if( $category->trashed() )
				<td>{{ $category->deleted_at->toDateString() }}</td>
				<td>
					<a href="{{ route( 'admin.categories.recover', $category->id ) }}" class="btn btn-success btn-sm">
						<span data-feather="rotate-ccw"></span>
						Restore
					</a>
					&nbsp;&nbsp;
					<a href="javascript:;" onClick="deleteCategory( '{{ $category->id }}' )" class="btn btn-danger btn-sm">
						<span data-feather="trash-2"></span>
						Delete
					</a>
					<form method="POST" action="{{ route( 'admin.categories.destroyFromTrash', $category->id ) }}" id="delete-category-{{ $category->id }}" style="display: none;">
						@csrf
						@method( 'DELETE' )
						<!-- <input type="hidden" name="category_id" value=""> -->
					</form>
				</td>
				@else
				<td>{{ $category->created_at->toDateString() }}</td>
				<td>
					<a href="{{ route( 'admin.categories.edit', $category->slug ) }}" class="btn btn-info btn-sm">
						<span data-feather="edit"></span>
						Edit
					</a>
					&nbsp;&nbsp;
					<a href="{{ route( 'admin.categories.remove', $category->slug ) }}" class="btn btn-warning btn-sm" id="trash-category-{{ $category->id }}">
						<span data-feather="trash"></span>
						Trash
					</a>
					&nbsp;&nbsp;
					<a href="javascript:;" onClick="deleteCategory( '{{ $category->id }}' )" class="btn btn-danger btn-sm">
						<span data-feather="trash-2"></span>
						Delete
					</a>
					<form method="POST" action="{{ route( 'admin.categories.destroy', $category->slug ) }}" id="delete-category-{{ $category->id }}" style="display: none;">
						@csrf
						@method( 'DELETE' )
						<!-- <input type="hidden" name="category_id" value=""> -->
					</form>
				</td>
				@endif
			</tr>
			@endforeach
		@else
		<tr>
			<td colspan="8" style="text-align: center;" class="alert alert-info">No category found!</td>
		</tr>
		@endif
		</tbody>
	</table>
</div>

<div class="row">
	<div class="col-md-12">
		{{ $categories->links() }}
	</div>
</div>
@endsection

@section( 'scripts' )
<script type="text/javascript">
function deleteCategory( id )
{
	
	let choice = confirm( "Are you sure, you want to delete this category?" );
	if( choice ) {

		document.getElementById( 'delete-category-' + id ).submit();

	}

}
</script>
@endsection

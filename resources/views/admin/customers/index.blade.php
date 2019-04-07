@extends( 'admin.app' )

@section( 'breadcrumbs' )
<li class="breadcrumb-item"><a href="{{ route( 'admin.dashboard' ) }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Customers</li>
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
	<h2 class="h2">@if( isset( $trash ) ) Trashed Customers List @else Customers List @endif</h2>
	<div class="btn-toolbar mb-2 mb-md-0">
		<a href="{{route('admin.profiles.create')}}" class="btn btn-sm btn-outline-primary">
			<span data-feather="plus"></span>
			Add Customer
		</a>
	</div>
</div>
<div class="table-responsive">
	<table class="table table-striped table-sm">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Email</th>
				<th>Slug</th>
				<th>Role</th>
				<th>Address</th>
				<th>Contact</th>
				@if( isset( $trash ) )
				<th>Date Deleted</th>
				@else
				<th>Date Created</th>
				@endif
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		@if( isset( $customers ) && $customers->count() > 0 )
			@foreach( $customers as $customer )
			<tr>
				<td>{{ @$customer->id }}</td>
				<td>{{ @$customer->name }}</td>
				<td>{{ @$customer->user->email }}</td>
				<td>{{ @$customer->slug }}</td>
				<td>{{ @$customer->user->role->name }}</td>
				{{-- <td>{{ @$customer->getUserCountry() }}</td> --}}
				<td>{{ @$customer->address }}</td>
				<td>{{ @$customer->phone }}</td>
				@if( $customer->trashed() )
				<td>{{ $customer->deleted_at->toDateString() }}</td>
				<td>
					<a href="{{ route( 'admin.profiles.recover', $customer->id ) }}" class="btn btn-success btn-sm">
						<span data-feather="rotate-ccw"></span>
						Restore
					</a>
					&nbsp;&nbsp;
					<a href="javascript:;" onClick="deleteCustomer( '{{ $customer->id }}' )" class="btn btn-danger btn-sm">
						<span data-feather="trash-2"></span>
						Delete
					</a>
					<form method="POST" action="{{ route( 'admin.profiles.destroyFromTrash', $customer->id ) }}" id="delete-customer-{{ $customer->id }}" style="display: none;">
						@csrf
						@method( 'DELETE' )
						<!-- <input type="hidden" name="customer_id" value=""> -->
					</form>
				</td>
				@else
				<td>{{ $customer->created_at->toDateString() }}</td>
				<td>
					<a href="{{ route( 'admin.profiles.edit', $customer->slug ) }}" class="btn btn-info btn-sm">
						<span data-feather="edit"></span>
						Edit
					</a>
					&nbsp;&nbsp;
					<a href="@if( Auth::id() === $customer->user_id ) {{ 'javascript:void(0);' }} @else {{ route( 'admin.profiles.remove', $customer->slug ) }} @endif" class="btn btn-warning btn-sm @if( Auth::id() === $customer->user_id ) {{ 'disabled' }} @endif" id="trash-customer-{{ $customer->id }}">
						<span data-feather="trash"></span>
						Trash
					</a>
					&nbsp;&nbsp;
					<a href="javascript:;" @if( Auth::id() !== $customer->user_id ) onClick="deleteCustomer( '{{ $customer->id }}' ) @endif" class="btn btn-danger btn-sm @if( Auth::id() === $customer->user_id ) {{ 'disabled' }} @endif">
						<span data-feather="trash-2"></span>
						Delete
					</a>
					@if( Auth::id() !== $customer->user_id )
					<form method="POST" action="{{ route( 'admin.profiles.destroy', $customer->slug ) }}" id="delete-customer-{{ $customer->id }}" style="display: none;">
						@csrf
						@method( 'DELETE' )
						<!-- <input type="hidden" name="customer_id" value=""> -->
					</form>
					@endif
				</td>
				@endif
			</tr>
			@endforeach
		@else
		<tr>
			<td colspan="9" style="text-align: center;" class="alert alert-info">No customer found!</td>
		</tr>
		@endif
		</tbody>
	</table>
</div>

<div class="row">
	<div class="col-md-12">
		{{ $customers->links() }}
	</div>
</div>
@endsection

@section( 'scripts' )
<script type="text/javascript">
function deleteCustomer( id )
{
	
	let choice = confirm( "Are you sure, you want to delete this user?" );
	if( choice ) {

		document.getElementById( 'delete-customer-' + id ).submit();

	}

}
</script>
@endsection

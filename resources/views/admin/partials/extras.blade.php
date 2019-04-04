@if(request()->ajax())
	<div class="row align-items-center options">
		<div class="col-sm-12">
			<h5 class="pt-2 pb-2 bg-primary text-center" style="color:#fff;">Extras</h5>
		</div>
		<div class="col-sm-4">
			<label class="form-control-label" for="option-@php echo $_GET[ 'count' ]; @endphp">Option@php echo $_GET[ 'count' ]; @endphp</label>
			<input type="text" name="option[]" class="form-control" id="option-@php echo $_GET[ 'count' ]; @endphp" value="" placeholder="size">
		</div>
		<div class="col-sm-8">
			<label class="form-control-label" for="values-@php echo $_GET[ 'count' ]; @endphp">Values</label>
			<input type="text" name="values[]" class="form-control" id="values-@php echo $_GET[ 'count' ]; @endphp" placeholder="options1 | option2 | option3" />
			<label class="form-control-label" for="additional-price-@php echo $_GET[ 'count' ]; @endphp">Additional Prices</label>
			<input type="text" name="prices[]" class="form-control" id="additional-price-@php echo $_GET[ 'count' ]; @endphp" placeholder="price1 | price2 | price3" />
		</div>
	</div>
@else
	<p class="alert alert-danger">You can't access directly!</p>
@endif
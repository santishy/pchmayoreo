<!--Card-->
<div class="card top-space">
	<!--Card image-->
	<img class="img-responsive" src="{{url('articles/image/'.$fileName)}}" alt="Card image cap">
	<!--/.Card image-->
	<!--Card content-->
	<div class="card-block">
		<form class="inline-block" method="POST" action="{{$ruta}}">
			<div class="form-group">
				<input name="forma_pago" value="{{$forma_pago}}" type="hidden">
				<input name="total" type="hidden" value="{{$shopping_cart->total()}}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<button class="btn btn-raised btn-primary btn-block">Pagar</button>
			</div>
		</form>
	</div>
	<!--/.Card content-->
</div>
				
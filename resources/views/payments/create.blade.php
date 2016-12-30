@extends('layouts.header')
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="well top-space">
					<h3>Resumen de tu carrito de compras.</h3>
					<div class="row top-space">
						<div class="col-md-4 col-xs-4 col-lg-2 sale-data">
							<span>{{number_format($shopping_cart->total(),2)}} USD</span>
							Subtotal
						</div>
						<div class="col-md-4 col-xs-4 col-lg-2 sale-data">
							<span>{{number_format($envio,2)}} USD</span>
							Envío
						</div>
						<div class="col-md-4 col-xs-4 col-lg-2 sale-data" style="border:none">
							<span>{{number_format($shopping_cart->total()+$envio,2)}} USD</span>
							Total
						</div>
					</div>
					<table class="table">
						<thead>
							<th>Articulo</th>
							<th>Precio</th>
							<th>cantidad</th>
						</thead>
						<tbody>
							@foreach($products as $product)
								<tr>
									<td>{{$product->descripcion}}</td>
									<td>{{$product->price}}</td>
									<td>
										<a href="#" 
											data-type="number" 
											data-pk="{{$product->in_shopping_cart_id}}"
											data-url='{{url("in_shopping_carts/$product->in_shopping_cart_id")}}'
											data-value="{{$product->qty}}"
											data-name="qty"
											class="set-qty"
											data-tile="Cantidad">
										</a>
									</td>
								</tr>
							@endforeach

						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-4">			
				@include('cards.card_pago',['ruta'=>url("/pagos"),'shopping_cart'=>$shopping_cart,
						'forma_pago'=>'paypal','fileName'=>'paypal.png'])
				@include('cards.card_pago',['ruta'=>url("/pagos"),'shopping_cart'=>$shopping_cart,
				'forma_pago'=>'referencia','fileName'=>'referencia.png'])
			</div>
		</div>
	</div>
@endsection
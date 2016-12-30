<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShoppingCart;
use App\Payment;
use App\Paypal;
use App\Order;
use App\Dolar;
use App\libs\nusoap;
class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $shopping_cart=null;
    public function __construct()
    {
        $this->middleware('shoppingcart');
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($shopping_cart_id)
    {
        $shopping_cart=ShoppingCart::findBySessionShoppingCart($shopping_cart_id);
        $this->shopping_cart=$shopping_cart;
        \Session::put('shopping_cart_id',$shopping_cart->id);
        $products=$shopping_cart->articulos()->get();
        $envio=Dolar::cambio(99);
        return view('payments.create',
                    ['shopping_cart'=>$shopping_cart,
                    'products'=>$products,
                    'envio'=>$envio]);
    }
    public function store(Request $request)
    {
        $payment=Payment::create(['forma_pago'=>$request->forma_pago,'total'=>$request->total]);
        \Session::put('id_pago',$payment->id);

        switch ($request->forma_pago) {
            case 'paypal':
                return $this->paypal($request);
                break;
            case 'credito':
                break;
            default:
                # code...
                break;
        }
        
    }
    public function paypal($request)
    {
        $shopping_cart=$request->shopping_cart;
        $paypal=new Paypal($shopping_cart);
        $payment=$paypal->generate();
        return redirect($payment->getApprovalLink());
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAndStore(Request $request)
    {
     
        //$shopping_cart=ShoppingCart::findBySessionShoppingCart(\Session::get('shopping_cart_id'));
        $shopping_cart=$request->shopping_cart;
        $paypal=new Paypal($shopping_cart);
        $response = $paypal->execute($request->paymentId,$request->PayerID);
        if($response->state=='approved')
        {
            $shopping_cart->approve();
            $order= Order::createFormPaypalResponse($response,$shopping_cart,\Session::get('id_pago'));
            $order->sendMail();
            return view('orders.order',['order'=>$order,'shopping_cart'=>$shopping_cart]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

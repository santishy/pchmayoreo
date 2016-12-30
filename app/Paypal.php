<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Articulo;
use App\Dolar;
class Paypal 
{
    private $_apiContext;
    private $shopping_cart;
    private $_clientID='AVFlmFoXKSRgugi6J4UQa2Rmmry54Vfy_jEm6dE770XppCCb8u1dU641SsVd0u_-bynp-dO2C_WeEN6B';
    private $_clientSecret='EB0gn3RBrBsEBRLcVQ6y4gGaQWSSeHHCxyM05kOk3tnp8oc_PR3V1XIhjKYwnyFfs1S-_SlB7mXWyXH9';
    
    public function __construct($shopping_cart)
    {
    	$this->_apiContext=\PaypalPayment::ApiContext($this->_clientID,$this->_clientSecret);
    	$this->shopping_cart=$shopping_cart;
    	$config=config('paypal_payment');
    	$flatConfig=array_dot($config);
    	$this->_apiContext->setConfig($flatConfig);
    }
    public function generate()
    {
        $payment=\PaypalPayment::payment()->setIntent('sale')
                                ->setPayer($this->payer())
                                ->setTransactions([$this->transaction()])
                                ->setRedirectUrls($this->redirectUrls());
        try{
            $payment->create($this->_apiContext);
        }catch(\Exception $err)
        {
            dd($err);
            exit(1);
        }
        return $payment;
    }
    public function payer()
    {
        $payer=\PaypalPayment::payer();
        return $payer->setPaymentMethod('paypal');
    }
    public function redirectUrls()
    {
        return \PaypalPayment::redirectUrls()
                ->setReturnUrl(url('/pagos/updateAndStore'))
                ->setCancelUrl(url('/carrito'));
    }
    public function transaction()
    {
        return \PaypalPayment::transaction()
                ->setAmount($this->amount())
                ->setItemList($this->items())
                ->setDescription('Compra en Isco Computadoras')
                ->setInvoiceNumber(\Session::get('pago_id'));
    }
    public function items()
    {
        $items=[];
        $articulos=$this->shopping_cart->BelongToManyArticulos()->get();
        foreach ($articulos as $articulo) 
        {
            array_push($items, $articulo->paypalArticulo());
        }
        
        return \PaypalPayment::itemList()
                ->setItems($items);
    }
    public function amount()
    {
        return \PaypalPayment::amount()
                ->setCurrency('USD')
                ->setTotal($this->shopping_cart->total());
                //->setDetails($this->details());
    }
    public function details()
    {
        return \PaypalPayment::details()
                ->setTax(0)
                ->setShipping(0)
                ->setSubTotal($this->shopping_cart->total());
    }
    public function execute($paymentId,$payerID)
    {
        $payment = \PaypalPayment::getById($paymentId,$this->_apiContext);
        $execution= \PaypalPayment::PaymentExecution()->setPayerId($payerID);
        return $payment->execute($execution,$this->_apiContext);
    }
}

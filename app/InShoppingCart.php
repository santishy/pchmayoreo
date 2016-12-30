<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InShoppingCart extends Model
{
    //
    protected $table='in_shopping_carts';
    public $timestamps=false;
    public function shoppingCart()
    {
    	return $this->belongsTo('App\ShoppingCart','shopping_cart_id','id')
    	->first();
    }
}

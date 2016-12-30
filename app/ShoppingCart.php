<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use nusoap_client;
class ShoppingCart extends Model
{
	protected $table='shopping_carts';
    public static function findBySessionShoppingCart($shopping_cart_id)
    {
    	return ShoppingCart::find($shopping_cart_id);
    }
    public function inShoppingCart()
    {
    	return $this->hasMany('App\inShoppingCart');
    }

    public function articulos()
    {
        $query = DB::table('articulos')                   
            ->join('in_shopping_carts','articulos.id_articulo',
            '=','in_shopping_carts.id_articulo')
            ->join('shopping_carts','in_shopping_carts.shopping_cart_id',
            '=','shopping_carts.id')
            ->join('utilidades','utilidades.id_utilidad','=','articulos.id_utilidad')
            ->where('shopping_carts.id','=',$this->id)
            ->select('in_shopping_carts.*','articulos.*','shopping_carts.*');
        return $query->addSelect(DB::raw('in_shopping_carts.id as in_shopping_cart_id,
                                precioMasUtilidad(articulos.precio,utilidades.ut) as precioUtilidad'))
                     ->orderBy('moneda','asc'); 
    }
    public function scopeBelongToManyArticulos($query)
    {
        return $this->belongsToMany('App\Articulo','in_shopping_carts','shopping_cart_id','id_articulo')
                    ->select('qty','skuFabricante','descripcion','price','articulos.id_articulo');           
    }
    
    public function total()
    {
        $total=0;
        foreach($this->articulos()->get() as $articulo)
        {
            $total+=Dolar::cambio($articulo->price)*$articulo->qty;
        }
        return $total;
    }
    public function scopeMonedas($query)
    {
        return $query->distinct();
    }
    public function scopeDividirPorMoneda($query,$moneda)
    {
        $query->where('moneda','=',$moneda)
        ->select('in_shopping_carts.*','articulos.*','shopping_carts.*');
        return $query->addSelect(DB::raw('in_shopping_carts.id as in_shopping_cart_id,
                                in_shopping_carts.qty as iCantidad,articulos.sku as strSku,
                                precioMasUtilidad(articulos.precio,utilidades.ut) as precioUtilidad'))
                     ->orderBy('almacen','asc');

    }
    public static function dividirAlmacenes($data)
    {
        $folio=array();
        $i=0;
        $carrito=array();
        $j=0;
        $temp=$data[0]['almacen'];
        $l=0;
        $t=0;
        while($i<count($data))
        {
            while($j<count($data))
            {
                if($temp==$data[$j]['almacen'])
                {
                    $moneda=$data[$j]['moneda'];
                    $almacen=$data[$j]['almacen'];
                    $carrito[]=array('strSku' => $data[$j]['strSku'],'iCantidad'=>$data[$j]['iCantidad'] );
                    $ban=true;
                }
                else
                {
                    $ban=false;
                    $temp=$data[$j]['almacen'];
                    $t=$j;
                    $j=count($data);
                }
                if($ban)
                {
                    $j++;
                    $i=$j;
                    $t=$i;
                }
            }
            $j=$t;
            if(!empty($carrito) )
                {
                    $vec[$l++]=array("carrito"=>$carrito,"moneda"=>$moneda,"almacen"=>$almacen);
                    $carrito= array();
                }
        }
        return $vec;
    }
    function apartado($almacen,$moneda,$data){
        //include_once('lib/nusoap.php')
        $strUrl = 'http://serviciosmayoristas.pchmayoreo.com/servidor.php?wsdl';
        try{
            $client = new nusoap_client($strUrl, array("cache_wsdl" => WSDL_CACHE_NONE));
            $result = $client->call("GenerarRemision", array(
                6722,
                "112012",
                $almacen,
                $moneda,

                    $data,

                "0012code"
            ));
            return $result;
           
        }
        catch(Exception $ex)
        {
             print_r($ex->getMessage());
        }
    }
    public function approve()
    {
        $this->updateCustomIdAndStatus();
    }
    public function genereteCustomId()
    {
        return md5($this->id.''.$this->updated_at);
    }
    public function updateCustomIdAndStatus()
    {
        $this->status='Aprobado';
        $this->customid=$this->genereteCustomId();
        $this->save();
    }
    public function order()
    {
        return $this->hasOne('App\Order','shopping_cart_id','id')->first();
    }
}

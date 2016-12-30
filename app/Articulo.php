<?php

namespace App;
use App\Dolar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Articulo extends Model
{
    //
    protected $table="articulos";
    protected $primaryKey="id_articulo";
    public function paypalArticulo()
    {
    	return \PaypalPayment::item()
    			->setName($this->skuFabricante)	
    			->setDescription($this->descripcion)
    			->setCurrency('USD')
    			->setQuantity($this->qty)
    			->setPrice(Dolar::Cambio($this->price));
    }
   
    public function precioUtilidad()// este se puede llamar desde arrriba sustituyendo el $this->price
    {
        return $this->join('utilidades','articulos.id_utilidad','=','utilidades.id_utilidad')
                ->where('articulos.id_articulo','=',$this->id_articulo)
                ->select('utilidad')
                ->addSelect(DB::raw('precioMasUtilidad(articulos.precio,utilidades.ut) as precioUtilidad'));
    }
    public  function detinvart($id_articulo,$almacen)
    {
        return $this->belongsToMany('App\Inventario','detinvart','id_articulo','id_inventario')
                ->where('almacen','=',$almacen)
                ->select('existencia')->first();
                //CHECAR BIEN ESTA FUNCION, LAS LLAVES COMO SE ACOMODAN
                // CHECAR COMO PIDEN LAS COSAS, EN ESTE CASO PEDIRIA TODOS LOS
                //INVENTARIOS DND ESTA ESE ARTICULO

    }
}

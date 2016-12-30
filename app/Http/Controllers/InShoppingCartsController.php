<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\InShoppingCart;
use App\Articulo;
class InShoppingCartsController extends Controller
{
   

    public function store(Request $request)
    {
        
    }

   
   
    public function update(Request $request, $id)
    {
        $inShoppingCart=InShoppingCart::find($id);
       
        // obtener existencia de dicho articulo .. en detinvart
        if($request->name=='qty')
        {
            $id_articulo=$inShoppingCart->id_articulo;
            $articulo=Articulo::find($id_articulo);
            //dd([$id_articulo,$inShoppingCart->almacen]);
            $existencia=$articulo->detinvart($id_articulo,$inShoppingCart->almacen)->existencia;
            
            if($request->value > $existencia)
            {
                return response()->json([
                    'success'=>false,
                    'status'=>'500']);
            }
            else
            {
                $field=$request->name;
                $inShoppingCart->$field=$request->value;
                $inShoppingCart->save();
                return response()->json([
                    'success'=>true]);
            }
        }
        
       /* $field=$request->name;
        $inShoppingCart->$field=$request->value;
        $inShoppingCart->save();
        return $inShoppingCart->$field;*/  //CON ESTO FUNCIONA EL ESTE METODO BIEN
    }

   
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Dolar extends Model
{
    protected $table='dolares';
    protected $primaryKey='id_dolar';
    public static function Cambio($monto)
    {
    	$dolar = DB::table('dolares')->orderBy('id_dolar','desc')
                                                ->take(1)->get();
        $cambio=$dolar[0]->precio;
        return number_format(($monto/$cambio),2);
    }
}

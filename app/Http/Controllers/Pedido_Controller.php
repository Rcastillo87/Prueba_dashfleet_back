<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Cliente;

class Pedido_Controller extends Controller{

    public function get_consulta_pedido(Request $request){

        $validator = Validator::make($request->all(), [
            'tipo'  => 'required',
            'cedula'  => 'required|integer',
            'codigo' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['successful' => false, 'message' => "Data no cumple con las validaciones", 'data' => $validator->errors()]);
        }

        $user = Cliente::where('tipo', $request->tipo )
        ->where('cedula', $request->cedula )
        ->get()->first();
        if( is_null( $user ) ){
            return response()->json(['successful' => false, 'message' =>"Usuario no existe", 'data' => [] ]);
        }

        $pedido = Pedido::where('codigo', $request->codigo )
        ->where('id_cliente', $user->id )
        ->get()->first();
        if( is_null( $pedido ) ){
            return response()->json(['successful' => false, 'message' =>"Pedido no existe", 'data' => [] ]);
        }

        $productos = Producto::where('id_pedido', $pedido->id)->get();

        $data['cliente'] = $user;
        $data['pedido'] = $pedido;
        $data['productos'] = $productos;

        return response()->json(['successful' => true, 'message' =>"Consulta exitosa", 'data' => $data]);
    }
}
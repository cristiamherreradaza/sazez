<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Combo;
use App\CombosProducto;
use App\Producto;
use App\Marca;
use DataTables;

class ComboController extends Controller
{
    public function nuevo()
    {
        return view('combo.nuevo');
    }
    
    public function ajax_listado_producto()
    {
        $lista_productos = Producto::select('id', 'nombre', 'nombre_venta', 'marca_id');
        return Datatables::of($lista_productos)
            ->addColumn('action', function ($lista_productos) {
                return '<button onclick="adicionar_producto_combo('.$lista_productos->id.')" class="btn btn-info"><i class="fas fa-plus"></i></a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);
    }

    public function guarda(Request $request)
    {
        $combo = new Combo();
        $combo->user_id = Auth::user()->id;
        $combo->nombre = $request->nombre_combo;
        $combo->fecha_inicio = $request->fecha_inicio;
        $combo->fecha_final = $request->fecha_final;
        $combo->save();

        // Guardamos y enviamos a la variable el nuevo combo creado
        $nuevo_combo = Combo::where('nombre', $request->nombre_combo)
                            ->where('fecha_inicio', $request->fecha_inicio)
                            ->where('fecha_final', $request->fecha_final)
                            ->first();

        //productos existentes en combo
        //$productos_combo = CombosProducto::where('combo_id', $nuevo_combo->id)->get();

        return redirect('Combo/editar/'.$nuevo_combo->id);
    }

    public function lista_combo_productos($id)
    {
        $productos_combo = CombosProducto::where('combo_id', $id)->get();
        //dd($productos_combo);
        //$productos_combo = CombosProducto::find($id);
        //$productos_combo = CombosProducto::where('combo_id', $id)->with('precio')->get();
        // $productos_combo = CombosProducto::with(['producto' => function ($query) use ($id) {
        //     $query->where('combo_id', 'like', $id);
        // }])->get();
        //dd($productos_combo);
        // foreach($productos_combo as $prod){
        //     echo $prod->producto->precio->precio;
        // }
        return view('combo.lista_combo_productos')->with(compact('productos_combo'));
        //{{ $producto_combo->producto->marca->nombre }}
    }

    public function editar($id)
    {
        $nuevo_combo = Combo::find($id);
        $productos_combo = CombosProducto::where('combo_id', $id)->get();
        return view('combo.editar')->with(compact('nuevo_combo', 'productos_combo'));
    }

    // public function agregar_producto($combo_id, $producto_id)
    // {
    //     $sw=1;
    //     $combos_productos = new ProductosCombo();
    //     $combos_productos->user_id = Auth::user()->id;
    //     $combos_productos->combo_id = $combo_id;
    //     $combos_productos->producto_id = $producto_id;
    //     $combos_productos->save();
    //     return sw;
    // }
    
    public function agregar_combo_producto(Request $request)
    {
        $combos_productos = new CombosProducto();
        $combos_productos->user_id = Auth::user()->id;
        $combos_productos->combo_id = $request->combo_id;
        $combos_productos->producto_id = $request->producto_id;
        $combos_productos->precio = 0000;
        $combos_productos->save();
    }
    
    public function eliminar_combo_producto(Request $request)
    {
        //dd($request->producto_id);
        $combos_productos = CombosProducto::where('combo_id', $request->combo_id)
                                        ->where('producto_id', $request->producto_id)
                                        ->first();
        $combos_productos->delete();
    }

    public function elimina_producto($combo_id, $producto_id)
    {
        $combos_productos = CombosProducto::where('combo_id', $combo_id)
                                        ->where('producto_id', $producto_id)
                                        ->first();
        $combos_productos->delete();
    }

    public function listado()
    {
        $combos = Combo::get();
        return view('combo.listado')->with(compact('combos'));
    }

    public function eliminar($id)
    {
        $combo = Combo::find($id);
        $combo->delete();
        CombosProducto::where('combo_id', $id)->delete();
        return redirect('Combo/listado');
    }
}

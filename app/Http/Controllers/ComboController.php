<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Combo;
use App\CombosProducto;
use App\Producto;
use App\Precio;
use App\Marca;
use App\Cupone;
use DataTables;
use App\Movimiento;

class ComboController extends Controller
{
    public function nuevo()
    {
        return view('combo.nuevo');
    }
    
    public function ajaxBuscaProducto(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('combo.ajaxBuscaProducto')->with(compact('productos'));
    }

    public function guarda(Request $request)
    {
        // Creacion del Combo
        $combo = new Combo();
        $combo->user_id = Auth::user()->id;
        $combo->nombre = $request->nombre_combo;
        $combo->fecha_inicio = $request->fecha_inicio;
        $combo->fecha_final = $request->fecha_final;
        $combo->save();
        $combo_id = $combo->id;

        // En la variable llaves se asigna todos los productos que se encuentran en el combo
        $llaves = array_keys($request->precio);
        foreach ($llaves as $key => $ll) 
        {
            // Creación de ComboProducto
            $productosCombo = new CombosProducto();
            $productosCombo->user_id = Auth::user()->id;
            $productosCombo->combo_id = $combo_id;
            $productosCombo->producto_id = $ll;
            $productosCombo->precio = $request->precio[$ll];
            $productosCombo->cantidad = $request->cantidad[$ll];
            $productosCombo->save();
        }
        return redirect('Combo/listado');
    }

    public function editar($id)
    {
        $combo = Combo::find($id);
        $productos_combo = CombosProducto::where('combo_id', $id)->get();
        return view('combo.editar')->with(compact('combo', 'productos_combo'));
    }

    public function actualiza(Request $request)
    {
        $combo = Combo::find($request->id);        
        $combo->user_id = Auth::user()->id;
        $combo->nombre = $request->nombre_combo;
        $combo->fecha_inicio = $request->fecha_inicio;
        $combo->fecha_final = $request->fecha_final;
        $combo->save();
        $combo_id = $combo->id;
        //Eliminamos los productos del combo
        CombosProducto::where('combo_id', $combo_id)->delete(); 
        //Volvemos a introducirlos
        $llaves = array_keys($request->precio);
        foreach ($llaves as $key => $ll) 
        {
            // Creación de ComboProducto
            $productosCombo = new CombosProducto();
            $productosCombo->user_id = Auth::user()->id;
            $productosCombo->combo_id = $combo_id;
            $productosCombo->producto_id = $ll;
            $productosCombo->precio = $request->precio[$ll];
            $productosCombo->cantidad = $request->cantidad[$ll];
            $productosCombo->save();
        }
        return redirect('Combo/listado');
    }

    public function lista_combo_productos($id)
    {
        $productos_combo = CombosProducto::where('combo_id', $id)->get();
        $precios = Precio::get();
        return view('combo.lista_combo_productos')->with(compact('productos_combo', 'precios'));
        //{{ $producto_combo->producto->marca->nombre }}
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
        $combos = Combo::orderBy('id', 'desc')->get();
        return view('combo.listado')->with(compact('combos'));
    }

    public function eliminar($id)
    {
        $combo = Combo::find($id);
        $combo->delete();
        CombosProducto::where('combo_id', $id)->delete();
        return redirect('Combo/listado');
    }

    public function actualiza_precio(Request $request)
    {
        $combo_producto = CombosProducto::find($request->id);
        $combo_producto->precio = $request->precio;
        $combo_producto->save();
    }

    public function ajaxMuestraPromo(Request $request)
    {
        $datosCombo = Combo::find($request->combo_id);
        $itemsCombo = CombosProducto::where('combo_id', $request->combo_id)->get();
        // dd($itemsCombo);
        return view('combo.ajaxMuestraPromo')->with(compact('datosCombo', 'itemsCombo'));
    }
}

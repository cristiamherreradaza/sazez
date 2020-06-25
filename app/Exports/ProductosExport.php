<?php

namespace App\Exports;

use DB;
use App\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductosExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $productos = DB::table('productos')
        //             ->leftjoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
        //             ->leftjoin('marcas', 'productos.marca_id', '=', 'marcas.id')
        //             ->leftjoin('movimientos', 'productos.id', '=', 'movimientos.producto_id')
        //             ->select('productos.codigo', 'productos.nombre as nombre_producto', 'productos.nombre_venta', 'tipos.nombre as nombre_tipo', 'marcas.nombre as nombre_marca', 'productos.modelo', DB::raw('SUM(movimientos.ingreso) - SUM(movimientos.salida) as cantidad'))
        //             ->groupBy('movimientos.producto_id')
        //             ->get();
        
        $productos = Producto::select('productos.codigo', 'productos.nombre as nombre_producto', 'productos.nombre_venta', 'tipos.nombre as nombre_tipo', 'marcas.nombre as nombre_marca', 'productos.modelo', DB::raw('SUM(movimientos.ingreso) - SUM(movimientos.salida) as cantidad'))
                    ->leftjoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
                    ->leftjoin('marcas', 'productos.marca_id', '=', 'marcas.id')
                    ->leftjoin('movimientos', 'productos.id', '=', 'movimientos.producto_id')
                    ->groupBy('movimientos.producto_id')
                    ->get();
        
        return $productos;                
    }

    public function map($producto): array
    {
        /**
        * @var Invoice $invoice
        */
        return [
            $producto->codigo,
            $producto->nombre_producto,
            $producto->nombre_venta,
            $producto->nombre_tipo,
            $producto->nombre_marca,
            $producto->modelo,
            $producto->cantidad
        ];
    }

    public function headings() : array
    {
        return [
            'Codigo',
            'Nombre Producto',
            'Nombre Venta Producto',
            'Tipo',
            'Marca',
            'Modelo',
            'Cantidad'
        ];
    }

    public function registerEvents(): array
    {
        $styleArray = [
            'font' => [
                'bold' => true,
                'color' => array('rgb' => 'FF0000'),
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            
        ];
        return [
            AfterSheet::class => function(AfterSheet $event) use ($styleArray) {
                $event->sheet->getStyle('A1:G1')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->freezePane('B1');
            },
        ];
    }
}

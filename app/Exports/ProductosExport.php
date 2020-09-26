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
use Maatwebsite\Excel\Concerns\Exportable;

class ProductosExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $almacen;

    public function __construct($almacen)
    {
        $this->almacen = $almacen;
    }

    public function collection()
    {
        $almacen = $this->almacen;

        $productos = Producto::whereNull('estado')->get();
        
        // $productos = Producto::select('productos.codigo', 'productos.nombre as nombre_producto', 'productos.nombre_venta', 'tipos.nombre as nombre_tipo', 'marcas.nombre as nombre_marca', 'productos.modelo', DB::raw('SUM(movimientos.ingreso) - SUM(movimientos.salida) as cantidad'))
        //                     ->leftjoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
        //                     ->leftjoin('marcas', 'productos.marca_id', '=', 'marcas.id')
        //                     ->leftjoin('movimientos', 'productos.id', '=', 'movimientos.producto_id')
        //                     ->groupBy('movimientos.producto_id')
        //                     ->get();
        return $productos;                
    }

    public function map($producto): array
    {
        /**
        * @var Invoice $invoice
        */
        $sucursal = session('sucursal');

        return [
            $sucursal->nombre,
            $producto->codigo,
            $producto->nombre,
            $producto->tipo->nombre,
            $producto->marca->nombre,
            ''
        ];
    }

    public function headings() : array
    {
        return [
            'Almacen',
            'Codigo',
            'Nombre Producto',
            'Tipo',
            'Marca',
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
                $event->sheet->getStyle('A1:F1')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->freezePane('B1');
            },
        ];
    }
}

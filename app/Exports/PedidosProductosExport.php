<?php

namespace App\Exports;

use App\PedidosProducto;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
//use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class PedidosProductosExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
   
   	use Exportable;
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        // return PedidosProducto::where('pedido_id', $this->id)
        //             ->get();   
        $pedidos = DB::table('pedidos')
                ->where('pedidos.id', '=', $this->id)
                ->join('pedidos_productos', 'pedidos.id', '=', 'pedidos_productos.pedido_id')
                ->join('almacenes', 'pedidos.almacene_solicitante_id', '=', 'almacenes.id')
                ->join('productos', 'pedidos_productos.producto_id', '=', 'productos.id')
                ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
                ->join('tipos', 'productos.tipo_id', '=', 'tipos.id')
                ->select(
                    'pedidos.numero as numero_pedido',
                    'almacenes.nombre as nombre_almacen',
                    'productos.codigo',
                    'productos.nombre as nombre_producto',
                    'marcas.nombre as nombre_marca',
                    'tipos.nombre as nombre_tipo',
                    'productos.modelo',
                    'productos.colores',
                    'pedidos_productos.cantidad'
                    )
                ->get();
        return $pedidos;
    }

    public function map($pedidos): array
    {
        /**
        * @var Invoice $invoice
        */
        return [
            $pedidos->numero_pedido,
            $pedidos->nombre_almacen,
            $pedidos->codigo,
            $pedidos->nombre_producto,
            $pedidos->nombre_marca,
            $pedidos->nombre_tipo,
            $pedidos->cantidad,
            '0',
        ];
    }

    public function headings() : array
    {
        return [
            'Pedido Nro',
            'Almacen Solicitante',
            'Codigo',
            'Nombre',
            'Marca',
            'Tipo',
            'Cantidad solicitada',
            'Cantidad a enviar'
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
                $event->sheet->getStyle('A1:H1')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->freezePane('B1');
            },
        ];
    }
}

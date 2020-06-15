<?php

namespace App\Exports;

use App\Nota;
use App\NotasPropuesta;
//use Illuminate\Contracts\View\View;
//use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
//use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class NotasExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
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
        $notapropuesta = NotasPropuesta::find($this->id);
        return Nota::where('asignatura_id', $notapropuesta->asignatura_id)
                    ->where('turno_id', $notapropuesta->turno_id)
                    ->where('user_id', $notapropuesta->user_id)
                    ->where('paralelo', $notapropuesta->paralelo)
                    ->where('anio_vigente', $notapropuesta->anio_vigente)
                    ->get();                
    }

    public function map($nota): array
    {
        /**
        * @var Invoice $invoice
        */
        return [
            $nota->id,
            $nota->persona->nombres. ' ' .$nota->persona->apellido_paterno. ' ' .$nota->persona->apellido_materno,
            $nota->persona->carnet,
            $nota->nota_asistencia,
            $nota->nota_practicas,
            $nota->nota_puntos_ganados,
            $nota->nota_primer_parcial,
            $nota->nota_examen_final,
        ];
    }

    public function headings() : array
    {
        return [
            '# Id',
            'Nombres y Apellidos',
            'CI',
            'Asistencia',
            'Practicas',
            'Puntos Ganados',
            'Primer Parcial',
            'Examen Final',
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

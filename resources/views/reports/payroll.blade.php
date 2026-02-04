<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planilla de Sueldos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; text-transform: uppercase; }
        .header p { margin: 2px 0; color: #555; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f0f0f0; text-transform: uppercase; font-size: 10px; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .signatures { margin-top: 80px; width: 100%; }
        .sig-box { width: 30%; display: inline-block; text-align: center; border-top: 1px solid #000; margin: 0 9%; }

        @media print {
            .no-print { display: none; }
            @page { size: landscape; margin: 1cm; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="no-print" style="padding:10px; cursor:pointer; margin-bottom:20px;">🖨️ Imprimir Planilla</button>

    <div class="header">
        <h1>PLANILLA DE SUELDOS Y SALARIOS</h1>
        <p>Periodo: {{ strtoupper(\Carbon\Carbon::create()->month($month)->locale('es')->monthName) }} {{ $year }}</p>
        <p>Expresado en Bolivianos</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Empleado</th>
                <th>Cargo</th>
                <th>C.I.</th>
                <th>Haber Básico</th>
                <th>Bonos</th>
                <th>Desc. Atrasos</th>
                <th>Desc. Faltas</th>
                <th>Líquido Pagable</th>
                <th style="width: 150px;">Firma Conforme</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBasico = 0; $totalLiq = 0;
                $i = 1;
            @endphp
            @foreach($payrolls as $row)
            <tr>
                <td>{{ $i++ }}</td>
                <td class="text-left">{{ $row->employee->name }}</td>
                <td>{{ $row->employee->position }}</td>
                <td>{{ $row->employee->ci }}</td>
                <td class="text-right">{{ number_format($row->base_salary, 2) }}</td>
                <td class="text-right">{{ number_format($row->bonuses, 2) }}</td>
                <td class="text-right">{{ number_format($row->discount_lates, 2) }}</td>
                <td class="text-right">{{ number_format($row->discount_absences, 2) }}</td>
                <td class="text-right font-bold">{{ number_format($row->final_pay, 2) }}</td>
                <td></td> {{-- Espacio para firma --}}
            </tr>
            @php
                $totalBasico += $row->base_salary;
                $totalLiq += $row->final_pay;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f0f0f0; font-weight: bold;">
                <td colspan="4" class="text-right">TOTALES:</td>
                <td class="text-right">{{ number_format($totalBasico, 2) }}</td>
                <td colspan="3"></td>
                <td class="text-right">{{ number_format($totalLiq, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div class="sig-box">
            Realizado por<br>
            <strong>Recursos Humanos</strong>
        </div>
        <div class="sig-box">
            Aprobado por<br>
            <strong>Gerencia General</strong>
        </div>
    </div>
</body>
</html>

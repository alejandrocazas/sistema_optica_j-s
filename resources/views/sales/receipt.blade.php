<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recibo #{{ $sale->receipt_number }}</title>
    <style>
        @page { margin: 0; size: 72mm auto; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            margin: 4mm;
            color: #000;
        }

        /* UTILIDADES */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* ENCABEZADO */
        .company { font-size: 14px; font-weight: bold; text-transform: uppercase; line-height: 1.1; margin-bottom: 2px; }
        .sub-header { font-size: 9px; text-transform: uppercase; }

        /* SEPARADORES */
        .dashed { border-top: 1px dashed #000; margin: 6px 0; }
        .cut-line { border-top: 2px dashed #000; margin: 25px 0 15px 0; padding-top: 5px; text-align: center; font-size: 8px; }

        /* TABLAS */
        .info-table { width: 100%; font-size: 9px; margin-bottom: 5px; }
        .info-table td { vertical-align: top; padding-bottom: 2px; }
        .label { font-weight: bold; width: 65px; }

        .products-table { width: 100%; font-size: 9px; border-collapse: collapse; margin-top: 5px; }
        .products-table th { text-align: left; border-bottom: 1px dashed #000; padding-bottom: 3px; }
        .products-table td { padding-top: 3px; vertical-align: top; }

        .totals-table { width: 100%; font-size: 10px; margin-top: 5px; }
        .totals-table td { text-align: right; padding-bottom: 2px; }
        .total-final { font-size: 12px; font-weight: bold; border-top: 1px dashed #000; padding-top: 3px; }

        /* MARCA DE AGUA (LOGO CENTRADO) */
        .watermark {
            position: fixed;
            top: 25%;
            left: 15%;
            width: 70%;
            z-index: -1;
            opacity: 0.1; /* Transparencia suave */
        }

        .fiscal-warning { text-align: center; font-weight: bold; font-size: 9px; margin: 10px 0; }
        .qr-box { text-align: center; margin: 10px 0; }
    </style>
</head>
<body>

    {{-- MARCA DE AGUA --}}
    @if(isset($logoBase64))
        <img src="{{ $logoBase64 }}" class="watermark">
    @endif

    {{-- ========================================== --}}
    {{--             RECIBO CLIENTE                 --}}
    {{-- ========================================== --}}

    <div class="text-center">
        <div class="company">GRUPO ÓPTICO J&S</div>
        <div class="sub-header">SUCURSAL: {{ $sale->branch->name ?? 'CENTRAL' }}</div>
        <div class="sub-header">{{ $sale->branch->address ?? 'ORURO, BOLIVIA' }}</div>
        <div class="sub-header">TELF: {{ $sale->branch->phone ?? '61665776' }}</div>

        <div class="bold" style="margin-top: 8px; font-size: 12px;">NOTA DE VENTA</div>
        <div class="bold">Nº {{ $sale->receipt_number }}</div>
    </div>

    <div class="dashed"></div>

    <table class="info-table">
        <tr>
            <td class="label">CLIENTE:</td>
            <td class="uppercase">{{ $sale->patient ? $sale->patient->name : 'PÚBLICO GENERAL' }}</td>
        </tr>
        @if($sale->patient && $sale->patient->ci)
        <tr>
            <td class="label">CI/NIT:</td>
            <td>{{ $sale->patient->ci }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">EMISIÓN:</td>
            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @if($sale->delivery_date)
        <tr>
            <td class="label">ENTREGA:</td>
            <td class="bold">{{ $sale->delivery_date->format('d/m/Y H:i') }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">ATENDIÓ:</td>
            <td class="uppercase">{{ \Illuminate\Support\Str::limit($sale->user->name, 15) }}</td>
        </tr>
    </table>

    <div class="dashed"></div>

    <table class="products-table">
        <thead>
            <tr>
                <th>DESCRIPCIÓN</th>
                <th class="text-right" width="25%">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->details as $detail)
            <tr>
                <td>
                    {{ $detail->quantity }}x {{ $detail->product->name }}
                    @if($detail->quantity > 1) <span style="font-size:8px">({{ number_format($detail->price, 2) }})</span> @endif
                </td>
                <td class="text-right">{{ number_format($detail->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>SUBTOTAL:</td>
            <td>{{ number_format($sale->total + ($sale->discount ?? 0), 2) }}</td>
        </tr>
        @if($sale->discount > 0)
        <tr>
            <td>DESCUENTO:</td>
            <td>-{{ number_format($sale->discount, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td class="total-final">TOTAL A PAGAR:</td>
            <td class="total-final">Bs {{ number_format($sale->total, 2) }}</td>
        </tr>
        <tr><td colspan="2" style="height:3px;"></td></tr>
        <tr>
            <td>A CUENTA:</td>
            <td>{{ number_format($sale->paid_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="bold">{{ $sale->balance > 0 ? 'SALDO:' : 'CAMBIO:' }}</td>
            <td class="bold">{{ number_format(abs($sale->total - $sale->paid_amount), 2) }}</td>
        </tr>
    </table>

    <div class="fiscal-warning">"NO ES VÁLIDO PARA CRÉDITO FISCAL"</div>

    @if(isset($qrImage))
        <div class="qr-box">
            <img src="data:image/svg+xml;base64,{{ $qrImage }}" width="70">
        </div>
    @endif

    <div class="text-center" style="font-size: 9px;">
        Gracias por su preferencia<br>
        <strong>www.grupoopticojs.com</strong>
    </div>


    {{-- ========================================== --}}
    {{--       RECIBO RESUMIDO (CONTROL INTERNO)    --}}
    {{-- ========================================== --}}

    <div class="cut-line">✂ - - - - - CONTROL INTERNO / TALLER - - - - - ✂</div>

    <div class="text-center">
        <div class="company" style="font-size: 11px;">GRUPO ÓPTICO J&S</div>
        <div class="bold uppercase">RESUMEN DE TRABAJO</div>
        <div style="font-size: 10px;">Nº {{ $sale->receipt_number }}</div>
    </div>

    <div class="dashed"></div>

    <table class="info-table">
        {{-- NUEVO CAMPO: MODALIDAD DE CONSULTA --}}
        <tr>
            <td class="label">MODO:</td>
            <td class="bold" style="font-size: 11px; background-color: #eee;">
                {{-- Verifica que 'has_consultation' sea el nombre real de tu columna en BD --}}
                {{ $sale->has_consultation ? 'CON CONSULTA (C/S)' : 'SIN CONSULTA (S/C)' }}
            </td>
        </tr>
        <tr style="height: 5px;"></tr> {{-- Espacio --}}

        <tr>
            <td class="label">CLIENTE:</td>
            <td class="bold uppercase" style="font-size: 11px;">{{ $sale->patient ? $sale->patient->name : 'S/N' }}</td>
        </tr>
        <tr>
            <td class="label">VENDEDOR:</td>
            <td class="uppercase">{{ \Illuminate\Support\Str::limit($sale->user->name, 15) }}</td>
        </tr>
        <tr>
            <td class="label">SUCURSAL:</td>
            <td class="uppercase">{{ $sale->branch->name ?? 'CENTRAL' }}</td>
        </tr>
        @if($sale->delivery_date)
        <tr>
            <td class="label">F. ENTREGA:</td>
            <td class="bold" style="font-size: 11px; border: 1px solid #000; padding: 2px;">{{ $sale->delivery_date->format('d/m/Y H:i') }}</td>
        </tr>
        @endif
    </table>

    <div class="dashed"></div>

    {{-- RESUMEN ECONÓMICO --}}
    <table class="info-table" style="font-size: 11px;">
        <tr>
            <td class="bold">TOTAL:</td>
            <td class="text-right bold">Bs {{ number_format($sale->total, 2) }}</td>
        </tr>
        <tr>
            <td>A CUENTA:</td>
            <td class="text-right">{{ number_format($sale->paid_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="bold">SALDO:</td>
            <td class="text-right bold" style="font-size: 13px;">Bs {{ number_format($sale->balance, 2) }}</td>
        </tr>
    </table>

    @if($sale->observations)
    <div style="margin-top: 5px; font-size: 9px; border: 1px dashed #000; padding: 3px;">
        <strong>OBS:</strong> {{ $sale->observations }}
    </div>
    @endif

</body>
</html>

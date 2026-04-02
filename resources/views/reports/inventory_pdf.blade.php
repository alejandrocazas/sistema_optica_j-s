<!DOCTYPE html>
<html>
<head>
    <title>Inventario Físico - Grupo Óptico J&S</title>
    <style>
        /* Tipografía y reset compatibles con PDF */
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }

        /* Colores corporativos */
        .text-gold { color: #C59D5F; }

        /* Cabecera estructurada con tabla invisible para alinear perfectamente */
        .header-table { width: 100%; border-bottom: 3px solid #C59D5F; padding-bottom: 15px; margin-bottom: 25px; }
        .header-table td { vertical-align: middle; border: none; padding: 0; }

        .brand-name { font-family: Georgia, serif; font-size: 28px; font-weight: bold; margin: 0; letter-spacing: 1px; color: #171717; }
        .brand-slogan { font-size: 9px; text-transform: uppercase; letter-spacing: 3px; color: #666; margin-top: 4px; }

        .report-title { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #171717; margin: 0 0 6px 0; text-align: right; }
        .report-meta { font-size: 11px; color: #555; text-align: right; margin: 3px 0; }

        /* Tabla Principal de Datos */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }

        /* Encabezados Oscuros con texto Dorado */
        .data-table th { background-color: #171717; color: #C59D5F; font-weight: bold; text-align: left; padding: 12px 8px; text-transform: uppercase; font-size: 9px; letter-spacing: 1px; border: 1px solid #171717; }

        /* Filas de datos */
        .data-table td { padding: 8px; border: 1px solid #e5e7eb; vertical-align: middle; }
        .data-table tr:nth-child(even) { background-color: #f9fafb; }

        /* Control preciso de anchos de columna */
        .center { text-align: center; }
        .col-nro { width: 4%; text-align: center; font-weight: bold; color: #9ca3af; font-size: 10px; }
        .col-code { width: 16%; font-family: monospace; font-size: 12px; font-weight: bold; color: #171717; }
        .col-stock { width: 12%; text-align: center; font-weight: bold; font-size: 13px; background-color: #f3f4f6; }
        .col-write { width: 14%; text-align: center; }

        /* Cajas especiales para llenado manual con bolígrafo */
        .write-box { width: 100%; height: 18px; border-bottom: 1px dotted #9ca3af; }

        /* Paginación automática y Pie de página */
        @page { margin: 40px 40px; }
        .footer { position: fixed; bottom: -20px; left: 0; right: 0; text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 8px; }
        .pagenum:before { content: counter(page); }
    </style>
</head>
<body>

    <div class="footer">
        Grupo Óptico J&S - Documento interno generado el {{ date('d/m/Y H:i') }} - Página <span class="pagenum"></span>
    </div>

    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <h1 class="brand-name">GRUPO ÓPTICO <span class="text-gold">J&S</span></h1>
                <div class="brand-slogan">Visión y Vida</div>
            </td>
            <td style="width: 50%;">
                <h2 class="report-title">Toma de Inventario Físico</h2>
                <p class="report-meta"><strong>CATEGORÍA:</strong> {{ strtoupper($categoriaNombre) }}</p>
                <p class="report-meta"><strong>FECHA DE CORTE:</strong> {{ date('d/m/Y') }}</p>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="col-nro">#</th>
                <th class="col-code">CÓDIGO</th>
                <th>PRODUCTO / DESCRIPCIÓN</th>
                <th class="col-stock">STOCK SISTEMA</th>
                <th class="center" style="width: 14%;">CONTEO FÍSICO</th>
                <th class="center" style="width: 14%;">DIFERENCIA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td class="col-nro">{{ $index + 1 }}</td>
                <td class="col-code">{{ $product->code }}</td>
                <td>
                    <strong style="color: #171717; font-size: 12px;">{{ $product->name }}</strong>
                    @if(!empty($product->batch))
                        <br><span style="color: #6b7280; font-size: 9px; text-transform: uppercase;">Lote: {{ $product->batch }}</span>
                    @endif
                </td>
                <td class="col-stock">
                    {{ $product->stock }}
                </td>
                <td class="col-write"><div class="write-box"></div></td>
                <td class="col-write"><div class="write-box"></div></td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>

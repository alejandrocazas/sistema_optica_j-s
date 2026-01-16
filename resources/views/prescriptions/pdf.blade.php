<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Receta #{{ $prescription->id }}</title>
    <style>
        /* TAMAÑO COMPACTO (1/4 DE CARTA APROX) */
        @page {
            margin: 0;
            size: 108mm 140mm; /* Ancho x Alto ajustado */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 5mm;
            color: #1f2937;
            font-size: 8px; /* Letra pequeña para formato compacto */
            line-height: 1.1;
        }

        /* COLORES */
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; color: white; }

        /* ESTRUCTURA */
        table { width: 100%; border-collapse: collapse; }

        /* ENCABEZADO */
        .header td { vertical-align: middle; }
        .company-title { font-size: 10px; font-weight: 900; text-transform: uppercase; color: #111; }
        .company-sub { font-size: 6px; color: #C59D5F; letter-spacing: 1px; font-weight: bold;}
        .contact-info { font-size: 5px; color: #666; text-align: right; line-height: 1.1; }

        /* CAJA PACIENTE (COMPACTA) */
        .patient-bar {
            margin-top: 8px;
            background-color: #f3f4f6;
            border-left: 2px solid #C59D5F;
            padding: 3px 5px;
        }
        .label { font-size: 5px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
        .value { font-size: 9px; font-weight: bold; color: #000; }

        /* TABLA RX */
        .rx-table { margin-top: 8px; border: 1px solid #e5e7eb; }
        .rx-table th { background: #C59D5F; color: white; font-size: 6px; padding: 2px; text-transform: uppercase; }
        .rx-table td { border: 1px solid #e5e7eb; text-align: center; padding: 4px; font-weight: bold; font-size: 9px; }
        .eye-col { background: #fdfdfd; color: #C59D5F; font-weight: 900; font-size: 8px; width: 15px; }

        /* CAJA INFERIOR (ADD + DIP) */
        .bottom-data { margin-top: 5px; }
        .data-box { border: 1px solid #ddd; text-align: center; padding: 2px; border-radius: 3px; }

        /* OBSERVACIONES */
        .obs-box {
            margin-top: 5px;
            border-top: 1px dashed #ccc;
            padding-top: 3px;
            min-height: 25px;
        }

        /* FIRMA */
        .footer { text-align: center; margin-top: 15px; }
        .sign-line { border-bottom: 1px solid #000; width: 60%; margin: 0 auto 2px auto; }
        .specialist { font-size: 7px; font-weight: bold; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td width="15%">
                @if(isset($logoBase64))
                    <img src="{{ $logoBase64 }}" style="height: 30px; width: auto;">
                @endif
            </td>
            <td width="50%" style="padding-left: 5px;">
                <div class="company-title">Consultorio Optometrico</div>
                <div class="company-sub">J&S</div>
            </td>
            <td width="35%">
                <div class="contact-info">
                    6 de Octubre, Adolfo Mier<br>
                    Oruro, Bolivia | Cel: 76141807<br>
                    <strong style="color:#000; font-size:7px;">RECETA Nº {{ str_pad($prescription->id, 5, '0', STR_PAD_LEFT) }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="patient-bar">
        <table>
            <tr>
                <td width="65%">
                    <span class="label">Paciente:</span><br>
                    <span class="value">{{ \Illuminate\Support\Str::limit($prescription->patient->name, 25) }}</span>
                </td>
                <td width="15%">
                    <span class="label">Edad:</span><br>
                    <span class="value">{{ $prescription->patient->age }}</span>
                </td>
                <td width="20%" align="right">
                    <span class="label">Fecha:</span><br>
                    <span class="value" style="font-size:7px;">{{ $prescription->created_at->format('d/m/y') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="font-size:6px; font-weight:bold; color:#C59D5F; margin-top:5px;">REFRACCIÓN LEJANA</div>
    <table class="rx-table">
        <thead>
            <tr>
                <th></th>
                <th>ESF</th>
                <th>CIL</th>
                <th>EJE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="eye-col">OD</td>
                <td>{{ $prescription->od_esfera > 0 ? '+' : '' }}{{ number_format((float)$prescription->od_esfera, 2) }}</td>
                <td>{{ $prescription->od_cilindro > 0 ? '+' : '' }}{{ number_format((float)$prescription->od_cilindro, 2) }}</td>
                <td>{{ $prescription->od_eje }}°</td>
            </tr>
            <tr>
                <td class="eye-col">OI</td>
                <td>{{ $prescription->oi_esfera > 0 ? '+' : '' }}{{ number_format((float)$prescription->oi_esfera, 2) }}</td>
                <td>{{ $prescription->oi_cilindro > 0 ? '+' : '' }}{{ number_format((float)$prescription->oi_cilindro, 2) }}</td>
                <td>{{ $prescription->oi_eje }}°</td>
            </tr>
        </tbody>
    </table>

    <table class="bottom-data">
        <tr>
            <td width="20%">
                <div class="data-box">
                    <div class="label">ADD</div>
                    <div class="value">{{ $prescription->add_od > 0 ? '+' : '' }}{{ number_format((float)$prescription->add_od, 2) }}</div>
                </div>
            </td>

            <td width="20%" style="padding-left: 5px;">
                <div class="data-box">
                    <div class="label">D.I.P.</div>
                    <div class="value">{{ $prescription->dip }}</div>
                </div>
            </td>

            <td width="60%" style="padding-left: 10px; vertical-align: bottom;">
                <div style="border-bottom: 1px dotted #ccc; font-size: 8px;">
                    <span style="color:#C59D5F; font-weight:bold;">Dx:</span> {{ $prescription->diagnostico }}
                </div>
            </td>
        </tr>
    </table>

    <div class="obs-box">
        <span style="color:#b45309; font-weight:bold; font-size:6px;">OBSERVACIONES:</span>
        <p style="margin:2px 0; font-style:italic; font-size:8px;">
            {{ \Illuminate\Support\Str::limit($prescription->observaciones, 100) }}
        </p>
    </div>

    <div class="footer">
        <div class="sign-line"></div>
        <div class="specialist">ESPECIALISTA EN SALUD VISUAL</div>
    </div>

</body>
</html>

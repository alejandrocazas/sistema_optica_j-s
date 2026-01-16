<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Receta #{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* CONFIGURACIÓN DE PÁGINA: MEDIA CARTA */
        @page {
            margin: 0;
            size: 140mm 216mm; /* Ancho x Alto */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 15mm; /* Margen interno del contenido */
            color: #1f2937; /* Gris muy oscuro (casi negro) */
            font-size: 10px;
            line-height: 1.4;
        }

        /* UTILIDADES DE COLOR DE MARCA */
        .text-gold { color: #C59D5F; }
        .bg-gold { background-color: #C59D5F; color: white; }
        .border-gold { border-color: #C59D5F; }
        .text-muted { color: #6b7280; }

        /* ENCABEZADO */
        .header-table { width: 100%; border-bottom: 2px solid #C59D5F; padding-bottom: 10px; margin-bottom: 15px; }
        .logo-img { height: 50px; width: auto; } /* Ajusta según tu logo */
        .company-name { font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .company-details { font-size: 8px; color: #6b7280; }

        /* SECCIÓN PACIENTE */
        .patient-box {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9fafb;
            border-left: 3px solid #C59D5F;
        }
        .info-label { font-size: 8px; text-transform: uppercase; color: #9ca3af; font-weight: bold; letter-spacing: 1px; }
        .info-value { font-size: 12px; font-weight: bold; color: #111; border-bottom: 1px dotted #ccc; display: block; margin-bottom: 4px; }

        /* TABLA DE MEDIDAS (RX) */
        .rx-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #C59D5F;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
        }

        .rx-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .rx-table th {
            text-align: center;
            font-size: 8px;
            text-transform: uppercase;
            color: #6b7280;
            padding: 4px;
            border-bottom: 1px solid #C59D5F;
        }

        .rx-table td {
            text-align: center;
            padding: 8px 4px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: bold;
            font-size: 12px;
        }

        .eye-col {
            background-color: #fdfdfd;
            color: #C59D5F;
            font-weight: 900;
            border-right: 1px solid #eee;
        }

        /* NOTAS Y DIAGNÓSTICO */
        .notes-section {
            border: 1px dashed #d1d5db;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        /* PIE DE PÁGINA / FIRMA */
        .footer {
            position: fixed;
            bottom: 15mm;
            left: 15mm;
            right: 15mm;
            text-align: center;
        }
        .signature-line {
            width: 180px;
            border-bottom: 1px solid #1f2937;
            margin: 0 auto 5px auto;
        }
        .specialist-name { font-weight: bold; font-size: 11px; }
        .specialist-role { font-size: 9px; color: #C59D5F; text-transform: uppercase; letter-spacing: 1px;}

        /* MARCA DE AGUA (OPCIONAL) */
        .watermark {
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(197, 157, 95, 0.05); /* Dorado muy transparente */
            font-weight: bold;
            z-index: -1;
            white-space: nowrap;
        }
    </style>
</head>
<body>

    {{-- MARCA DE AGUA --}}
    <div class="watermark">CENTRO ÓPTICO</div>

    {{-- ENCABEZADO CON LOGO --}}
    <table class="header-table">
        <tr>
            <td width="60%" style="vertical-align: middle;">
                {{-- AQUÍ VA EL LOGO --}}
                @if(isset($logoBase64))
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo">
                @else
                    {{-- Si no hay logo, mostramos texto estilizado --}}
                    <div class="company-name text-gold" style="font-size: 24px; line-height: 1;">J & S</div>
                    <div style="font-size: 10px; letter-spacing: 3px; text-transform: uppercase;">Grupo Óptico</div>
                @endif
            </td>
            <td width="40%" style="text-align: right; vertical-align: middle;">
                <div class="company-name" style="font-size: 10px;">RECETA MÉDICA</div>
                <div style="font-size: 16px; font-weight: bold; color: #C59D5F;">Nº {{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="company-details">{{ date('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    {{-- DATOS DEL PACIENTE --}}
    <div class="patient-box">
        <table width="100%">
            <tr>
                <td width="65%">
                    <span class="info-label">Paciente</span>
                    <span class="info-value">{{ $prescription->patient->name }}</span>
                </td>
                <td width="15%">
                    <span class="info-label">Edad</span>
                    <span class="info-value">{{ $prescription->patient->age }} Años</span>
                </td>
                <td width="20%">
                    <span class="info-label">CI / DNI</span>
                    <span class="info-value">{{ $prescription->patient->ci }}</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- TABLA DE REFRACCIÓN (EL CORAZÓN) --}}
    <div class="rx-title">Refracción Lejana</div>
    <table class="rx-table">
        <thead>
            <tr>
                <th width="10%"></th>
                <th width="30%">ESFERA</th>
                <th width="30%">CILINDRO</th>
                <th width="30%">EJE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="eye-col">OD</td>
                <td>{{ $prescription->od_esfera != 0 ? number_format((float)$prescription->od_esfera, 2) : 'Neutro' }}</td>
                <td>{{ $prescription->od_cilindro != 0 ? number_format((float)$prescription->od_cilindro, 2) : '-' }}</td>
                <td>{{ $prescription->od_eje ? $prescription->od_eje . '°' : '-' }}</td>
            </tr>
            <tr>
                <td class="eye-col">OI</td>
                <td>{{ $prescription->oi_esfera != 0 ? number_format((float)$prescription->oi_esfera, 2) : 'Neutro' }}</td>
                <td>{{ $prescription->oi_cilindro != 0 ? number_format((float)$prescription->oi_cilindro, 2) : '-' }}</td>
                <td>{{ $prescription->oi_eje ? $prescription->oi_eje . '°' : '-' }}</td>
            </tr>
        </tbody>
    </table>

    @if($prescription->add_od || $prescription->add_oi)
        <div class="rx-title" style="margin-top: 15px;">Adición (Lectura)</div>
        <table class="rx-table">
            <tbody>
                <tr>
                    <td class="eye-col" width="10%">ADD</td>
                    <td width="45%">OD: <strong>{{ $prescription->add_od > 0 ? '+' : '' }}{{ number_format((float)$prescription->add_od, 2) }}</strong></td>
                    <td width="45%">OI: <strong>{{ $prescription->add_oi > 0 ? '+' : '' }}{{ number_format((float)$prescription->add_oi, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    @endif

    {{-- OTROS DATOS --}}
    <table width="100%" style="margin-top: 10px; margin-bottom: 15px;">
        <tr>
            <td width="30%">
                <span class="info-label">D.I.P.</span>
                <div style="border: 1px solid #ddd; padding: 5px; text-align: center; border-radius: 3px; font-weight: bold;">
                    {{ $prescription->dip ?? '--' }} mm
                </div>
            </td>
            <td width="70%" style="padding-left: 10px;">
                <span class="info-label">Diagnóstico</span>
                <div style="border-bottom: 1px solid #ddd; padding: 5px; font-size: 11px;">
                    {{ $prescription->diagnostico ?? 'General' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- OBSERVACIONES --}}
    @if($prescription->observaciones)
        <div class="notes-section">
            <span class="info-label" style="color: #C59D5F;">Observaciones / Recomendaciones:</span>
            <p style="margin: 5px 0 0 0; font-style: italic; font-size: 10px;">
                {{ $prescription->observaciones }}
            </p>
        </div>
    @endif

    {{-- FIRMA --}}
    <div class="footer">
        <div class="signature-line"></div>
        <div class="specialist-name">{{ strtoupper($prescription->user->name) }}</div>
        <div class="specialist-role">Especialista en Salud Visual</div>
    </div>

</body>
</html>

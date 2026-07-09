<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 2.5cm 2cm; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #1a1a1a;
        }
        h2 { font-size: 14pt; margin-bottom: 1rem; }
        h3 { font-size: 12pt; margin-top: 1.5rem; margin-bottom: 0.5rem; color: #2563eb; }
        hr { border: none; border-top: 1px solid #ccc; margin: 1.5rem 0; }
        p { margin: 0.5rem 0; }
        ul { margin: 0.5rem 0; padding-left: 1.5rem; }
        strong { color: #111; }
        .firma { margin-top: 2rem; }
    </style>
</head>
<body>
    {!! $contenido !!}

    @if($paciente)
        <div style="margin-top:3rem;padding-top:1.5rem;border-top:1px solid #ccc;font-size:9pt;color:#666;">
            <p>Documento generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }} desde PsicoCMS.</p>
        </div>
    @endif
</body>
</html>

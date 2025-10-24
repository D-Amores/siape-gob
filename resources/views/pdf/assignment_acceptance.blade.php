<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Aceptación de Bien Asignado</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2, h4 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        .signature { margin-top: 50px; text-align: center; }
        .signature-line { border-top: 1px solid #000; width: 250px; margin: 0 auto; }
    </style>
</head>
<body>
    <h2>Acta de Aceptación de Bien Asignado</h2>
    <h4>Fecha de emisión: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h4>

    <p>Por medio del presente documento se hace constar que el siguiente bien ha sido recibido conforme.</p>

    <table>
        <tr><th>Bien</th><td>{{ $assignment->asset->name }}</td></tr>
        <tr><th>Modelo</th><td>{{ $assignment->asset->model ?? 'N/A' }}</td></tr>
        <tr><th>Número de Serie</th><td>{{ $assignment->asset->serial_number ?? 'N/A' }}</td></tr>
        <tr><th>Asignado por</th><td>{{ $assignment->assigner->name }}</td></tr>
        <tr><th>Recibido por</th><td>{{ $assignment->receiver->name }}</td></tr>
        <tr><th>Fecha de Asignación</th><td>{{ \Carbon\Carbon::parse($assignment->assignment_date)->format('d/m/Y') }}</td></tr>
        <tr><th>Fecha de Aceptación</th><td>{{ \Carbon\Carbon::parse($assignment->accepted_at)->format('d/m/Y H:i') }}</td></tr>
        <tr><th>Descripción</th><td>{{ $assignment->asset->description ?? 'Sin descripción' }}</td></tr>
    </table>

    <div class="signature">
        <p>__________________________________</p>
        <p>{{ $assignment->receiver->name }}</p>
        <p>Firma del Receptor</p>
    </div>
</body>
</html>

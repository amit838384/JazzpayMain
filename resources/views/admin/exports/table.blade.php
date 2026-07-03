<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h3 { text-align: center; margin-bottom: 4px; }
        p.gen { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 5px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    @php
        // Accept either $data (PFS/Consumption exports) or $rows (dashboard exports)
        $tableRows = $data ?? $rows ?? [];
    @endphp

    <h3>{{ $title ?? 'Report' }}</h3>
    <p class="gen">Generated: {{ date('d-M-Y H:i') }}</p>

    <table>
        <thead>
            <tr>@foreach($headings as $h)<th>{{ $h }}</th>@endforeach</tr>
        </thead>
        <tbody>
            @forelse($tableRows as $row)
                <tr>@foreach($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>
            @empty
                <tr><td colspan="{{ count($headings) }}" style="text-align:center;">No data available</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

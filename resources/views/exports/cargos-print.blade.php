<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cargos Print</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 20px; }
        .meta { font-size: 13px; color: #444; margin-bottom: 12px; }
        .btn { padding: 8px 14px; border: 0; background: #111827; color: white; border-radius: 6px; cursor: pointer; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dcdcdc; padding: 8px; text-align: left; font-size: 12px; vertical-align: top; }
        th { background: #f5f5f5; }
        @media print { .no-print { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
<h2>Cargos Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

<button class="btn no-print" onclick="window.print()">Print</button>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Code</th>
        <th>Type</th>
        <th>UOM</th>
        <th>Description</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($cargos as $index => $cargo)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $cargo->name }}</td>
            <td>{{ $cargo->code ?: '—' }}</td>
            <td>{{ $cargo->type ?: '—' }}</td>
            <td>{{ $cargo->uom ?: '—' }}</td>
            <td>{{ $cargo->description ?: '—' }}</td>
            <td>{{ $cargo->is_active ? 'Active' : 'Inactive' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7">No cargos found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

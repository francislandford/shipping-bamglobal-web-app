<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cargos PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .meta { margin-bottom: 12px; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
<h2>Cargos Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

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

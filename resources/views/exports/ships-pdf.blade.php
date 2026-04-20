<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ships PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .meta { margin-bottom: 12px; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
<h2>Ships Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Type: {{ $filters['type'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Ship Name</th>
        <th>IMO Number</th>
        <th>Call Sign</th>
        <th>Flag</th>
        <th>Type</th>
        <th>Owner</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($ships as $index => $ship)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $ship->name }}</td>
            <td>{{ $ship->imo_number ?: '—' }}</td>
            <td>{{ $ship->call_sign ?: '—' }}</td>
            <td>{{ $ship->flag ?: '—' }}</td>
            <td>{{ $ship->type ?: '—' }}</td>
            <td>{{ $ship->owner ?: '—' }}</td>
            <td>{{ $ship->is_active ? 'Active' : 'Inactive' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8">No ships found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

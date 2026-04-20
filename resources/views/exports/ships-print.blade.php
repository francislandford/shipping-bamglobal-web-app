<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ships Print</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 20px; }
        .meta { font-size: 13px; color: #444; margin-bottom: 12px; }
        .btn { padding: 8px 14px; border: 0; background: #111827; color: white; border-radius: 6px; cursor: pointer; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dcdcdc; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #f5f5f5; }
        @media print { .no-print { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
<h2>Ships Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Type: {{ $filters['type'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

<button class="btn no-print" onclick="window.print()">Print</button>

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

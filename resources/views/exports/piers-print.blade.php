<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Piers Print</title>
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
<h2>Piers Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Port: {{ $filters['port'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

<button class="btn no-print" onclick="window.print()">Print</button>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Pier Name</th>
        <th>Code</th>
        <th>Port</th>
        <th>Location</th>
        <th>Capacity</th>
        <th>Contact Person</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($piers as $index => $pier)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $pier->name }}</td>
            <td>{{ $pier->code ?: '—' }}</td>
            <td>{{ $pier->port?->name ?: '—' }}</td>
            <td>{{ $pier->location ?: '—' }}</td>
            <td>{{ $pier->capacity ?: '—' }}</td>
            <td>{{ $pier->contact_person ?: '—' }}</td>
            <td>{{ $pier->email ?: '—' }}</td>
            <td>{{ $pier->phone ?: '—' }}</td>
            <td>{{ $pier->is_active ? 'Active' : 'Inactive' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="10">No piers found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

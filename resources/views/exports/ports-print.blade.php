<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ports Print</title>
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
<h2>Ports Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Country: {{ $filters['country'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

<button class="btn no-print" onclick="window.print()">Print</button>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Port Name</th>
        <th>Code</th>
        <th>Country</th>
        <th>City</th>
        <th>Location</th>
        <th>Contact Person</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($ports as $index => $port)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $port->name }}</td>
            <td>{{ $port->code ?: '—' }}</td>
            <td>{{ $port->country ?: '—' }}</td>
            <td>{{ $port->city ?: '—' }}</td>
            <td>{{ $port->location ?: '—' }}</td>
            <td>{{ $port->contact_person ?: '—' }}</td>
            <td>{{ $port->email ?: '—' }}</td>
            <td>{{ $port->phone ?: '—' }}</td>
            <td>{{ $port->is_active ? 'Active' : 'Inactive' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="10">No ports found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

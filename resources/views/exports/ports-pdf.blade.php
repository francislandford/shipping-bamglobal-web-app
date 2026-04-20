<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ports PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .meta { margin-bottom: 12px; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
<h2>Ports Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Country: {{ $filters['country'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

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

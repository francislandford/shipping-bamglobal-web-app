<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Piers PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .meta { margin-bottom: 12px; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
<h2>Piers Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Port: {{ $filters['port'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

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

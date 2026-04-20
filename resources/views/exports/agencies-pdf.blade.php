<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agencies PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .meta { margin-bottom: 12px; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
<h2>Agencies Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Agency Name</th>
        <th>Code</th>
        <th>Contact Person</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($agencies as $index => $agency)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $agency->name }}</td>
            <td>{{ $agency->code ?: '—' }}</td>
            <td>{{ $agency->contact_person ?: '—' }}</td>
            <td>{{ $agency->email ?: '—' }}</td>
            <td>{{ $agency->phone ?: '—' }}</td>
            <td>{{ $agency->address ?: '—' }}</td>
            <td>{{ $agency->is_active ? 'Active' : 'Inactive' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8">No agencies found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

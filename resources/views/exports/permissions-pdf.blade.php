<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Permissions PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .meta { margin-bottom: 12px; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
<h2>Permissions Report</h2>
<div class="meta">Search: {{ $filters['search'] ?: 'All' }}</div>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Permission</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($permissions as $index => $permission)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $permission->name }}</td>
            <td>{{ $permission->created_at?->format('M d, Y H:i') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3">No permissions found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

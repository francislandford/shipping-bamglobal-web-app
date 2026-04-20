<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Roles Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .meta { margin-bottom: 12px; font-size: 13px; color: #444; }
        .btn { padding: 8px 14px; border: 0; background: #111827; color: white; border-radius: 6px; cursor: pointer; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #f5f5f5; }
        @media print { .no-print { display:none; } body { margin:0; } }
    </style>
</head>
<body>
<h2>Roles Report</h2>
<div class="meta">Search: {{ $filters['search'] ?: 'All' }}</div>
<button class="btn no-print" onclick="window.print()">Print</button>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Role</th>
        <th>Permissions Count</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($roles as $index => $role)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $role->name }}</td>
            <td>{{ $role->permissions_count }}</td>
            <td>{{ $role->created_at?->format('M d, Y H:i') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No roles found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

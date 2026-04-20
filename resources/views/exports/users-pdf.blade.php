<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h2 {
            margin-bottom: 8px;
        }

        .meta {
            margin-bottom: 12px;
            font-size: 11px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }
    </style>
</head>
<body>
<h2>Users Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Role: {{ $filters['role'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Roles</th>
        <th>Status</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($users as $index => $user)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->roles->pluck('name')->implode(', ') ?: 'No role' }}</td>
            <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
            <td>{{ $user->created_at?->format('M d, Y H:i') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6">No users found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

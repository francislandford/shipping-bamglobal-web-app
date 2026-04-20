<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 20px;
        }

        .header {
            margin-bottom: 20px;
        }

        .meta {
            font-size: 13px;
            color: #444;
            margin-bottom: 12px;
        }

        .btn {
            padding: 8px 14px;
            border: 0;
            background: #111827;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #dcdcdc;
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }

        th {
            background: #f5f5f5;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
<div class="header">
    <h2>Users Report</h2>
    <div class="meta">
        Search: {{ $filters['search'] ?: 'All' }} |
        Role: {{ $filters['role'] ?: 'All' }} |
        Status: {{ $filters['status'] ?: 'All' }}
    </div>
    <button class="btn no-print" onclick="window.print()">Print</button>
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

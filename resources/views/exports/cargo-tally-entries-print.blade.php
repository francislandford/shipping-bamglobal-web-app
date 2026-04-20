<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cargo Tally Entries Print</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 20px; }
        .meta { font-size: 13px; color: #444; margin-bottom: 12px; }
        .btn { padding: 8px 14px; border: 0; background: #111827; color: white; border-radius: 6px; cursor: pointer; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dcdcdc; padding: 8px; text-align: left; font-size: 12px; vertical-align: top; }
        th { background: #f5f5f5; }
        @media print { .no-print { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
<h2>Cargo Tally Entries Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Ship: {{ $filters['ship'] ?: 'All' }} |
    Agency: {{ $filters['agency'] ?: 'All' }} |
    Port: {{ $filters['port'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }}
</div>

<button class="btn no-print" onclick="window.print()">Print</button>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Ship</th>
        <th>Voyage</th>
        <th>Agency</th>
        <th>Port</th>
        <th>Pier</th>
        <th>Hatch</th>
        <th>Compartment</th>
        <th>Load Date</th>
        <th>Destination</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Remarks</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($entries as $index => $entry)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $entry->ship?->name ?: '—' }}</td>
            <td>{{ $entry->voyage }}</td>
            <td>{{ $entry->agency?->name ?: '—' }}</td>
            <td>{{ $entry->port?->name ?: '—' }}</td>
            <td>{{ $entry->pier?->name ?: '—' }}</td>
            <td>{{ $entry->hatch_no ?: '—' }}</td>
            <td>{{ $entry->compartment ?: '—' }}</td>
            <td>{{ $entry->load_date?->format('Y-m-d') ?: '—' }}</td>
            <td>{{ $entry->destination ?: '—' }}</td>
            <td>{{ $entry->package_description ?: '—' }}</td>
            <td>{{ $entry->total_quantity }}</td>
            <td>{{ $entry->condition_remarks ?: '—' }}</td>
            <td>{{ $entry->is_active ? 'Active' : 'Inactive' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="14">No cargo tally entries found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

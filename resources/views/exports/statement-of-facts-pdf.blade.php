<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statement of Facts PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        h2 { margin-bottom: 8px; }
        .meta { margin-bottom: 12px; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f1f1f1; }
        .small { font-size: 9px; color: #555; }
    </style>
</head>
<body>
<h2>Statement of Facts Report</h2>

<div class="meta">
    Search: {{ $filters['search'] ?: 'All' }} |
    Ship: {{ $filters['ship'] ?: 'All' }} |
    Port: {{ $filters['port'] ?: 'All' }} |
    Cargo: {{ $filters['cargo'] ?: 'All' }} |
    Status: {{ $filters['status'] ?: 'All' }} |
    Date From: {{ $filters['dateFrom'] ?: 'All' }} |
    Date To: {{ $filters['dateTo'] ?: 'All' }}
</div>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Ship / Cargo</th>
        <th>Port / Pier</th>
        <th>Report Date</th>
        <th>Planned / Actual</th>
        <th>Loading Method</th>
        <th>Hours Lost</th>
        <th>Created By</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($records as $index => $record)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                <div>{{ $record->ship?->name ?: '—' }}</div>
                <div class="small">{{ $record->cargoItem?->name ?: $record->cargo ?: '—' }}</div>
            </td>
            <td>
                <div>{{ $record->port?->name ?: '—' }}</div>
                <div class="small">{{ $record->pier?->name ?: '—' }}</div>
            </td>
            <td>
                <div>{{ $record->report_date?->format('Y-m-d') ?: '—' }}</div>
                <div class="small">{{ $record->report_time ?: '—' }}</div>
            </td>
            <td>
                <div>Planned: {{ number_format((float) $record->quantity_to_be_loaded, 2) }} {{ $record->uom }}</div>
                <div class="small">Actual: {{ number_format((float) $record->actual_total_loaded, 2) }} {{ $record->uom }}</div>
            </td>
            <td>
                <div>Grabs: {{ number_format((float) $record->loaded_by_grabs_qty, 2) }}</div>
                <div class="small">Ship Loaders: {{ number_format((float) $record->loaded_by_ship_loaders_qty, 2) }}</div>
            </td>
            <td>{{ number_format((float) $record->total_hours_lost, 2) }}</td>
            <td>{{ $record->user?->name ?: '—' }}</td>
            <td>{{ $record->is_active ? 'Active' : 'Inactive' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="9">No records found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>

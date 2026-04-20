<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statement of Facts Print</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 20px; }
        h2 { margin-bottom: 8px; }
        .meta { font-size: 13px; color: #444; margin-bottom: 14px; }
        .btn { padding: 8px 14px; border: 0; background: #111827; color: white; border-radius: 6px; cursor: pointer; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dcdcdc; padding: 8px; text-align: left; font-size: 12px; vertical-align: top; }
        th { background: #f5f5f5; }
        .small { font-size: 11px; color: #555; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
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

<button class="btn no-print" onclick="window.print()">Print</button>

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statement of Facts Print</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; margin: 20px; font-size: 13px; }
        h1, h2, h3 { margin: 0 0 10px; }
        .topbar { margin-bottom: 20px; }
        .btn { padding: 8px 14px; border: 0; background: #111827; color: white; border-radius: 6px; cursor: pointer; margin-bottom: 16px; }
        .section { margin-bottom: 20px; border: 1px solid #dcdcdc; border-radius: 10px; padding: 14px; }
        .section-title { font-size: 15px; font-weight: bold; margin-bottom: 12px; }
        .grid { display: table; width: 100%; border-collapse: collapse; }
        .grid-row { display: table-row; }
        .grid-cell { display: table-cell; width: 33.33%; padding: 8px 10px; vertical-align: top; }
        .label { font-size: 12px; color: #555; margin-bottom: 4px; }
        .value { font-size: 13px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #dcdcdc; padding: 8px; text-align: left; vertical-align: top; font-size: 12px; }
        th { background: #f5f5f5; }
        .notes { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; min-height: 50px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }

        @media print {
            .no-print { display: none; }
            body { margin: 0; }
            .section { break-inside: avoid; }
        }
    </style>
</head>
<body>
<div class="topbar">
    <h1>Statement of Facts</h1>
    <button class="btn no-print" onclick="window.print()">Print</button>
</div>

<div class="section">
    <div class="section-title">Basic Information</div>
    <div class="grid">
        <div class="grid-row">
            <div class="grid-cell">
                <div class="label">Ship</div>
                <div class="value">{{ $record->ship?->name ?: '—' }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Port</div>
                <div class="value">{{ $record->port?->name ?: '—' }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Pier</div>
                <div class="value">{{ $record->pier?->name ?: '—' }}</div>
            </div>
        </div>

        <div class="grid-row">
            <div class="grid-cell">
                <div class="label">Cargo</div>
                <div class="value">{{ $record->cargoItem?->name ?: $record->cargo ?: '—' }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Report Date</div>
                <div class="value">{{ $record->report_date?->format('d/m/Y') ?: '—' }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Report Time</div>
                <div class="value">{{ $record->report_time ?: '—' }}</div>
            </div>
        </div>

        <div class="grid-row">
            <div class="grid-cell">
                <div class="label">UOM</div>
                <div class="value">{{ $record->uom ?: '—' }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Created By</div>
                <div class="value">{{ $record->user?->name ?: '—' }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Status</div>
                <div class="value">
                    @if ($record->is_active)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Quantity Summary</div>
    <div class="grid">
        <div class="grid-row">
            <div class="grid-cell">
                <div class="label">Quantity To Be Loaded</div>
                <div class="value">{{ number_format((float) $record->quantity_to_be_loaded, 2) }} {{ $record->uom }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Actual Total Loaded</div>
                <div class="value">{{ number_format((float) $record->actual_total_loaded, 2) }} {{ $record->uom }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Balance To Load</div>
                <div class="value">{{ number_format((float) $record->balance_to_load, 2) }} {{ $record->uom }}</div>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Loading Method / Summary</div>
    <div class="grid">
        <div class="grid-row">
            <div class="grid-cell">
                <div class="label">Loaded by Grabs</div>
                <div class="value">{{ number_format((float) $record->loaded_by_grabs_qty, 2) }} {{ $record->uom }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Loaded by Ship Loaders</div>
                <div class="value">{{ number_format((float) $record->loaded_by_ship_loaders_qty, 2) }} {{ $record->uom }}</div>
            </div>
            <div class="grid-cell">
                <div class="label">Total Hours Lost</div>
                <div class="value">{{ number_format((float) $record->total_hours_lost, 2) }}</div>
            </div>
        </div>
    </div>

    <div style="margin-top: 14px;">
        <div class="label">Loading Method Notes</div>
        <div class="notes">{{ $record->loading_method_notes ?: 'No notes provided.' }}</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Arrival / Berthing Events</div>
    <table>
        <thead>
        <tr>
            <th style="width: 20%;">Date</th>
            <th style="width: 15%;">Time</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($record->events as $event)
            <tr>
                <td>{{ $event->event_date?->format('d/m/Y') ?: '—' }}</td>
                <td>{{ $event->event_time ?: '—' }}</td>
                <td>{{ $event->description ?: '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No events recorded.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Loading Shifts</div>
    <table>
        <thead>
        <tr>
            <th>Start</th>
            <th>End</th>
            <th>Quantity Loaded</th>
            <th>UOM</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($record->loadingShifts as $shift)
            <tr>
                <td>{{ $shift->start_datetime?->format('d/m/Y H:i') ?: '—' }}</td>
                <td>{{ $shift->end_datetime?->format('d/m/Y H:i') ?: '—' }}</td>
                <td>{{ number_format((float) $shift->quantity_loaded, 2) }}</td>
                <td>{{ $shift->uom ?: '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No shifts recorded.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">High Tide Information</div>
    <table>
        <thead>
        <tr>
            <th>Date</th>
            <th>1st High Water</th>
            <th>2nd High Water</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($record->tides as $tide)
            <tr>
                <td>{{ $tide->tide_date?->format('d/m/Y') ?: '—' }}</td>
                <td>{{ $tide->first_high_water ?: '—' }}</td>
                <td>{{ $tide->second_high_water ?: '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No tide information recorded.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Delay / Time Lost Summary</div>
    <table>
        <thead>
        <tr>
            <th>Start</th>
            <th>End</th>
            <th>Hours Lost</th>
            <th>Reason</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($record->delays as $delay)
            <tr>
                <td>{{ $delay->start_datetime?->format('d/m/Y H:i') ?: '—' }}</td>
                <td>{{ $delay->end_datetime?->format('d/m/Y H:i') ?: '—' }}</td>
                <td>{{ number_format((float) $delay->hours_lost, 2) }}</td>
                <td>{{ $delay->reason ?: '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No delays recorded.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Draft / Completion</div>
    <table>
        <thead>
        <tr>
            <th>FWD Draft</th>
            <th>MID Draft</th>
            <th>AFT Draft</th>
            <th>Loading Completed At</th>
            <th>Vessel Sailed At</th>
            <th>Remarks</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($record->drafts as $draft)
            <tr>
                <td>{{ $draft->fwd_draft !== null ? number_format((float) $draft->fwd_draft, 2) : '—' }}</td>
                <td>{{ $draft->mid_draft !== null ? number_format((float) $draft->mid_draft, 2) : '—' }}</td>
                <td>{{ $draft->aft_draft !== null ? number_format((float) $draft->aft_draft, 2) : '—' }}</td>
                <td>{{ $draft->loading_completed_at?->format('d/m/Y H:i') ?: '—' }}</td>
                <td>{{ $draft->vessel_sailed_at?->format('d/m/Y H:i') ?: '—' }}</td>
                <td>{{ $draft->remarks ?: '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No draft/completion records found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>

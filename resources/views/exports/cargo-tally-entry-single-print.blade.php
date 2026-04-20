<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cargo Tally Entry Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 20px;
            font-size: 13px;
        }

        h1, h2, h3 {
            margin: 0 0 10px;
        }

        .topbar {
            margin-bottom: 20px;
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

        .section {
            margin-bottom: 20px;
            border: 1px solid #dcdcdc;
            border-radius: 10px;
            padding: 14px;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .grid-row {
            display: table-row;
        }

        .grid-cell {
            display: table-cell;
            width: 33.33%;
            padding: 8px 10px;
            vertical-align: top;
        }

        .label {
            font-size: 12px;
            color: #555;
            margin-bottom: 4px;
        }

        .value {
            font-size: 13px;
            font-weight: 600;
        }

        .notes {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            min-height: 50px;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-active {
            background: #dcfce7;
            color: #166534;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }

            .section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
<div class="topbar">
    <h1>Cargo Tally Entry</h1>
    <button class="btn no-print" onclick="window.print()">Print</button>
</div>

<div class="section">
    <div class="section-title">Operational Information</div>

    <div class="grid">
        <div class="grid-row">
            <div class="grid-cell">
                <div class="label">Ship</div>
                <div class="value">{{ $entry->ship?->name ?: '—' }}</div>
            </div>

            <div class="grid-cell">
                <div class="label">Voyage</div>
                <div class="value">{{ $entry->voyage ?: '—' }}</div>
            </div>

            <div class="grid-cell">
                <div class="label">Agency</div>
                <div class="value">{{ $entry->agency?->name ?: '—' }}</div>
            </div>
        </div>

        <div class="grid-row">
            <div class="grid-cell">
                <div class="label">Port</div>
                <div class="value">{{ $entry->port?->name ?: '—' }}</div>
            </div>

            <div class="grid-cell">
                <div class="label">Pier</div>
                <div class="value">{{ $entry->pier?->name ?: '—' }}</div>
            </div>

            <div class="grid-cell">
                <div class="label">Load Date</div>
                <div class="value">{{ $entry->load_date?->format('d/m/Y') ?: '—' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Cargo Location & Destination</div>

    <div class="grid">
        <div class="grid-row">
            <div class="grid-cell">
                <div class="label">Hatch No.</div>
                <div class="value">{{ $entry->hatch_no ?: '—' }}</div>
            </div>

            <div class="grid-cell">
                <div class="label">Compartment</div>
                <div class="value">{{ $entry->compartment ?: '—' }}</div>
            </div>

            <div class="grid-cell">
                <div class="label">Destination</div>
                <div class="value">{{ $entry->destination ?: '—' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Cargo Details</div>

    <div class="grid">
        <div class="grid-row">
            <div class="grid-cell">
                <div class="label">Total Quantity</div>
                <div class="value">{{ number_format((float) $entry->total_quantity, 2) }}</div>
            </div>

            <div class="grid-cell">
                <div class="label">Status</div>
                <div class="value">
                    @if ($entry->is_active)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </div>
            </div>

            <div class="grid-cell">
                <div class="label">Created At</div>
                <div class="value">{{ $entry->created_at?->format('d/m/Y H:i') ?: '—' }}</div>
            </div>
        </div>
    </div>

    <div style="margin-top: 14px;">
        <div class="label">Description and Quality of Packages</div>
        <div class="notes">
            {{ $entry->package_description ?: 'No description provided.' }}
        </div>
    </div>

    <div style="margin-top: 14px;">
        <div class="label">Remarks on Condition of Articles</div>
        <div class="notes">
            {{ $entry->condition_remarks ?: 'No remarks provided.' }}
        </div>
    </div>
</div>
</body>
</html>

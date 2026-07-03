<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Parent Topup Details</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        h2 { text-align: center; font-size: 14px; margin-bottom: 4px; }
        h4 { text-align: center; font-size: 11px; margin-bottom: 12px; color: #444; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2e2e7a; color: #fff; padding: 6px 8px; text-align: left; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f5f5f5; }
        .total-row td { font-weight: bold; border-top: 2px solid #2e2e7a; }
        .badge-success { color: green; font-weight: bold; }
        .badge-pending { color: orange; font-weight: bold; }
        .badge-failed  { color: red;   font-weight: bold; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align:right; margin-bottom:10px;">
        <button onclick="window.print()" 
                style="background:#2e2e7a; color:#fff; border:none; padding:8px 20px; border-radius:4px; cursor:pointer; font-size:13px;">
            Download PDF
        </button>
        <button onclick="window.close()"
                style="background:#6c757d; color:#fff; border:none; padding:8px 20px; border-radius:4px; cursor:pointer; font-size:13px; margin-left:8px;">
            Close
        </button>
    </div>

    <h2>PARENT TOPUP DETAILS</h2>
    <h4>Topup Date Between {{ $fromDate }} And {{ $toDate }}</h4>

    <table>
        <thead>
            <tr>
                <th>SI NO.</th>
                <th>Parent Name</th>
                <th>Transaction Number</th>
                <th>School Name</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; $totalAmount = 0; @endphp
            @foreach($topup as $row)
                @php
                    $par = $data->firstWhere('id', $row->parent_id);
                    $sch = $par ? $school->firstWhere('id', $par->school_id) : null;

                    $statusText  = 'pending';
                    $statusClass = 'badge-pending';
                    if ($row->payment_status == 1) { $statusText = 'success'; $statusClass = 'badge-success'; }
                    if ($row->payment_status == 2) { $statusText = 'failed';  $statusClass = 'badge-failed'; }

                    $totalAmount += $row->amount;
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $par->name ?? '-' }}</td>
                    <td>{{ $row->transaction_number ?? '-' }}</td>
                    <td>{{ $sch->school_name ?? '-' }}</td>
                    <td>{{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-M-Y h:i A') : '-' }}</td>
                    <td>{{ $row->amount }}</td>
                    <td class="{{ $statusClass }}">{{ $statusText }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5">Total No. of Records: {{ $topup->count() }}</td>
                <td colspan="2">Total Amount: {{ $totalAmount }} QAR</td>
            </tr>
        </tbody>
    </table>

    <script>
        // Auto trigger print dialog on load
        window.onload = function() { window.print(); }
    </script>
</body>
</html>

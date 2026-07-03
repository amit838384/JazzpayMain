<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Onsite Sales</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; }
        h2 { text-align: center; font-size: 14px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2e2e7a; color: #fff; padding: 5px 6px; text-align: left; }
        td { padding: 4px 6px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f5f5f5; }
        .success { color: green; font-weight: bold; }
        .pending { color: orange; font-weight: bold; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:right; margin-bottom:10px;">
        <button onclick="window.print()"
                style="background:#2e2e7a; color:#fff; border:none; padding:8px 20px; border-radius:4px; cursor:pointer;">
            Download PDF
        </button>
        <button onclick="window.close()"
                style="background:#6c757d; color:#fff; border:none; padding:8px 20px; border-radius:4px; cursor:pointer; margin-left:8px;">
            Close
        </button>
    </div>

    <h2>ONSITE SALES</h2>

    <table>
        <thead>
            <tr>
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Dish Name</th>
                <th>Student Name</th>
                <th>School</th>
                <th>Cafeteria</th>
                <th>Payment Mode</th>
                <th>Payment Status</th>
                <th>Credits (QAR)</th>
                <th>Card</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                @php
                    $di  = $dish->firstWhere('id', $row->dish_id);
                    $stu = $student->firstWhere('id', $row->student_id);
                    $sch = $school->firstWhere('id', $row->school_id);
                    $caf = $cafeteria->firstWhere('id', $row->cafeteria_id);
                @endphp
                <tr>
                    <td>{{ $row->transaction_no ?? '--' }}</td>
                    <td>{{ $row->date }}</td>
                    <td>{{ $di->dish_name ?? '-' }}</td>
                    <td>{{ $stu->student_name ?? '-' }}</td>
                    <td>{{ $sch->school_name ?? '-' }}</td>
                    <td>{{ $caf->cafeteria_name ?? '-' }}</td>
                    <td>{{ $row->payment_type ?? '--' }}</td>
                    <td class="{{ $row->payment_status == 1 ? 'success' : 'pending' }}">
                        {{ $row->payment_status == 1 ? 'Success' : 'Pending' }}
                    </td>
                    <td>{{ $row->grand_total }}</td>
                    <td>{{ $row->creditcard ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
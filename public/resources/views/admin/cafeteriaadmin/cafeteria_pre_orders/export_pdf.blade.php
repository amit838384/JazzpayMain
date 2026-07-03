<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pre-Orders</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; }
        h2 { text-align: center; font-size: 14px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2e2e7a; color: #fff; padding: 5px 6px; text-align: left; }
        td { padding: 4px 6px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f5f5f5; }
        .success { color: green; font-weight: bold; }
        .pending { color: orange; font-weight: bold; }
        .failed  { color: red;   font-weight: bold; }
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

    <h2>PRE-ORDERS</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Dish Name</th>
                <th>Student Name</th>
                <th>School</th>
                <th>Cafeteria</th>
                <th>Payment Mode</th>
                <th>Transaction No</th>
                <th>Payment Status</th>
                <th>Date</th>
                <th>Credits</th>
            </tr>
        </thead>
        <tbody>
            @foreach($preorder as $row)
                @php
                    $di  = $dish->firstWhere('id', $row->dish_id);
                    $stu = $student->firstWhere('id', $row->student_id);
                    $sch = $school->firstWhere('id', $row->school_id);
                    $cafName = ($cafeteria && $cafeteria->id == $row->cafeteria_id)
                        ? $cafeteria->cafeteria_name
                        : ($cafeterias->firstWhere('id', $row->cafeteria_id)->cafeteria_name ?? '-');
                    $statusText  = 'Pending';
                    $statusClass = 'pending';
                    if ($row->payment_status == 1) { $statusText = 'Success'; $statusClass = 'success'; }
                    if ($row->payment_status == 2) { $statusText = 'Failed';  $statusClass = 'failed'; }
                @endphp
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $di->dish_name ?? '-' }}</td>
                    <td>{{ $stu->student_name ?? '-' }}</td>
                    <td>{{ $sch->school_name ?? '-' }}</td>
                    <td>{{ $cafName }}</td>
                    <td>{{ $row->payment_type ?? '--' }}</td>
                    <td>{{ $row->transaction_no ?? '--' }}</td>
                    <td class="{{ $statusClass }}">{{ $statusText }}</td>
                    <td>{{ $row->date }}</td>
                    <td>{{ $row->total_price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
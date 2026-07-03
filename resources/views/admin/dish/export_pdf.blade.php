<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dishes</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        h2 { text-align: center; font-size: 14px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2e2e7a; color: #fff; padding: 6px 8px; text-align: left; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f5f5f5; }
        .active { color: green; font-weight: bold; }
        .inactive { color: red; font-weight: bold; }
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

    <h2>DISHES</h2>

    <table>
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Dish Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Cafeteria</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($dish as $row)
                @php
                    $cat       = $dishcategory->firstWhere('id', $row->dish_category_id);
                    $cafe_name = $cafe->firstWhere('id', $row->cafeteria_id);
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $row->dish_name }}</td>
                    <td>{{ Str::limit($row->description, 40) }}</td>
                    <td>{{ $row->price }}</td>
                    <td>{{ $cat->name ?? '-' }}</td>
                    <td>{{ $cafe_name->cafeteria_name ?? '-' }}</td>
                    <td class="{{ $row->status == 1 ? 'active' : 'inactive' }}">
                        {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
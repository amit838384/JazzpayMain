<div style="font-family: Arial, sans-serif; max-width:600px; margin:auto; border:1px solid #eee; border-radius:8px; overflow:hidden;">
    <div style="background:#4c2c92; color:#fff; padding:16px 20px;">
        <h2 style="margin:0;">New Pre-Order Received</h2>
    </div>
    <div style="padding:20px; color:#333;">
        <p>Dear {{ $school->school_name ?? 'School Team' }},</p>
        <p>A new pre-order has been placed. Details below:</p>

        <table style="width:100%; border-collapse:collapse;">
            <tr><td style="padding:8px; border:1px solid #eee;"><strong>Invoice No</strong></td>
                <td style="padding:8px; border:1px solid #eee;">{{ $order->transaction_no ?? '#'.$order->id }}</td></tr>
            <tr><td style="padding:8px; border:1px solid #eee;"><strong>Date</strong></td>
                <td style="padding:8px; border:1px solid #eee;">{{ $order->date }}</td></tr>
            <tr><td style="padding:8px; border:1px solid #eee;"><strong>Student</strong></td>
                <td style="padding:8px; border:1px solid #eee;">{{ $student->student_name ?? '-' }}</td></tr>
            <tr><td style="padding:8px; border:1px solid #eee;"><strong>Dish</strong></td>
                <td style="padding:8px; border:1px solid #eee;">{{ $dish->dish_name ?? '-' }}</td></tr>
            <tr><td style="padding:8px; border:1px solid #eee;"><strong>Quantity</strong></td>
                <td style="padding:8px; border:1px solid #eee;">{{ $order->qty }}</td></tr>
            <tr><td style="padding:8px; border:1px solid #eee;"><strong>Total Price</strong></td>
                <td style="padding:8px; border:1px solid #eee;">{{ $order->total_price }}</td></tr>
            <tr><td style="padding:8px; border:1px solid #eee;"><strong>Payment</strong></td>
                <td style="padding:8px; border:1px solid #eee;">
                    {{ $order->payment_type ?? '-' }}
                    ({{ $order->payment_status == 1 ? 'Paid' : 'Pending' }})
                </td></tr>
            @if($order->addons)
            <tr><td style="padding:8px; border:1px solid #eee;"><strong>Addons</strong></td>
                <td style="padding:8px; border:1px solid #eee;">{{ $order->addons }}</td></tr>
            @endif
        </table>

        <p style="margin-top:20px;">Regards,<br>JazzSmartPay</p>
    </div>
</div>

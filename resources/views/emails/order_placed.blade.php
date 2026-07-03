<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Thank you for your order, {{ $orderDetails['f_name'] }} {{ $orderDetails['l_name'] }}!</h1>
    <p>Your order has been placed successfully. Here are the details:</p>

    <h3>Order Details:</h3>
    <ul>
        @foreach ($orderDetails['products'] as $product)
            <li>
                {{ $product['product_name'] }} (Qty: {{ $product['qty'] }}) - ${{ $product['total_price'] }}
            </li>
        @endforeach
    </ul>

    <h3>Subtotal:</h3>
    <p>${{ $orderDetails['subtotal'] }}</p>

    <p>If you have any questions, please contact us.</p>
</body>
</html>

<div class="card mb-3">
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
				{{--	
                @forelse ($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>QAR {{ $item['price'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>QAR {{ $item['total'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">
                        <i class="bi bi-exclamation-triangle"></i> No Data Available
                    </td>
                </tr>
                @endforelse
				--}}
            </tbody>
        </table>
        <div class="mt-3 text-end">
		{{--<p><strong>Total Amount:</strong> QAR {{ $totalAmount }}</p>
            <p><strong>Discount:</strong> QAR {{ $discount }}</p>
            <p><strong>Payable:</strong> QAR {{ $payable }}</p>--}}
        </div>
    </div>
</div>

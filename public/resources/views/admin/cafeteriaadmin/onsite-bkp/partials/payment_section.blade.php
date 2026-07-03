<div class="card p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <input type="checkbox" name="wallet_used" checked>
            <label>From Wallet</label> QAR {{ $walletUsed ?? '' }}
        </div>
        <div><strong>Total Payable:</strong> QAR {{ $payable ?? '' }}</div>
        <div>
            <button class="btn btn-light">Cash</button>
            <button class="btn btn-light">Credit Card</button>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-end gap-2">
        <button class="btn btn-primary">View Sales</button>
        <button class="btn btn-success">Submit & Print</button>
        <button class="btn btn-dark">Submit</button>
    </div>
</div>

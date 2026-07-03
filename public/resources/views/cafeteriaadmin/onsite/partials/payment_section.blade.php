<div class="card p-3 mb-3 shadow-sm border-0">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

        <!-- Wallet -->
        <div class="d-flex align-items-center">
            <input type="checkbox" class="form-check-input me-2" name="wallet_used" id="wallet_used">
            <label for="wallet_used" class="fw-semibold mb-0">From Wallet</label>
            <span class="ms-2 text-warning fw-bold">QAR {{ $walletUsed ?? 0 }}</span>
        </div>

        <!-- Total Payable -->
        <div class="fw-semibold">
            <span>Total Payable</span>
        <p class="text-primary fw-bold ms-2" id="totalAmount">
    QAR <span id="totalAmount">0</span>
</p>
        </div>

        <!-- Payment method toggle -->
        <div class="btn-group" role="group" aria-label="Payment Method">
            <input type="radio" class="btn-check" name="payment_method" id="cash" autocomplete="off" checked>
            <label class="btn btn-outline-primary fw-semibold px-4" for="cash">CASH</label>

            <input type="radio" class="btn-check" name="payment_method" id="credit" autocomplete="off">
            <label class="btn btn-outline-primary fw-semibold px-4" for="credit">CREDITCARD</label>
        </div>

        <!-- <div id="creditField" class="d-none">
            <input type="text" class="form-control form-control-sm" placeholder="Card No">
        </div> -->


        <!-- Discount Input -->
        <div>
            <input type="text" class="form-control form-control-sm" placeholder="Discount...">
        </div>

        <!-- Actions -->
        <div class="d-flex gap-2">
            <button class="btn btn-light border">View Sales</button>
            <button class="btn btn-primary">Submit & Print</button>
            <button type="button" id="submitBtn" class="btn btn-dark">Submit</button>
        </div>
    </div>
</div>


<!-- <script>
    const cash = document.getElementById("cash");
    const credit = document.getElementById("credit");
    const creditField = document.getElementById("creditField");

    cash.addEventListener("change", () => {
        creditField.classList.add("d-none");
    });

    credit.addEventListener("change", () => {
        creditField.classList.remove("d-none");
    });
</script> -->
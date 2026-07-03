<style>
    /* Cart Card */
    .cart-card {
        width: 526px;
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .cart-card .card-body {
        padding: 0;
    }

    /* Table Styling */
    .cart-table thead {
        background: #9B203D;
        color: #fff;
    }
    .cart-table th {
        font-weight: 600;
        text-align: center;
    }
    .cart-table td {
        vertical-align: middle;
        text-align: center;
    }
    .cart-table tbody tr:hover {
        background: #f9f9f9;
    }
    .empty-row {
        text-align: center;
        color: #999;
        font-style: italic;
    }

    /* Payment Summary */
    #paymentSummary {
        border-radius: 12px;
        background: #fff;
        border: 1px solid #eee;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
   /*  #paymentSummary p {
        font-size: 1rem;
        margin-bottom: 8px;
    }
    #paymentSummary .fw-semibold {
        font-size: 1.1rem;
        color: #333;
    }
    #paymentSummary .text-danger {
        font-weight: 600;
    } */
    /* #paymentSummary .text-primary {
        font-weight: 600;
    } */
</style>

<!-- Cart Table -->
<div class="card cart-card mt-3">
    <div class="card-body">
        <table class="table cart-table mb-0">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Price (QAR)</th>
                    <th>Qty</th>
                    <th>Total (QAR)</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <tr>
                    <td colspan="5" class="empty-row">No items added yet</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Payment Summary -->
<div id="paymentSummary" class="mt-3 p-4">
    <p class="fw-semibold">Cart Total: <span id="summaryTotal">QAR 0.00</span></p>
    <p class="text-danger">Discount: <span id="summaryDiscount">QAR 0.00</span></p>
    <p class="text-primary">After Discount: <span id="summaryAfterDiscount">QAR 0.00</span></p>
    <hr>
    <p class="mb-0 fw-bold text-success fs-5">Payable: <span id="summaryPayable">QAR 0.00</span></p>
</div>
<br>
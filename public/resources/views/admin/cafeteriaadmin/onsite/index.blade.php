@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- LEFT COLUMN --}}
        <div class="col-md-6">
            @include('admin.cafeteriaadmin.onsite.partials.student_info')
            @include('admin.cafeteriaadmin.onsite.partials.order_table')
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-md-6">
            @include('admin.cafeteriaadmin.onsite.partials.food_menu')
        </div>
            @include('admin.cafeteriaadmin.onsite.partials.payment_section')

    </div>
</div>
<div class="modal fade" id="salesModal" tabindex="-1" aria-labelledby="salesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered"> <!-- added modal-dialog-centered -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="salesModalLabel">Sales Records</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="salesTable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Student Name</th>
                <th>Payment Mode</th>
                <th>Credits (QAR)</th>
                <th>Card</th>
                <th>View</th>
                <th>Print</th>
              </tr>
            </thead>
            <tbody>
              @php $i = 1; @endphp
                @foreach($orderdetails as $order)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $order->date }}</td>

                    {{-- Student Name --}}
                    @php $studentName = 'N/A'; @endphp
                    @foreach($student as $stud)
                    @if($stud->id == $order->student_id)
                        @php $studentName = $stud->student_name; @endphp
                        @break
                    @endif
                    @endforeach
                    <td>{{ $studentName }}</td>

                    <td>{{ $order->payment_type }}</td>
                    <td>{{ $order->grand_total }}</td>
                    <td>No</td>
                    <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info" target="_blank">View</a>
                    </td>
                   <td>
                    <a href="{{ route('admin.orders.print', $order->id) }}" 
                        class="btn btn-sm btn-secondary">
                        Print
                    </a>
                    </td>
                </tr>
                @endforeach


            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const selected = this.dataset.category;
            document.querySelectorAll('.food-item').forEach(item => {
                item.style.display = item.dataset.category === selected ? 'block' : 'none';
            });
        });
    });
</script>
<script>
    let cart = [];
let studentWallet = 0; 

function renderCart() {
    const tbody = document.getElementById("cart-body");
    tbody.innerHTML = "";

    if (cart.length === 0) {
        tbody.innerHTML = `<tr>
            <td colspan="4" class="text-center text-muted">No Data Available</td>
        </tr>`;
        updateTotals(0);
        return;
    }

    let totalAmount = 0;

    cart.forEach((item, index) => {
        const row = document.createElement("tr");
        const itemTotal = item.price * item.qty;
        totalAmount += itemTotal;

        row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.name}</td>
            <td>QAR ${item.price}</td>
            <td>
                <button class="btn btn-sm btn-light" onclick="updateQty(${index}, -1)">-</button>
                <span class="mx-2">${item.qty}</span>
                <button class="btn btn-sm btn-light" onclick="updateQty(${index}, 1)">+</button>
            </td>
            <td>QAR ${itemTotal}</td>
        `;

        tbody.appendChild(row);
    });

    updateTotals(totalAmount);
}

function updateTotals(totalAmount) {
    const walletCheckbox = document.getElementById("wallet_used");
    const discountPercent = parseFloat(document.getElementById("discountInput").value) || 0;

    let walletUsed = 0;
    let payable = totalAmount;

    const discountValue = (totalAmount * discountPercent / 100);
    const discountedTotal = totalAmount - discountValue;

    if (walletCheckbox.checked && studentWallet > 0) {
        if (discountedTotal <= studentWallet) {
            walletUsed = discountedTotal;
            payable = 0;
        } else {
            walletUsed = studentWallet;
            payable = discountedTotal - studentWallet;
        }
    } else {
        payable = discountedTotal;
    }

    document.querySelector(".text-warning").innerText = `QAR ${walletUsed.toFixed(2)}`;
    document.getElementById("totalAmount").innerText = payable.toFixed(2);

    document.getElementById("summaryTotal").innerText = `QAR ${totalAmount.toFixed(2)}`;
    document.getElementById("summaryDiscount").innerText = `QAR ${discountValue.toFixed(2)}`;
    document.getElementById("summaryAfterDiscount").innerText = `QAR ${discountedTotal.toFixed(2)}`;
    document.getElementById("summaryPayable").innerText = `QAR ${payable.toFixed(2)}`;
}
function updateQty(index, change) {
    cart[index].qty += change;
    if (cart[index].qty <= 0) {
        cart.splice(index, 1);
    }
    renderCart();
}

/* function selectStudent(name, card, admission, grade, wallet, spend, parentName, parentBalance, parentNumber, foods) {
    document.getElementById("studentName").value = name;
    document.getElementById("cardNo").value = card;
    document.getElementById("admissionNo").value = admission;
    document.getElementById("grade").value = grade;

    document.getElementById("walletBalance").innerText = wallet;
    document.getElementById("spendLimit").innerText = spend;
    document.getElementById("parentName").innerText = parentName;
    document.getElementById("parentBalance").innerText = parentBalance;
    document.getElementById("parentNumber").innerText = parentNumber;
    document.getElementById("restrictedfood").innerText = foods ? foods : "-";

    studentWallet = parseFloat(wallet) || 0;

    const walletCheckbox = document.getElementById("wallet_used");
    walletCheckbox.checked = studentWallet > 0;

    renderCart();
    dropdown.classList.add("d-none");
} */


// In index.blade.php, update selectStudent() to call applyAllergyFilter at the end:

function selectStudent(name, card, admission, grade, wallet, spend, parentName, parentBalance, parentNumber, foods) {
    document.getElementById("studentName").value = name;
    document.getElementById("cardNo").value = card;
    document.getElementById("admissionNo").value = admission;
    document.getElementById("grade").value = grade;

    document.getElementById("walletBalance").innerText = wallet;
    document.getElementById("spendLimit").innerText = spend;
    document.getElementById("parentName").innerText = parentName;
    document.getElementById("parentBalance").innerText = parentBalance;
    document.getElementById("parentNumber").innerText = parentNumber;
    document.getElementById("restrictedfood").innerText = foods ? foods : "-";

    studentWallet = parseFloat(wallet) || 0;

    const walletCheckbox = document.getElementById("wallet_used");
    walletCheckbox.checked = studentWallet > 0;

    renderCart();
    dropdown.classList.add("d-none");

    // ← ADD THIS LINE: trigger allergy filter when student is selected
    applyAllergyFilter(foods);
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", () => {
            const id = button.dataset.id;
            const name = button.dataset.name;
            const price = parseFloat(button.dataset.price);

            const existing = cart.find(item => item.id === id);

            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({ id, name, price, qty: 1 });
            }

            renderCart();
        });
    });

    document.getElementById("wallet_used").addEventListener("change", () => renderCart());
    document.getElementById("discountInput").addEventListener("input", () => renderCart());

    document.getElementById("cash").addEventListener("change", () => {
        document.getElementById("creditField").classList.add("d-none");
    });

    document.getElementById("credit").addEventListener("change", () => {
        document.getElementById("creditField").classList.remove("d-none");
    });

    renderCart();
});

</script>


@endsection



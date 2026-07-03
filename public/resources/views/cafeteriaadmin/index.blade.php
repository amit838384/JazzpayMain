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

function renderCart() {
    const tbody = document.getElementById("cart-body");
    tbody.innerHTML = "";

    if (cart.length === 0) {
        tbody.innerHTML = `<tr>
            <td colspan="4" class="text-center text-muted">No Data Available</td>
        </tr>`;
        document.getElementById("totalAmount").innerText = 0;
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

    document.getElementById("totalAmount").innerText = totalAmount;
}

function updateQty(index, change) {
    cart[index].qty += change;
    if (cart[index].qty <= 0) {
        cart.splice(index, 1);
    }
    renderCart();
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

    renderCart();
});
</script>




@endsection



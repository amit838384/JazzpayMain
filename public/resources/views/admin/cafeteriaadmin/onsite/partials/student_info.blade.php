<style>
    /* Wallet Balance Badge */
    .wallet-box {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
    }

    /* Input fields */
    .input-box label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
        color: #444;
    }
    .input-box input {
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
        padding: 8px 10px;
        transition: all 0.2s ease-in-out;
        background: #f8f9fa;
    }
    .input-box input:focus {
        border-color: #9B203D;
        box-shadow: 0 0 6px rgba(155, 32, 61, 0.3);
        background: #fff;
    }

    /* Dropdown container */
    .dropdown-table {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 12px;
        max-height: 250px;
        overflow-y: auto;
        z-index: 1050;
        margin-top: 4px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        animation: fadeIn 0.2s ease-in-out;
    }

    /* Table */
    .dropdown-table table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .dropdown-table thead {
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .dropdown-table th {
        padding: 10px;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        font-weight: 700;
        background: #9B203D;
        color: #fff;
    }
    .dropdown-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f1f1;
        color: #444;
    }

    /* Hover rows */
    .dropdown-table tbody tr {
        transition: background 0.2s;
    }
    .dropdown-table tbody tr:hover {
        background: #fdf1f5;
        cursor: pointer;
    }

    /* Scrollbar styling */
    .dropdown-table::-webkit-scrollbar {
        width: 8px;
    }
    .dropdown-table::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .dropdown-table::-webkit-scrollbar-thumb {
        background: #bbb;
        border-radius: 10px;
    }
    .dropdown-table::-webkit-scrollbar-thumb:hover {
        background: #888;
    }

    /* Fade animation */
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-5px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

<div class="position-relative">
    <!-- Wallet Balance -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="wallet-box">
                💳 Wallet Balance: QAR <span id="walletBalance">0</span>
            </div>
        </div>
    </div>

    <!-- Student Info -->
    <div class="row">
        <div class="col-md-6 mt-2">
            <div class="input-box">
                <label for="studentName" class="form-label">Student Name *</label>
                <input type="text" id="studentName" class="form-control" placeholder="Type student name...">
            </div>
        </div>

        <div class="col-md-6 mt-2">
            <div class="input-box">
                <label for="cardNo" class="form-label">Card Number *</label>
                <input type="text" id="cardNo" class="form-control" placeholder="Card no..." readonly>
            </div>
        </div>

        <div class="col-md-6 mt-2">
            <div class="input-box">
                <label for="admissionNo" class="form-label">Admission No *</label>
                <input type="text" id="admissionNo" class="form-control" placeholder="Admission no..." readonly>
            </div>
        </div>

        <div class="col-md-6 mt-2">
            <div class="input-box">
                <label for="grade" class="form-label">Grade *</label>
                <input type="text" id="grade" class="form-control" placeholder="Grade..." readonly>
                <input type="hidden" id="cafeteria_id" value="{{ $cafeteria->id }}">
            </div>
        </div>
    </div>

    <!-- Dropdown Table -->
    <div class="dropdown-table d-none" id="studentDropdown">
        <table class="table table-hover" id="studentTable">
            <thead>
                <tr>
                    <th>Admission No</th>
                    <th>Grade</th>
                    <th>Student Name</th>
                    <th>Card Number</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($student as $stu)
                    @php
                        $foods = $restrictedFoods->where('student_id', $stu->id)->pluck('name')->toArray();
                        $foodList = implode(', ', $foods);
                    @endphp
                    <tr onclick="selectStudent(
                        '{{ addslashes($stu->student_name) }}',
                        '{{ $stu->card_no }}',
                        '{{ $stu->admission_no }}',
                        '{{ $stu->grade }}',
                        '{{ $stu->wallet_balance }}',
                        '{{ $stu->spend_limit ?? 0 }}',
                        '{{ addslashes($stu->parent->name ?? '-') }}',
                        '{{ $stu->parent->topup_balance ?? 0 }}',
                        '{{ $stu->parent->mobile ?? '-' }}',
                        '{{ addslashes($foodList) }}'
                    )">
                        <td>{{ $stu->admission_no }}</td>
                        <td>{{ $stu->grade }}</td>
                        <td>{{ $stu->student_name }}</td>
                        <td>{{ $stu->card_no }}</td>
                        <td>QAR {{ $stu->wallet_balance }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Info Section -->
<div style="margin-top:15px; margin-bottom:10px;">
    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
        <div><strong>Date:</strong> {{ now()->format('d-M-Y') }}</div>
        <div><strong>Daily Limit:</strong> QAR <span id="spendLimit">0</span></div>
    </div>

    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
        <div><strong>Parent Balance:</strong> QAR <span id="parentBalance">0</span></div>
        <div><strong>Parent Name:</strong> <span id="parentName">-</span></div>
    </div>

    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
        <div><strong>Number:</strong> <span id="parentNumber">-</span></div>
    </div>

    {{-- Allergies always visible --}}
    <div style="margin-top:6px;">
        <div><strong>Allergies :</strong> <span id="restrictedfood">N/A</span></div>
    </div>
</div>

<script>
const input = document.getElementById("studentName");
const dropdown = document.getElementById("studentDropdown");

input.addEventListener("focus", () => {
    dropdown.classList.remove("d-none");
});

input.addEventListener("input", () => {
    const filter = input.value.toLowerCase();
    const rows = dropdown.querySelectorAll("tbody tr");
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});

document.addEventListener("click", (e) => {
    if (!e.target.closest(".position-relative")) {
        dropdown.classList.add("d-none");
    }
});

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
    document.getElementById("restrictedfood").innerText = foods ? foods : "N/A";

    studentWallet = parseFloat(wallet) || 0;

    const walletCheckbox = document.getElementById("wallet_used");
    walletCheckbox.checked = studentWallet > 0;

    renderCart();
    dropdown.classList.add("d-none");
}
</script>
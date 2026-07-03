<!-- <style>
    #student_id option {
        font-family: monospace; /* Keeps spacing aligned */
        white-space: pre;       /* Keeps extra spaces visible */
    }
</style>
<div class="card mb-3">
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-6"><strong>Date:</strong> {{ now()->format('d-M-Y') }}</div>
            <div class="col-6 text-end"><strong>Wallet Balance:</strong> QAR {{ $walletBalance ?? 0 }}</div>
        </div>
        <div class="row g-2">
               <div class="col-md-6">
        <label for="student_id" class="form-label">Select Student</label>
        <select id="student_id" class="form-select">
            <option value="select">Select Student</option>
          @foreach($student as $stu)
    <option value="{{ $stu->id }}">
        {{ $stu->admission_no }} | {{ $stu->grade }} | {{ $stu->student_name }} | {{ $stu->card_no }} | QAR {{ $stu->wallet_balance }}
    </option>
@endforeach
        </select>
    </div>
            <div class="col-md-6">
                <label>Card Number</label>
                <input type="text" class="form-control"  readonly>
            </div>
            <div class="col-md-6">
                <label>Admission No</label>
                <input type="text" class="form-control" readonly>
            </div>
            <div class="col-md-6">
                <label>Grade</label>
                <input type="text" class="form-control"  readonly>
            </div>
            <div class="col-md-6">
                <label>Daily Limit</label>
                <input type="text" class="form-control"  readonly>
            </div>
            <div class="col-md-6">
                <label>Credit Used</label>
                <input type="text" class="form-control"  readonly>
            </div>
        </div>
        
    </div>
</div> -->



<style>
    /* Dropdown container */
    .dropdown-table {
        position: absolute;
        top: 100%; /* appear just below input */
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        max-height: 220px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        margin-top: 4px;
    }

    /* Table styling */
    .dropdown-table table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        border-radius: 8px;
        overflow: hidden;
    }

    /* Sticky header */
    .dropdown-table thead {
        position: sticky;
        top: 0;
        background: #f9f9f9;
        z-index: 2;
    }

    .dropdown-table th {
        padding: 10px;
        font-weight: 600;
        font-size: 11px;
        color: #333;
        border-bottom: 1px solid #e1e1e1;
        text-transform: uppercase;
        background: #f1f1f1;
    }

    .dropdown-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f1f1;
        color: #555;
        font-size: 11px;
    }

    /* Row hover */
    .dropdown-table tbody tr:hover {
        background-color: #f0f7ff;
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
    .input-box input {
        border-color: #888;
        background: #8888881c;
    }
</style>

<div class="position-relative">

    <div class="row mb-2">
        <div class="col-6"><strong>Date:</strong> {{ now()->format('d-M-Y') }}</div>
        <div class="col-6 text-end"><strong>Wallet Balance:</strong> QAR {{ $walletBalance ?? 0 }}</div>
    </div>

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
            </div>
        </div>
    </div>

    <input type="text" id="cafeteria_id" class="form-control" value="{{ $cafeteria->id }}">
    <!-- Dropdown Table -->
    <div class="dropdown-table d-none" id="studentDropdown">
        <table class="table table-bordered table-hover" id="studentTable">
            <thead class="table-light">
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
                <tr onclick="selectStudent(
                    '{{ $stu->student_name }}',
                    '{{ $stu->card_no }}',
                    '{{ $stu->admission_no }}',
                    '{{ $stu->grade }}'
                )">
                    <td>{{ $stu->admission_no }}</td>
                    <td>{{ $stu->grade }}</td>
                    <td>{{ $stu->student_name }}</td>
                    <td>{{ $stu->card_no }}</td>
                    <td>{{ $stu->wallet_balance }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
const input = document.getElementById("studentName");
const dropdown = document.getElementById("studentDropdown");

// show dropdown when focus
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

// set all fields when selecting
function selectStudent(name, card, admission, grade) {
    document.getElementById("studentName").value = name;
    document.getElementById("cardNo").value = card;
    document.getElementById("admissionNo").value = admission;
    document.getElementById("grade").value = grade;
    dropdown.classList.add("d-none");
}
</script>

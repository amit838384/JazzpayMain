<style>
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
</div>

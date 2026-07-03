@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow p-4">
        <h4 class="mb-3">Create New Meal Plan</h4>
        <form action="{{ route('admin.cafeteria.plans.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Plan Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter plan name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Duration (in days)</label>
                <select name="duration_days" class="form-select" required>
                    <option value="">Select Duration</option>
                    <option value="7">Weekly (7 Days)</option>
                    <option value="15">15 Days</option>
                    <option value="30">Monthly (30 Days)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Meals Included</label><br>
                <label><input type="checkbox" name="meals[]" value="breakfast"> Breakfast</label>
                <label class="ms-3"><input type="checkbox" name="meals[]" value="lunch"> Lunch</label>
                <label class="ms-3"><input type="checkbox" name="meals[]" value="dinner"> Dinner</label>
            </div>

            {{-- <div class="mb-3">
                <label class="form-label">Days of Week</label><br>
                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                    <label class="me-2">
                        <input type="checkbox" name="days_of_week[]" value="{{ $day }}"> {{ $day }}
                    </label>
                @endforeach
            </div> --}}

            <div class="mb-3">
                <label class="form-label">Price (₹)</label>
                <input type="number" name="price" class="form-control" placeholder="Enter price" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Auto Renew</label>
                <select name="auto_renew" class="form-select">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="active" value="1" checked>
                <label class="form-check-label">Active Plan</label>
            </div>

            <button type="submit" class="btn btn-primary">Save Plan</button>
        </form>
    </div>
</div>
@endsection

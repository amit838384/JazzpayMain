@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Dish Sales Insight</div>
    <div class="card-body">

        {{-- Filters --}}
        <form method="GET" action="{{ url()->current() }}">
            <div class="row g-3 mb-4 align-items-end">
                <div class="col-md-2">
					<label class="form-label">From Date</label>
					<input type="date" name="fdate" class="form-control"
						   value="{{ request('fdate') ? \Carbon\Carbon::parse(request('fdate'))->format('Y-m-d') : '' }}">
				</div>
				<div class="col-md-2">
					<label class="form-label">To Date</label>
					<input type="date" name="tdate" class="form-control"
						   value="{{ request('tdate') ? \Carbon\Carbon::parse(request('tdate'))->format('Y-m-d') : '' }}">
				</div>
                <div class="col-md-2">
                    <label class="form-label">Filter By School</label>
                    <select name="school_id" class="form-select">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}"
                                {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Filter By Cafeteria</label>
                    <select name="cafeteria_id" class="form-select">
                        <option value="">All Cafeterias</option>
                        @foreach($cafeterias as $cafeteria)
                            <option value="{{ $cafeteria->id }}"
                                {{ request('cafeteria_id') == $cafeteria->id ? 'selected' : '' }}>
                                {{ $cafeteria->cafeteria_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Order Type</label>
                    <select name="order_type" class="form-select">
						<option value="">All</option>
						<option value="pre_order" {{ request('order_type') == 'pre_order' ? 'selected' : '' }}>Pre Order</option>
						<option value="onsite"    {{ request('order_type') == 'onsite'    ? 'selected' : '' }}>Onsite</option>
					</select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ url()->current() }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>

        {{-- Tables --}}
        <div class="row">
            <div class="col-md-4">
                <h4>Highest Sales</h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th>Dish Name</th><th>Qty</th><th>Amount</th></tr>
                    </thead>
                    <tbody>
                        @foreach($highestSales as $item)
                        <tr>
                            <td>{{ $item->dish->dish_name ?? 'Unknown' }}</td>
                            <td>{{ $item->total_qty }}</td>
                            <td>{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <h4>Lowest Sales</h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th>Dish Name</th><th>Qty</th><th>Amount</th></tr>
                    </thead>
                    <tbody>
                        @foreach($lowestSales as $item)
                        <tr>
                            <td>{{ $item->dish->dish_name ?? 'Unknown' }}</td>
                            <td>{{ $item->total_qty }}</td>
                            <td>{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <h4>No Sales</h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th>Dish Name</th></tr>
                    </thead>
                    <tbody>
                        @foreach($noSales as $dish)
                        <tr>
                            <td>{{ $dish->dish_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
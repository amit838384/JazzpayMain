@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- LEFT COLUMN --}}
        <div class="col-md-6">
            @include('admin.cafeteriaadmin.onsite.partials.student_info')
            @include('admin.cafeteriaadmin.onsite.partials.order_table')
            @include('admin.cafeteriaadmin.onsite.partials.payment_section')
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-md-6">
            @include('admin.cafeteriaadmin.onsite.partials.food_menu')
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
@endsection

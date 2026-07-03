<style>
    .card-box-design{
        background-color: #fff;
        transition: all .5s ease-in-out;
        position: relative;
        border: 0rem solid transparent;
        border-radius: 0.3rem;
        box-shadow: 0 1px 2px rgba(56, 65, 74, .15);
        margin: 0 !important;
    }
    .add-btn{
        float: right;
    }
    .category-btn {
        border-color: #e6e6e6;
        border-radius: 8px !important;
        color: #888;
    }
    .category-btn:hover{
        background-color: #9B203D !important;
        color: #fff;
        border-color: #9B203D !important;
    }
    div#food-items {
        height: 280px;
    }
</style>

<div class="btn-group mb-3 flex-wrap">
    @foreach ($categories as $category)
        <button 
            class="btn btn-outline-dark m-1 category-btn" 
            data-category="{{ Str::slug($category->name) }}">
            {{ $category->name }}
        </button>
    @endforeach
</div>

<div class="row" id="food-items">
    @foreach ($items as $item)
        <div class="col-md-6 mb-3 food-item" 
             data-category="{{ Str::slug($item->category->name ?? '') }}"
             style="{{ Str::slug($item->category->name ?? '') != Str::slug($firstCategory->name ?? '') ? 'display:none;' : '' }}">
            <div class="card card-box-design">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">QAR {{ $item->price }}</p>
                    <h6 class="fs-16 fw-semibold my-2">{{ Str::limit($item->dish_name, 25) }}</h6>
                    <button 
                        class="add-btn btn btn-sm btn-success add-to-cart" 
                        data-id="{{ $item->id }}"
                        data-name="{{ $item->dish_name }}"
                        data-price="{{ $item->price }}">
                        + Add
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
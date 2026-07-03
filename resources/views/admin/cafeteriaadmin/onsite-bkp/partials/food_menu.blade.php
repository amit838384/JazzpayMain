<div class="btn-group mb-3 flex-wrap">
    @foreach ($categories as $category)
        <button 
            class="btn btn-outline-dark m-1 category-btn " 
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
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h6>{{ Str::limit($item->dish_name, 25) }}</h6>
                    <p>QAR {{ $item->price }}</p>
                    <button class="btn btn-sm btn-success add-to-cart" data-id="{{ $item->id }}">
                        + Add
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>

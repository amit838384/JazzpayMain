<style>
    .card-box-design {
        background: #fff;
        transition: all .3s ease-in-out;
        border-radius: 12px;
        border: 1px solid #eee;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    .card-box-design:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.15);
    }
    .card-body { padding: 1.2rem; }
    .price-tag {
        font-size: 0.9rem;
        font-weight: bold;
        color: #9B203D;
        background: #f8d7da;
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
    }
    .dish-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 10px 0;
        color: #333;
    }
    .add-btn {
        background: #9B203D;
        border: none;
        border-radius: 20px;
        font-size: 0.85rem;
        padding: 6px 16px;
        transition: all 0.3s;
    }
    .add-btn:hover { background: #7a172f; }
    .category-btn {
        border-radius: 25px !important;
        padding: 6px 16px;
        font-weight: 500;
        color: #555;
        border: 1px solid #ccc;
        transition: all 0.3s;
    }
    .category-btn:hover, .category-btn.active {
        background: #9B203D !important;
        color: #fff !important;
        border-color: #9B203D !important;
    }
    div#food-items {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 5px;
    }
    #food-items::-webkit-scrollbar { width: 6px; }
    #food-items::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

    .food-item.allergy-blocked {
        position: relative;
        opacity: 0.45;
        pointer-events: none;
    }
    .food-item.allergy-blocked::after {
        content: '⚠ Allergen';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(220, 53, 69, 0.85);
        color: #fff;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 4px 12px;
        border-radius: 20px;
        pointer-events: none;
        z-index: 10;
        white-space: nowrap;
    }
</style>

<!-- Category Buttons -->
<div class="btn-group mb-3 flex-wrap">
    @foreach ($categories as $category)
        <button
            class="btn btn-outline-dark m-1 category-btn {{ $loop->first ? 'active' : '' }}"
            data-category="{{ Str::slug($category->name) }}">
            {{ $category->name }}
        </button>
    @endforeach
</div>

<!-- Food Items -->
<div class="row" id="food-items">
    @foreach ($items as $item)
        @php
            // Normalize description: strip newlines, extra spaces, lowercase
            $dishDesc = strtolower(preg_replace('/\s+/', ' ', trim($item->description ?? '')));
            $dishName = strtolower(trim($item->dish_name ?? ''));
        @endphp
        <div class="col-md-6 mb-3 food-item"
             data-category="{{ Str::slug($item->category->name ?? '') }}"
             data-dish-id="{{ $item->id }}"
             data-dish-name="{{ $dishName }}"
             data-dish-desc="{{ $dishDesc }}"
             style="{{ Str::slug($item->category->name ?? '') != Str::slug($firstCategory->name ?? '') ? 'display:none;' : '' }}">
            <div class="card card-box-design h-100">
                <div class="card-body d-flex flex-column">
                    <span class="price-tag">QAR {{ number_format($item->price, 2) }}</span>
                    <h6 class="dish-title">{{ Str::limit($item->dish_name, 25) }}</h6>
                    <img src="{{ $item->image }}" alt="Item Image" style="width: 100px;">
                    <div class="mt-auto text-end">
                        <button
                            class="add-btn btn btn-sm text-white add-to-cart"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->dish_name }}"
                            data-price="{{ $item->price }}">
                            + Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
function applyAllergyFilter(foodsString) {
    const allFoodItems = document.querySelectorAll('.food-item');

    // Clear previous blocks
    allFoodItems.forEach(item => item.classList.remove('allergy-blocked'));

    if (!foodsString || foodsString.trim() === '' || foodsString.trim().toLowerCase() === 'n/a') {
        return;
    }

    // Split by comma, trim, lowercase, remove empty
    const allergens = foodsString.split(',')
        .map(a => a.trim().toLowerCase())
        .filter(a => a.length > 0);

    if (allergens.length === 0) return;

    allFoodItems.forEach(item => {
        const dishName = (item.getAttribute('data-dish-name') || '').toLowerCase();
        const dishDesc = (item.getAttribute('data-dish-desc') || '').toLowerCase();
        const combined = dishName + ' ' + dishDesc;

        const hasAllergen = allergens.some(allergen => {
            // Use word-boundary style check: allergen appears as a whole word
            const regex = new RegExp('(^|[\\s,])' + allergen + '([\\s,]|$)', 'i');
            return regex.test(combined);
        });

        if (hasAllergen) {
            item.classList.add('allergy-blocked');
        }
    });
}
</script>
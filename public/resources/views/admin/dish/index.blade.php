@extends('layouts.app')
<style>
.food-tag { padding:0.5rem 0.75rem; font-size:0.875rem; font-weight:500; border-radius:0.375rem; display:inline-flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem; }
.food-tag .btn-close { width:0.6em; height:0.6em; background-size:0.6em; opacity:0.7; padding:0.25rem; margin-left:0.25rem; }
.food-tag .btn-close:hover { opacity:1; background-color:rgba(255,255,255,0.2); border-radius:0.25rem; }
#foodTypeWrapper { min-height:2.5rem; padding:0.5rem; border:1px solid #dee2e6; border-radius:0.375rem; background-color:#f8f9fa; }
</style>

@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Dishes</h5>
            <button type="button" class="btn fw-medium px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
                    data-bs-toggle="modal" data-bs-target="#addDishModal">
                Add Dishes
            </button>
        </div>
    </div>

    {{-- Total entries + export icons --}}
    <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="fw-medium">{{ $dish->total() }} Total Entries</div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dish_export_pdf') }}?{{ http_build_query(request()->query()) }}"
               target="_blank"
               class="btn btn-sm rounded-circle" style="background:#2e2e7a; color:#fff;" title="Export PDF">
                <i class="bx bxs-file-pdf fs-16"></i>
            </a>
            <a href="{{ route('admin.dish_export_excel') }}?{{ http_build_query(request()->query()) }}"
               class="btn btn-sm rounded-circle" style="background:#2e2e7a; color:#fff;" title="Export Excel">
                <i class="bx bxs-file fs-16"></i>
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.dish') }}">
        <div class="d-flex gap-3 mb-3 flex-wrap align-items-center">
            <div style="max-width:280px; flex:1;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="name" class="form-control border-start-0"
                           placeholder="Dish Name" value="{{ request('name') }}">
                </div>
            </div>
            <div style="min-width:220px;">
                <select name="cafeteria_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter By Cafeteria</option>
                    @foreach($cafe as $c)
                        <option value="{{ $c->id }}" {{ request('cafeteria_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->cafeteria_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:220px;">
                <select name="category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter By Category</option>
                    @foreach($dishcategory as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn fw-medium px-3"
                    style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
                    data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                <i class="bx bx-import me-1"></i> Bulk Import
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sr No</th>
                            <th>Name</th>
                            <th>Descrip...</th>
                            <th>Price</th>
                            <th>Category Name</th>
                            <th>Image</th>
                            <th>Cafeteria</th>
                            <th>Show Only In POS</th>
                            <th>Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dish as $row)
                            @php
                                $cat  = $dishcategory->firstWhere('id', $row->dish_category_id);
                                $cafe_name = $cafe->firstWhere('id', $row->cafeteria_id);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($row->dish_name, 12) }}</td>
                                <td>{{ $row->description ? '-' : '-' }}</td>
                                <td>{{ $row->price }}</td>
                                <td>{{ Str::limit($cat->name ?? '-', 10) }}</td>
                                <td>
                                    @if($row->image)
                                        <img src="{{ $row->image }}" width="50" style="border-radius:4px;">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $cafe_name->cafeteria_name ?? '-' }}</td>
                                <td>{{ $row->show_in_pos ? 'Yes' : 'No' }}</td>
                                <td>
                                    <form action="{{ route('admin.dishchangeStatus', $row->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-link p-0 text-{{ $row->status == 1 ? 'success' : 'danger' }}">
                                            {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center1">
                                    {{-- Edit pencil --}}
                                    <button type="button" class="btn btn-sm btn-link text-dark p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editMenuModal{{ $row->id }}"
                                            title="Edit">
                                        <i class="bx bx-pencil fs-18"></i>
                                    </button>
                                    {{-- Three-dot kebab --}}
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-link text-dark p-1" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <form action="{{ route('admin.dish_delete', $row->id) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this dish?')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bx bx-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                   {{-- Edit Modal --}}
									<div class="modal fade" id="editMenuModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
										<div class="modal-dialog modal-lg modal-dialog-scrollable">
											<form method="POST" action="{{ route('admin.dish_update', $row->id) }}" enctype="multipart/form-data">
												@csrf
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Edit Dish</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
													</div>
													<div class="modal-body">

														<div class="mb-3">
															<label class="form-label">Dish Name</label>
															<input type="text" name="name" class="form-control" value="{{ $row->dish_name }}" required>
														</div>

														<div class="mb-3">
															<label class="form-label">Select Cafeteria</label>
															<select name="cafeteria_id" class="form-select" required>
																@foreach($cafe as $s)
																	<option value="{{ $s->id }}" {{ $row->cafeteria_id == $s->id ? 'selected' : '' }}>
																		{{ $s->cafeteria_name }}
																	</option>
																@endforeach
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">Select Category</label>
															<select name="category_id" class="form-select" required>
																@foreach($dishcategory as $cat2)
																	<option value="{{ $cat2->id }}" {{ $row->dish_category_id == $cat2->id ? 'selected' : '' }}>
																		{{ $cat2->name }}
																	</option>
																@endforeach
															</select>
														</div>

														<div class="mb-3">
															<label class="form-label">Description <span class="text-muted">(optional)</span></label>
															<textarea name="desc" class="form-control">{{ $row->desc2 }}</textarea>
														</div>

														{{-- Ingredients multi-select: admin can add/remove --}}
														<div class="mb-3">
															<label class="form-label">Ingredients <span class="text-muted">(select one or more)</span></label>
															@php $currentIngredients = array_map('trim', explode(',', $row->description ?? '')); @endphp
															<select name="ingredients[]" class="form-select" multiple size="6">
																@foreach($ingredients as $ing)
																	<option value="{{ $ing->name }}" {{ in_array($ing->name, $currentIngredients) ? 'selected' : '' }}>
																		{{ $ing->name }}
																	</option>
																@endforeach
															</select>
															<small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select/deselect multiple.</small>
														</div>

														<div class="row">
															<div class="col-md-6 mb-3">
																<label class="form-label">Price</label>
																<input type="text" name="price" class="form-control" value="{{ $row->price }}" required>
															</div>
															<div class="col-md-6 mb-3">
																<label class="form-label">Serving Of</label>
																<input type="text" name="serving_of" class="form-control" value="{{ $row->serving_of }}" required>
															</div>
														</div>

														<div class="row">
															<div class="col-md-6 mb-3">
																<label class="form-label">Calories</label>
																<input type="text" name="calories" class="form-control" value="{{ $row->calories }}" required>
															</div>
															<div class="col-md-6 mb-3">
																<label class="form-label">Protein</label>
																<input type="text" name="Protein" class="form-control" value="{{ $row->protein }}" required>
															</div>
														</div>

														<div class="row">
															<div class="col-md-6 mb-3">
																<label class="form-label">Carbohydrate</label>
																<input type="text" name="Carbohydrate" class="form-control" value="{{ $row->carbohydrates }}" required>
															</div>
															<div class="col-md-6 mb-3">
																<label class="form-label">Fats</label>
																<input type="text" name="fats" class="form-control" value="{{ $row->fats }}" required>
															</div>
														</div>

														<div class="mb-3 form-check">
															<input type="checkbox" class="form-check-input" id="show_in_pos{{ $row->id }}" name="show_in_pos" value="1"
																{{ $row->show_in_pos ? 'checked' : '' }}>
															<label class="form-check-label" for="show_in_pos{{ $row->id }}">Show only in POS</label>
														</div>

														<div class="mb-3">
															<label class="form-label fw-semibold">Upload New Image</label><br>
															@if($row->image)
																<img src="{{ $row->image }}" width="80" class="mb-2">
															@endif
															<input type="file" name="image" class="form-control mt-2">
														</div>

													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-success">Update</button>
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
													</div>
												</div>
											</form>
										</div>
									</div>

                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">No records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($dish->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $dish->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

	{{-- Bulk Import Modal --}}
	<div class="modal fade" id="bulkImportModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title fw-semibold">Bulk Import Dishes</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form action="{{ route('admin.dish_bulk_import') }}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Select Cafeteria <span class="text-danger">*</span></label>
							<select name="cafeteria_id" class="form-select" required>
								<option value="">Select Cafeteria *</option>
								@foreach($cafe as $c)
									<option value="{{ $c->id }}">{{ $c->cafeteria_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<input type="file" name="file" class="form-control" accept=".csv,.xlsx,.txt" required>
						</div>
						<div class="mb-2">
							<small class="text-muted">
								CSV columns: <code>dish_name, category_name, description, price, serving_of, calories, protein, carbohydrates, fats, food_type</code><br>
								Category will be auto-created if it does not exist for the selected cafeteria.
							</small>
						</div>
						<a href="{{ route('admin.dish_import_sample') }}" class="btn btn-sm btn-outline-secondary">
							<i class="bx bx-download me-1"></i> Download Sample CSV
						</a>
					</div>
					<div class="modal-footer border-0">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary"
								style="background:#2e2e7a; border-color:#2e2e7a;">Upload</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Add Dish Modal --}}
	<div class="modal fade" id="addDishModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<form method="POST" action="{{ secure_url(route('admin.dish_store', [], false)) }}" enctype="multipart/form-data">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add Dish</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Enter Name</label>
							<input type="text" name="name" class="form-control" placeholder="Enter name" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Select Cafeteria</label>
							<select name="cafeteria_id" id="cafeteria_id" class="form-select" required>
								<option value="">-- Select Cafeteria --</option>
								@foreach($cafe as $s)
									<option value="{{ $s->id }}">{{ $s->cafeteria_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label">Select Category</label>
							<select name="category_id" id="category_id" class="form-select" required>
								<option value="">-- Select Category --</option>
							</select>
						</div>
						<div class="mb-3">
							<label class="form-label">Description <span class="text-muted">(optional)</span></label>
							<textarea name="desc" class="form-control"></textarea>
						</div>
						<div class="mb-3">
							<label class="form-label">Select Ingredients</label>
							<select name="ingredients[]" class="form-select" multiple>
								@foreach($ingredients as $s)
									<option value="{{ $s->name }}">{{ $s->name }}</option>
								@endforeach
							</select>
							<small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="form-label">Price</label>
								<input type="text" name="price" class="form-control" placeholder="Enter price" required>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Serving Of</label>
								<input type="text" name="serving_of" class="form-control" placeholder="Enter serving" required>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="form-label">Calories</label>
								<input type="text" name="calories" class="form-control" placeholder="Enter calories" required>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Protein</label>
								<input type="text" name="Protein" class="form-control" placeholder="Enter Protein" required>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="form-label">Carbohydrate</label>
								<input type="text" name="Carbohydrate" class="form-control" placeholder="Enter Carbohydrate" required>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Fats</label>
								<input type="text" name="fats" class="form-control" placeholder="Enter fats" required>
							</div>
						</div>
						<div class="mb-3 form-check">
							<input type="checkbox" class="form-check-input" id="show_in_pos" name="show_in_pos" value="1">
							<label class="form-check-label" for="show_in_pos">Show only in POS</label>
						</div>
						<div class="mb-3">
							<label class="form-label fw-semibold">Upload Image <span class="text-danger">*</span></label>
							<input type="file" name="image" class="form-control" required>
							<small class="text-muted">Accepted: JPG, PNG. Max: 2MB.</small>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Add</button>
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					</div>
				</div>
			</form>
		</div>
	</div>



</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#cafeteria_id').on('change', function () {
    var cafeteriaId = $(this).val();
    if (cafeteriaId) {
        $.ajax({
            url: '/admin/get-categories/' + cafeteriaId,
            type: 'GET',
            success: function (data) {
                $('#category_id').empty().append('<option value="">-- Select Category --</option>');
                $.each(data, function (key, category) {
                    $('#category_id').append('<option value="' + category.id + '">' + category.name + '</option>');
                });
            }
        });
    } else {
        $('#category_id').empty().append('<option value="">-- Select Category --</option>');
    }
});

let addFoodTypes = [];
function addFoodType() {
    let input = document.getElementById('foodTypeInput');
    let value = input.value.trim();
    if (!value || addFoodTypes.includes(value.toLowerCase())) { input.value = ''; return; }
    addFoodTypes.push(value.toLowerCase());
    input.value = '';
    renderAddFoodTypes();
}
function removeFoodType(index) { addFoodTypes.splice(index, 1); renderAddFoodTypes(); }
function renderAddFoodTypes() {
    let html = '';
    addFoodTypes.forEach((type, index) => {
        html += `<span class="badge bg-success me-2">${type} <span style="cursor:pointer;" onclick="removeFoodType(${index})">&times;</span></span>`;
    });
    document.getElementById('foodTypeList').innerHTML = html;
    document.getElementById('food_type').value = addFoodTypes.join(',');
}

const modalFoodTypes = {};
function initializeEditModal(modalId) {
    const hidden = document.getElementById('food_type' + modalId);
    if (!hidden) return;
    modalFoodTypes[modalId] = hidden.value ? hidden.value.split(',').map(i => i.trim()).filter(i => i) : [];
    renderModalFoodTypes(modalId);
}
function addFoodTypeToModal(modalId) {
    const input = document.getElementById('foodTypeInput' + modalId);
    const value = input.value.trim();
    if (!value) return;
    if (modalFoodTypes[modalId].some(t => t.toLowerCase() === value.toLowerCase())) { input.value = ''; return; }
    modalFoodTypes[modalId].push(value);
    input.value = '';
    renderModalFoodTypes(modalId);
}
function removeFoodTypeFromModal(modalId, typeToRemove) {
    const decoded = typeToRemove.replace(/\\/g, '');
    const index = modalFoodTypes[modalId].findIndex(i => i.toLowerCase() === decoded.toLowerCase());
    if (index > -1) { modalFoodTypes[modalId].splice(index, 1); renderModalFoodTypes(modalId); }
}
function removeModalFoodType(modalId, index) {
    if (modalFoodTypes[modalId]) { modalFoodTypes[modalId].splice(index, 1); renderModalFoodTypes(modalId); }
}
function renderModalFoodTypes(modalId) {
    const container = document.getElementById('foodTypeList' + modalId);
    const hidden    = document.getElementById('food_type' + modalId);
    if (!container || !hidden) return;
    let html = '';
    modalFoodTypes[modalId].forEach((type, index) => {
        html += `<span class="badge bg-success me-2 mb-2">${type} <span style="cursor:pointer; margin-left:5px;" onclick="removeModalFoodType('${modalId}', ${index})">&times;</span></span>`;
    });
    container.innerHTML = html;
    hidden.value = modalFoodTypes[modalId].join(',');
}
document.addEventListener('DOMContentLoaded', function () {
    @foreach($dish as $row)
    (function() {
        const el = document.getElementById('editMenuModal{{ $row->id }}');
        if (el) el.addEventListener('show.bs.modal', function () { initializeEditModal('{{ $row->id }}'); });
    })();
    @endforeach
});
</script>


@endsection
@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page heading --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Email Content Management</h5>
        </div>
    </div>


    {{-- Total entries + search --}}
    <div class="mb-2 fw-medium">{{ $templates->count() }} Total Entries</div>

    <div class="mb-3" style="max-width:360px;">
        <form method="GET" action="{{ route('admin.mails.index') }}">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bx bx-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0"
                       placeholder="Email type" value="{{ request('search') }}">
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th style="width:200px;">Email Type</th>
                            <th>Email Content</th>
                            <th style="width:120px;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>{{ $template->id }}</td>
                                <td>{{ $template->email_type }}</td>
                                <td>
                                    @php
                                        $preview = $template->email_content;
                                        $preview = preg_replace('/<p[^>]*>/i', '', $preview);
                                        $preview = preg_replace('/<\/p>/i', "\n", $preview);
                                        $preview = preg_replace('/<br\s*\/?>/i', "\n", $preview);
                                        $preview = strip_tags($preview);
                                        $preview = html_entity_decode($preview, ENT_QUOTES, 'UTF-8');
                                        $preview = trim($preview);
                                        $preview = Str::limit($preview, 160);
                                    @endphp
                                    <div class="text-muted" style="font-size:.875rem; white-space:pre-line; max-height:75px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical;">{{ $preview }}</div>
                                </td>
                                <td class="text-center">
                                    {{-- Edit pencil → dedicated Jodit edit page --}}
                                    <a href="{{ route('admin.mails.edit', $template->id) }}"
                                       class="btn btn-sm btn-link text-dark p-1" title="Edit">
                                        <i class="bx bx-pencil fs-18"></i>
                                    </a>

                                    {{-- Three-dot kebab --}}
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-link text-dark p-1"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item btn-view-template"
                                                        data-id="{{ $template->id }}"
                                                        data-type="{{ $template->email_type }}"
                                                        data-content="{{ e($template->email_content) }}">
                                                    More Details
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No email templates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ============================================================
     DETAILS / VIEW MODAL
============================================================ --}}
<div class="modal fade" id="viewTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold">Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div id="view_email_type_badge"
                         class="border rounded px-3 py-2 fw-medium"
                         style="display:inline-block; min-width:120px; border-color:#2e2e7a !important; color:#2e2e7a;">
                    </div>
                </div>
                <div class="border rounded p-3"
                     style="min-height:300px; max-height:450px; overflow-y:auto; font-size:.9rem; line-height:1.7;">
                    <div id="view_email_content"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Details / view modal ----
    document.querySelectorAll('.btn-view-template').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const type    = this.dataset.type;
            const content = this.dataset.content;

            document.getElementById('view_email_type_badge').textContent = type;
            // Render HTML content (from Jodit) properly
            document.getElementById('view_email_content').innerHTML = content;

            const modal = new bootstrap.Modal(document.getElementById('viewTemplateModal'));
            modal.show();
        });
    });

});
</script>
@endpush
@endsection
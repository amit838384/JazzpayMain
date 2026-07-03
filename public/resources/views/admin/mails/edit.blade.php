@extends('layouts.app')
@section('content')

<style>
    .cke_notification_warning,
    .cke_notifications_area { display: none !important; }
    #jodit_loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 450px;
        background: #f8f9fa;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 1rem;
        color: #6c757d;
        gap: 10px;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3.24.4/build/jodit.min.css">
<script src="https://cdn.jsdelivr.net/npm/jodit@3.24.4/build/jodit.min.js"></script>

<div class="container-fluid">

    <div class="card mb-4" style="background-color:#2e2e7a; border:none;">
        <div class="card-body py-3 px-4">
            <h5 class="mb-0 fw-semibold" style="color:#f5c518;">Edit Email Content</h5>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        // Convert plain-text \n to <br> if content has no HTML tags yet
        $content = $template->email_content;
        if (!preg_match('/<[^>]+>/', $content)) {
            $content = nl2br(e($content));
        }
    @endphp

    <form method="POST" action="{{ route('admin.mails.update', $template->id) }}" id="editMailForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="email_type"    value="{{ $template->email_type }}">
        <input type="hidden" name="email_content" id="email_content_input">

        {{-- Store initial content for Jodit --}}
        <script>
            var joditInitialContent = {!! json_encode($content) !!};
        </script>

        <div class="card" style="border:1px solid #ccc; border-radius:6px; overflow:hidden;">
            <div class="card-body p-0">
                <div id="jodit_loading">
                    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                    Loading editor...
                </div>
                <textarea id="jodit_editor" name="jodit_editor_visible"
                          style="display:none;"></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="submit" class="btn px-4 fw-medium"
                    style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;">Submit</button>
            <a href="{{ route('admin.mails.index') }}"
               class="btn px-4 fw-medium"
               style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;">Close</a>
        </div>
    </form>

</div>

<script>
(function () {
    function initJodit() {
        if (typeof Jodit === 'undefined') {
            setTimeout(initJodit, 200);
            return;
        }

        if (typeof CKEDITOR !== 'undefined') {
            Object.keys(CKEDITOR.instances).forEach(function (key) {
                try { CKEDITOR.instances[key].destroy(true); } catch (e) {}
            });
        }

        document.getElementById('jodit_loading').style.display = 'none';
        document.getElementById('jodit_editor').style.display  = '';

        var editor = Jodit.make('#jodit_editor', {
            height: 450,
            toolbarButtonSize: 'middle',
            buttons: [
                'bold','italic','underline','strikethrough','eraser','|',
                'ul','ol','|',
                'font','fontsize','paragraph','lineHeight','|',
                'file','image','video','|',
                'hr','table','link','symbols','|',
                'indent','outdent','align','|',
                'brush','|',
                'undo','redo','fullsize','preview','print','dots'
            ],
            showCharsCounter: true,
            showWordsCounter: true,
            showXPathInStatusbar: false,
            statusbar: true,
        });

        // Set content after editor is ready
        editor.value = joditInitialContent;

        document.getElementById('editMailForm').addEventListener('submit', function () {
            document.getElementById('email_content_input').value = editor.value;
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initJodit);
    } else {
        initJodit();
    }
})();
</script>
@endsection
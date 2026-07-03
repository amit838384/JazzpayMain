<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;

class MailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List all email templates, with optional search by email_type.
     */
    public function index(Request $request)
    {
        $query = EmailTemplate::query();

        if ($request->filled('search')) {
            $query->where('email_type', 'like', '%' . $request->search . '%');
        }

        $templates = $query->orderBy('id')->get();

        return view('admin.mails.index', compact('templates'));
    }

    /**
     * Show dedicated edit page with Jodit editor.
     */
    public function edit($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('admin.mails.edit', compact('template'));
    }

    /**
     * Return a single template as JSON (used by the Details modal).
     */
    public function show($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return response()->json($template);
    }

    /**
     * Update email_type and email_content (Edit modal form submit).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email_type'    => 'required|string|max:255',
            'email_content' => 'required|string',
        ]);

        $template = EmailTemplate::findOrFail($id);
        $template->update([
            'email_type'    => $request->email_type,
            'email_content' => $request->email_content,
        ]);

        return redirect()->route('admin.mails.index')
                         ->with('success', 'Email template updated successfully.');
    }
}
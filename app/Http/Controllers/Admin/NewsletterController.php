<?php

namespace App\Http\Controllers\Admin;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::latest()->paginate();

        return Inertia::render('Admin/Newsletters/Index', compact('newsletters'));
    }

    public function create()
    {
        return Inertia::render('Admin/Newsletters/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'html' => 'required|string',
            'body' => 'required|array',
            'body.uk' => 'required|string',
            'body.en' => 'required|string',
            'scheduled_at' => 'nullable|date',
        ]);

        Newsletter::create($data);

        return redirect()->route('newsletters.index')->with('success', 'Розсилка створена');
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        return back()->with('success', 'Розсилку видалено');
    }
}

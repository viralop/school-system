<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Term;
use Illuminate\Http\Request;

class TermManagementController extends Controller
{
    public function index()
    {
        $levels = Level::with('terms')->orderBy('order')->get();

        return view('supervisor.terms', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $levels = Level::orderBy('order')->get();
        $order = Term::max('order') ?? 0;

        foreach ($levels as $level) {
            Term::create([
                'name' => $request->name,
                'level_id' => $level->id,
                'order' => $order + 1,
                'status' => 'open',
            ]);
        }

        return back()->with('success', "Term '{$request->name}' added to all levels.");
    }

    public function update(Request $request, Term $term)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $term->update(['name' => $request->name]);

        return back()->with('success', "Term '{$term->name}' updated.");
    }

    public function close(Term $term)
    {
        $term->close();

        return back()->with('success', "Term '{$term->name}' closed.");
    }

    public function open(Term $term)
    {
        $term->open();

        return back()->with('success', "Term '{$term->name}' reopened.");
    }

    public function destroy(Term $term)
    {
        if ($term->grades()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete term with existing grades.']);
        }

        $term->delete();

        return back()->with('success', 'Term deleted.');
    }
}

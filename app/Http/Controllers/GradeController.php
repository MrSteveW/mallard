<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradeController extends Controller
{
    public function index()
    {
        return Inertia::render('Grades/Index', [
            'grades' => Grade::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Grades/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $grade = Grade::create([
            'name' => $validated['name'],
        ]);

        return redirect('/grades')->with('message', 'Grade created successfully.');
    }

    public function show(Grade $grade)
    {
        return Inertia::render('Grades/Show', [
            'grade' => $grade,
        ]);
    }

    public function edit(Grade $grade)
    {
        return Inertia::render('Grades/Edit', ['grade' => $grade]);
    }

    public function update(Grade $grade, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $grade->update([
            'name' => $validated['name'],
        ]);

        return redirect('/grades')->with('message', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect('/grades');
    }
}

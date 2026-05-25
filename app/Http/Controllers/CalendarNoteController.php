<?php

namespace App\Http\Controllers;

use App\Models\CalendarNote;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class CalendarNoteController extends Controller
{
    
    public function store(Request $request)
    {
        Gate::authorize('create', CalendarNote::class);
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'note' => ['required', 'string', 'max:255'],
        ]);

        CalendarNote::create([
            'date' => $validated['date'],
            'note' => $validated['note'],
            'source' => 'manual',
        ]);

        return redirect('/duties')->with('message', 'Note created successfully.');
    }

    public function update(CalendarNote $calendarNote, Request $request)
    {
        Gate::authorize('update', $calendarNote);
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'note' => ['required', 'string', 'max:255'],
        ]);

        $calendarNote->update([
            'date' => $validated['date'],
            'note' => $validated['note'],
        ]);

        return redirect('/duties')->with('message', 'Note updated successfully.');
    }

    public function destroy(CalendarNote $calendarNote)
    {
        Gate::authorize('destroy', $calendarNote);
        $calendarNote->delete();

        return redirect('/duties');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Show events management page.
     */
    public function index()
    {
        $events = Event::latest()->get();
        return view('events.index', compact('events'));
    }

    /**
     * Return all events as JSON for FullCalendar.
     */
    public function getEvents()
    {
        $events = Event::all(['id', 'title', 'start_date as start', 'end_date as end']);
        return response()->json($events);
    }

    /**
     * Show the form for creating a new event.
     */
public function list()
    {
        $events = Event::orderBy('start_date', 'asc')->get(['id', 'title', 'start_date', 'end_date']);
    return response()->json($events);
    }

    /**
     * Store event (form submission).
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Event::create($request->only('title', 'start_date', 'end_date'));

        return redirect()->back()->with('success', 'Event created successfully.');
    }

    /**
     * Store event via AJAX.
     */
    public function storeAjax(Request $request)
    {
        $event = Event::create([
            'title' => $request->title,
            'start_date' => $request->start,
            'end_date' => $request->end,
        ]);

        return response()->json($event);
    }

    /**
     * Update event via AJAX.
     */
    public function updateAjax(Request $request, Event $event)
    {
        $event->update([
            'title' => $request->title,
            'start_date' => $request->start,
            'end_date' => $request->end,
        ]);

        return response()->json($event);
    }

    /**
     * Delete event via AJAX.
     */
    public function destroyAjax(Event $event)
    {
        $event->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Delete event (form submission).
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->back()->with('success', 'Event deleted successfully.');
    }
}

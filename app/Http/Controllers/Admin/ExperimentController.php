<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\ExperimentEntry;
use Illuminate\Http\Request;

class ExperimentController extends Controller
{
    public function addEntry(Experiment $experiment)
    {
        return view('admin.experiments.add-entry', compact('experiment'));
    }

    public function storeEntry(Request $request, Experiment $experiment)
    {
        $request->validate([
            'entry_date' => 'required|datetime',
            'type' => 'required|in:observation,result,note,update',
            'content' => 'required|string|max:5000',
        ]);

        ExperimentEntry::create([
            'experiment_id' => $experiment->id,
            'entry_date' => $request->entry_date,
            'type' => $request->type,
            'content' => $request->content,
        ]);

        return redirect()->route('experiments.show', $experiment)->with('success', 'Entry added successfully!');
    }

    public function index()
    {
        $experiments = Experiment::all();

        return view('admin.experiments.index', compact('experiments'));
    }

    public function create()
    {
        return view('admin.experiments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,paused',
        ]);

        Experiment::create($request->all());

        return redirect()->route('admin.experiments.index')->with('success', 'Experiment created successfully!');
    }

    public function edit(Experiment $experiment)
    {
        $resources    = $experiment->resources()->get();
        $allResources = \App\Models\Resource::orderBy('name')->get(['id', 'name']);
        return view('admin.experiments.edit', compact('experiment', 'resources', 'allResources'));
    }

    public function update(Request $request, Experiment $experiment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,paused',
        ]);

        $experiment->update($request->all());

        return redirect()->route('admin.experiments.index')->with('success', 'Experiment updated successfully!');
    }

    public function destroy(Experiment $experiment)
    {
        $experiment->delete();

        return redirect()->route('admin.experiments.index')->with('success', 'Experiment deleted successfully!');
    }

    public function archive(Request $request, Experiment $experiment)
    {
        $experiment->update(['archived' => ! $experiment->archived]);

        $message = $experiment->archived ? 'Experiment archived!' : 'Experiment restored to the home page.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'archived' => $experiment->archived,
            ]);
        }

        return redirect()->route('admin.experiments.index')->with('success', $message);
    }
}

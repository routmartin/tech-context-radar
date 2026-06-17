<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsRadarPayloads;
use App\Models\SavedSignal;
use App\Models\Signal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SavedSignalController extends Controller
{
    use BuildsRadarPayloads;

    public function index(Request $request): Response
    {
        $signals = $request->user()->savedSignals()->with(['category', 'source'])->orderByPivot('created_at', 'desc')->get();

        return Inertia::render('Saved', [
            'categories' => $this->categories(),
            'signals' => $this->signalCollection($signals, $request->user()),
        ]);
    }

    public function store(Request $request, Signal $signal): RedirectResponse
    {
        SavedSignal::updateOrCreate(['user_id' => $request->user()->id, 'signal_id' => $signal->id], ['created_at' => now()]);

        return back()->with('toast', 'Signal saved to your intelligence library.');
    }

    public function destroy(Request $request, Signal $signal): RedirectResponse
    {
        SavedSignal::query()->where('user_id', $request->user()->id)->where('signal_id', $signal->id)->delete();

        return back()->with('toast', 'Signal removed from saved intelligence.');
    }
}

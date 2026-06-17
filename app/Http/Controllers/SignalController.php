<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsRadarPayloads;
use App\Models\Signal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SignalController extends Controller
{
    use BuildsRadarPayloads;

    public function show(Request $request, Signal $signal): Response
    {
        $signal->load(['category', 'source']);

        $related = Signal::query()
            ->with(['category', 'source'])
            ->where('id', '!=', $signal->id)
            ->where('category_id', $signal->category_id)
            ->orderByDesc('priority_score')
            ->limit(3)
            ->get();

        if ($related->count() < 3) {
            $related = $related->merge(Signal::query()
                ->with(['category', 'source'])
                ->whereNotIn('id', $related->pluck('id')->push($signal->id))
                ->orderByDesc('priority_score')
                ->limit(3 - $related->count())
                ->get());
        }

        return Inertia::render('Signals/Show', [
            'signal' => $this->signalPayload($signal, $request->user()),
            'relatedSignals' => $this->signalCollection($related, $request->user()),
        ]);
    }
}

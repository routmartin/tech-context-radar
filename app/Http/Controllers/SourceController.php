<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsRadarPayloads;
use App\Models\Signal;
use App\Models\Source;
use Inertia\Inertia;
use Inertia\Response;

class SourceController extends Controller
{
    use BuildsRadarPayloads;

    public function __invoke(): Response
    {
        $sources = Source::query()
            ->with(['category', 'signals' => fn ($query) => $query->orderByDesc('priority_score')])
            ->orderBy('name')
            ->get();

        return Inertia::render('Sources', [
            'categories' => $this->categories(),
            'sources' => $sources->map(fn (Source $source) => $this->sourcePayload($source))->values(),
            'summary' => [
                'sourcesScanned' => $sources->count(),
                'updatesCollected' => $sources->sum('updates_today'),
                'lowImpactFiltered' => 39,
                'prioritySignals' => Signal::query()->where('priority_score', '>=', 80)->count(),
            ],
        ]);
    }
}

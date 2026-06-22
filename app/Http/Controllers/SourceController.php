<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsRadarPayloads;
use App\Models\Category;
use App\Models\RawUpdate;
use App\Models\Signal;
use App\Models\Source;
use Inertia\Inertia;
use Inertia\Response;

class SourceController extends Controller
{
    use BuildsRadarPayloads;

    public function __invoke(): Response
    {
        $focusSlugs = config('radar.focus_categories', ['ai']);
        $categoryIds = Category::whereIn('slug', $focusSlugs)->pluck('id');

        $sources = Source::query()
            ->with([
                'category',
                'rawUpdates' => fn ($query) => $query->latest('published_at'),
                'signals' => fn ($query) => $query->orderByDesc('priority_score'),
            ])
            ->whereIn('category_id', $categoryIds)
            ->orderBy('name')
            ->get();

        $todayUpdates = RawUpdate::query()
            ->whereIn('source_id', Source::whereIn('category_id', $categoryIds)->pluck('id'))
            ->where('created_at', '>=', now()->startOfDay());

        return Inertia::render('Sources', [
            'categories' => $this->categories(),
            'sources' => $sources->map(fn (Source $source) => $this->sourcePayload($source))->values(),
            'summary' => [
                'sourcesScanned' => $sources->whereNotNull('last_scanned_at')->count(),
                'updatesCollected' => (clone $todayUpdates)->count(),
                'lowImpactFiltered' => (clone $todayUpdates)->whereIn('status', [RawUpdate::STATUS_IGNORED, RawUpdate::STATUS_COLLECTED])->count(),
                'prioritySignals' => Signal::query()->whereIn('category_id', $categoryIds)->where('priority_score', '>=', 80)->count(),
            ],
        ]);
    }
}

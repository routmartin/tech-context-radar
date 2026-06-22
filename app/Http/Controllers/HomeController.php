<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsRadarPayloads;
use App\Models\Category;
use App\Models\Signal;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    use BuildsRadarPayloads;

    public function __invoke(): Response
    {
        $focusSlugs = config('radar.focus_categories', ['ai']);
        $categoryIds = Category::whereIn('slug', $focusSlugs)->pluck('id');

        $signals = Signal::query()
            ->with(['category', 'source', 'rawUpdates.source'])
            ->whereIn('category_id', $categoryIds)
            ->orderByDesc('priority_score')
            ->limit(5)
            ->get();

        return Inertia::render('Home', [
            'briefing' => $this->briefing(),
            'categories' => $this->categories(),
            'signals' => $this->signalCollection($signals),
        ]);
    }
}

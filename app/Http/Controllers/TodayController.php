<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsRadarPayloads;
use App\Models\Category;
use App\Models\Signal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TodayController extends Controller
{
    use BuildsRadarPayloads;

    public function __invoke(Request $request): Response
    {
        $preferred = $request->user()->preference->preferred_categories ?? [];
        $categoryIds = Category::whereIn('name', $preferred)->pluck('id');

        $signals = Signal::query()
            ->with(['category', 'source'])
            ->whereIn('category_id', $categoryIds)
            ->orderByDesc('priority_score')
            ->orderByDesc('published_at')
            ->get();

        return Inertia::render('Home', [
            'briefing' => $this->briefing(),
            'categories' => $this->categories(),
            'signals' => $this->signalCollection($signals, $request->user()),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsRadarPayloads;
use App\Models\Signal;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    use BuildsRadarPayloads;

    public function __invoke(): Response
    {
        $signals = Signal::query()->with(['category', 'source'])->orderByDesc('priority_score')->limit(3)->get();

        return Inertia::render('Home', [
            'briefing' => $this->briefing(),
            'categories' => $this->categories(),
            'signals' => $this->signalCollection($signals),
        ]);
    }
}

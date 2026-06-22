<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsRadarPayloads;
use App\Models\UserPreference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    use BuildsRadarPayloads;

    public function edit(Request $request): Response
    {
        return Inertia::render('Settings', [
            'categories' => $this->categories(),
            'preference' => $this->preferenceFor($request),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'briefing_length_minutes' => ['required', 'integer', 'min:5', 'max:20'],
            'priority_threshold' => ['required', 'integer', 'min:50', 'max:95'],
            'preferred_categories' => ['array'],
            'preferred_categories.*' => ['string', Rule::exists('categories', 'name')],
            'daily_reminder_enabled' => ['boolean'],
            'priority_alerts_enabled' => ['boolean'],
            'weekly_summary_enabled' => ['boolean'],
            'compact_mode_enabled' => ['boolean'],
            'dark_mode_enabled' => ['boolean'],
        ]);

        UserPreference::updateOrCreate(['user_id' => $request->user()->id], $validated + ['user_id' => $request->user()->id]);

        return back()->with('toast', 'Radar preferences updated.');
    }

    private function preferenceFor(Request $request): array
    {
        $preference = UserPreference::firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'briefing_length_minutes' => 10,
                'priority_threshold' => 70,
                'preferred_categories' => ['AI', 'DevTools', 'Frameworks', 'Cloud'],
                'daily_reminder_enabled' => true,
                'priority_alerts_enabled' => true,
                'weekly_summary_enabled' => false,
                'compact_mode_enabled' => false,
                'dark_mode_enabled' => true,
            ],
        );

        return [
            'briefing_length_minutes' => $preference->briefing_length_minutes,
            'priority_threshold' => $preference->priority_threshold,
            'preferred_categories' => $preference->preferred_categories ?? [],
            'daily_reminder_enabled' => $preference->daily_reminder_enabled,
            'priority_alerts_enabled' => $preference->priority_alerts_enabled,
            'weekly_summary_enabled' => $preference->weekly_summary_enabled,
            'compact_mode_enabled' => $preference->compact_mode_enabled,
            'dark_mode_enabled' => $preference->dark_mode_enabled,
        ];
    }
}

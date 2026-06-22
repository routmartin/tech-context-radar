<?php

namespace Tests\Feature;

use App\Models\ReadSignal;
use App\Models\SavedSignal;
use App\Models\Signal;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RadarFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_home_and_authenticated_radar_routes_render(): void
    {
        $this->seed();

        $user = User::where('email', 'builder@techcontextradar.test')->firstOrFail();
        $signal = Signal::query()->firstOrFail();

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->has('briefing')
                ->has('categories')
                ->has('signals')
            );

        $this->actingAs($user)->get('/today')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->has('briefing')
                ->has('categories')
                ->has('signals')
            );

        $this->actingAs($user)->get(route('signals.show', $signal))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Signals/Show')
                ->has('signal')
                ->has('relatedSignals')
            );

        $this->actingAs($user)->get('/saved')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Saved')->has('categories')->has('signals'));

        $this->actingAs($user)->get('/sources')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Sources')->has('categories')->has('sources')->has('summary'));

        $this->actingAs($user)->get('/settings')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Settings')->has('categories')->has('preference'));
    }

    public function test_user_can_save_remove_and_mark_signal_as_read(): void
    {
        $this->seed();

        $user = User::where('email', 'builder@techcontextradar.test')->firstOrFail();
        $signal = Signal::query()
            ->whereDoesntHave('savedSignals', fn ($query) => $query->where('user_id', $user->id))
            ->whereDoesntHave('readSignals', fn ($query) => $query->where('user_id', $user->id))
            ->firstOrFail();

        $this->actingAs($user)->post(route('signals.save', $signal))
            ->assertRedirect();

        $this->assertDatabaseHas(SavedSignal::class, [
            'user_id' => $user->id,
            'signal_id' => $signal->id,
        ]);

        $this->actingAs($user)->post(route('signals.read', $signal))
            ->assertRedirect();

        $this->assertDatabaseHas(ReadSignal::class, [
            'user_id' => $user->id,
            'signal_id' => $signal->id,
        ]);

        $this->actingAs($user)->delete(route('signals.unsave', $signal))
            ->assertRedirect();

        $this->assertDatabaseMissing(SavedSignal::class, [
            'user_id' => $user->id,
            'signal_id' => $signal->id,
        ]);
    }

    public function test_user_can_update_radar_preferences(): void
    {
        $this->seed();

        $user = User::where('email', 'builder@techcontextradar.test')->firstOrFail();

        $this->actingAs($user)->put('/settings/preferences', [
            'briefing_length_minutes' => 15,
            'priority_threshold' => 80,
            'preferred_categories' => ['AI', 'Security'],
            'daily_reminder_enabled' => false,
            'priority_alerts_enabled' => true,
            'weekly_summary_enabled' => true,
            'compact_mode_enabled' => true,
            'dark_mode_enabled' => true,
        ])->assertRedirect();

        $this->assertDatabaseHas(UserPreference::class, [
            'user_id' => $user->id,
            'briefing_length_minutes' => 15,
            'priority_threshold' => 80,
            'daily_reminder_enabled' => false,
            'priority_alerts_enabled' => true,
            'weekly_summary_enabled' => true,
            'compact_mode_enabled' => true,
            'dark_mode_enabled' => true,
        ]);

        $this->assertSame(['AI', 'Security'], $user->preference()->firstOrFail()->preferred_categories);
    }
}

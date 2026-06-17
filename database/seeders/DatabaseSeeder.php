<?php

namespace Database\Seeders;

use App\Models\Briefing;
use App\Models\Category;
use App\Models\SavedSignal;
use App\Models\Signal;
use App\Models\Source;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'AI', 'slug' => 'ai'],
            ['name' => 'DevTools', 'slug' => 'devtools'],
            ['name' => 'Frameworks', 'slug' => 'frameworks'],
            ['name' => 'Cloud', 'slug' => 'cloud'],
            ['name' => 'Security', 'slug' => 'security'],
            ['name' => 'Blogs', 'slug' => 'blogs'],
        ])->mapWithKeys(fn (array $category, int $index) => [
            $category['slug'] => Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name'], 'sort_order' => $index + 1],
            ),
        ]);

        $sourceRows = [
            ['OpenAI', 'ai', 'Very high', 12, 'healthy'],
            ['Anthropic Research', 'ai', 'Very high', 8, 'healthy'],
            ['DeepMind Research', 'ai', 'High', 5, 'healthy'],
            ['GitHub Changelog', 'devtools', 'Very high', 16, 'healthy'],
            ['GitLab Releases', 'devtools', 'High', 6, 'healthy'],
            ['VS Code Update Feed', 'devtools', 'High', 11, 'healthy'],
            ['Flutter', 'frameworks', 'High', 7, 'healthy'],
            ['Laravel', 'frameworks', 'High', 4, 'healthy'],
            ['AWS', 'cloud', 'High', 18, 'healthy'],
            ['Cloudflare Blog', 'cloud', 'High', 14, 'healthy'],
            ['Vercel Changelog', 'cloud', 'High', 9, 'degraded'],
            ['The Hacker News', 'security', 'Medium', 22, 'healthy'],
            ['SecurityWeek', 'security', 'Medium', 17, 'healthy'],
            ['Snyk Blog', 'security', 'High', 10, 'healthy'],
            ['The GitHub Engineering Blog', 'blogs', 'High', 3, 'healthy'],
            ['Stripe Engineering', 'blogs', 'High', 2, 'healthy'],
        ];

        $sources = collect($sourceRows)->mapWithKeys(function (array $row, int $index) use ($categories) {
            [$name, $categorySlug, $trust, $updates, $status] = $row;

            return [
                Str::slug($name) => Source::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'category_id' => $categories[$categorySlug]->id,
                        'name' => $name,
                        'trust_level' => $trust,
                        'updates_today' => $updates,
                        'last_scanned_at' => now()->subMinutes(8 + ($index * 7)),
                        'status' => $status,
                    ],
                ),
            ];
        });

        $signalRows = [
            ['OpenAI improves coding agent workflow', 'ai', 'openai', 'New controls make long-running coding agents easier to steer, resume, and review from a developer workflow.', 'Agentic coding is moving from demos into daily engineering work. Better steering lowers the cost of delegating larger tasks without losing code ownership.', 'Teams using AI coding assistants should revisit review boundaries, branch hygiene, and task handoff patterns.', 'Trial the workflow on one non-critical backlog item, then document where human review remains mandatory.', 94, 4, 5],
            ['Flutter web rendering gets smoother', 'frameworks', 'flutter', 'Rendering changes reduce frame drops and improve perceived smoothness for complex Flutter web screens.', 'Flutter web remains attractive for shared-code product surfaces, but rendering quality has been the main adoption question.', 'Mobile teams shipping admin panels or customer portals from Flutter can re-test screens previously rejected for jank.', 'Benchmark one animation-heavy web flow and compare frame timing before changing architecture.', 82, 3, 3],
            ['Copilot review automation improves', 'devtools', 'github-changelog', 'Pull request review automation is getting better at summarizing risk and suggesting targeted fixes.', 'Code review is one of the highest-friction places for AI to help because noise quickly erodes trust.', 'Engineering leads can use review bots for first-pass context, but ownership and security-sensitive changes still need humans.', 'Enable automated summaries on a pilot repo and track whether review cycle time improves.', 88, 3, 4],
            ['Claude tool-use reliability improvements', 'ai', 'anthropic-research', 'Tool calls are becoming more predictable across multi-step tasks that combine search, file edits, and structured outputs.', 'Reliability in tool use is a practical unlock for agents that work inside real projects instead of isolated chat sessions.', 'Developers building internal agents can simplify retry logic in some flows while still validating side effects.', 'Review your agent traces for avoidable retries and add stricter schema validation around tool outputs.', 80, 4, 4],
            ['Prompt caching now supports multi-turn', 'ai', 'openai', 'Multi-turn prompt caching can reduce latency and cost for repeated context-heavy AI workflows.', 'Caching changes the economics of long-context agents, documentation assistants, and customer-support copilots.', 'Apps that resend large system context or repository context should measure cache hit rates and cost reduction.', 'Audit repeated prompt blocks and move stable context into cache-friendly request structure.', 78, 3, 3],
            ['Critical npm package takeover campaign detected', 'security', 'snyk-blog', 'Attackers are targeting maintainers and dormant packages to inject credential-stealing payloads into dependency trees.', 'Dependency attacks remain high-impact because compromise can spread through trusted build and release pipelines.', 'Frontend and Node teams should tighten install policies, lockfile review, and package provenance checks.', 'Run dependency audit, rotate exposed tokens, and require review for lockfile-only changes.', 96, 5, 6],
            ['Workers AI inference now supports LoRA adapters', 'cloud', 'cloudflare-blog', 'Edge inference workflows can now load LoRA adapters for more specialized model behavior.', 'Smaller model customization at the edge can make AI features faster and cheaper for domain-specific use cases.', 'Teams building low-latency AI features should evaluate whether adapter-based customization removes a hosted inference dependency.', 'Prototype one narrow classification or generation task at the edge and compare latency against your current provider.', 74, 3, 2],
            ['Scaling GitHub Actions for large monorepos', 'blogs', 'the-github-engineering-blog', 'GitHub shared patterns for keeping CI fast and understandable as repository size and workflow count grow.', 'CI time quietly taxes every developer. Monorepo teams need disciplined caching, ownership, and workflow partitioning.', 'Large Laravel, mobile, or full-stack repositories can borrow scheduling and dependency graph ideas to reduce wasted jobs.', 'Identify your three slowest workflows and add ownership, cache metrics, and path-based triggering.', 71, 4, 2],
        ];

        $signals = collect($signalRows)->map(function (array $row, int $index) use ($categories, $sources) {
            [$title, $categorySlug, $sourceSlug, $summary, $why, $impact, $action, $priority, $readTime, $sourceCount] = $row;

            return Signal::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'category_id' => $categories[$categorySlug]->id,
                    'source_id' => $sources[$sourceSlug]?->id,
                    'title' => $title,
                    'summary' => $summary,
                    'why_it_matters' => $why,
                    'developer_impact' => $impact,
                    'recommended_action' => $action,
                    'priority_score' => $priority,
                    'read_time_minutes' => $readTime,
                    'source_count' => $sourceCount,
                    'published_at' => Carbon::today()->addHours(8)->subMinutes($index * 41),
                ],
            );
        });

        Briefing::updateOrCreate(
            ['briefing_date' => Carbon::today()->toDateString()],
            [
                'title' => 'Today’s Radar',
                'summary' => 'AI agent workflows, dependency security, and developer tooling produced the clearest priority signals today.',
                'reading_time_minutes' => 10,
                'priority_signal_count' => 3,
                'low_impact_filtered_count' => 39,
                'confidence_score' => 82,
            ],
        );

        $user = User::updateOrCreate(
            ['email' => 'builder@techcontextradar.test'],
            ['name' => 'Demo Builder', 'password' => Hash::make('password')],
        );

        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'briefing_length_minutes' => 10,
                'priority_threshold' => 70,
                'preferred_categories' => ['AI', 'DevTools', 'Frameworks', 'Cloud', 'Security'],
                'daily_reminder_enabled' => true,
                'priority_alerts_enabled' => true,
                'weekly_summary_enabled' => false,
                'compact_mode_enabled' => false,
                'dark_mode_enabled' => true,
            ],
        );

        $signals->take(4)->each(fn (Signal $signal) => SavedSignal::updateOrCreate(
            ['user_id' => $user->id, 'signal_id' => $signal->id],
            ['created_at' => now()->subHours($signal->id)],
        ));

        $signals->take(2)->each(fn (Signal $signal) => $user->readSignalRecords()->updateOrCreate(
            ['signal_id' => $signal->id],
            ['created_at' => now()->subMinutes($signal->id * 12)],
        ));
    }
}

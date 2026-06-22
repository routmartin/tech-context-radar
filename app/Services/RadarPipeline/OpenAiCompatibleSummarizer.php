<?php

namespace App\Services\RadarPipeline;

use App\Models\RawUpdate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class OpenAiCompatibleSummarizer implements AiSummarizer
{
    public function summarize(RawUpdate $update): AiSummary
    {
        $apiKey = config('radar.ai.api_key');

        if (! $apiKey) {
            throw new MissingAiConfigurationException('RADAR_AI_API_KEY is not configured.');
        }

        $payload = [
            'model' => config('radar.ai.model'),
            'temperature' => 0.2,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You summarize AI and developer technology news for busy senior developers. Return only valid compact JSON, with no markdown.',
                ],
                [
                    'role' => 'user',
                    'content' => $this->prompt($update),
                ],
            ],
        ];

        $response = Http::withToken($apiKey)
            ->timeout((int) config('radar.ai.timeout', 45))
            ->acceptJson()
            ->post(rtrim((string) config('radar.ai.base_url'), '/').'/chat/completions', $payload);

        if (! $response->successful()) {
            throw new RuntimeException("AI provider returned HTTP {$response->status()}");
        }

        $content = data_get($response->json(), 'choices.0.message.content');
        $data = json_decode((string) $content, true);

        if (! is_array($data)) {
            throw new RuntimeException('AI provider did not return valid JSON.');
        }

        $priorityScore = (int) ($data['priority_score'] ?? 50);
        $confidenceScore = (int) ($data['confidence_score'] ?? 70);

        if ($priorityScore > 0 && $priorityScore <= 10) {
            $priorityScore *= 10;
        }

        if ($confidenceScore > 0 && $confidenceScore <= 10) {
            $confidenceScore *= 10;
        }

        return new AiSummary(
            summary: Str::limit((string) ($data['summary'] ?? $update->excerpt ?? $update->title), 700),
            whyItMatters: Str::limit((string) ($data['why_it_matters'] ?? 'This may affect developer workflows or architecture choices.'), 700),
            developerImpact: Str::limit((string) ($data['developer_impact'] ?? 'Review whether this changes your stack, tooling, or delivery process.'), 700),
            recommendedAction: Str::limit((string) ($data['recommended_action'] ?? 'Read the source and decide whether it needs follow-up.'), 500),
            priorityScore: max(0, min(100, $priorityScore)),
            confidenceScore: max(0, min(100, $confidenceScore)),
        );
    }

    private function prompt(RawUpdate $update): string
    {
        return json_encode([
            'task' => 'Summarize this source update as a short developer intelligence signal.',
            'rules' => [
                'Be brief.',
                'Do not invent facts beyond the source text.',
                'Score priority by likely developer impact, not hype.',
                'Return keys: summary, why_it_matters, developer_impact, recommended_action, priority_score, confidence_score.',
                'priority_score and confidence_score must be integers from 0 to 100.',
            ],
            'source' => [
                'name' => $update->source?->name,
                'trust_level' => $update->source?->trust_level,
                'category' => $update->source?->category?->name,
            ],
            'update' => [
                'title' => $update->title,
                'url' => $update->url,
                'published_at' => $update->published_at?->toIso8601String(),
                'excerpt' => $update->excerpt,
                'content' => Str::limit((string) $update->raw_content, 5000),
            ],
        ], JSON_PRETTY_PRINT);
    }
}

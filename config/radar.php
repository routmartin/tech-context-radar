<?php

return [
    'focus_categories' => ['ai', 'devtools', 'frameworks', 'cloud'],

    'agent' => [
        'max_items' => (int) env('RADAR_AGENT_MAX_ITEMS', 25),
        'min_priority' => (int) env('RADAR_AGENT_MIN_PRIORITY', 70),
        'lookback_hours' => (int) env('RADAR_AGENT_LOOKBACK_HOURS', 72),
    ],

    'ai' => [
        'provider' => env('RADAR_AI_PROVIDER', 'openai_compatible'),
        'api_key' => env('RADAR_AI_API_KEY'),
        'base_url' => env('RADAR_AI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai/'),
        'model' => env('RADAR_AI_MODEL', 'gemini-3.1-flash-lite'),
        'timeout' => (int) env('RADAR_AI_TIMEOUT', 45),
    ],
];

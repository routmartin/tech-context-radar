<?php

namespace App\Services\RadarPipeline;

use Illuminate\Support\Carbon;

readonly class FeedItem
{
    public function __construct(
        public string $title,
        public string $url,
        public ?string $excerpt,
        public ?string $rawContent,
        public ?Carbon $publishedAt,
    ) {}
}

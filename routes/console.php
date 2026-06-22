<?php

use App\Jobs\GenerateDailyBriefingJob;
use App\Jobs\RankSignalsJob;
use App\Jobs\ScanFeedsJob;
use App\Jobs\SummarizeUpdatesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('radar:scan-feeds {--sync : Run immediately in the current process}', function () {
    $this->option('sync') ? ScanFeedsJob::dispatchSync() : ScanFeedsJob::dispatch();
    $this->info('Feed scan queued.');
})->purpose('Collect trusted AI and tech updates from enabled RSS feeds');

Artisan::command('radar:summarize-updates {--sync : Run immediately in the current process}', function () {
    $this->option('sync') ? SummarizeUpdatesJob::dispatchSync() : SummarizeUpdatesJob::dispatch();
    $this->info('Update summarization queued.');
})->purpose('Summarize collected updates with the configured AI provider');

Artisan::command('radar:rank-signals {--sync : Run immediately in the current process}', function () {
    $this->option('sync') ? RankSignalsJob::dispatchSync() : RankSignalsJob::dispatch();
    $this->info('Signal ranking queued.');
})->purpose('Promote high-priority summarized updates into radar signals');

Artisan::command('radar:generate-briefing {--sync : Run immediately in the current process}', function () {
    $this->option('sync') ? GenerateDailyBriefingJob::dispatchSync() : GenerateDailyBriefingJob::dispatch();
    $this->info('Daily briefing generation queued.');
})->purpose('Generate today’s radar briefing from current signals');

Artisan::command('radar:run-agent {--sync : Run immediately in the current process}', function () {
    if ($this->option('sync')) {
        ScanFeedsJob::dispatchSync();
        SummarizeUpdatesJob::dispatchSync();
        RankSignalsJob::dispatchSync();
        GenerateDailyBriefingJob::dispatchSync();
    } else {
        Bus::chain([
            new ScanFeedsJob,
            new SummarizeUpdatesJob,
            new RankSignalsJob,
            new GenerateDailyBriefingJob,
        ])->dispatch();
    }

    $this->info('Radar summarizer agent queued.');
})->purpose('Run the full AI summarizer agent pipeline');

Schedule::command('radar:run-agent')
    ->dailyAt('05:30')
    ->withoutOverlapping();

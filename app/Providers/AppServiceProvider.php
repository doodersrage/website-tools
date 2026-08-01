<?php

namespace App\Providers;

use App\Services\GoogleScraperService;
use App\Services\PageFetcher;
use App\Services\PageRankService;
use App\Services\ReportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PageFetcher::class, function () {
            return new PageFetcher(
                maxAttempts: config('sera.max_fetch_attempts'),
                timeout: config('sera.fetch_timeout'),
                proxies: config('sera.proxies'),
            );
        });

        $this->app->singleton(PageRankService::class);

        $this->app->singleton(GoogleScraperService::class, function ($app) {
            $dataCenters = config('sera.google_data_centers');
            $baseIp = $dataCenters[array_rand($dataCenters)];

            return new GoogleScraperService(
                fetcher: $app->make(PageFetcher::class),
                illegalSites: config('sera.illegal_sites'),
                searchBaseUrl: "http://{$baseIp}/search?",
                perPage: config('sera.per_page'),
                listing: config('sera.listing'),
            );
        });

        $this->app->singleton(ReportService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

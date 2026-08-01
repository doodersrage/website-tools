<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PageFetcher
{
    private int $attempt = 1;

    public function __construct(
        private readonly int $maxAttempts,
        private readonly int $timeout,
        /** @var list<string> */
        private readonly array $proxies,
    ) {}

    public function fetch(string $url): string
    {
        $this->attempt = 1;

        return $this->fetchWithRetry($url);
    }

    private function fetchWithRetry(string $url): string
    {
        $request = Http::timeout($this->timeout)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ]);

        if ($this->proxies !== []) {
            $proxy = $this->proxies[array_rand($this->proxies)];
            $request = $request->withOptions(['proxy' => $proxy]);
        }

        $response = $request->get($url);
        $body = trim($response->body());

        if ($this->attempt < $this->maxAttempts && ($body === '' || ! $response->successful())) {
            $this->attempt++;

            return $this->fetchWithRetry($url);
        }

        if ($body === '') {
            throw new RuntimeException("Unable to fetch URL: {$url}");
        }

        return $body;
    }
}

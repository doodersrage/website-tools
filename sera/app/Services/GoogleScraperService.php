<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

class GoogleScraperService
{
    private string $html = '';

    public function __construct(
        private readonly PageFetcher $fetcher,
        /** @var list<string> */
        private readonly array $illegalSites,
        private readonly string $searchBaseUrl,
        private readonly int $perPage,
        private readonly int $listing,
    ) {}

    /**
     * @return list<array{title: string, link: string}>
     */
    public function getDomainsList(string $searchTerm): array
    {
        $url = $this->searchBaseUrl.'q='.$searchTerm.'&num='.$this->perPage;
        $this->html = $this->fetcher->fetch($url);

        $results = [];

        foreach ($this->parseResultLinks($this->html) as $link) {
            $domain = $this->getDomain($link);

            if (! in_array($domain, $this->illegalSites, true)) {
                $results[] = [
                    'title' => $link,
                    'link' => $link,
                ];
            }
        }

        return $results;
    }

    /**
     * @return list<array{link: string}>
     */
    public function allInAnchor(string $searchTerm): array
    {
        $url = $this->searchBaseUrl.'q=allinanchor:'.$searchTerm.'&num='.$this->perPage;
        $this->html = $this->fetcher->fetch($url);

        $results = [];
        $previousDomain = '';

        foreach ($this->parseResultLinks($this->html) as $link) {
            $domain = $this->getDomain($link);

            if (in_array($domain, $this->illegalSites, true)) {
                continue;
            }

            if ($previousDomain !== $domain) {
                $results[] = ['link' => $link];
            }

            $previousDomain = $domain;
        }

        return $results;
    }

    public function inAnchor(string $searchTerm): int
    {
        $url = $this->searchBaseUrl.'q=inanchor:'.$searchTerm.'&num='.$this->listing;
        $this->html = $this->fetcher->fetch($url);

        return $this->resultCount();
    }

    public function getBacklinks(string $url): int
    {
        $searchUrl = $this->searchBaseUrl.'q=link:'.urlencode(trim($url)).'&num='.$this->listing;
        $this->html = $this->fetcher->fetch($searchUrl);

        return $this->resultCount();
    }

    /**
     * @return list<array{rank: int, link: string, pagerank: string, backlinks: int, anchor_info: string}>
     */
    public function processDomains(string $searchTerm, PageRankService $pageRank): array
    {
        $domains = $this->getDomainsList($searchTerm);
        $anchorResults = $this->allInAnchor($searchTerm);
        $rows = [];

        foreach ($domains as $index => $item) {
            $rank = $index + 1;
            $domain = $this->getDomain($item['link']);

            $anchorFind = 0;
            $anchorFindArr = [];

            foreach ($anchorResults as $curAnchor) {
                $anchorFind++;
                $anchorDomain = $this->getDomain($curAnchor['link']);

                if ($anchorDomain === $domain) {
                    $anchorFindArr[] = $anchorFind;
                }
            }

            $allInCount = count($anchorFindArr) > 0 ? $anchorFindArr[0] : $this->perPage;
            $partialCount = $this->inAnchor($item['link']);

            $rows[] = [
                'rank' => $rank,
                'link' => $item['link'],
                'pagerank' => $pageRank->getAssignedPageRank($item['link']),
                'backlinks' => $this->getBacklinks($item['link']),
                'anchor_info' => $allInCount.' / '.$partialCount,
            ];
        }

        return $rows;
    }

    public function getDomain(string $url): string
    {
        $url = str_replace(['http://', 'https://'], '', $url);
        $slashPos = strpos($url, '/');

        return $slashPos === false ? $url : substr($url, 0, $slashPos);
    }

    /**
     * @return list<string>
     */
    private function parseResultLinks(string $html): array
    {
        $crawler = new Crawler($html);
        $links = [];

        $crawler->filter('h3')->each(function (Crawler $node) use (&$links) {
            $anchor = $node->filter('a');

            if ($anchor->count() === 0) {
                return;
            }

            $href = trim($anchor->attr('href') ?? '');

            if ($href !== '') {
                $links[] = $href;
            }
        });

        if ($links === []) {
            $crawler->filter('a[href^="http"]')->each(function (Crawler $node) use (&$links) {
                $href = trim($node->attr('href') ?? '');

                if ($href !== '' && ! str_contains($href, 'google.com')) {
                    $links[] = $href;
                }
            });
        }

        return $links;
    }

    private function resultCount(): int
    {
        if (! str_contains($this->html, ' of about ') && ! str_contains($this->html, ' of ')) {
            return 0;
        }

        $useAboutFormat = str_contains($this->html, ' of about ');
        $crawler = new Crawler($this->html);

        if ($crawler->filter('#result-stats, p#resultStats')->count() === 0) {
            return 0;
        }

        $results = trim($crawler->filter('#result-stats, p#resultStats')->first()->text());
        $parts = explode(' ', $results);
        $index = $useAboutFormat ? 6 : 5;

        return isset($parts[$index]) ? (int) str_replace(',', '', $parts[$index]) : 0;
    }
}

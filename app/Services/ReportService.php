<?php

namespace App\Services;

class ReportService
{
    public function __construct(
        private readonly GoogleScraperService $scraper,
        private readonly PageRankService $pageRank,
    ) {}

    /**
     * @param  list<string>  $keywords
     * @return array{
     *     domain_row: array{rank: int, link: string, pagerank: string, backlinks: int|null, anchor_info: string|null}|null,
     *     keyword_rows: list<array{keyword: string, rows: list<array{rank: int, link: string, pagerank: string, backlinks: int, anchor_info: string}>}>
     * }
     */
    public function generate(string $domain, array $keywords, bool $exactSearch): array
    {
        $preparedKeywords = $this->prepareKeywords($keywords, $exactSearch);

        $domainRow = null;

        if ($domain !== '') {
            $domainRow = [
                'rank' => 1,
                'link' => $domain,
                'pagerank' => $this->pageRank->getAssignedPageRank($domain),
                'backlinks' => null,
                'anchor_info' => null,
            ];
        }

        $keywordRows = [];

        foreach ($preparedKeywords as $keyword) {
            $keywordRows[] = [
                'keyword' => $this->displayKeyword($keyword),
                'rows' => $this->scraper->processDomains($keyword, $this->pageRank),
            ];
        }

        return [
            'domain_row' => $domainRow,
            'keyword_rows' => $keywordRows,
        ];
    }

    /**
     * @param  list<string>  $keywords
     * @return list<string>
     */
    private function prepareKeywords(array $keywords, bool $exactSearch): array
    {
        $prepared = [];

        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);

            if ($keyword === '') {
                continue;
            }

            $encoded = urlencode($keyword);
            $prepared[] = $exactSearch ? '"'.$encoded.'"' : $encoded;
        }

        return $prepared;
    }

    private function displayKeyword(string $keyword): string
    {
        return strip_tags(str_replace(['%22', '+'], ['"', ' '], $keyword));
    }
}

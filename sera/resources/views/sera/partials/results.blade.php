@foreach ($report['keyword_rows'] as $keywordBlock)
    <div class="sera-keyword-block">
        <h2 class="sera-keyword-title">{{ $keywordBlock['keyword'] }}</h2>

        <div class="sera-table-wrap">
            <table class="tbl_results">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Link</th>
                        <th class="hdr-ste-2">PR</th>
                        <th class="hdr-ste-3">Backlinks</th>
                        <th class="hdr-ste-4">All-in / Partial</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($loop->first && $report['domain_row'])
                        <tr class="sera-domain-row">
                            <td>{{ $report['domain_row']['rank'] }}</td>
                            <td class="sera-link-cell">{{ $report['domain_row']['link'] }}</td>
                            <td>{{ $report['domain_row']['pagerank'] }}</td>
                            <td>—</td>
                            <td>—</td>
                        </tr>
                    @endif

                    @forelse ($keywordBlock['rows'] as $row)
                        <tr>
                            <td>{{ $row['rank'] }}</td>
                            <td class="sera-link-cell"><a href="{{ $row['link'] }}" target="_blank" rel="noopener">{{ $row['link'] }}</a></td>
                            <td>{{ $row['pagerank'] }}</td>
                            <td>{{ number_format($row['backlinks']) }}</td>
                            <td>{{ $row['anchor_info'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="sera-empty">No results found for this keyword.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endforeach

<div class="sera-print">
    <button type="button" class="sera-btn-link" onclick="window.print()">Print results</button>
</div>

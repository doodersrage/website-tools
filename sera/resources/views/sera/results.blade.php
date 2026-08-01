@extends('sera.layout')

@section('title', 'Report Generated')

@section('content')
    <div class="sera-results-header">
        @if($elapsed)
            <p>Total search time: {{ $elapsed }}</p>
        @endif
        <a href="{{ route('sera.index') }}" class="sera-btn sera-btn-secondary">Generate Another Report</a>
    </div>

    @include('sera.partials.results', ['report' => $report])
@endsection

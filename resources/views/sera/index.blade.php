<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CM Sera Tool</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="sera-body">
    <main class="sera-container">
        <header class="sera-header">
            <h1>CM Sera Tool</h1>
            <p class="sera-subtitle">Keyword competitiveness analysis for SEO research</p>
        </header>

        <section id="form-area" class="sera-card">
            <form id="sera-form" method="post" action="{{ route('sera.generate') }}">
                @csrf

                <div class="sera-field">
                    <label for="domain">Domain</label>
                    <input type="text" id="domain" name="domain" value="{{ $domain }}" placeholder="example.com">
                </div>

                <div class="sera-field">
                    <label for="keywords">Keywords</label>
                    <textarea id="keywords" name="keywords" rows="7" required>{{ $keywords }}</textarea>
                    <p class="sera-hint">One keyword per line</p>
                </div>

                <div class="sera-field sera-field-inline">
                    <input type="checkbox" id="exact_srch" name="exact_srch" value="1" @checked($exactSearch)>
                    <label for="exact_srch">Exact search (wrap terms in quotes)</label>
                </div>

                <div class="sera-field">
                    <label for="email_address">Email address <span class="sera-optional">(optional)</span></label>
                    <input type="email" id="email_address" name="email_address" value="{{ $emailAddress }}" placeholder="you@company.com">
                </div>

                <div class="sera-actions">
                    <button type="submit" id="submit-btn" class="sera-btn">Generate Report</button>
                </div>
            </form>
        </section>

        <section id="status-area" class="sera-status hidden" aria-live="polite">
            <div class="sera-spinner" role="status"></div>
            <p id="status-text">Performing requested search…</p>
            <p id="timer" class="sera-timer">00 : 00</p>
        </section>

        <section id="results-area" class="sera-results"></section>
    </main>
</body>
</html>

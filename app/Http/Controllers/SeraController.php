<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateReportRequest;
use App\Mail\ReportResults;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SeraController extends Controller
{
    public function index(Request $request): View
    {
        return view('sera.index', [
            'domain' => old('domain', session('domain', '')),
            'keywords' => old('keywords', session('keywords', "blue thursday monkey\nnorfolk insurance\nsearch engine optimization")),
            'exactSearch' => old('exact_srch', session('exact_srch', false)),
            'emailAddress' => old('email_address', session('email_address', '')),
        ]);
    }

    public function generate(GenerateReportRequest $request, ReportService $reportService)
    {
        $validated = $request->validated();

        session([
            'domain' => $validated['domain'],
            'keywords' => $validated['keywords'],
            'exact_srch' => $validated['exact_srch'],
            'email_address' => $validated['email_address'] ?? '',
        ]);

        $keywords = preg_split('/\r\n|\r|\n/', $validated['keywords']) ?: [];

        $report = $reportService->generate(
            $validated['domain'],
            $keywords,
            $validated['exact_srch'],
        );

        $resultsHtml = view('sera.partials.results', compact('report'))->render();

        if (! empty($validated['email_address'])) {
            Mail::to($validated['email_address'])->send(new ReportResults($resultsHtml));
        }

        if ($request->ajax()) {
            return response()->view('sera.partials.ajax-response', [
                'resultsHtml' => $resultsHtml,
            ]);
        }

        return view('sera.results', [
            'report' => $report,
            'elapsed' => null,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function show($report)
    {
        $validated = request()->validate([
            'name' => 'required',
            'year' => 'sometimes|nullable|numeric',
            'begin_date' => 'sometimes|nullable|date',
            'end_date' => 'sometimes|nullable|date',
        ]);

        abort_unless($report = $this->reportAvailable($report), 404);

        try {
            $resolved = app()->makeWith(
                "\\App\Exports\\Reports\\{$report}\\{$report}Export",
                ['options' => $validated]
            );
        } catch (Exception $e) {
            abort(404, 'Cannot instantiate report');
        }

        return Excel::download($resolved, "{$report}.xlsx");
    }



    private function reportAvailable($requestedReport)
    {
        return $this->availableReports()->first(function ($availableReport) use ($requestedReport) {
            return Str::lower($availableReport) == Str::lower($requestedReport);
        });
    }

    private function availableReports()
    {
        return Cache::remember('reports', 3600, function () {
            $reportsFolder = app_path() . '/Exports/Reports';

            return collect(resolve(Filesystem::class)->directories($reportsFolder))
                ->map(function ($directory) {
                    return resolve(FileSystem::class)->name($directory);
                });
        });
    }
}

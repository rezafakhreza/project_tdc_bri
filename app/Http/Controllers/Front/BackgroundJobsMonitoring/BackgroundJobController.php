<?php

namespace App\Http\Controllers\Front\BackgroundJobsMonitoring;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BackgroundJobsMonitoring\Process;
use App\Models\BackgroundJobsMonitoring\BackgroundJob;

class BackgroundJobController extends Controller
{
    public function daily()
    {
        return view('front.background-jobs-monitoring.background-jobs-daily');
    }

    // Get data for daily background jobs monitoring
    private function getFormattedData($data, $month, $year)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $formattedData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%s-%s-%s', $year, str_pad($month, 2, '0', STR_PAD_LEFT), str_pad($day, 2, '0', STR_PAD_LEFT));
            $formattedData[$date] = [];
        }

        foreach ($data as $datum) {
            $formattedData[$datum->execution_date][$datum->process->name] = [
                'status' => $datum->status,
                'notes' => $datum->notes,
            ];
        }

        return $formattedData;
    }


    public function getBackgroundJobs(Request $request)
    {
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        // Mengambil data untuk bulan yang dipilih dan bulan-bulan sebelumnya dalam tahun yang sama
        $startMonth = 1; // Januari
        $startYear = $year;
        $endMonth = $month;
        $endYear = $year;

        $type1Data = $this->getDataForMonthRange('Product', $startMonth, $startYear, $endMonth, $endYear);
        $type2Data = $this->getDataForMonthRange('Non-Product', $startMonth, $startYear, $endMonth, $endYear);

        return response()->json([
            'type1' => ['processes' => $type1Data],
            'type2' => ['processes' => $type2Data]
        ]);
    }

    private function getDataForMonthRange($type, $startMonth, $startYear, $endMonth, $endYear)
    {
        $formattedData = [];

        while ($startYear < $endYear || ($startYear == $endYear && $startMonth <= $endMonth)) {
            $data = $this->getFormattedData(
                BackgroundJob::with('process')
                    ->where('type', $type)
                    ->whereYear('execution_date', $startYear)
                    ->whereMonth('execution_date', $startMonth)
                    ->get(),
                $startMonth,
                $startYear
            );

            $formattedData = array_merge($formattedData, $data);

            if ($startMonth == 12) {
                $startMonth = 1;
                $startYear++;
            } else {
                $startMonth++;
            }
        }

        return $formattedData;
    }


    public function showDataAmountCharts(Request $request)
    {
        $mode = 'date'; // Tetapkan mode default ke 'date'
        $chosenMonth = $request->input('month', date('m'));
        $chosenYear = $request->input('year', date('Y'));

        $processes = Process::all();

        $allChartData = [];

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $chosenMonth, $chosenYear);
        $labels = range(1, $daysInMonth);
        foreach ($processes as $process) {
            $s4glAmounts = array_fill(0, $daysInMonth, 0);
            $eimAmounts = array_fill(0, $daysInMonth, 0);

            $results = $process->backgroundJobs()
                ->whereYear('execution_date', $chosenYear)
                ->whereMonth('execution_date', $chosenMonth)
                ->orderBy('execution_date', 'asc')
                ->get();

            foreach ($results as $result) {
                $executionDate = is_string($result->execution_date) ? new \DateTime($result->execution_date) : $result->execution_date;
                $day = (int) $executionDate->format('d') - 1;
                $s4glAmounts[$day] = $result->data_amount_to_S4GL;
                $eimAmounts[$day] = $result->data_amount_to_EIM;
            }

            $allChartData[$process->name] = [
                'labels' => $labels,
                's4glAmounts' => $s4glAmounts,
                'eimAmounts' => $eimAmounts
            ];
        }

        return view('front.background-jobs-monitoring.background-jobs-data-amount', [
            'allChartData' => $allChartData,
            'mode' => $mode,
            'chosenMonth' => $chosenMonth,
            'chosenYear' => $chosenYear
        ]);
    }



    public function showDurationCharts(Request $request)
    {
        $mode = 'date'; // Tetapkan mode default ke 'date'
        $chosenMonth = $request->input('month', date('m'));
        $chosenYear = $request->input('year', date('Y'));

        $processes = Process::all();

        $allChartData = [];

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $chosenMonth, $chosenYear);
        $labels = range(1, $daysInMonth);
        foreach ($processes as $process) {
            $durationsEIM = array_fill(0, $daysInMonth, 0);
            $durationsS4GL = array_fill(0, $daysInMonth, 0);

            $results = $process->backgroundJobs()
                ->whereYear('execution_date', $chosenYear)
                ->whereMonth('execution_date', $chosenMonth)
                ->orderBy('execution_date', 'asc')
                ->get();

            foreach ($results as $result) {
                $executionDate = is_string($result->execution_date) ? new \DateTime($result->execution_date) : $result->execution_date;
                $day = (int) $executionDate->format('d') - 1;
                $durationsEIM[$day] += $result->duration_to_EIM;
                $durationsS4GL[$day] += $result->duration_to_S4GL;
            }

            $allChartData[$process->name] = [
                'labels' => $labels,
                'durationsEIM' => $durationsEIM,
                'durationsS4GL' => $durationsS4GL
            ];
        }

        return view('front.background-jobs-monitoring.background-jobs-duration', [
            'allChartData' => $allChartData,
            'mode' => $mode,
            'chosenMonth' => $chosenMonth,
            'chosenYear' => $chosenYear
        ]);
    }

}
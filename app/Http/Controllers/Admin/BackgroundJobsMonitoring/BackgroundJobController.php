<?php

namespace App\Http\Controllers\Admin\BackgroundJobsMonitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\BackgroundJobsMonitoring\Process;
use App\Models\BackgroundJobsMonitoring\BackgroundJob;

class BackgroundJobController extends Controller
{
    /**
     * List all background jobs. If request is ajax, return datatables.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = BackgroundJob::with('process')->select('bjm_background_jobs.*');

            return DataTables::of($query)
                ->addColumn('updated_at', function ($job) {
                    return $job->updated_at->format('d F Y H:i:s'); // Format the date as needed
                })
                ->addColumn('action', function ($jobs) {
                    return '
                        <div class="flex gap-2">
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                            href="' . route('admin.background-jobs-monitoring.jobs.edit', $jobs->id) . '">
                            <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="edit" class="mx-auto svg-inline--fa fa-edit fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zM20.5 510.3c-3.2 3.2-8.4 3.2-11.6 0L3.8 498.6c-3.2-3.2-3.2-8.4 0-11.6l47.3-47.3 61.1 61.1-47.1 47.3zm0 0"></path></svg>
                        </a>
                        <form class="block w-full" action="' . route('admin.background-jobs-monitoring.jobs.destroy', $jobs->id) . '" method="POST">
                            <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none btn-delete ease hover:bg-red-600 focus:outline-none focus:shadow-outline">
                                <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="trash" class="mx-auto svg-inline--fa fa-trash fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12zM432 80h-82.41l-34-56.7A48 48 0 0 0 274.41 0H173.59a48 48 0 0 0-40.59 23.3L99 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h304a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0-16-16zM171.84 50.91A6 6 0 0 1 177 48h94a6 6 0 0 1 5.15 2.91L293.61 80H154.39zM368 464H80V128h288zm-216-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12z"></path></svg>
                            </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('admin.background-jobs-monitoring.background-jobs.index');
    }

    /**
     * Show the form to create a new background job.
     */
    public function create()
    {
        $processes = Process::all();

        return view('admin.background-jobs-monitoring.background-jobs.create', compact('processes'));
    }

    /**
     * Store a new background job.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'process_id' => 'required|exists:bjm_processes,id',
            'data_amount_to_EIM' => 'required|numeric',
            'data_amount_to_S4GL' => 'required|numeric',
            'status' => 'required|in:Normal Run,Rerun Background Job,Manual Run Background Job,Pending',
            'duration_to_EIM' => 'required|integer',
            'duration_to_S4GL' => 'required|integer',
            'execution_date' => 'required|date',
        ]);

        // Check if background job already exists in that date
        $existingJob = BackgroundJob::where('process_id', $request->process_id)
            ->where('execution_date', $request->execution_date)
            ->first();

        if ($existingJob) {

            $request->session()->put('existingJobId', $existingJob->id);

            return redirect()->back()->withErrors(['error' => 'Background job already exists in that date. Would you like to edit it?']);
        }

        BackgroundJob::create($request->only([
            'type',
            'process_id',
            'data_amount_to_EIM',
            'data_amount_to_S4GL',
            'status',
            'duration_to_EIM',
            'duration_to_S4GL',
            'notes',
            'execution_date',
        ]));

        return redirect()->route('admin.background-jobs-monitoring.jobs.index')->with('success', 'Background job created.');
    }

    /**
     * Show the form to edit a background job.
     */
    public function edit(string $id)
    {
        $job = BackgroundJob::findOrFail($id);
        $processes = Process::all();

        return view('admin.background-jobs-monitoring.background-jobs.edit', compact('job', 'processes'));
    }

    /**
     * Update an existing background job.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type' => 'required|string',
            'process_id' => 'required|exists:bjm_processes,id',
            'data_amount_to_EIM' => 'required|numeric',
            'data_amount_to_S4GL' => 'required|numeric',
            'status' => 'required|in:Normal Run,Rerun Background Job,Manual Run Background Job,Pending',
            'duration_to_EIM' => 'required|integer',
            'duration_to_S4GL' => 'required|integer',
            'execution_date' => 'required|date',
        ]);

        $job = BackgroundJob::findOrFail($id);

        $job->update($request->only([
            'type',
            'process_id',
            'data_amount_to_EIM',
            'data_amount_to_S4GL',
            'status',
            'duration_to_EIM',
            'duration_to_S4GL',
            'notes',
            'execution_date',
        ]));

        return redirect()->route('admin.background-jobs-monitoring.jobs.index')->with('success', 'Background job updated.');
    }

    /**
     * Delete an existing background job.
     */
    public function destroy(string $id)
    {
        $job = BackgroundJob::findOrFail($id);
        $job->delete();

        return redirect()->route('admin.background-jobs-monitoring.jobs.index')->with('success', 'Background job deleted.');
    }

    /**
     * Get processes by type for form select input.
     */
    public function getProcessesByType(Request $request)
    {
        $type = $request->input('type');
        $currentProcessId = $request->input('currentProcessId');

        $processes = Process::where(function ($query) use ($type, $currentProcessId) {
            $query->where('type', $type)
                ->where('is_active', true);
            if ($currentProcessId) {
                $query->orWhere('id', $currentProcessId);
            }
        })
            ->get();

        return response()->json($processes);
    }
}

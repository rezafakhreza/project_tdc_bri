<?php

namespace App\Http\Controllers\Admin\Deployment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Deployment\Deployment;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Deployment\DeploymentModule;
use App\Models\Deployment\DeploymentServerType;
use App\Models\Deployment\DeploymentCMStatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeploymentController extends Controller
{
    /**
     * List of all deployments. if request is ajax, return datatables.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Deployment::with(['module', 'serverType', 'cmStatus']);

            return DataTables::of($query)
                ->addColumn('module', function ($deployment) {
                    $moduleNames = $deployment->module()->pluck('name')->implode(', ');
                    return $moduleNames;
                })
                ->addColumn('server_type', function ($deployment) {
                    $serverTypeNames = $deployment->serverType()->pluck('name')->implode(', ');
                    return $serverTypeNames;
                })

                ->addColumn('cm_status_id', function ($deployment) {
                    return $deployment->cmStatus ? $deployment->cmStatus->cm_status_name : 'N/A';
                })

                ->addColumn('cm_status_color', function ($deployment) {
                    return $deployment->cmStatus ? $deployment->cmStatus->colour : 'N/A';
                })
                
                ->addColumn('updated_at', function ($deployment) {
                    $latestUpdate = null;
                
                    // Cek apakah ada module terkait pada deployment
                    if ($deployment->module->isNotEmpty()) {
                        foreach ($deployment->module as $module) {
                            if ($module->pivot->updated_at > $latestUpdate) {
                                $latestUpdate = $module->pivot->updated_at;
                            }
                        }
                    }
                    // Cek apakah ada serverType terkait pada deployment
                    if ($deployment->serverType->isNotEmpty()) {
                        foreach ($deployment->serverType as $serverType) {
                            if ($serverType->pivot->updated_at > $latestUpdate) {
                                $latestUpdate = $serverType->pivot->updated_at;
                            }
                        }
                    }
                    // Bandingkan dengan updated_at pada deployment itu sendiri
                    if ($deployment->updated_at > $latestUpdate) {
                        $latestUpdate = $deployment->updated_at;
                    }
                    // Jika ditemukan updated_at terbaru, parse menggunakan Carbon dan format
                    if ($latestUpdate) {
                        $latestUpdate = Carbon::parse($latestUpdate);
                        return $latestUpdate->format('d F Y H:i:s');
                    }
                
                    return '';
                })
            
                ->addColumn('action', function ($deployment) {
                    return '
                        <div class="flex gap-2">
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                            href="' . route('admin.deployments.deployment.edit', $deployment->id) . '">
                            <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="edit" class="mx-auto svg-inline--fa fa-edit fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zM20.5 510.3c-3.2 3.2-8.4 3.2-11.6 0L3.8 498.6c-3.2-3.2-3.2-8.4 0-11.6l47.3-47.3 61.1 61.1-47.1 47.3zm0 0"></path></svg>
                        </a>
                        <form class="block w-full" action="' . route('admin.deployments.deployment.destroy', $deployment->id) . '" method="POST">
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

    
        return view('admin.deployment.deployments.index');
    }

    /**
     * Show the form to create a new deployment.
     */
    
    public function create()
    {
        $modules = DeploymentModule::where('is_active', 1)->get();
        $serverTypes = DeploymentServerType::where('is_active', 1)->get();
        $cmStatuses = DeploymentCMStatus::all();

        return view('admin.deployment.deployments.create', compact('modules', 'serverTypes', 'cmStatuses'));
    }

    /**
     * Store a new deployment.
     */
    public function store(Request $request)
    {
        if (Deployment::where('title', $request->title)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Deployment already exists. Please choose another title.');
        }
        // ini untuk mengubah id
        $title = $request->input('title');
        $deploy_date = $request->input('deploy_date');
        $id = substr(str_replace(' ', '', $title), 9, 5) . str_replace('-', '', $deploy_date);

        $request->merge(['id' => $id]);

        $data = Deployment::create([
            'id' => $id,
            'title' => $request->input('title'),
            'deploy_date' => $request->input('deploy_date'),
            'document_status' => $request->input('document_status'),
            'document_description' => $request->input('document_description'),
            'cm_status_id' => $request->input('cm_status_id'),
            'cm_description' => $request->input('cm_description'),
        ]);

        $data->module()->attach($request->input('module_id'));
        $data->serverType()->attach($request->input('server_type_id'));


        return redirect()->route('admin.deployments.deployment.index')
            ->with('success', 'Success Create Deployment');
    }

    /**
     * Show the form to edit a deployment.
     */


    public function edit(Deployment $deployment)
    {
        $id = $deployment->id;
        $module = $deployment->module;
        $serverType = $deployment->serverType;
        $cmStatus = $deployment->cmStatus;
        $cmStatuses = DeploymentCMStatus::all();
        $modules = DeploymentModule::where('is_active', 1)->get();
        $serverTypes = DeploymentServerType::where('is_active', 1)->get();
        
        

        return view('admin.deployment.deployments.edit', compact('id', 'deployment', 'modules', 'serverTypes', 'cmStatuses'));
    }

    public function update(Request $request, Deployment $deployment)
    {

        if ($deployment->title != $request->title) {
            if (Deployment::where('title', $request->title)->first()) {
                return redirect()->back()->withInput()->with('error', 'Deployment already exists.');
            }
        }

        $deployment->update([
            'title' => $request->input('title'),
            'deploy_date' => $request->input('deploy_date'),
            'document_status' => $request->input('document_status'),
            'document_description' => $request->input('document_description'),
            'cm_status_id' => $request->input('cm_status_id'),
            'cm_description' => $request->input('cm_description'),
        ]);

        $deployment->module()->sync($request->input('module_id'));
        $deployment->serverType()->sync($request->input('server_type_id'));

        return redirect()->route('admin.deployments.deployment.index')->with('success', 'Deployment updated successfully.');
    }
    /**
     * Delete a deployment.
     */
    public function destroy($idDeploy)
    {
        $deployment = Deployment::findOrFail($idDeploy);
        $deployment->delete();

        return redirect()->route('admin.deployments.deployment.index')->with('success', 'Deployment deleted successfully.');
    }

    /**
     * Get server types by module id.
     */

    public function jabar(Request $request)
    {
        if ($request->has('module_id')) {
            $moduleIds = $request->input('module_id');
            $data = DB::table('deployment_modules')->whereIn('id', $moduleIds)->get();

            $hasil = '';
            foreach ($data as $value) {
                $hasil .= '<option value="' . $value->name . '">' . $value->id . '/' . $value->name . '</option>';
            }
            echo $hasil;
        }
    }
}

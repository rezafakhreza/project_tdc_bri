<?php

namespace App\Http\Controllers\Front\Deployment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Deployment\Deployment;
use App\Models\Deployment\DeploymentModule;
use App\Models\Deployment\DeploymentServerType;
use Illuminate\Support\Facades\Log;

class DeploymentController extends Controller
{
    public function index()
    {
        $modules = DeploymentModule::all();
        return view('front.deployment.deployments-chart', compact('modules'));
    }

    public function calendar()
    {
        return view('front.deployment.deployments-calendar');
    }

    public function chartServerType()
    {
        $serverTypes = DeploymentServerType::all();
        return view('front.deployment.deployments-chart-server-type', compact('serverTypes'));
    }

    /**
     * Get events for calendar
     */
    public function getEvents()
    {
        $deployments = Deployment::all();
        $events = [];

        foreach ($deployments as $deployment) {
            
            $moduleNames = $deployment->module()->pluck('name')->implode(', ');
            $serverTypeNames = $deployment->serverType()->pluck('name')->implode(', ');

            $events[] = [
                'id' => $deployment->id,
                'title' => $deployment->title,
                'start' => $deployment->deploy_date,
                'module' => $moduleNames,
                'server_type' => $serverTypeNames,
                'status_doc' => $deployment->document_status,
                'document_description' => $deployment->document_description,
                'status_cm' => $deployment->cmStatus->cm_status_name,
                'color' =>  $deployment->cmStatus->colour,
                'cm_description' => $deployment->cm_description,
                
            ];

            
        }

        return response()->json($events);
    }

    /**
     * Get chart data
     */
    public function getChartData(Request $request)
    {
        $module_id = $request->input('module_id');
        $year = $request->input('year', date('Y'));

        $data = DB::table('deployments')
        ->select(DB::raw('MONTH(deployments.deploy_date) as month'), 'deployment_modules.name as module','deployment_server_types.name as server_type', DB::raw('COUNT(*) as count'))
        ->join('deployment_has_module', 'deployment_has_module.deployment_id', '=', 'deployments.id')
        ->join('deployment_has_server_type', 'deployment_has_server_type.deployment_id', '=', 'deployments.id')
        ->join('deployment_server_types', 'deployment_has_server_type.server_type_id', '=', 'deployment_server_types.id')
        ->join('deployment_modules', 'deployment_has_module.module_id', '=', 'deployment_modules.id')
        ->where('deployment_modules.id', $module_id)
        ->whereYear('deployments.deploy_date', $year)
        ->groupBy(DB::raw('MONTH(deployments.deploy_date)'), 'deployment_modules.name', 'deployment_server_types.name')
        ->get();

        return response()->json($data);
    }
    public function getChartDataServer(Request $request)
    {
        
        $server_type_id = $request->input('server_type_id');
        $year = $request->input('year', date('Y'));

        $data = DB::table('deployments')
        ->select(DB::raw('MONTH(deployments.deploy_date) as month'), 'deployment_server_types.name as server_type', 'deployment_modules.name as module', DB::raw('COUNT(*) as count'))
        ->join('deployment_has_module', 'deployment_has_module.deployment_id', '=', 'deployments.id')
        ->join('deployment_has_server_type', 'deployment_has_server_type.deployment_id', '=', 'deployments.id')
        ->join('deployment_server_types', 'deployment_has_server_type.server_type_id', '=', 'deployment_server_types.id')
        ->join('deployment_modules', 'deployment_has_module.module_id', '=', 'deployment_modules.id')
        ->where('deployment_server_types.id', $server_type_id)
        ->whereYear('deployments.deploy_date', $year)
        ->groupBy(DB::raw('MONTH(deployments.deploy_date)'), 'deployment_modules.name', 'deployment_server_types.name')
        ->get();

        return response()->json($data);

    }
}
        
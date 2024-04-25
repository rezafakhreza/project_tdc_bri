<?php

namespace App\Http\Controllers\Admin\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserManagement\Incident;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\UserManagement\IncidentsImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class IncidentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = DB::table('usman_incident')
                ->leftJoin('usman_branch', 'usman_incident.branch_code', '=', 'usman_branch.branch_code')
                ->select('usman_incident.id', 'usman_incident.reported_date', 'usman_incident.pn', 'usman_incident.nama', 'usman_incident.jabatan', 'usman_incident.bagian', 'usman_incident.req_type', 'usman_branch.branch_name', 'usman_branch.kanwil_name', 'usman_incident.req_status', 'usman_incident.exec_status', 'usman_incident.execution_date', 'usman_incident.sla_category')
                ->get();

            return DataTables::of($query)
                ->addColumn('branch_name', function ($row) {
                    return $row->branch_name;
                })
                ->addColumn('kanwil_name', function ($row) {
                    return $row->kanwil_name;
                })
                ->addColumn('req_type', function ($row) {
                    return $row->req_type;
                })
                ->rawColumns(['branch_name', 'req_type'])
                ->make(true);
        } //tinggal masukkin else biar bisa detek soalnya ini masih yg kondisi berhasil aja jd semua kemungkinan bisa muncul popup berhasil tp ada pesan error dari website

        return view('admin.user-management.incidents.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user-management.incidents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'File is required',
            'file.mimes' => 'File must be an Excel document',
        ]);

        $file = $request->file('file');
        $namaFile = $file->getClientOriginalName();

        // Memeriksa apakah file dengan nama yang sama sudah ada
        if (file_exists(public_path('/DataImport/'.$namaFile))) {
            // Menampilkan pesan konfirmasi untuk menimpa file (ini masih gangaruh)
            if ($request->has('overwrite') && $request->overwrite == 'true') {
                // Jika konfirmasi dilakukan, hapus file lama
                unlink(public_path('/DataImport/'.$namaFile));
                // Hapus semua insiden
                Incident::truncate();

            } else {
                // Jika tidak ingin menimpa, kembalikan dengan pesan error
                return redirect()->back()->withErrors(['file' => 'File with the same name already exists.']);
            }
        }

        // Pindahkan file baru ke direktori tujuan
        $file->move('DataImport', $namaFile);

        $branchCheck = DB::table('usman_branch')->count();
        if ($branchCheck == 0) {
            // Jika tidak ada data branch, hapus file yang sudah diunggah
            unlink(public_path('/DataImport/' . $namaFile));
            return redirect()->back()->withErrors(['file' => 'Masukkan data Branch terlebih dahulu.']);
        }

        // Import data dari file baru
        Excel::import(new IncidentsImport, public_path('/DataImport/' . $namaFile));
        return redirect()->route('admin.user-management.incidents.index')
            ->with('success', 'Incidents imported successfully');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */



     public function destroy(){
        
        // Temukan semua data incident
        $incidents = Incident::all();
    
        // Periksa apakah ada data incident yang ditemukan
        if ($incidents->isEmpty()) {
            return redirect()->route('admin.user-management.incidents.index')
                ->with('error', 'No incidents found to delete');
        }
    
        // Loop untuk menghapus file yang terkait jika ada (ini masih gangaruh)
        foreach ($incidents as $incident) {
            if (!empty($incident->file_path)) {
                // Hapus file dari storage
                Storage::delete('DataImport/'.$incident->file_path);
            }
        }

        // Hapus semua data incident dari database
        Incident::truncate();
    
        // Redirect dengan pesan sukses
        return redirect()->route('admin.user-management.incidents.index')
            ->with('success', 'All incidents deleted successfully');
    }
    
    



}
<?php

namespace App\Http\Controllers\Admin\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserManagement\Branch;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\UserManagement\BranchImport;
use Illuminate\Support\Facades\Storage;
// use DB;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = DB::table('usman_branch')
                ->select('usman_branch.branch_code', 'usman_branch.branch_name', 'usman_branch.kanwil_code', 'usman_branch.kanwil_name')
                ->get();

            return DataTables::of($query)
                ->make();
        }
        return view('admin.user-management.branch.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user-management.branch.create');
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
        if (file_exists(public_path('/DataImport/' . $namaFile))) {
            // Menampilkan pesan konfirmasi untuk menimpa file
            if ($request->has('overwrite') && $request->overwrite == 'true') {
                // Jika konfirmasi dilakukan, hapus file lama
                unlink(public_path('/DataImport/' . $namaFile));

                // Hapus semua insiden
                Branch::truncate();
            } else {
                // Jika tidak ingin menimpa, kembalikan dengan pesan error
                return redirect()->back()->withErrors(['file' => 'File with the same name already exists.']);
            }
        }

        // Pindahkan file baru ke direktori tujuan
        $file->move('DataImport', $namaFile);



        // Import data dari file baru
        Excel::import(new BranchImport, public_path('/DataImport/' . $namaFile));
        return redirect()->route('admin.user-management.branch.index')
            ->with('success', 'Branch imported successfully');
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
    public function destroy(string $id)
    {
        // Temukan semua data incident
        $branch = Branch::all();

        // Periksa apakah ada data incident yang ditemukan
        if ($branch->isEmpty()) {
            return redirect()->route('admin.user-management.incidents.index')
                ->with('error', 'No incidents found to delete');
        }

        // Loop untuk menghapus file yang terkait jika ada (ini masih gangaruh)
        foreach ($branch as $branchs) {
            if (!empty($branchs->file_path)) {
                // Hapus file dari storage
                Storage::delete('DataImport/' . $branchs->file_path);
            }
        }

        // Hapus semua data incident dari database
        Branch::truncate();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.user-management.incidents.index')
            ->with('success', 'All incidents deleted successfully');
    }
}

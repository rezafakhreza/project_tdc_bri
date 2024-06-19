<?php

namespace App\Http\Controllers\Admin\Brisol;

use Illuminate\Http\Request;
use App\Models\Brisol\IncidentBrisol;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Brisol\IncidentsImport;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class IncidentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = IncidentBrisol::with(['branch']);

            return DataTables::of($query)
                ->addColumn('branch_code', function ($incident){
                    return $incident->branch ? $incident->branch->branch_code : 'N/A';
                })

                ->addColumn('branch_code', function ($incident){
                    return $incident->branch ? $incident->branch->kanwil_name : 'N/A';
                })
                
                ->make();
        }
        return view('admin.brisol.incidents.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brisol.incidents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ], [
            'file.required' => 'File is required',
            'file.mimes' => 'File must be an Excel document',
        ]);

        $file = $request->file('file');
        $namaFile = $file->getClientOriginalName();
        $filePath = public_path('/DataImport/' . $namaFile);

        try {
            // Memeriksa apakah file dengan nama yang sama sudah ada
            if (file_exists($filePath)) {
                // Menampilkan pesan konfirmasi untuk menimpa file
                if ($request->has('overwrite') && $request->overwrite == 'true') {
                    // Jika konfirmasi dilakukan, hapus file lama
                    unlink($filePath);
                    // Hapus semua insiden
                    IncidentBrisol::truncate();
                } else {
                    // Jika tidak ingin menimpa, kembalikan dengan pesan error
                    return redirect()->back()->withErrors(['file' => 'File with the same name already exists.']);
                }
            }
            // Pindahkan file baru ke direktori tujuan
            $file->move('DataImport', $namaFile);
            Excel::import(new IncidentsImport, public_path('/DataImport/' . $namaFile));
            return redirect()->route('admin.brisol.incidents.index')->with('success', 'Incidents imported successfully');

        } catch (QueryException $e) {
            // Jika terjadi kesalahan, kembalikan dengan pesan error
            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file jika sudah dipindahkan ke direktori tujuan
            }
            return redirect()->back()->with('error', 'Database error'. $e->getMessage());

        } catch (\Exception $e) {
            // Tangani kesalahan umum
            // Jika terjadi kesalahan, kembalikan dengan pesan error
            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file jika sudah dipindahkan ke direktori tujuan
            }
            return redirect()->back()->with('error', 'The data does not match to the template: ' . $e->getMessage());
        }
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
        $incidents = IncidentBrisol::all();

        // Periksa apakah ada data incident yang ditemukan
        if ($incidents->isEmpty()) {
            return redirect()->route('admin.user-management.incidents.index')
                ->with('error', 'No incidents found to delete');
        }

        // Loop untuk menghapus file yang terkait jika ada (ini masih gangaruh)
        foreach ($incidents as $incident) {
            if (!empty($incident->file_path)) {
                // Hapus file dari storage
                Storage::delete('DataImport/' . $incident->file_path);
            }
        }

        // Hapus semua data incident dari database
        IncidentBrisol::truncate();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.brisol.incidents.index')
            ->with('success', 'All incidents deleted successfully');
    }
}

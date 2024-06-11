<?php

namespace App\Http\Controllers\Admin\Brisol;

use Illuminate\Http\Request;
use App\Models\Brisol\Incident;
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
            $query = Incident::all();

            return DataTables::of($query)
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

        try {
            // Memeriksa apakah file dengan nama yang sama sudah ada
            if (file_exists(public_path('/DataImport/' . $namaFile))) {
                // Menampilkan pesan konfirmasi untuk menimpa file
                if ($request->has('overwrite') && $request->overwrite == 'true') {
                    // Jika konfirmasi dilakukan, hapus file lama
                    unlink(public_path('/DataImport/' . $namaFile));

                    // Hapus semua insiden
                    Incident::truncate();
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
            // Tangani kesalahan query database
            $errorInfo = $e->errorInfo; // Ambil informasi kesalahan dari QueryException
            // Ambil pesan kesalahan dari errorInfo
            $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown database error';
            // Decode HTML entities to convert &#039; to '
            $errorMessage = html_entity_decode($errorMessage);
            // Kembalikan dengan pesan kesalahan yang sudah di-decode
            return redirect()->back()->withErrors(['Database error' => $errorMessage]);

        } catch (\Exception $e) {
            // Tangani kesalahan umum
            return redirect()->back()->with('error', 'There was an error processing the file: ' . $e->getMessage());
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
                Storage::delete('DataImport/' . $incident->file_path);
            }
        }

        // Hapus semua data incident dari database
        Incident::truncate();

        // Redirect dengan pesan sukses
        return redirect()->route('admin.brisol.incidents.index')
            ->with('success', 'All incidents deleted successfully');
    }
}

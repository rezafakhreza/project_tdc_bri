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
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
                ->addColumn('branch_code', function ($incident) {
                    return $incident->branch ? $incident->branch->branch_code : 'N/A';
                })

                ->addColumn('branch_code', function ($incident) {
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
        $branchCount = DB::table('usman_branch')->count(); // Menghitung jumlah data branch

        // Mengecek apakah ada data branch
        if ($branchCount > 0) {
            // Mengecek apakah semua kode UKER yang ada di file ada di database
            // Membaca file Excel
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();

            // Mengambil jumlah baris yang terisi dalam kolom 'G'
            $highestRow = $worksheet->getHighestRow();
            $kodeUkers = [];

            // Mengambil nilai kode UKER dari setiap baris dalam kolom 'G'
            // Mengambil nilai kode UKER dari setiap baris dalam kolom 'H'
            for ($row = 2; $row <= $highestRow; ++$row) {
                $branchCode = $worksheet->getCell('H' . $row)->getValue();
                $incidentsImport = new IncidentsImport();
                $kodeUkers[] = $incidentsImport->extractBranchCode($branchCode);
            }
            // Mengambil semua kode UKER yang ada di database
            $branchCodes = DB::table('usman_branch')->pluck('branch_code')->toArray();
            // Inisialisasi array untuk menyimpan kode UKER yang tidak ada di database
            $missingBranchCodes = [];

            // Mengecek setiap kode UKER yang ada di file
            foreach ($kodeUkers as $kodeUker) {
                // Menghapus angka nol di awal kode UKER
                $trimmedKodeUker = ltrim($kodeUker, '0');
                $trimmedKodeUker = str_pad($trimmedKodeUker, 4, '0', STR_PAD_LEFT);

                // Jika kode UKER tidak ada di database, tambahkan ke array missingBranchCodes
                if (!in_array($trimmedKodeUker, $branchCodes)) {
                    $trimmedKodeUker = '0000';
                }
            }

            if (!empty($missingBranchCodes)) {
                unlink($filePath);
                $errorMessage = 'Masukkan Kode Branch berikut terlebih dahulu dalam User Management : ' . implode(', ', $missingBranchCodes);
                return redirect()->back()->withErrors(['file' => $errorMessage]);
            }
        } else {
            // Jika tidak ada data branch, kembalikan dengan pesan error
            return redirect()->back()->withErrors(['file' => 'Tidak ada data Branch tersedia. Masukkan data Branch terlebih dahulu']);
        }

        Excel::import(new IncidentsImport, public_path('/DataImport/' . $namaFile));
        return redirect()->route('admin.brisol.incidents.index')->with('success', 'Incidents imported successfully');
        } catch (QueryException $e) {
            // Jika terjadi kesalahan, kembalikan dengan pesan error
            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file jika sudah dipindahkan ke direktori tujuan
            }
            return redirect()->back()->with('error', 'Database error' . $e->getMessage());
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

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
use Illuminate\Database\QueryException;

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
                ->select('usman_incident.reported_date', 'usman_incident.pn', 'usman_incident.nama', 'usman_incident.jabatan', 'usman_incident.bagian', 'usman_incident.req_type', 'usman_branch.branch_name', 'usman_branch.kanwil_name', 'usman_incident.req_status', 'usman_incident.exec_status', 'usman_incident.execution_date', 'usman_incident.sla_category')
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
                ->rawColumns(['branch_name', 'level_uker'])
                ->make(true);
        }

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
        $filePath = public_path('/DataImport/' . $namaFile);

        try {
            // Memeriksa apakah file dengan nama yang sama sudah ada
            if (file_exists($filePath)) {
                // Menampilkan pesan konfirmasi untuk menimpa file (ini masih gangaruh)
                if ($request->has('overwrite') && $request->overwrite == 'true') {
                    // Jika konfirmasi dilakukan, hapus file lama
                    unlink($filePath);
                    // Hapus semua insiden
                    Incident::truncate();
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
                for ($row = 2; $row <= $highestRow; ++$row) {
                    $kodeUkers[] = $worksheet->getCell('I' . $row)->getValue();
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
                        $missingBranchCodes[] = $trimmedKodeUker;
                    }
                }

                if (!empty($missingBranchCodes)) {
                    unlink($filePath);
                    $errorMessage = 'Kode UKER berikut tidak tersedia dalam data Branch: ' . implode(', ', $missingBranchCodes);
                    return redirect()->back()->withErrors(['file' => $errorMessage]);
                }
            } else {
                // Jika tidak ada data branch, kembalikan dengan pesan error
                return redirect()->back()->withErrors(['file' => 'Tidak ada data Branch tersedia. Masukkan data Branch terlebih dahulu']);
            }

            // Import data dari file baru
            Excel::import(new IncidentsImport, $filePath);
            return redirect()->route('admin.user-management.incidents.index')
                ->with('success', 'Incidents imported successfully');

        } catch (QueryException $e) {
            // Tangani kesalahan query database
            $errorInfo = $e->errorInfo; // Ambil informasi kesalahan dari QueryException
            // Ambil pesan kesalahan dari errorInfo
            $errorMessage = isset($errorInfo[2]) ? $errorInfo[2] : 'Unknown database error';
            // Decode HTML entities to convert &#039; to '
            $errorMessage = html_entity_decode($errorMessage);

            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file jika sudah dipindahkan ke direktori tujuan
            }
            // Kembalikan dengan pesan kesalahan yang sudah di-decode
            return redirect()->back()->withErrors(['Database error' => $errorMessage]);

        } catch (\Exception $e) {
            // Tangani kesalahan umum
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



    public function destroy()
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
        return redirect()->route('admin.user-management.incidents.index')
            ->with('success', 'All incidents deleted successfully');
    }
}

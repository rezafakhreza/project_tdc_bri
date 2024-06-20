<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserManagement\UsmanSAP;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\UserManagement\UserSAPImport;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;


class UsmanSAPController extends Controller
{
    //
    public function index()
    {
        if (request()->ajax()) {
            $query = UsmanSAP::query();

            return DataTables::of($query)
                ->make();
        }
        return view('admin.user-management.sap.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user-management.sap.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if ($request->input_method == 'excel') {
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
                    // Menampilkan pesan konfirmasi untuk menimpa file
                    if ($request->has('overwrite') && $request->overwrite == 'true') {
                        // Jika konfirmasi dilakukan, hapus file lama
                        unlink(public_path('/DataImport/' . $namaFile));
                    } else {
                        // Jika tidak ingin menimpa, kembalikan dengan pesan error
                        return redirect()->back()->withErrors(['file' => 'File with the same name already exists.']);
                    }
                }

                // Pindahkan file baru ke direktori tujuan
                $file->move('DataImport', $namaFile);

                // Import data dari file baru
                Excel::import(new UserSAPImport, $filePath);
                return redirect()->route('admin.user-management.sap.index')
                    ->with('success', 'User imported successfully');

            } catch (QueryException $e) {
                // Jika terjadi kesalahan, kembalikan dengan pesan error
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file jika sudah dipindahkan ke direktori tujuan
                }
                return redirect()->back()->with('error', 'Database error' . $e->getMessage());
                
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
       
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {

        // $branch = UsmanSAP::all();
        UsmanSAP::truncate();

        return redirect()->route('admin.user-management.sap.index')
            ->with('success', 'User deleted successfully');
    }
}

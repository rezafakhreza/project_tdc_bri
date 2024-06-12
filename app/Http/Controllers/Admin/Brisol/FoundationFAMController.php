<?php

namespace App\Http\Controllers\Admin\Brisol;

use App\Http\Controllers\Controller;
use App\Imports\Brisol\FoundationFAMImport;
use Illuminate\Http\Request;
use App\Models\Brisol\FoundationFAM;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;

class FoundationFAMController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = FoundationFAM::query();

            return DataTables::of($query)
                // ->addColumn('action', function ($fam) {
                //     return '
                //         <div class="flex gap-2">
                //         <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-gray-700 border border-gray-700 rounded-md select-none btn-edit ease hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                //             href="' . route('admin.brisol.foundation-fam.edit', $fam->id) . '">
                //             <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="edit" class="mx-auto svg-inline--fa fa-edit fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zM20.5 510.3c-3.2 3.2-8.4 3.2-11.6 0L3.8 498.6c-3.2-3.2-3.2-8.4 0-11.6l47.3-47.3 61.1 61.1-47.1 47.3zm0 0"></path></svg>
                //         </a>
                //         <form class="block w-full" action="' . route('admin.brisol.foundation-fam.destroy', $fam->id) . '" method="POST">
                //             <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none btn-delete ease hover:bg-red-600 focus:outline-none focus:shadow-outline">
                //                 <svg aria-hidden="true" width="24px" height="24px" focusable="false" data-prefix="fas" data-icon="trash" class="mx-auto svg-inline--fa fa-trash fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12zM432 80h-82.41l-34-56.7A48 48 0 0 0 274.41 0H173.59a48 48 0 0 0-40.59 23.3L99 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h304a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0-16-16zM171.84 50.91A6 6 0 0 1 177 48h94a6 6 0 0 1 5.15 2.91L293.61 80H154.39zM368 464H80V128h288zm-216-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12z"></path></svg>
                //             </button>
                //             ' . method_field('delete') . csrf_field() . '
                //         </form>
                //         </div>';
                // })
                // ->rawColumns(['action'])
                ->make();
        }
        return view('admin.brisol.foundation-fam.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brisol.foundation-fam.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->input_method == 'excel') {
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
            if (file_exists($filePath)) {
                // Menampilkan pesan konfirmasi untuk menimpa file
                if ($request->has('overwrite') && $request->overwrite == 'true') {
                    // Jika konfirmasi dilakukan, hapus file lama
                    unlink($filePath);
                } else {
                    // Jika tidak ingin menimpa, kembalikan dengan pesan error
                    return redirect()->back()->withErrors(['file' => 'File with the same name already exists.']);
                }
            }
            // Pindahkan file baru ke direktori tujuan
                $file->move('DataImport', $namaFile);
            
            // Import data dari file baru
            Excel::import(new FoundationFAMImport, public_path('/DataImport/' . $namaFile));
            return redirect()->route('admin.brisol.foundation-fam.index')
                ->with('success', 'Foundation FAM imported successfully');

            } catch (QueryException $e) {
                // Jika terjadi kesalahan, kembalikan dengan pesan error
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file jika sudah dipindahkan ke direktori tujuan
                }
                return redirect()->back()->withErrors(['Database error' => $e->getMessage()]);

            } catch (\Exception $e) {
                // Tangani kesalahan umum
                // Jika terjadi kesalahan, kembalikan dengan pesan error
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file jika sudah dipindahkan ke direktori tujuan
                }
                return redirect()->back()->with('error', 'The data does not match to the template: ' . $e->getMessage());
            }

        } elseif ($request->input_method == 'manual') {
            // $request->validate([
            //     'branch_code' => 'required|max:4',
            //     'branch_name' => 'required|max:200',
            //     'level_uker' => 'required|in:AIW,BRI UNIT,Campus,Kanpus,KC,KCP,KK,Regional Office',
            //     'uker_induk_wilayah_code' => 'required|max:4',
            //     'kanwil_name' => 'required|max:200',
            //     'uker_induk_kc' => 'required|max:4',
            //     'sbo' => 'required|in:SBO,NON SBO',
            // ]);

            // $data = [
            //     'branch_code' => $branch_code,
            //     'branch_name' => $request->input('branch_name'),
            //     'level_uker' => $request->input('level_uker'),
            //     'uker_induk_wilayah_code' => $uker_induk_wilayah_code,
            //     'kanwil_name' => $request->input('kanwil_name'),
            //     'uker_induk_kc' => $uker_induk_kc,
            //     'sbo' => $request->input('sbo'),
            //     'is_active' => $request->boolean('is_active'),
            // ];

            // FoundationFAM::create($data);

            return redirect()->route('admin.brisol.foundation-fam.index')
                ->with('success', 'Foundation FAM created successfully');
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
        $fam = FoundationFAM::findOrfail($id);

        return view('admin.brisol.foundation-fam.edit', compact('fam'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // // Validasi input
        // $request->validate([
        //     'branch_name' => 'required|string|max:255',
        //     'level_uker' => 'required|string|max:255',
        //     'kanwil_name' => 'required|string|max:255',
        //     'sbo' => 'required|string|max:255',
        //     'is_active' => 'required|boolean',
        // ]);

        // // Temukan cabang berdasarkan ID
        // $branch = FoundationFAM::findOrFail($id);

        // // Periksa apakah nama cabang sudah ada
        // if ($branch->branch_code != $request->branch_name) {
        //     if (FoundationFAM::where('branch_code', $request->branch_name)->exists()) {
        //         return redirect()->back()->with('error', 'Branch name already exists.');
        //     }
        // }

        // // Periksa apakah nama kanwil sudah ada
        // if ($branch->uker_induk_wilayah_code != $request->level_uker) {
        //     if (Branch::where('branch_code', $request->level_uker)->exists()) {
        //         return redirect()->back()->with('error', 'Level Uker name already exists.');
        //     }
        // }

        // // Update data cabang
        // $branch->update([
        //     'branch_name' => $request->branch_name,
        //     'level_uker' => $request->level_uker,
        //     'kanwil_name' => $request->kanwil_name,
        //     'sbo' => $request->sbo,
        //     'is_active' => $request->boolean('is_active'),
        // ]);

        return redirect()->route('admin.brisol.foundation-fam.index')->with('success', 'Foundation FAM updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        // $fam = FoundationFAM::findOrFail($id);

        // if ($fam->usman()->count()) {
        //     return redirect()->back()->with('error', 'There is Incident data. Foundation FAM cannot be deleted.');
        // }

        // $fam->delete();

        // return redirect()->route('admin.brisol.foundation-fam.index')
        //     ->with('success', 'Foundation FAM deleted successfully');
    }
}

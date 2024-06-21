<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserManagement\UsmanSAP;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\UserManagement\UserSAPImport;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Bus;

class UsmanSAPController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = UsmanSAP::query();
            return DataTables::of($query)->make();
        }
        return view('admin.user-management.sap.index');
    }

    public function create()
    {
        return view('admin.user-management.sap.create');
    }

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
            if (file_exists($filePath)) {
                if ($request->has('overwrite') && $request->overwrite == 'true') {
                    unlink($filePath);
                } else {
                    return redirect()->back()->withErrors(['file' => 'File with the same name already exists.']);
                }
            }

            $file->move('DataImport', $namaFile);

            Excel::import(new UserSAPImport, $filePath);

            return redirect()->route('admin.user-management.sap.index')
                ->with('success', 'User imported successfully');
        } catch (QueryException $e) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return redirect()->back()->with('error', 'The data does not match the template: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy()
    {
        UsmanSAP::truncate();

        return redirect()->route('admin.user-management.sap.index')
            ->with('success', 'User deleted successfully');
    }
}

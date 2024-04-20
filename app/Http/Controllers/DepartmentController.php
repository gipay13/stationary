<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $department = Departments::orderby('created_at', 'ASC');
            return DataTables::of($department)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return '
                        <button data-value="'.$item->id.'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 edit-department">
                            Edit
                        </button>
                        <button data-value="'.$item->id.'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 delete-department">
                            Delete
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('pages.department.index');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                $department = Departments::create([
                    'nama' => $request->name,
                ]);
                $department->save();

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Departemen Berhasil Ditambah']);
            }
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $department = Departments::where('id', $request->val)->first();
            return response()->json(['department' => $department]);
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                $department = Departments::where('id', $request->id);
                $department->update([
                    'nama' => $request->name,
                ]);

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Departemen Berhasil Diubah']);
            }
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $user = User::where('department_id', $request->val)->first();

            if ($user) {
                return $this->response(200, 'OK', ['icon' => 'warning', 'title' => 'Gagal', 'text' => 'Departemen Ini Telah Digunakan Di List Produk']);
            }

            Departments::where('id', $request->val)->delete();
            return $this->response(200, 'OK', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Departemen Berhasil Dihapus']);
        }
    }

    private function validation(Request $request)
    {
        $rules = [
            'name' => ['required', 'unique:departments,nama,'.$request->id]
        ];

        $messages = [
            'name.required' => 'Departemen harus diisi',
            'name.unique' => 'Departemen sudah ada',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}

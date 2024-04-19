<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $suppliers = Suppliers::orderby('created_at', 'ASC');
            return DataTables::of($suppliers)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return '
                        <button data-value="'.$item->id.'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 edit-supplier">
                            Edit
                        </button>
                        <button data-value="'.$item->id.'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 delete-supplier">
                            Delete
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('pages.supplier.index');
    }


    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                $supplier = Suppliers::create([
                    'nama' => $request->name,
                    'alamat' => $request->address,
                    'telepon' => $request->phone,
                ]);
                $supplier->save();

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Supplier Berhasil Ditambah']);
            }
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $supplier = Suppliers::findOrFail($request->val);
            return response()->json(['supplier' => $supplier]);
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                $category = Suppliers::where('id', $request->id);
                $category->update([
                    'nama' => $request->name,
                    'alamat' => $request->address,
                    'telepon' => $request->phone,
                ]);

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Supplier Berhasil Diubah']);
            }
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            // $category = Products::where('slug', $request->val)->first();

            // if ($category) {
            //     return $this->response(200, 'OK', ['icon' => 'warning', 'title' => 'Gagal', 'text' => 'Kategori Ini Telah Digunakan Di List Barang']);
            // }

            Suppliers::where('id', $request->val)->delete();
            return $this->response(200, 'OK', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Supplier Berhasil Dihapus']);
        }
    }

    public function validation(Request $request)
    {
        $rules = [
            'name' => 'required',
            'address' => 'required',
            'phone' => ['required', 'numeric'],
        ];

        $messages = [
            'name.required' => 'Kategori harus diisi',
            'address.required' => 'Alamat harus diisi',
            'phone.required' => 'Telepon harus diisi',
            'phone.numeric' => 'Telepon harus angka',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}

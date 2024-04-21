<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $product = Products::with(['category', 'supplier'])->orderby('created_at', 'ASC');
            return DataTables::of($product)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    if (Auth::user()->hasRole('Admin')) {
                        return '
                            <button data-value="'.$item->id.'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 edit-product">
                                Edit
                            </button>
                            <button data-value="'.$item->id.'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 delete-product">
                                Delete
                            </button>
                        ';
                    }
                })
                ->rawColumns(['action'])
                ->make();
        }
        $data = [
            'category' => Categories::all(),
            'supplier' => Suppliers::all()
        ];
        return view('pages.product.index', $data);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                $product = Products::create([
                    'id_kategori' => $request->category,
                    'id_supplier' => $request->supplier,
                    'kode' => $this->setProductCode(),
                    'nama' => $request->name,
                ]);
                $product->save();

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Barang Berhasil Ditambah']);
            }
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $product = Products::findOrFail($request->val);
            return response()->json(['product' => $product]);
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                $product = Products::where('id', $request->id);
                $product->update([
                    'id_kategori' => $request->category,
                    'id_supplier' => $request->supplier,
                    'nama' => $request->name,
                ]);

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Barang Berhasil Diubah']);
            }
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            Products::where('id', $request->val)->delete();
            return $this->response(200, 'OK', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Barang Berhasil Dihapus']);
        }
    }

    private function validation(Request $request)
    {
        $rules = [
            'category' => 'required',
            'supplier' => 'required',
            'name' => ['required'],
        ];

        $messages = [
            'category.required' => 'Kategori harus diisi',
            'supplier.required' => 'Supplier harus diisi',
            'name.required' => 'Nama harus diisi',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}

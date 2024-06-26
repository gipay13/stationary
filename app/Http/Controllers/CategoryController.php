<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $category = Categories::orderby('created_at', 'ASC');
            return DataTables::of($category)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return '
                    <button data-value="'.$item->id.'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 edit-category">
                        Edit
                    </button>
                    <button data-value="'.$item->id.'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 delete-category">
                        Delete
                    </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('pages.category.index');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                $category = Categories::create([
                    'nama' => Str::title($request->name),
                    'slug' => Str::slug($request->name)
                ]);
                $category->save();

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Kategori Berhasil Ditambah']);
            }
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $category = Categories::where('id', $request->val)->first();
            return response()->json(['category' => $category]);
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                $category = Categories::where('id', $request->id);
                $category->update([
                    'nama' => Str::title($request->name),
                    'slug' => Str::slug($request->name)
                ]);

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Kategori Berhasil Diubah']);
            }
        }
    }

    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $product = Products::where('id_kategori', $request->val)->first();

            if ($product) {
                return $this->response(200, 'OK', ['icon' => 'warning', 'title' => 'Gagal', 'text' => 'Kategori Ini Telah Digunakan Di List Produk']);
            }

            Categories::where('id', $request->val)->delete();
            return $this->response(200, 'OK', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Kategori Berhasil Dihapus']);
        }
    }

    private function validation(Request $request)
    {
        $rules = [
            'name' => ['required', 'unique:categories,nama,'.$request->id]
        ];

        $messages = [
            'name.required' => 'Kategori harus diisi',
            'name.unique' => 'Kategori sudah ada',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}

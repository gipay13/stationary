<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class SearchProduct extends Controller
{
    public function __invoke(Request $request)
    {
        if(request()->ajax()){
            $query = Products::select(['id', 'kode', 'nama'])
                        ->where('kode', 'like', '%'.$request->val.'%')
                        ->orWhere('nama', 'like', '%'.$request->val.'%')
                        ->get();
            $product = [];

            foreach ($query as $q) {
                $product[] = [
                    'id'    => $q->id,
                    'text'  => $q->kode.' - '.$q->nama,
                ];
            }

		    return $product;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Stationaries;

abstract class Controller
{
    public function response($code, $message, $response)
    {
        return response()->json([
            'metaData' => [
                'code' => $code,
                'message' => $message
            ],
            'response' => $response
        ]);
    }

    public function setProductCode()
    {
        $product = Products::latest('ID')->first();
        $check = $product ? $product->kode : '0000';
        $substr = (int)substr($check, -4);
        $count = $substr > 0 ? $substr+1 : 1;
        $padded = str_pad($count, '4', 0, STR_PAD_LEFT);
        return 'BRG.'.$padded;
    }

    public function setStationaryNumber()
    {
        $stationary = Stationaries::latest()->first();
        $check = $stationary ? $stationary->nomor_pengajuan : '0000';
        $substr = (int)substr($check, -4);
        $count = $substr > 0 ? $substr+1 : 1;
        $padded = str_pad($count, '4', 0, STR_PAD_LEFT);
        return 'STTNRY.'.$padded;
    }
}

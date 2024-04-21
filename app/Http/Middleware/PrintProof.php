<?php

namespace App\Http\Middleware;

use App\Models\Stationaries;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrintProof
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $stationary = Stationaries::where('kode', $request->id)->first();
        if ($stationary->id_status == 1 || $stationary->id_status == 3) {
            return redirect(route('stationary.show', $stationary->kode))->with('error', 'Bukti Pengambilan Barang Tidak Bisa Di Cetak');
        }
        return $next($request);
    }
}

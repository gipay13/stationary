<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\StationaryService;
use App\Models\Stationaries;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StationaryController extends Controller
{
    private $service;

    public function __construct(StationaryService $stationaryService) {
        $this->service = $stationaryService;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $stationary = Stationaries::with('product')
                ->when(Auth::user()->hasRole('Staff'), function($query){
                    return $query->where('id_user', Auth::user()->id);
                })
                ->when(Auth::user()->hasRole('Supervisor'), function($query){
                    return $query->where('id_supervisor', Auth::user()->id);
                })
                ->orderby('created_at', 'DESC');
            return DataTables::of($stationary)
                    ->addIndexColumn()
                    ->addColumn('status', function ($item) {
                        if ($item->id_status == 1) {
                            return '<span class="bg-indigo-500 py-1 px-4 rounded-full text-white text-xs">Diajukan</span>';
                        } else if ($item->id_status == 2) {
                            return '<span class="bg-green-500 py-1 px-4 rounded-full text-white text-xs">Diterima</span>';
                        } else {
                            return '<span class="bg-red-500 py-1 px-4 rounded-full text-white text-xs">Ditolak</span>';
                        }
                    })
                    ->addColumn('action', function ($item) {
                        return '
                            <a href="'.route('stationary.show', $item->nomor_pengajuan).'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Detail
                            </a>
                        ';
                    })
                    ->rawColumns(['action', 'status'])
                    ->make();
        }

        return view('pages.stationary.index');
    }

    public function create()
    {
        return view('pages.stationary.create');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                $stationary_number = $this->setStationaryNumber();

                Stationaries::create([
                    'nomor_pengajuan' => $stationary_number,
                    'id_user' => Auth::user()->id,
                    'id_produk' => $request->product,
                    'id_supervisor' => $request->supervisor,
                    'keterangan' => $request->note,
                    'id_status' => Stationaries::DIAJUKAN,
                ])->save();

                $this->service->sendEmailAfterCreateStationary(Auth::user()->id, $request->supervisor, $stationary_number);

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Permintaan Diajukan']);
            }
        }
    }

    public function show(string $id)
    {
        $data = ['stationary' => Stationaries::where('nomor_pengajuan', $id)->first()];
        return view('pages.stationary.show', $data);
    }


    public function update(Request $request)
    {
        if ($request->ajax()) {
            $stationary = Stationaries::where('nomor_pengajuan', $request->number);
            if ($request->status == 'approve') {
                $stationary->update([
                    'id_status' => Stationaries::DITERIMA,
                ]);

                $this->service->sendEmailAfterApprovingStationary(Auth::user()->id, $request->number);

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Pengajuan Diterima']);
            }

            $stationary->update([
                'id_status' => Stationaries::DITOLAK,
            ]);

            return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'Pengajuan Ditolak']);
        }
    }

    public function print($id)
    {
        $data = ['stationary' => Stationaries::where('nomor_pengajuan', $id)];
        return Pdf::loadView('pages.stationary.print', $data)->stream();
    }

    private function validation(Request $request)
    {
        $rules = [
            'supervisor' => 'required',
            'product' => 'required',
            'note' => 'required',
        ];

        $messages = [
            'supervisor.required' => 'Supervisor harus diisi',
            'product.required' => 'Produk harus diisi',
            'note.required' => 'Supplier harus diisi',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}

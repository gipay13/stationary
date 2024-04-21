@extends('layouts.dashboard.master')

@section('content')
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengajuan') }}
            </h2>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('error'))
                        <div class="p-4 bg-red-500 text-white rounded-sm mb-5">
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                    <a href="{{route('stationary.index')}}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Kembali
                    </a>
                    <div class="flex items-center mb-3 mt-5">
                        <div class="w-1/3">
                            <span>Nomor Pengajuan</span>
                        </div>
                        <div class="w-2/3">
                            <span class="font-semibold">{{$stationary->kode}}</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <div class="w-1/3">
                            <span>User</span>
                        </div>
                        <div class="w-2/3">
                            <span class="font-semibold">{{$stationary->user->name}}</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <div class="w-1/3">
                            <span>Barang</span>
                        </div>
                        <div class="w-2/3">
                            <span class="font-semibold">{{$stationary->product->nama}}</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <div class="w-1/3">
                            <span>Keterangan</span>
                        </div>
                        <div class="w-2/3">
                            <span class="font-semibold">{{$stationary->keterangan}}</span>
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <div class="w-1/3">
                            <span>Status</span>
                        </div>
                        <div class="w-2/3">
                            <span class="font-semibold">
                                @if ($stationary->id_status == 1)
                                    <span class="bg-indigo-500 py-1 px-4 rounded-full text-white text-xs">Diajukan</span>
                                @elseif ($stationary->id_status == 2)
                                    <span class="bg-green-500 py-1 px-4 rounded-full text-white text-xs">Diterima</span>
                                @else
                                    <span class="bg-red-500 py-1 px-4 rounded-full text-white text-xs">Ditolak</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <div class="w-1/3">
                            <span>Aksi</span>
                        </div>
                        <div class="w-2/3">
                            <span class="font-semibold">
                                @if ($stationary->id_status == 1)
                                    @role('Supervisor')
                                        <button data-status="approve" data-number="{{$stationary->kode}}" class="status-update inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Terima
                                        </button>
                                        <button data-status="reject" data-number="{{$stationary->kode}}" class="status-update inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Tolak
                                        </button>
                                    @endrole
                                @elseif ($stationary->id_status == 2)
                                    <a target="_blank" href="{{ route('stationary.print', $stationary->kode) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 edit-product">
                                        Cetak
                                    </a>
                                @else

                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <div class="w-1/3">
                            <span>Catatan</span>
                        </div>
                        <div class="w-2/3">
                            <span class="font-semibold">{{$stationary->catatan}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.status-update',function () {
                let number = $(this).attr('data-number');
                let status = $(this).attr('data-status');
                let message = status == 'approve' ? 'Terima Pengajuan?' : 'Tolak Pengajuan?'

                Swal.fire({
                    title: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "patch",
                            url: "{{ url()->current() }}",
                            data: {
                                number:number,
                                status:status
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.metaData.code == 201) {
                                    Swal.fire({
                                        icon: response.response.icon,
                                        title: response.response.title,
                                        text: response.response.text,
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                    }).then((result) => {
                                        location.reload()
                                    });
                                }
                            },
                            error: function (response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Ada Kesalahan, Silahkan Hubungi SIMRS',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                }).then((result) => {
                                    location.reload()
                                });
                            }
                        });
                    }
                })
            });
        });
    </script>
@endpush

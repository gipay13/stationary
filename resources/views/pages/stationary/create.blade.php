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
                    <a href="{{route('stationary.index')}}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Kembali
                    </a>
                    <ul id="error" class="mb-4"></ul>
                    <form id="stationary-form">
                        <div class="mb-4">
                            <x-input-label for="product" :value="__('Produk')" />
                            <select class="selectpicker" name="product" id="product" style="width: 100%">
                            </select>
                        </div>

                        <!-- Keterangan -->
                        <div class="pb-5">
                            <x-input-label for="note" :value="__('Catatan')" />
                            <x-text-input id="note" class="block mt-1 w-full" type="text" name="note" required autocomplete="note" />
                        </div>

                        <x-primary-button id="submit">
                            {{ __('Ajukan') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#supervisor').select2({
                placeholder: '-- Cari --',
                allowClear: false,
                minimumInputLength: 3,
                ajax: {
                    url: "{{ route('get-user-supervisor') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {val: params.term};
                    },
                    processResults: function (data) {
                        return {results: data};
                    },
                    cache: true
                },
            });

            $('#product').select2({
                placeholder: '-- Cari --',
                allowClear: false,
                minimumInputLength: 3,
                ajax: {
                    url: "{{ route('get-product') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {val: params.term};
                    },
                    processResults: function (data) {
                        return {results: data};
                    },
                    cache: true
                },
            });

            $('#stationary-form').submit(function (e) {
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: "{{ route('stationary.store') }}",
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function (response) {
                        $('#submit').attr('disabled', true);
                    },
                    success: function (response) {
                        if (response.metaData.code == 422) {
                            $('#error').html('');
                            $('#error').addClass('p-4 bg-red-500 text-white rounded-sm');
                            $.each(response.response, function (key, value) {
                                $('#error').append('<li>'+value+'</li>');
                            });
                        } else {
                            $('#error').html('');
                            $('#error').removeClass('p-4 bg-red-500 text-white rounded-sm');
                            Swal.fire({
                                title: response.response.title,
                                text: response.response.text,
                                icon: response.response.icon,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then((result) => {
                                location.href = "{{ route('stationary.index') }}"
                            });
                        }
                        $('#submit').removeAttr('disabled');
                    },
                    error: function (response) {
                        Swal.fire({
                            title: 'Ooops',
                            text: 'Ada Kesalahan, Silahkan Hubungi SIMRS',
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        }).then((result) => {
                            location.reload()
                        });
                    }
                });
            });
        });
    </script>
@endpush

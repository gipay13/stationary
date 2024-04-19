<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table id="category-table" class="display pt-10">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <ul id="error" class="mb-4"></ul>
                    <form id="category-form" data-form="create">
                        <!-- Name -->
                        <div class="pb-5">
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
                            <input type="hidden" name="id" id="id">
                        </div>

                        <div class="flex gap-2">
                            <x-primary-button id="submit">
                                {{ __('Tambah') }}
                            </x-primary-button>
                            <button id="cancel" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.0.4/js/dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('#category-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: true,
                ajax: {
                    url: '{!! url()->current() !!}',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama'},
                    { data: 'action', name: 'action'},
                ],
            });

            $('#category-form').submit(function (e) {
                e.preventDefault();

                if ($('#category-form').attr('data-form') == 'create') {
                    var route = "{{ route('category.store') }}"
                    var method = "post"
                } else {
                    var route = "{{ route('category.update') }}"
                    var method = "patch"
                }


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: method,
                    url: route,
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
                                location.reload()
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

            $(document).on('click', '.edit-category',function () {
                let val = $(this).attr('data-value');

                $.ajax({
                    type: "get",
                    url: "{{ route('category.edit') }}",
                    data: { val:val },
                    dataType: "json",
                    success: function (response) {
                        $('#id').val(response.category.id);
                        $('#name').val(response.category.nama);
                        $('#submit').html('Simpan');
                        $('#category-form').attr('data-form', 'edit');
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

            $(document).on('click', '.delete-category',function () {
                let val = $(this).attr('data-value');

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Data Tidak Bisa Dikembalikan",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "delete",
                            url: "{{ route('category.destroy') }}",
                            data: { val:val },
                            dataType: "json",
                            success: function (response) {
                                if (response.metaData.code == 200) {
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

            $('#cancel').on('click', function () {
                $('#id').val('');
                $('#name').val('');
                $('#submit').html('Tambah');
                $('#category-form').attr('data-form', 'create');
            });
        });
    </script>
</x-app-layout>

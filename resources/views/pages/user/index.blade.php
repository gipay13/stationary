<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table id="user-table" class="display pt-10">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Status</th>
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
                    <form id="user-form">
                        <!-- Name -->
                        <div class="pb-5">
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
                            <input type="hidden" name="id" id="id">
                        </div>

                        <!-- Email -->
                        <div class="pb-5">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="email" />
                        </div>

                        <div class="pb-5">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="role" required autofocus>
                                <option value="">-- Pilih --</option>
                                @foreach ($role as $r)
                                    <option value="{{$r->name}}">{{$r->name}}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id" id="id">
                        </div>

                        <!-- Password -->
                        <div class="pb-5">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="pb-5">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
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
            $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: true,
                ajax: {
                    url: '{!! url()->current() !!}',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'email', name: 'email'},
                    { data: 'name', name: 'name'},
                    { data: 'roles', name: 'roles.name', orderable: false, searchable: false},
                    { data: 'status', name: 'status', orderable: false, searchable: false},
                    { data: 'action', name: 'action'},
                ],
            });

            $('#user-form').submit(function (e) {
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: "{{ route('user.store') }}",
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

            $(document).on('click', '.delete-user',function () {
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
                            url: "{{ route('user.destroy') }}",
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
                $('#name').val('');
                $('#email').val('');
                $('#role').val('');
                $('#password').val('');
                $('#password_confirmation').val('');
            });
        });
    </script>
</x-app-layout>

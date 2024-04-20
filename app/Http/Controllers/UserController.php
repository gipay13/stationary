<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $suppliers = User::orderby('created_at', 'ASC');
            return DataTables::of($suppliers)
                ->addIndexColumn()
                ->addColumn('roles', function ($item) {
                    return $item->roles->map(function($role) {
                        return $role->name;
                    })->implode(',');
                })
                ->addColumn('status', function ($item) {
                    return $item->email_verified_at == null ? '<span class="bg-red-500 py-1 px-4 rounded-full text-white text-xs">Belum Verifikasi Email</span>' : '<span class="bg-green-500 py-1 px-4 rounded-full text-white text-xs">Aktif</span>';
                })
                ->addColumn('action', function ($item) {
                    return '
                        <button data-value="'.$item->id.'" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 delete-user">
                            Delete
                        </button>
                    ';
                })
                ->rawColumns(['action', 'status'])
                ->toJson();
        }
        $data = ['role' => Role::all()];
        return view('pages.user.index', $data);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validation = $this->validation($request);

            if ($validation->fails()) {
                return $this->response(422, 'Unprocessable Entity', $validation->errors());
            } else {
                DB::transaction(function () use ($request) {
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]);

                    $user->assignRole($request->role);
                });

                return $this->response(201, 'Created', ['icon' => 'success', 'title' => 'Sukses', 'text' => 'User Berhasil Ditambah']);
            }
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $user = User::with('roles')->findOrFail($request->val);
            return response()->json(['user' => $user]);
        }
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    private function validation(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:191', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        $messages = [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Password harus sesuai',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}

<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserWithSupervisorRole extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if(request()->ajax()){
            $query = User::role('Supervisor')->select(['id', 'email', 'name'])
                        ->where('email', 'like', '%'.$request->val.'%')
                        ->orWhere('name', 'like', '%'.$request->val.'%')
                        ->get();
            $user = [];

            foreach ($query as $q) {
                $user[] = [
                    'id'    => $q->id,
                    'text'  => $q->name,
                ];
            }

		    return $user;
        }
    }
}

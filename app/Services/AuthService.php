<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    { 
        //
    }

    /**
     * Register User
     *
     * @return mixed
     */
    public function addUser($request, $role)
    {
        return new User([
            'firstname'     => $request->firstname,
            'lastname'      => $request->lastname,
            'email'         => $request->email,
            'company'       => $request->company,
            'password'      => Hash::make($request->password),
            'user_role_id'  => $role
        ]);
    }

}

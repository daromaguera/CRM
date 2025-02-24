<?php

namespace App\Http\Controllers\Auth;

use App\Models\UserTeamMember;
use App\Models\User;
use App\Mail\InviteMembersMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\UserRequest;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::findOrFail($id);
    }
    /**
     * Update storage for the user image.
     */
    public function image(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        if (!is_null($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->image = $request->file('image')->storePublicly('images/users/profileImage', 'public');
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function firstname(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->firstname = $validated['firstname'];
         
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Update the lastname of the specified resource in storage.
     */
    public function lastname(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->lastname = $validated['lastname'];
         
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Update the username of the specified resource in storage.
     */
    public function username (UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->username = $validated['username'];
         
        $user->save();

        return response()->json($user, 200);
    }
    /**
     * Update the phone of the specified resource in storage.
     */
    public function phone(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->phone = $validated['phone'];
         
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Update the email of the specified resource in storage.
     */
    public function email(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->email = $validated['email'];
         
        $user->save();

        return response()->json($user, 200);
    }

    /**
     *  
     * Update the password of the specified resource in storage.
     */
    public function password(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->password = bcrypt($validated['password']);
         
        $user->save();

        return response()->json($user, 200);
        
    }

    /**
     * Update the realtor_license_no in storage.
     */
    public function realtor_license_no(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->realtor_license_no = $validated['realtor_license_no'];
         
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Update the timezone in storage.
     */
    public function postal_zip(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->postal_zip = $validated['postal_zip'];
         
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Update the country in storage.
     */
    public function country(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();

        $user->country = $validated['country'];

        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Update the state_province in storage.
     */
    public function state_province(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->state_province = $validated['state_province'];
         
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Update the city_municipality in storage.
     */
    public function city_municipality(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validated();
 
        $user->city_municipality = $validated['city_municipality'];
         
        $user->save();

        return response()->json($user, 200);
    }
  

    /**
     * Update the specified resource in storage.
     */
    public function more_information(UserRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return response()->json([
            'userData' => $user,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function team_members(UserRequest $request)
    {
        $user = $request->user();

        $teamMembersData = [];
        foreach ($request->members as $member) {
            $teamMembersData[] = [
                'user_id' => $user->id,
                'email' => $member['email'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            Mail::to($member['email'])->send(new InviteMembersMail([
                'email' => $member['email'],
                'token' => $user->id,
            ]));
        }

        UserTeamMember::insert($teamMembersData);

        return response()->json([
            'members' => $teamMembersData,
        ], 201);
    }

   
}

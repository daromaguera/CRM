<?php

namespace App\Http\Controllers\Drip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\DripCampaigns;

class DripCampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($page, $itemPerPage, $userType)
    {
        $user_id = auth()->id(); 
        $user_settings = User::where('id', $user_id)->first();
        $data = array();
        $sql = null;
        $i = 0;
        if($userType == 1){
            // $sql = User::where('user_role_id', 2)->orderBy('id', 'desc')->paginate($itemPerPage, ['*'], 'page', $page);
            // foreach ($sql as $item) {
            //     $data[$i] = [
            //         'user_id' => $item->id,
            //         'first_name' => $item->firstname,
            //         'last_name' => $item->lastname,
            //         'time_on_doors' => 123, // Add dynamic info soon
            //         'doors_knocked' => 123, // Add dynamic info soon
            //         'contracts' => 123, // Add dynamic info soon
            //         'appointment_set' => Appointment::where('user_id', $item->id)->count(), // Add dynamic info soon
            //     ];
            //     $i++;
            // }
        }else if($userType == 2){
            $sql = DripCampaigns::where('user_id', $user_id)->orderBy('id', 'desc')->paginate($itemPerPage, ['*'], 'page', $page);
            foreach ($sql as $item) {
                $data[$i] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'triggered_words' => $item->triggered_words,
                    'message' => $item->message,
                    'time' => $item->time,
                    'status' => $item->status ? 'Active' : '',
                    'length' => '6 stages',
                    'contracts' => 16,
                    'unassigned_contracts' => 350,
                    'assigned_contracts' => 350,
                    'closed_contracts' => 3500,
                    'channels' => 4
                ];
                $i++;
            }
        }
        return response()->json([
            'success' => true,
            'error' => false,
            'total' => DripCampaigns::where('user_id', $user_id)->count(),
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $page, $itemPerPage, $userType)
    {
        $validated = $request->validate([
            'dripName' => 'required|string|max:255',
            'description' => 'required|string|max:10000',
            'triggerWords' => 'required|string|max:255',
            'firstMessage' => 'required|string|max:10000',
            'time' => 'required|string|max:20',
        ]);
    
        // Create a new contact and save it to the database
        $dripResult = DripCampaigns::create([
            'user_id' => auth()->user()->id,  // Assuming user is logged in, you can get the user_id from the session
            'name' => $validated['dripName'],  // Map 'fullname' to 'name' column
            'description' => $validated['description'],
            'triggered_words' => $validated['triggerWords'],
            'message' => $validated['firstMessage'],
            'time' => $validated['time'],
            'status' => true,
        ]);

        if($dripResult){
            return $this->index($page, $itemPerPage, $userType);
        }else{
            return response()->json([
                'success' => false,
                'error' => true,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $page, $itemPerPage, $userType)
    {
        $user_id = auth()->id(); 
        $user_settings = User::where('id', $user_id)->first();
        $data = array();
        $sql = null;
        $i = 0;
        if($userType == 1){
            // $sql = User::where('user_role_id', 2)->orderBy('id', 'desc')->paginate($itemPerPage, ['*'], 'page', $page);
            // foreach ($sql as $item) {
            //     $data[$i] = [
            //         'user_id' => $item->id,
            //         'first_name' => $item->firstname,
            //         'last_name' => $item->lastname,
            //         'time_on_doors' => 123, // Add dynamic info soon
            //         'doors_knocked' => 123, // Add dynamic info soon
            //         'contracts' => 123, // Add dynamic info soon
            //         'appointment_set' => Appointment::where('user_id', $item->id)->count(), // Add dynamic info soon
            //     ];
            //     $i++;
            // }
        }else if($userType == 2){
            $sql = DripCampaigns::where('user_id', $user_id)
                ->where(function($query) use ($request) {
                    $searchTerm = strtoupper($request['data_to_search']);
                    $query->whereRaw('UPPER(name) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereRaw('UPPER(description) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereRaw('UPPER(triggered_words) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereRaw('UPPER(message) LIKE ?', ['%' . $searchTerm . '%']);
                })->orderBy('id', 'desc')->paginate($itemPerPage, ['*'], 'page', $page);
            foreach ($sql as $item) {
                $data[$i] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'triggered_words' => $item->triggered_words,
                    'message' => $item->message,
                    'time' => $item->time,
                    'status' => $item->status ? 'Active' : '',
                    'length' => '6 stages',
                    'contracts' => 16,
                    'unassigned_contracts' => 350,
                    'assigned_contracts' => 350,
                    'closed_contracts' => 3500,
                    'channels' => 4
                ];
                $i++;
            }
        }
        return response()->json([
            'success' => true,
            'error' => false,
            'total' => DripCampaigns::where('user_id', $user_id)->count(),
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $page, $itemPerPage, $userType)
    {
        $validated = $request->validate([
            'id' => 'required',
            'dripName' => 'required|string|max:255',
            'description' => 'required|string|max:10000',
            'triggerWords' => 'required|string|max:255',
            'firstMessage' => 'required|string|max:10000',
            'time' => 'required|string|max:20',
        ]);
    
        // Create a new contact and save it to the database
        $dripResult = DripCampaigns::where('id', (int)$validated['id'])->update([
            'name' => $validated['dripName'], 
            'description' => $validated['description'],
            'triggered_words' => $validated['triggerWords'],
            'message' => $validated['firstMessage'],
            'time' => $validated['time']
        ]);

        if($dripResult){
            $updatedDrip = DripCampaigns::find((int)$validated['id']);
  
            return response()->json([
                'success' => true,
                'error' => false,
                'updated_data' => $updatedDrip,
                'main_data' => $this->index($page, $itemPerPage, $userType)
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'error' => true,
                'data' => []
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

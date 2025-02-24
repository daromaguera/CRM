<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Contact;
use App\Models\ContactsAppointed;

class CrmDashboard extends Controller
{
    public function logistics($user_id){
        $data[0] = [
            'Unassigned_Contracts' => 350,
            'Assigned_Contracts' => 350,
            'Closed_Contracts' => 3500,
            'Channels' => 4,
            'Time_In_Doors' => 661,
            'Doors_Knocked' => 323,
            'Appointments_Set' => Appointment::where('user_id', $user_id)->count(),
            'Contacts_Sent' => 244,
        ];
        return $data;
    }
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
            $sql = User::where('user_role_id', 2)->orderBy('id', 'desc')->paginate($itemPerPage, ['*'], 'page', $page);
            foreach ($sql as $item) {
                $data[$i] = [
                    'user_id' => $item->id,
                    'first_name' => $item->firstname,
                    'last_name' => $item->lastname,
                    'time_on_doors' => 123, // Add dynamic info soon
                    'doors_knocked' => 123, // Add dynamic info soon
                    'contracts' => 123, // Add dynamic info soon
                    'appointment_set' => Appointment::where('user_id', $item->id)->count(), // Add dynamic info soon
                ];
                $i++;
            }
        }else if($userType == 2){
            $sql = Contact::where('user_id', $user_id)->orderBy('id', 'desc')->paginate($itemPerPage, ['*'], 'page', $page);
            foreach ($sql as $item) {
                $sqlContactAppointed = ContactsAppointed::where('contact_id', $item->id)->orderBy('id', 'DESC')->first();
                $getAppointment = null;
                if ($sqlContactAppointed) {
                    $getAppointment = Appointment::where('id', $sqlContactAppointed->appointment_id)->orderBy('id', 'DESC')->first();
                }

                $diffInDays = 0;
                if($getAppointment){
                    $date = Carbon::createFromFormat('Y-m-d H:i:s', $getAppointment->date_start, $getAppointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(); // Convert to desired timezone
                    $now = Carbon::now($user_settings->timezone); // Get current time in same timezone

                    $diffInDays = (int)$now->diffInDays($date);
                }

                $data[$i] = [
                    'id' => $item->id,
                    'fullname' => $item->name,
                    'status' => $diffInDays >= 1 && $diffInDays < 5 ? 2 : ($diffInDays >= 5 ? 1 : 0 ), // 1 - under appointment but greater-than or equal 5 days, 2 - pass due, 3 - under appointment but less-than 5 days
                    'diff_in_days' => $diffInDays,
                    'deal_flow_status' => 'Under Contract', // Add dynamic info soon
                    'address' => $item->address, // Add dynamic info soon
                    'phone' => $item->phone,
                    'email' => $item->email,
                    'years_in_home' => $item->years_in_home,
                    'possible_equity' => $item->possible_equity,
                    'rough_credit_score' => $item->rough_credit_score,
                    'zillow_estimate' => $item->zillow_estimate
                ];
                $i++;
            }
        }

        // Return data with proper JSON structure
        return response()->json([
            'success' => true,
            'error' => false,
            'total' => Contact::where('user_id', $user_id)->count(),
            'data' => $data,
            'logistics' => $this->logistics($user_id),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Create
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $page, $itemPerPage, $userType)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'yearsInHome' => 'required|integer',
            'possibleEquity' => 'nullable|numeric',
            'roughCreditScore' => 'nullable|numeric',
            'zillowEstimate' => 'nullable|numeric',
        ]);
    
        // Create a new contact and save it to the database
        $contact = Contact::create([
            'user_id' => auth()->user()->id,  // Assuming user is logged in, you can get the user_id from the session
            'name' => $validated['fullname'],  // Map 'fullname' to 'name' column
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'years_in_home' => $validated['yearsInHome'],
            'possible_equity' => $validated['possibleEquity'],
            'rough_credit_score' => $validated['roughCreditScore'],
            'zillow_estimate' => $validated['zillowEstimate'],
        ]);

        return $this->index($page, $itemPerPage, $userType);
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
            $sql = Contact::where('user_id', $user_id)
                ->where(function($query) use ($request) {
                    $searchTerm = strtoupper($request['data_to_search']);
                    $query->whereRaw('UPPER(name) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereRaw('UPPER(address) LIKE ?', ['%' . $searchTerm . '%']);
                });
            $sql2 = $sql->orderBy('id', 'desc')
                ->paginate($itemPerPage, ['*'], 'page', $page);
            foreach ($sql2 as $item) {
                $sqlContactAppointed = ContactsAppointed::where('contact_id', $item->id)->orderBy('id', 'DESC')->first();
                $getAppointment = null;
                if ($sqlContactAppointed) {
                    $getAppointment = Appointment::where('id', $sqlContactAppointed->appointment_id)->first();
                }

                $diffInDays = 0;
                if($getAppointment){
                    $date = Carbon::parse($getAppointment->date_start)->setTimezone($getAppointment->timezone); // Convert to desired timezone
                    $now = Carbon::now($user_settings->timezone); // Get current time in same timezone

                    $diffInDays = (int)$now->diffInDays($date) + 1;
                }

                $data[$i] = [
                    'id' => $item->id,
                    'fullname' => $item->name,
                    'status' => $diffInDays >= 1 && $diffInDays < 5 ? 2 : ($diffInDays >= 5 ? 1 : 0 ), // 1 - under appointment but greater-than or equal 5 days, 2 - pass due, 3 - under appointment but less-than 5 days
                    'diff_in_days' => $diffInDays,
                    'deal_flow_status' => 'Under Contract', // Add dynamic info soon
                    'address' => $item->address, // Add dynamic info soon
                    'phone' => $item->phone,
                    'email' => $item->email,
                    'years_in_home' => $item->years_in_home,
                    'possible_equity' => $item->possible_equity,
                    'rough_credit_score' => $item->rough_credit_score,
                    'zillow_estimate' => $item->zillow_estimate
                ];
                $i++;
            }
        }

        // Return data with proper JSON structure
        return response()->json([
            'success' => true,
            'error' => false,
            'total' => $sql->count(),
            'data' => $data,
            'logistics' => $this->logistics($user_id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $page, $itemPerPage, $userType)
    {
        $resultEditContact = Contact::where('id', $request['id'])->tap(function ($query) use ($request) {  // to return the fields or contacts data being updated
            $query->update([
                'name' => $request['fullname'],
                'address' => $request['address'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'years_in_home' => $request['yearsInHome'],
                'possible_equity' => $request['possibleEquity'],
                'rough_credit_score' => $request['roughCreditScore'],
                'zillow_estimate' => $request['zillowEstimate'],
            ]);
        })->first();
        
        $user_id = auth()->id(); 
        $user_settings = User::where('id', $user_id)->first();
        $data = array();
        $sql = null;
        $i = 0;
        if($userType == 1){
            $sql = User::where('user_role_id', 2)->orderBy('id', 'desc')->paginate($itemPerPage, ['*'], 'page', $page);
            foreach ($sql as $item) {
                $data[$i] = [
                    'user_id' => $item->id,
                    'first_name' => $item->firstname,
                    'last_name' => $item->lastname,
                    'time_on_doors' => 123, // Add dynamic info soon
                    'doors_knocked' => 123, // Add dynamic info soon
                    'contracts' => 123, // Add dynamic info soon
                    'appointment_set' => Appointment::where('user_id', $item->id)->count(), // Add dynamic info soon
                ];
                $i++;
            }
        }else if($userType == 2){
            $sql = Contact::where('user_id', $user_id)->orderBy('id', 'desc')->paginate($itemPerPage, ['*'], 'page', $page);
            foreach ($sql as $item) {
                $sqlContactAppointed = ContactsAppointed::where('contact_id', $item->id)->orderBy('id', 'DESC')->first();
                $getAppointment = null;
                if ($sqlContactAppointed) {
                    $getAppointment = Appointment::where('id', $sqlContactAppointed->appointment_id)->first();
                }

                $diffInDays = 0;
                if($getAppointment){
                    $date = Carbon::parse($getAppointment->date_start)->setTimezone($getAppointment->timezone); // Convert to desired timezone
                    $now = Carbon::now($user_settings->timezone); // Get current time in same timezone

                    $diffInDays = (int)$now->diffInDays($date);
                }

                $data[$i] = [
                    'id' => $item->id,
                    'fullname' => $item->name,
                    'status' => $diffInDays >= 1 && $diffInDays < 5 ? 2 : ($diffInDays >= 5 ? 1 : 0 ), // 1 - under appointment but greater-than or equal 5 days, 2 - pass due, 3 - under appointment but less-than 5 days
                    'diff_in_days' => $diffInDays,
                    'deal_flow_status' => 'Under Contract', // Add dynamic info soon
                    'address' => $item->address, // Add dynamic info soon
                    'phone' => $item->phone,
                    'email' => $item->email,
                    'years_in_home' => $item->years_in_home,
                    'possible_equity' => $item->possible_equity,
                    'rough_credit_score' => $item->rough_credit_score,
                    'zillow_estimate' => $item->zillow_estimate
                ];
                $i++;
            }
        }

        // Return data with proper JSON structure
        return response()->json([
            'success' => true,
            'error' => false,
            'total' => Contact::where('user_id', $user_id)->count(),
            'data' => $data,
            'logistics' => $this->logistics($user_id),
            'resultEditedContact' => $resultEditContact,
        ]);
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

<?php

namespace App\Http\Controllers\Appointment;

use Carbon\Carbon;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Contact;
use App\Models\ContactsAppointed;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->id(); 
        $user_settings = User::where('id', $user_id)->first();

        // Example: Fetch all users with optional pagination
        // Getting all the APPOINTMENTS
        $sql_appointments = Appointment::where('user_id', $user_id)->get();
        // Check if appointments exist
        if ($sql_appointments->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'No appointments found.'
            ], 404);
        }
        $i = 0;
        $appointment_list = array();
        foreach ($sql_appointments as $appointments) {
            $selectedContacts = ContactsAppointed::with(['contact:id,name', 'appoint'])
                ->where('appointment_id', $appointments->id)
                ->get();
            $formattedContacts = $selectedContacts->map(function ($contactAppointed) {
                    $fullName = $contactAppointed->contact->name;
                    $nameParts = explode(' ', $fullName);
                    
                    // Extracting last name (last element of the array)
                    $lastName = array_pop($nameParts);
                    
                    // Remaining parts are considered as first/middle names
                    $firstName = implode(' ', $nameParts);
                    
                    return [
                        'id' => $contactAppointed->contact->id,
                        'complete_name' => "$lastName, $firstName"
                    ];
                });
            $appointment_list[$i] = [
                'id' => $appointments->id,
                'title' => $appointments->title,
                'timezone' => $appointments->timezone,
                'temporary_timezone' => $appointments->temporary_timezone,
                'allDay' => $appointments->allDay,
                'comments' => $appointments->comments,
                'user_id' => $appointments->user_id,
                'created_at' => $appointments->created_at,
                'updated_at' => $appointments->updated_at,
                'date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_start, $appointments->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                'date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_end, $appointments->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                'uc_date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_start, $appointments->timezone)->setTimezone($appointments->timezone)->toDateTimeString(),
                'uc_date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_end, $appointments->timezone)->setTimezone($appointments->timezone)->toDateTimeString(),
                'selectedContacts' => $formattedContacts
            ];
            $i++;
        }


        // Getting all the appointments schedule TOMORROW date
        $add_day = Carbon::now($user_settings->timezone)->addDay()->startOfDay();
        $sql_get_dates = Appointment::where('user_id', $user_id)->get();

        //$tomorrow = Appointment::where('user_id', $user_id)->whereDate('date_start', Carbon::now($user_settings->timezone)->addDay()->startOfDay())->get();

        $i = 0;
        $tomorrow = array();
        foreach ($sql_get_dates as $appointment) {
            $convertedDate = '';
            $convertedDate = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)
                ->setTimezone($user_settings->timezone)
                ->toDateString();
            
            if(Carbon::parse($add_day)->timezone($user_settings->timezone)->toDateString() == $convertedDate){
                $tomorrow[$i] = [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'timezone' => $appointment->timezone,
                    'temporary_timezone' => $appointment->temporary_timezone,
                    'allDay' => $appointment->allDay,
                    'comments' => $appointment->comments,
                    'user_id' => $appointment->user_id,
                    'created_at' => $appointment->created_at,
                    'updated_at' => $appointment->updated_at,
                    'date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'uc_date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                    'uc_date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                ];
                $i++;
            }
        }

        // Getting all the appointments schedule TODAY date
        $to_date = Carbon::now($user_settings->timezone)->startOfDay();

        $i = 0;
        $today = array();
        foreach ($sql_get_dates as $appointment) {
            $convertedDate = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)
                ->setTimezone($user_settings->timezone)
                ->toDateString();

            if(Carbon::parse($to_date)->timezone($user_settings->timezone)->toDateString() == $convertedDate){
                $today[$i] = [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'timezone' => $appointment->timezone,
                    'temporary_timezone' => $appointment->temporary_timezone,
                    'allDay' => $appointment->allDay,
                    'comments' => $appointment->comments,
                    'user_id' => $appointment->user_id,
                    'created_at' => $appointment->created_at,
                    'updated_at' => $appointment->updated_at,
                    'date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'uc_date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                    'uc_date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                ];
                $i++;
            }
        }

        // Return data with proper JSON structure
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $appointment_list,
            'tomorrow' => $tomorrow,
            'today' => $today,
            'contacts' => Contact::selectRaw("
                    CONCAT(
                        split_part(name, ' ', array_length(string_to_array(name, ' '), 1)), ', ',
                        regexp_replace(name, '(.*) (\\S+)$', '\\1')
                    ) AS complete_name, id
                ")
                ->where('user_id', $user_id)
                ->orderByRaw("split_part(name, ' ', array_length(string_to_array(name, ' '), 1)) ASC")
                ->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $user_id = auth()->id();
        $user_settings = User::where('id', $user_id)->first();

        $date_start = Carbon::createFromFormat(
            'Y-m-d H:i', 
            $data['date_start'] . ' ' . $data['timeStart'], 
            $data['timezone'] // For American STandard Time -> 'America/New_York', just pick state later
        );  // ->setTimezone($user_settings->timezone);
        $date_end = Carbon::createFromFormat(
            'Y-m-d H:i', 
            $data['date_start'] . ' ' . $data['timeEnd'], 
            $data['timezone'] // For American STandard Time -> 'America/New_York', just pick state later
        );  // ->setTimezone($user_settings->timezone);

        $appointment_sql_save = new Appointment();
        $appointment_sql_save->user_id = $user_id;
        $appointment_sql_save->title = $data['title'];
        $appointment_sql_save->date_start = $date_start;
        $appointment_sql_save->date_end = $date_end;
        $appointment_sql_save->timezone = $data['timezone'];
        $appointment_sql_save->allDay = $data['allDay'];
        $appointment_sql_save->comments = $data['extendedProps']['comments'];
        $appointment_sql_save->save();

        // Inserted People to be part of the Appointments
        $selectedContacts = $request['selectedContacts'];
        $i = 0;
        foreach ($selectedContacts as $contactId) {
            $contacts_appointed = new ContactsAppointed();
            $contacts_appointed->contact_id = $contactId;
            $contacts_appointed->appointment_id = $appointment_sql_save->id;
            $contacts_appointed->save();
        }

        // Getting all the APPOINTMENTS
        $sql_appointments = Appointment::where('user_id', $user_id)->get();
        $i = 0;
        $appointment_list = array();
        foreach ($sql_appointments as $appointments) {
            $selectedContacts = ContactsAppointed::with(['contact:id,name', 'appoint'])
                ->where('appointment_id', $appointments->id)
                ->get();
            $formattedContacts = $selectedContacts->map(function ($contactAppointed) {
                    $fullName = $contactAppointed->contact->name;
                    $nameParts = explode(' ', $fullName);
                    
                    // Extracting last name (last element of the array)
                    $lastName = array_pop($nameParts);
                    
                    // Remaining parts are considered as first/middle names
                    $firstName = implode(' ', $nameParts);
                    
                    return [
                        'id' => $contactAppointed->contact->id,
                        'complete_name' => "$lastName, $firstName"
                    ];
                });
            $appointment_list[$i] = [
                'id' => $appointments->id,
                'title' => $appointments->title,
                'timezone' => $appointments->timezone,
                'temporary_timezone' => $appointments->temporary_timezone,
                'allDay' => $appointments->allDay,
                'comments' => $appointments->comments,
                'user_id' => $appointments->user_id,
                'created_at' => $appointments->created_at,
                'updated_at' => $appointments->updated_at,
                'date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_start, $appointments->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                'date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_end, $appointments->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                'uc_date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_start, $appointments->timezone)->setTimezone($appointments->timezone)->toDateTimeString(),
                'uc_date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_end, $appointments->timezone)->setTimezone($appointments->timezone)->toDateTimeString(),
                'selectedContacts' => $formattedContacts
            ];
            $i++;
        }

        // Getting all the appointments schedule TOMORROW date
        $add_day = Carbon::now($user_settings->timezone)->addDay()->startOfDay();
        $sql_get_dates = Appointment::where('user_id', $user_id)->get();

        //$tomorrow = Appointment::where('user_id', $user_id)->whereDate('date_start', Carbon::now($user_settings->timezone)->addDay()->startOfDay())->get();

        $i = 0;
        $tomorrow = array();
        foreach ($sql_get_dates as $appointment) {
            $convertedDate = '';
            $convertedDate = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)
                ->setTimezone($user_settings->timezone)
                ->toDateString();
            
            if(Carbon::parse($add_day)->timezone($user_settings->timezone)->toDateString() == $convertedDate){
                $tomorrow[$i] = [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'timezone' => $appointment->timezone,
                    'temporary_timezone' => $appointment->temporary_timezone,
                    'allDay' => $appointment->allDay,
                    'comments' => $appointment->comments,
                    'user_id' => $appointment->user_id,
                    'created_at' => $appointment->created_at,
                    'updated_at' => $appointment->updated_at,
                    'date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'uc_date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                    'uc_date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                ];
                $i++;
            }
        }

        // Getting all the appointments schedule TODAY date
        $to_date = Carbon::now($user_settings->timezone)->startOfDay();

        $i = 0;
        $today = array();
        foreach ($sql_get_dates as $appointment) {
            $convertedDate = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)
                ->setTimezone($user_settings->timezone)
                ->toDateString();

            if(Carbon::parse($to_date)->timezone($user_settings->timezone)->toDateString() == $convertedDate){
                $today[$i] = [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'timezone' => $appointment->timezone,
                    'temporary_timezone' => $appointment->temporary_timezone,
                    'allDay' => $appointment->allDay,
                    'comments' => $appointment->comments,
                    'user_id' => $appointment->user_id,
                    'created_at' => $appointment->created_at,
                    'updated_at' => $appointment->updated_at,
                    'date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'uc_date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                    'uc_date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                ];
                $i++;
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $appointment_list,
            'message' => 'Event created successfully!',
            'tomorrow' => $tomorrow,
            'today' => $today,
            'contacts' => Contact::selectRaw("
                    CONCAT(
                        split_part(name, ' ', array_length(string_to_array(name, ' '), 1)), ', ',
                        regexp_replace(name, '(.*) (\\S+)$', '\\1')
                    ) AS complete_name, id
                ")
                ->where('user_id', $user_id)
                ->orderByRaw("split_part(name, ' ', array_length(string_to_array(name, ' '), 1)) ASC")
                ->get(),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $user_id = auth()->id();
        $user_settings = User::where('id', $user_id)->first();
        if($data['allDay']){
            $extendedProps = json_decode(json_encode($request->input('extendedProps')), true);
            Appointment::where('id', $data['id'])->update([
                'title'     => $data['title'],
                'allDay'    => $data['allDay'],
                'comments'  => $extendedProps['comments']
            ]);
        }else{
            $date_start = Carbon::createFromFormat(
                'Y-m-d H:i', 
                $data['date_start'] . ' ' . $data['timeStart'], 
                $data['timezone'] // For American STandard Time -> 'America/New_York', just pick state later
            ); // ->setTimeZone('UTC')
            $date_end = Carbon::createFromFormat(
                'Y-m-d H:i', 
                $data['date_start'] . ' ' . $data['timeEnd'], 
                $data['timezone'] // For American STandard Time -> 'America/New_York', just pick state later
            ); // ->setTimeZone('UTC')

            $extendedProps = json_decode(json_encode($request->input('extendedProps')), true);

            Appointment::where('id', $data['id'])->update([
                'title'     => $data['title'],
                'date_start' => $date_start,
                'date_end'   => $date_end,
                'allDay'    => $data['allDay'],
                'timezone'    => $data['timezone'],
                'comments'  => $extendedProps['comments']
            ]);
        }

        // deletion of all records with id in db
        ContactsAppointed::where('appointment_id', $data['id'])->delete();
        // Inserted People to be part of the Appointments
        $selectedContacts_sql = $data['selectedContacts'];
        foreach ($selectedContacts_sql as $contactId) {
            $contacts_appointed = new ContactsAppointed();
            $contacts_appointed->contact_id = $contactId;
            $contacts_appointed->appointment_id = $data['id'];
            $contacts_appointed->save();
        }

        // Getting all specific APPOINTMENTS
        $sql_appointments = Appointment::where('id', $data['id'])->get();
        $i = 0;
        $appointment_list = array();
        foreach ($sql_appointments as $appointments) {
            $selectedContacts = ContactsAppointed::with(['contact:id,name', 'appoint'])
                ->where('appointment_id', $appointments->id)
                ->get();
            $formattedContacts = $selectedContacts->map(function ($contactAppointed) {
                    $fullName = $contactAppointed->contact->name;
                    $nameParts = explode(' ', $fullName);
                    
                    // Extracting last name (last element of the array)
                    $lastName = array_pop($nameParts);
                    
                    // Remaining parts are considered as first/middle names
                    $firstName = implode(' ', $nameParts);
                    
                    return [
                        'id' => $contactAppointed->contact->id,
                        'complete_name' => "$lastName, $firstName"
                    ];
                });
            $appointment_list[$i] = [
                'id' => $appointments->id,
                'title' => $appointments->title,
                'timezone' => $appointments->timezone,
                'temporary_timezone' => $appointments->temporary_timezone,
                'allDay' => $appointments->allDay,
                'comments' => $appointments->comments,
                'user_id' => $appointments->user_id,
                'created_at' => $appointments->created_at,
                'updated_at' => $appointments->updated_at,
                'date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_start, $appointments->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                'date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_end, $appointments->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                'uc_date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_start, $appointments->timezone)->setTimezone($appointments->timezone)->toDateTimeString(),
                'uc_date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointments->date_end, $appointments->timezone)->setTimezone($appointments->timezone)->toDateTimeString(),
                'selectedContacts' => $formattedContacts
            ];
            $i++;
        }

        // Getting all the appointments schedule TOMORROW date
        $add_day = Carbon::now($user_settings->timezone)->addDay()->startOfDay();
        $sql_get_dates = Appointment::where('user_id', $user_id)->get();

        //$tomorrow = Appointment::where('user_id', $user_id)->whereDate('date_start', Carbon::now($user_settings->timezone)->addDay()->startOfDay())->get();

        $i = 0;
        $tomorrow = array();
        foreach ($sql_get_dates as $appointment) {
            $convertedDate = '';
            $convertedDate = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)
                ->setTimezone($user_settings->timezone)
                ->toDateString();
            
            if(Carbon::parse($add_day)->timezone($user_settings->timezone)->toDateString() == $convertedDate){
                $tomorrow[$i] = [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'timezone' => $appointment->timezone,
                    'temporary_timezone' => $appointment->temporary_timezone,
                    'allDay' => $appointment->allDay,
                    'comments' => $appointment->comments,
                    'user_id' => $appointment->user_id,
                    'created_at' => $appointment->created_at,
                    'updated_at' => $appointment->updated_at,
                    'date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'uc_date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                    'uc_date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                ];
                $i++;
            }
        }

        // Getting all the appointments schedule TODAY date
        $to_date = Carbon::now($user_settings->timezone)->startOfDay();

        $i = 0;
        $today = array();
        foreach ($sql_get_dates as $appointment) {
            $convertedDate = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)
                ->setTimezone($user_settings->timezone)
                ->toDateString();

            if(Carbon::parse($to_date)->timezone($user_settings->timezone)->toDateString() == $convertedDate){
                $today[$i] = [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'timezone' => $appointment->timezone,
                    'temporary_timezone' => $appointment->temporary_timezone,
                    'allDay' => $appointment->allDay,
                    'comments' => $appointment->comments,
                    'user_id' => $appointment->user_id,
                    'created_at' => $appointment->created_at,
                    'updated_at' => $appointment->updated_at,
                    'date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($user_settings->timezone)->toDateTimeString(),
                    'uc_date_start' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_start, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                    'uc_date_end' => Carbon::createFromFormat('Y-m-d H:i:s', $appointment->date_end, $appointment->timezone)->setTimezone($appointment->timezone)->toDateTimeString(),
                ];
                $i++;
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $appointment_list,
            'message' => 'Event updated successfully!',
            'tomorrow' => $tomorrow,
            'today' => $today,
            'contacts' => Contact::selectRaw("
                    CONCAT(
                        split_part(name, ' ', array_length(string_to_array(name, ' '), 1)), ', ',
                        regexp_replace(name, '(.*) (\\S+)$', '\\1')
                    ) AS complete_name, id
                ")
                ->where('user_id', $user_id)
                ->orderByRaw("split_part(name, ' ', array_length(string_to_array(name, ' '), 1)) ASC")
                ->get(),
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

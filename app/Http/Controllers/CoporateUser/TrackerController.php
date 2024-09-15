<?php

namespace App\Http\Controllers\CoporateUser;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\TrackingConfirmation;
use Carbon\Carbon;


class TrackerController extends Controller
{
    public function index(){
        try {
            // Get the logged-in user's ID
            $agentId = Auth::id();
            $userRole = Auth::user()->user_role;

            // If the user is SuperAdmin or MasterAdmin, retrieve all entries
            if (in_array($userRole, ['SuperAdmin', 'MasterAdmin'])) {
                // Retrieve all records from the tracker_bookings table
                $appointments = DB::connection('mysql_non_laravel')
                    ->table('tracker_bookings')
                    ->get();
            } else {
                // Otherwise, retrieve only the records where agent_id matches the logged-in user
                $appointments = DB::connection('mysql_non_laravel')
                    ->table('tracker_bookings')
                    ->where('agent_id', $agentId)
                    ->get();
            }

            // return $appointments;
            // Return the view with the appointments data
            return view('trackers.index', compact('appointments'));

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error retrieving appointments for agent', [
                'error' => $e->getMessage(),
                'agent_id' => $agentId,
            ]);

            // Redirect back with an error message
            return redirect()->back()->with('error', 'There was an error retrieving your appointments. Please try again.');
        }
        // return view('trackers.index');
    }
    public function create(){
        return view('trackers.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:11|max:11',
            'veh_make' => 'required|string|max:255',
            'veh_model' => 'required|string|max:255',
            'veh_year' => 'required|string|min:4|max:4',
            'veh_vin' => 'nullable|string|max:17',
            'plate_num' => 'required|string|min:7|max:8',
            'appointment_date' => 'required|date',
            'time' => 'required|string',
            'comments' => 'nullable|string|max:255',
            'referral_code' => 'nullable|string|max:255',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Log validation errors
            Log::error('Validation failed for appointment submission', [
                'errors' => $validator->errors()->all(),
                'request' => $request->all(),
            ]);

            // Return back with validation errors and old input
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Check if the appointment date and time are already booked
            $existingAppointment = DB::connection('mysql_non_laravel')
                ->table('tracker_bookings')
                ->where('appointment_date', $request->input('appointment_date'))
                ->where('appointment_time', $request->input('time'))
                ->exists();

            if ($existingAppointment) {
                // If the date and time are already booked, return with an error message
                return redirect()->back()->withInput()->with('error', 'The selected appointment date and time are already booked. Please choose another date or time.');
            }

            $existingPlateNumber = DB::connection('mysql_non_laravel')
                ->table('tracker_bookings')
                ->where('plate_num', $request->input('plate_num'))
                ->exists();

            if ($existingPlateNumber) {
                // If the plate number is already booked, return with an error message
                return redirect()->back()->withInput()->with('error', 'The inputted plate number is already booked.');
            }

            $metadata = [];

            // Insert the data into the non-Laravel database
            DB::connection('mysql_non_laravel')->table('tracker_bookings')->insert([
                'agent_id' => auth()->id(), // Assuming you're using Laravel's auth to get the logged-in user
                'fullname' => $request->input('fullname'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'veh_make' => $request->input('veh_make'),
                'veh_model' => $request->input('veh_model'),
                'veh_year' => $request->input('veh_year'),
                'plate_num' => $request->input('plate_num'),
                'appointment_date' => $request->input('appointment_date'),
                'appointment_time' => $request->input('time'),
                'metadata' => json_encode(['veh_vin' => $request->input('veh_vin')]),
                'comments' => $request->input('comments'),
                'referral_code' => $request->input('referral_code'),
                'status' => 'booked', // Assuming new entries have a status of 'pending'
                'created_at' => Carbon::now(), // Using Laravel's Carbon for current timestamp
            ]);

            // Prepare the data for email
            $appointmentData = $request->only([
                'fullname', 'email', 'veh_make', 'veh_model', 'veh_year', 'plate_num', 'appointment_date', 'time'
            ]);
            $appointmentData['appointment_time'] = $request->input('time');

            // Send confirmation email
            Mail::to($request->input('email'))->send(new TrackingConfirmation($appointmentData));

            // Log successful submission
            Log::info('Appointment successfully created', [
                'fullname' => $request->input('fullname'),
                'email' => $request->input('email'),
            ]);

            // Redirect to a success page or back with a success message
            return redirect()->route('trackers.index')->with('success', 'Appointment created successfully! Confirmation email sent.');

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error while creating appointment', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            // Return back with old values and an error message
            return redirect()->back()->withInput()->with('error', 'There was an error creating the appointment. Please try again.');
        }
    }
    // public function store(Request $request){
    //     //store tracker data
    //     return redirect()->route('trackers.index')->with('success', 'Tracker created successfully');
    // }
    public function show($id){
        return view('trackers.show');
    }
    public function edit($id){
        $appointment = DB::connection('mysql_non_laravel')->table('tracker_bookings')->find($id);
        return response()->json($appointment); // Return appointment data as JSON for the modal form
        // return view('trackers.edit');
    }
    public function update(Request $request, $id){
        //update tracker data

        // Validate request inputs
        $validatedData = $request->validate([
            'fullname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'veh_make' => 'required',
            'veh_model' => 'required',
            'veh_year' => 'required',
            'plate_num' => 'required',
        ]);

        // Retrieve the existing metadata for the appointment
        $appointment = DB::connection('mysql_non_laravel')->table('tracker_bookings')->find($id);
        $metadata = json_decode($appointment->metadata, true); // Decode metadata into array

        // Update the veh_vin in the metadata
        $metadata['veh_vin'] = $request->input('veh_vin');

        // Prepare the data for update
        $updateData = [
            'fullname' => $request->input('fullname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'veh_make' => $request->input('veh_make'),
            'veh_model' => $request->input('veh_model'),
            'veh_year' => $request->input('veh_year'),
            'plate_num' => $request->input('plate_num'),
            'metadata' => json_encode($metadata), // Save updated metadata as JSON string
        ];

        // Update the appointment record
        DB::connection('mysql_non_laravel')->table('tracker_bookings')
            ->where('id', $id)
            ->update($updateData);


        return redirect()->back()->with('success', 'Tracker updated successfully!');
        // return redirect()->route('trackers.index')->with('success', 'Tracker updated successfully');
    }
    public function destroy($id){
        //delete tracker data
        return redirect()->route('trackers.index')->with('success', 'Tracker deleted successfully');
    }

    public function complete(Request $request)
    {
        $id = $request->input('id');
        $comments = $request->input('comments');

        // Retrieve the existing metadata and update with comments
        $appointment = DB::connection('mysql_non_laravel')->table('tracker_bookings')->find($id);
        $metadata = json_decode($appointment->metadata, true);
        $metadata['completion_comments'] = $comments;

        // Update the status and metadata
        DB::connection('mysql_non_laravel')->table('tracker_bookings')
            ->where('id', $id)
            ->update([
                'status' => 'completed',
                'metadata' => json_encode($metadata),
                'comments' => $comments,
            ]);

        return redirect()->back()->with('success', 'Job completed successfully!');
    }
}

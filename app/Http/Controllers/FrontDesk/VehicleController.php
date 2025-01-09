<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

class VehicleController extends Controller
{
    public function index()
    {
        // Retrieve all vehicles
        $vehicles = Vehicle::get();
        // return $vehicles;

        // Retrieve active customers
        $customers = Customer::where('cust_view', '!=', 'hidden')->get();

        // Return the vehicles view
        return view('Vehicles.index', compact('vehicles', 'customers'));
    }

    public function store(Request $request)
    {
        $rules = [
            'plate_number' => 'required|string|max:10|unique:vehicles,plate_number',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_make' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|digits:11',
        ];

        $customMessages = [
            'plate_number.unique' => 'This plate number is already registered.',
            'owner_phone.digits' => 'Phone number must be exactly 11 digits.',
        ];

        try {
            // Validate the request
            $validatedData = Validator::make($request->all(), $rules, $customMessages)->validate();

            // Store the data in the database
            Vehicle::create([
                'plate_number' => $validatedData['plate_number'],
                'vehicle_model' => $validatedData['vehicle_model'],
                'vehicle_make' => $validatedData['vehicle_make'],
                'owner_name' => $validatedData['owner_name'],
                'owner_phone' => $validatedData['owner_phone'],
                'status' => 'visible',
            ]);

            // Log success
            Log::info('Vehicle added successfully', ['plate_number' => $validatedData['plate_number']]);

            return redirect()->back()->with('success', 'Vehicle added successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to add vehicle: ' . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Failed to add vehicle. Please try again.');
        }
    }

    public function edit($id)
    {
        try {
            // Retrieve the vehicle
            $vehicle = Vehicle::find($id);

            if (!$vehicle) {
                return response()->json(['error' => 'Vehicle not found'], 404);
            }

            Log::info('Vehicle data retrieved for editing', ['vehicle_id' => $id]);

            return response()->json($vehicle);
        } catch (\Exception $e) {
            Log::error('Error retrieving vehicle data', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching the vehicle data'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'body_type' => 'required|string',
            'vec_year' => 'required|numeric',
            'vec_make' => 'required|string',
            'vec_model' => 'required|string',
            'vec_plate' => 'required|string|max:8',
            'vec_vin' => 'required|string|max:17',
        ]);

        try {
            $vehicle = Vehicle::find($id);

            if ($vehicle) {
                $vehicle->update($request->all());

                Log::info('Vehicle updated successfully', ['vehicle_id' => $id]);

                return redirect()->back()->with('success', 'Vehicle updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Vehicle not found!');
            }
        } catch (\Exception $e) {
            Log::error('Error updating vehicle', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while updating the vehicle.');
        }
    }

    public function delete($id)
    {
        try {
            $vehicle = Vehicle::find($id);

            if ($vehicle) {
                $vehicle->update(['status' => 'hidden']);

                Log::info('Vehicle deleted successfully', ['vehicle_id' => $id]);

                return redirect()->back()->with('success', 'Vehicle deleted successfully!');
            } else {
                return redirect()->back()->with('error', 'Vehicle not found!');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting vehicle', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while deleting the vehicle.');
        }
    }
}

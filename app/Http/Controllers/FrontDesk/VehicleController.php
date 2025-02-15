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
        return view('vehicles.index', compact('vehicles', 'customers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required',
            'body_type' => 'required|string',
            'vec_year' => 'required|numeric',
            'vec_make' => 'required|string',
            'vec_model' => 'required|string',
            'vec_plate' => 'required|string|max:8',
            'vec_vin' => 'required|string|max:17',
        ]);

        try {
            // Create a new vehicle with the correct column mappings
            $vehicle = Vehicle::create([
                'cust_id' => $validatedData['customer_id'],  // Foreign key reference to customers table
                'vec_body' => $validatedData['body_type'],
                'vec_year' => $validatedData['vec_year'],
                'vec_make' => $validatedData['vec_make'],
                'vec_model' => $validatedData['vec_model'],
                'vec_plate' => $validatedData['vec_plate'],
                'vec_vin' => $validatedData['vec_vin'],
                'vec_view' => 'visible', // Default value
                'vec_reg_time' => time(), // Storing current timestamp
            ]);

            // Log success
            Log::info('Vehicle added successfully', ['vec_plate' => $validatedData['vec_plate']]);

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

    public function addVehicle(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'plate' => 'required|string|max:255',
            'model' => 'required|string|max:255',
        ]);

        // Create a new vehicle
        $vehicle = Vehicle::create([
            'number_plate' => $validated['plate'],
            'model' => $validated['model'],
            'customer_id' => $request->input('customer_id'), // Ensure this is passed from the front-end
        ]);

        return response()->json($vehicle, 201);
    }

    public function getVehiclesByCustomer(Request $request)
    {
        $customerId = $request->query('customer_id');
        $search = $request->query('search');
        $cust_id = $request->query('cust_id');

        // Fetch vehicles by customer ID
        if ($customerId) {
            $vehicles = Vehicle::where('cust_id', $customerId)->get();
            return response()->json($vehicles);
        }

        // Fetch vehicles by search query
        if ($search) {
            $vehicles = Vehicle::where('vec_plate', 'LIKE', '%' . $search . '%')->where('cust_id', $cust_id)->get();
            return response()->json($vehicles);
        }

        return response()->json([], 400); // Bad request if no parameters are provided
    }
}

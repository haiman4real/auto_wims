<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CustomersController extends Controller
{
    public function index()
    {
        $userRole = Auth::user()->user_role;

        // Retrieve all records from the customers table
        $customers = DB::connection('mysql_non_laravel')
                                ->table('customers')
                                ->where('cust_view', '!=', 'hidden')
                                ->get();


        // return $customers;
        return view('Customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'mode_of_contact' => 'required|string|in:email,sms|max:255', // Accept only 'email' or 'sms'
            'account_type' => 'required|string|in:individual,corporate|max:255', // Only 'individual' or 'corporate'
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|digits:11', // Must be exactly 11 digits
            'email' => 'required|email|max:255|unique:mysql_non_laravel.customers,cust_email', // Unique email in the custom DB
            'address' => 'required|string|max:255',
            'lga' => 'required|string|in:Agege,Alimosho,Apapa,Ifako-Ijaye,Ikeja,Kosofe,Mushin,Oshodi-Isolo,Shomolu,'
                . 'Eti-Osa,Lagos Island,Lagos Mainland,Surulere,Ojo,Ajeromi-Ifelodun,Amuwo-Odofin,Badagry,Ikorodu,'
                . 'Ibeju-Lekki,Epe', // Accept only LGAs from the list
        ];

        // Validate the incoming request data with custom messages
        $customMessages = [
            'phone_number.digits' => 'Phone number must be exactly 11 digits.',
            'email.unique' => 'This email is already registered.',
        ];

        try {
            // Validate the request
            $validatedData = Validator::make($request->all(), $rules, $customMessages)->validate();

            // Log validation success
            Log::info('Customer data validated successfully', ['data' => $validatedData]);

            // Store the data in the custom database connection
            DB::connection('mysql_non_laravel')->table('customers')->insert([
                'cust_name' => $validatedData['full_name'],             // Storing full name as cust_name
                'cust_mobile' => $validatedData['phone_number'],        // Storing phone number as cust_mobile
                'cust_email' => $validatedData['email'],                // Storing email as cust_email
                'cust_address' => $validatedData['address'],            // Storing address as cust_address
                'cust_lga' => $validatedData['lga'],                    // Storing LGA as cust_lga
                'cust_mode' => $validatedData['mode_of_contact'],       // Storing mode of contact as cust_mode
                'cust_type' => $validatedData['account_type'],          // Storing account type as cust_type
                'cust_reg_time' => time(),                               // Storing current time as cust_reg_time
                'cust_asset' => 0,                                   // Default null value for cust_asset
                'cust_view' => 'visible',                         // Adjust according to your logic
            ]);

            // Log the successful database insertion
            Log::info('Customer data added successfully', ['email' => $validatedData['email']]);

            return redirect()->back()->with('success', 'Customer added successfully!');

        } catch (ValidationException $e) {
            // Log validation errors
            Log::warning('Validation failed for customer data', ['errors' => $e->errors()]);

            // Redirect back with validation errors
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Failed to add customer: ' . $e->getMessage(), ['request_data' => $request->all()]);

            return redirect()->back()->with('error', 'Failed to add customer. Please try again.');
        }
    }

    public function deleteCustomer($customerId) {
        // Retrieve the customer
        $customer = DB::connection('mysql_non_laravel')
                    ->table('customers')
                    ->where('cust_id', $customerId)
                    ->first();

        if ($customer) {
            // Update the cust_view to 'hidden'
            DB::connection('mysql_non_laravel')
                    ->table('customers')
                    ->where('cust_id', $customerId)
                    ->update(['cust_view' => 'hidden']);

            // Log the successful update
            Log::info('Customer view updated to hidden', ['customer_id' => $customerId]);

            return redirect()->back()->with('success', 'Customer deleted successfully!');
        } else {
            // Customer not found, log an error
            Log::warning('Customer not found', ['customer_id' => $customerId]);

            return redirect()->back()->with('error', 'Customer not found!');
        }
    }

    public function edit($id)
    {
        try {
            // Fetch customer details
            $customer = DB::connection('mysql_non_laravel')->table('customers')->where('cust_id', $id)->first();

            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            // Log the event
            Log::info('Customer data retrieved for editing', ['customer_id' => $id]);

            return response()->json($customer);
        } catch (\Exception $e) {
            Log::error('Error retrieving customer data', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching the customer data'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:11',
            'address' => 'required|string|max:255',
            'lga' => 'required|string|max:255',
        ]);

        try {
            // Update the customer in the database
            DB::connection('mysql_non_laravel')->table('customers')
                ->where('cust_id', $id)
                ->update([
                    'cust_name' => $request->input('full_name'),
                    'cust_email' => $request->input('email'),
                    'cust_mobile' => $request->input('phone_number'),
                    'cust_address' => $request->input('address'),
                    'cust_lga' => $request->input('lga'),
                ]);

            // Log the successful update
            Log::info('Customer updated successfully', ['customer_id' => $id]);

            return redirect()->back()->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating customer', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'An error occurred while updating the customer.');
        }
    }

}

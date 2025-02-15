<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobServices;
use Illuminate\Support\Facades\Log;

class JobServicesController extends Controller
{
    public function returnServices(){
        $jobServices = JobServices::all();

        // return $jobServices;
        return view('job-services.index', compact('jobServices'));
    }

    public function addService(){
        return view('job-services.create');
    }

    public function storeService(Request $request){
        $validatedData = $request->validate([
            'serv_name' => 'required|string',
            'serv_cat' => 'required|string',
            'serv_duration' => 'required|string',
            'serv_amount' => 'required|numeric',
        ]);

        try {
            JobServices::create([
                'serv_name' => $validatedData['serv_name'],
                'serv_cat' => $validatedData['serv_cat'],
                'serv_duration' => $validatedData['serv_duration'],
                'serv_amount' => $validatedData['serv_amount'],
                'serv_status' => 'visible',
                'serv_reg_time' => time(),
            ]);
            Log::info('Service added successfully', ['name' => $validatedData['serv_name']]);

            return redirect()->back()->with('success', 'Service added successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to add service: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add service.');
        }
    }

    public function show($id){
        return view('job-services.show', compact('id'));
    }

    public function editService($id){
        return view('job-services.edit', compact('id'));
    }

    public function updateService(Request $request, $id){
        $validatedData = $request->validate([
            'serv_name' => 'required|string',
            'serv_cat' => 'required|string',
            'serv_duration' => 'required|string',
            'serv_amount' => 'required|numeric',
            'serv_status' => 'required|in:visible,hidden',
        ]);

        try {
            $service = JobServices::findOrFail($id);

            $service->update([
                'serv_name' => $validatedData['serv_name'],
                'serv_cat' => $validatedData['serv_cat'],
                'serv_duration' => $validatedData['serv_duration'],
                'serv_amount' => $validatedData['serv_amount'],
                'serv_status' => $validatedData['serv_status'],
            ]);
            Log::info('Service updated successfully', ['name' => $validatedData['serv_name']]);

            return response()->json(['message' => 'Service updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update service: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update service'], 500);
        }
    }

    public function destroyService($id){
        //
    }

}

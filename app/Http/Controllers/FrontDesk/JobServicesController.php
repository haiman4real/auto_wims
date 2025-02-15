<?php

namespace App\Http\Controllers\FrontDesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobServices;

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
        //
    }

    public function show($id){
        return view('job-services.show', compact('id'));
    }

    public function editService($id){
        return view('job-services.edit', compact('id'));
    }

    public function updateService(Request $request, $id){
        //
    }

    public function destroyService($id){
        //
    }

}

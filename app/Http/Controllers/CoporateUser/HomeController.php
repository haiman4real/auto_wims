<?php

namespace App\Http\Controllers\CoporateUser;

use App\Http\Controllers\Controller;
use App\Models\a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(a $a)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(a $a)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, a $a)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(a $a)
    {
        //
    }

    public function testNonLaravelDb()
    {
        $data = DB::connection('mysql_non_laravel')->table('customers')->get();
        return $data;
    }
}

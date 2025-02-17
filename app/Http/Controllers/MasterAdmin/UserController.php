<?php

namespace App\Http\Controllers\MasterAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(){
        $users = User::where('user_role', '!=', 'MasterAdmin')->get();

        $loggedInUser = Auth::user();

        // return $users;
        return view('MasterAdmin.Users.users', compact('users'));
    }

    public function create(){
        // $stations = Stations::where('status', 'active')->get();
        // $userRoles = UserRoles::where('status', 'active')->get();
        // return view('AdminOps.addusers')->with(['stations' => $stations, 'userRoles' => $userRoles]);
        return view('MasterAdmin.Users.addusers');
    }

    public function store(Request $request){
        $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_phone' => ['required'],
            'user_station' => ['required','string'],
        ]);

        function getRoleLevel($user_role) {
            switch ($user_role) {
                case 'MasterAdmin':
                    return 1;
                case 'SuperAdmin':
                    return 2;
                case 'AdminOne':
                    return 3;
                case 'AdminTwo':
                    return 4;
                case 'AdminThree':
                    return 5;
                case 'CustomerService':
                    return 6;
                case 'FrontDesk':
                    return 7;
                case 'Technician':
                    return 8;
                case 'ServiceAdvisor':
                    return 9;
                case 'JobController':
                    return 10;
                case 'AccountsAdmin':
                    return 11;
                case 'BusinessView':
                    return 12;
                case 'GuestUser':
                    return 13;
                case 'CoporateUser':
                    return 14;
                default:
                    // Default level if role doesn't match
                    return 0;
            }
        }

        function getStationNo($user_station) {
            switch ($user_station) {
                case 'HQ':
                    return 1;
                case 'Ojodu':
                    return 2;
                case 'Abuja':
                    return 3;
                case 'Asaba':
                    return 4;
                default:
                    // Default level if station doesn't match
                    return 0;
            }
        }

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_phone' => $request->user_phone,
            'user_level' => getRoleLevel($request->user_role),
            'user_role' => $request->user_role,
            'user_station' => $request->user_station,
            'user_station_no' => getStationNo($request->user_station),
        ]);
        $user->save();

        Log::info('New User '.$request->user_name.' added by '. Auth::user()->user_name);

        return redirect()->route('users.index')->with(["status" => true, "message" => 'User created successfully']);

    }

    public function edit(User $user)
    {
        // $stations = Stations::all();
        return view('users.edit', compact('user', 'stations'));
    }

    public function update(Request $request, User $user)
    {
        // return $request;

        $user->user_name = $request->input('user_name');
        $user->email = $request->input('email');
        $user->user_station = $request->input('user_station');
        $user->user_phone = $request->input('user_phone');
        $user->save();

        // return $user;
        // return $request->only(['user_name', 'email', 'user_station']);
        // $user->update($request->only(['user_name', 'email', 'user_station']));
        return redirect()->route('users.index')->with(["status" => true, "message" => "User Updated Successfully"]);
    }

    public function enableUser($id)
    {
        $user = User::findOrFail($id);
        $user->user_status = 'active';
        $user->login_attempts = 0;
        $user->save();

        Log::info("Enable User done by - " . Auth::user()->user_name);
        return redirect()->route('users.index')->with(["status" => true, "message" => "User enabled successfully."]);
    }

    public function disableUser($id)
    {
        Log::info("Disabling user");
        $user = User::findOrFail($id);
        $user->user_status = 'disabled';
        $user->save();

        Log::info("Disable User done by - " . Auth::user()->user_name);
        return redirect()->route('users.index')->with(["status" => true, "message" => "User disabled successfully."]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        Log::warning('Registering new user');

        $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_phone' => ['required'],
            'user_role' => ['required','string'],
            'user_station' => ['required','string'],
        ]);

        Log::info($request);

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

        Log::info(getRoleLevel($request->user_role));
        Log::info(getStationNo($request->user_station));

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

        Log::info($user);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

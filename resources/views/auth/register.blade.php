<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="user_name" :value="__('Name')" />
            <x-text-input id="user_name" class="block mt-1 w-full" type="text" name="user_name" :value="old('user_name')" required
                autofocus autocomplete="user_name" />
            <x-input-error :messages="$errors->get('user_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="user_phone" :value="__('Phone Number')" />
            <x-text-input id="user_phone" class="block mt-1 w-full" type="text" name="user_phone"
                :value="old('user_phone')" required autocomplete="user_phone" />
            <x-input-error :messages="$errors->get('user_phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- User Role -->
        <div class="mt-4">
            <x-input-label for="user_role" :value="__('Role')" />
            <select id="user_role" class="form-control block mt-1 w-full" type="text" name="user_role" :value="old('user_role')"
                required autocomplete="user_role">
                <option></option>
                <option value="MasterAdmin">Master Admin</option>
                <option value="SuperAdmin">Super Admin</option>
                <option value="AdminOne">Admin One</option>
                <option value="AdminTwo">Admin Two</option>
                <option value="AdminThree">Admin Three</option>
                <option value="CustomerService">Customer Service</option>
                <option value="FrontDesk">Front Desk</option>
                <option value="Technician">Technician</option>
                <option value="ServiceAdvisor">Service Advisor</option>
                <option value="JobController">Job Controller</option>
                <option value="AccountsAdmin">Accounts Admin</option>
                <option value="BusinessView">Business View</option>
                <option value="GuestUser">Guest User</option>
                <option value="CoporateUser">Corporate User</option>
            </select>
            <x-input-error :messages="$errors->get('user_role')" class="mt-2" />
        </div>

        <!-- User Station -->
        <div class="mt-4">
            <x-input-label for="user_station" :value="__('Station')" />
            <select id="user_station" class="form-control block mt-1 w-full" type="text" name="user_station" :value="old('user_station')"
                required autocomplete="user_station">
                <option></option>
                <option value="HQ">HQ</option>
                <option value="Ojodu">Ojodu</option>
                <option value="Abuja">Abuja</option>
                <option value="Asaba">Asaba</option>
            </select>
            <x-input-error :messages="$errors->get('user_station')" class="mt-2" />
        </div>



        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

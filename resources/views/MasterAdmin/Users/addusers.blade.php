<x-app-layout>
    <x-slot name="title">
        Add User
    </x-slot>
    <div class="card shadow-lg mx-4 ">
        <div class="card-body p-3">
          <div class="row gx-4">
            <div class="col-auto">
              <div class="avatar avatar-xl position-relative">
                {{-- <img src="{{asset('img/price-tag.png')}}" alt="profile_image" class="w-100 border-radius-lg shadow-sm"> --}}
                <i class="ni ni-books text-warning text-sm opacity-10"></i>
              </div>
            </div>
            <div class="col-auto my-auto">
              <div class="h-100">
                <h5 class="mb-1">
                    Add new user
                </h5>
                <p class="mb-0 font-weight-bold text-sm">
                  Enter new user information
                  @if ($errors->any())
                  <div class="alert alert-danger alert-dismissible text-white" role="alert">
                  <strong>Whoops!</strong> There were some problems with your input.<br><br>
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                      <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

              @endif
                </p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
              <div class="nav-wrapper position-relative end-0">

              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                  {{-- <p class="mb-0">Fill the form below:</p> --}}
                  {{-- <button class="btn btn-primary btn-sm ms-auto">Settings</button> --}}
                </div>
              </div>
              <hr class="horizontal dark">
              <div class="card-body">
                {{-- <p class="text-uppercase text-sm">User Information</p> --}}
                <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Name -->
                    <div>
                        <x-input-label for="user_name" class="form-control-label" :value="__('Name')" />
                        <x-text-input id="user_name" class="form-control" type="text" name="user_name" :value="old('user_name')" required
                            autofocus autocomplete="user_name" />
                        <x-input-error :messages="$errors->get('user_name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" class="form-control-label" :value="__('Email')" />
                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                            autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone Number -->
                    <div class="mt-4">
                        <x-input-label for="user_phone" class="form-control-label" :value="__('Phone Number')" />
                        <x-text-input id="user_phone" class="form-control" type="text" name="user_phone"
                                :value="old('user_phone')" minlength="11" maxlength="11" required
                                oninput="validatePhoneInput(this)" autocomplete="user_phone" />
                        <x-input-error :messages="$errors->get('user_phone')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" class="form-control-label" :value="__('Password')" />

                        <x-text-input id="password" class="form-control" type="password" name="password" required
                            autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" class="form-control-label" :value="__('Confirm Password')" />

                        <x-text-input id="password_confirmation" class="form-control" type="password"
                            name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- User Role -->
                    <div class="mt-4">
                        <x-input-label for="user_role" class="form-control-label" :value="__('Role')" />
                        <select id="user_role" class="form-select" type="text" name="user_role" :value="old('user_role')"
                            required autocomplete="user_role">
                            <option></option>
                            {{-- <option value="MasterAdmin">Master Admin</option> --}}
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
                            {{-- @forelse($userRoles as $userRole)
                                <option value="{{$userRole->id }}">{{ $userRole->role_public_name }}</option>
                            @empty
                            @endforelse --}}
                        </select>
                        <x-input-error :messages="$errors->get('user_role')" class="mt-2" />
                    </div>

                    <!-- User Station -->
                    <div class="mt-4">
                        <x-input-label for="user_station" class="form-control-label" :value="__('Station')" />
                        <select id="user_station" class="form-select" type="text" name="user_station" :value="old('user_station')"
                            required autocomplete="user_station">
                            <option></option>
                            <option value="HQ">HQ</option>
                            <option value="Ojodu">Ojodu</option>
                            <option value="Abuja">Abuja</option>
                            <option value="Asaba">Asaba</option>
                            {{-- @forelse($stations as $station)
                                <option value="{{$station->id }}">{{ $station->station }}</option>
                            @empty
                            @endforelse --}}
                        </select>
                        <x-input-error :messages="$errors->get('user_station')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="btn btn-primary btn-lg">
                            {{ __('Add New User') }}
                        </x-primary-button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>
    <script>
        function validatePhoneInput(input) {
            // Allow only digits and limit the length to 11
            input.value = input.value.replace(/\D/g, '').slice(0, 11);
        }
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="title">
        Manage Profile
    </x-slot>
    <div class="card shadow-lg mx-4 ">
        <div class="card-body p-3">
          <div class="row gx-4">
            <div class="col-auto">
              <div class="avatar avatar-xl position-relative">
                {{-- <img src="{{asset('img/price-tag.png')}}" alt="profile_image" class="w-100 border-radius-lg shadow-sm"> --}}
                <i class="ni ni-user-run text-warning text-sm opacity-10"></i>
              </div>
            </div>
            <div class="col-auto my-auto">
              <div class="h-100">
                <h5 class="mb-1">
                    Change Password
                </h5>
                <p class="mb-0 font-weight-bold text-sm">
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
          <div class="col-12">
            <div class="card mb-4">
              <div class="card-header pb-0">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Update Password') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Ensure your account is using a long, random password to stay secure.') }}
                    </p>
                </header>
              </div>
              @php
                $serial = 1;
              @endphp
              <div class="card-body">
                <div class="table-responsive p-0">
                    <section>
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <x-input-label for="update_password_current_password" :value="__('Current Password')" class="form-control-label" />
                                <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="update_password_password" :value="__('New Password')" class="form-control-label" />
                                <x-text-input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="form-control-label" />
                                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <x-primary-button class="btn btn-primary">{{ __('Save') }}</x-primary-button>

                                @if (session('status') === 'password-updated')
                                    <div class="alert alert-success" role="alert">
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                        >{{ __('Saved.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

</x-app-layout>

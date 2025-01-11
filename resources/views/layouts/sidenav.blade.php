<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fa fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="/" target="_blank">
        <img src="{{asset('img/autowims_logo.png')}}" height="auto" width="50px" class="navbar-brand-img h-100" alt="main_logo">
        {{-- <img src="./assets/img/logo-ct-dark.png"> --}}
        <span class="ms-1 font-weight-bold">{{ config('app.name') }} 2.0
            <br/>
            @if (request()->getHost() == config('app.domains.operational'))
                Operations
            @elseif (request()->getHost() == config('app.domains.corporate'))
                Corporate
            @elseif (request()->getHost() == config('app.domains.public'))
            @endif
        </span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="{{ route('dashboard') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        @if(Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
          <li class="nav-item">
            <a class="nav-link " href="{{ route('users.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Users</span>
            </a>
          </li>
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}

          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-single-02 text-yellow text-warning text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Service Order</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('customers.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-single-02 text-yellow text-warning text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Customers</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('vehicles.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Vehicles</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('self-service.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Self Service Bookings</span>
            </a>
          </li>
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['adminone', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['admintwo', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['adminthree', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['customerservice', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['frontdesk', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['technician', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['serviceadvisor', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['jobcontroller', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['accountsadmin', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['businessview', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['guestuser', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if(Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['coporateuser', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
          <li class="nav-item">
            <a class="nav-link " href="{{ route('trackers.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">SYC Trackers</span>
            </a>
          </li>
        @endif


        {{-- All Users Route --}}
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="{{ route('profile.manage') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
      </ul>
    </div>

  </aside>

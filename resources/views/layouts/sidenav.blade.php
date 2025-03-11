<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl fixed-start ms-4 " id="sidenav-main">
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
        @if(Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
          <li class="nav-item">
            <a class="nav-link " href="{{ route('users.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-users text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Users</span>
            </a>
          </li>
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}

          <li class="nav-item">
            <a class="nav-link " href="{{ route('customers.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-users text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Customers</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('vehicles.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-car text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Vehicles</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-cart-arrow-down text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Service Order</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.jobcontroller') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-first-order text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Job Control</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.technician.admin') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-cogs text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Technician</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.service_advisor.admin') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-single-02 text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Service Advisor</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.bookings') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-books text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Bookings</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.job_bank.admin') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-bag-17 text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Job Bank</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('self-service.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-calendar-grid-58 text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Self Service Bookings</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('job.services') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-server text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Job Services</span>
            </a>
          </li>
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['adminone']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['admintwo']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['adminthree']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['customerservice']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
          <li class="nav-item">
            <a class="nav-link " href="{{ route('customers.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-users text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Customers</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('vehicles.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-car text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Vehicles</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-cart-arrow-down text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Service Order</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.bookings') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-books text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Bookings</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.job_bank.admin') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-bag-17 text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Job Bank</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link " href="{{ route('job.services') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-server text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Job Services</span>
            </a>
          </li>
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['frontdesk']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['technician']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
          <li class="nav-item">
            <a class="nav-link " href="{{ route('customers.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-users text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Customers</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('vehicles.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-car text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Vehicles</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-cart-arrow-down text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Service Order</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.technician.user') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-cogs text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Technician</span>
            </a>
          </li>
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['serviceadvisor']))
          {{-- Role: {{ Auth::user()->user_role }} --}}

          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.jobcontroller') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-first-order text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Job Control</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.service_advisor.admin') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-single-02 text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Service Advisor</span>
            </a>
          </li>
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['jobcontroller']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
          <li class="nav-item">
            <a class="nav-link " href="{{ route('customers.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-users text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Customers</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('vehicles.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-car text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Vehicles</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-cart-arrow-down text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Service Order</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.jobcontroller') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-first-order text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Job Control</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="{{ route('service_booking.technician.admin') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-cogs text-yellow text-primary text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Technician</span>
            </a>
          </li>

        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['accountsadmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['businessview']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['guestuser']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
        @endif

        @if(Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['coporateuser', 'superadmin', 'masteradmin']))
          {{-- Role: {{ Auth::user()->user_role }} --}}
          <li class="nav-item">
            <a class="nav-link " href="{{ route('trackers.index') }}">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-calendar-grid-58 text-primary text-sm opacity-10"></i>
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

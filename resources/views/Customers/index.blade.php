<x-app-layout>

    <x-slot name="title">
        Customers
    </x-slot>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="header pb-4 pt-md-4">
            <div class="container-fluid">
            <div class="header-body" id="schedule-new-tracking">
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-6 col-lg-12">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-header pb-0">
                                <h6>Add New Customer
                                    <button id="toggleFormButton" class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add new customer
                                    </button>
                                </h6>
                            </div>

                            <!-- Form starts here, initially hidden -->
                            <div class="card-body" id="addNewCustomer" style="display: none;">
                                <!-- Success and error messages -->
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <form id="customerForm" action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="container">
                                        <div class="row mb-3">
                                            <div class="col-xl-8">
                                                <div class="form-group">
                                                    <label for="reg_mode" class="h6">Preferred Mode of Contact</label>
                                                    <select class="form-control" name="mode_of_contact" id="reg_mode">
                                                        <option value="email" {{ old('mode_of_contact') == 'email' ? 'selected' : '' }}>Email</option>
                                                        <option value="sms" {{ old('mode_of_contact') == 'sms' ? 'selected' : '' }}>SMS</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="form-group">
                                                    <label for="reg_type" class="h6">Account Type</label>
                                                    <select class="form-control" name="account_type" id="reg_type">
                                                        <option value="individual" {{ old('account_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                                        <option value="corporate" {{ old('account_type') == 'corporate' ? 'selected' : '' }}>Corporate</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label for="reg_name" class="h6">Full Name</label>
                                                    <input class="form-control" type="text" name="full_name" id="reg_name" value="{{ old('full_name') }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="reg_phone" class="h6">Phone Number</label>
                                                    <input class="form-control" type="text" maxlength="11" name="phone_number" id="reg_phone" value="{{ old('phone_number') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="reg_mail" class="h6">Email Address</label>
                                                    <input class="form-control" type="email" name="email" id="reg_mail" value="{{ old('email') }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="reg_address" class="h6">Address</label>
                                                    <input class="form-control" type="text" name="address" id="reg_address" value="{{ old('address') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="reg_lga" class="h6">Local Government Area</label>
                                                    <select class="form-control" name="lga" id="reg_lga" required>
                                                        <option value="Agege" {{ old('lga') == 'Agege' ? 'selected' : '' }}>Agege</option>
                                                        <option value="Alimosho" {{ old('lga') == 'Alimosho' ? 'selected' : '' }}>Alimosho</option>
                                                        <option value="Apapa" {{ old('lga') == 'Apapa' ? 'selected' : '' }}>Apapa</option>
                                                        <option value="Ifako-Ijaye" {{ old('lga') == 'Ifako-Ijaye' ? 'selected' : '' }}>Ifako-Ijaye</option>
                                                        <option value="Ikeja" {{ old('lga') == 'Ikeja' ? 'selected' : '' }}>Ikeja</option>
                                                        <option value="Kosofe" {{ old('lga') == 'Kosofe' ? 'selected' : '' }}>Kosofe</option>
                                                        <option value="Mushin" {{ old('lga') == 'Mushin' ? 'selected' : '' }}>Mushin</option>
                                                        <option value="Oshodi-Isolo" {{ old('lga') == 'Oshodi-Isolo' ? 'selected' : '' }}>Oshodi-Isolo</option>
                                                        <option value="Shomolu" {{ old('lga') == 'Shomolu' ? 'selected' : '' }}>Shomolu</option>
                                                        <option value="Eti-Osa" {{ old('lga') == 'Eti-Osa' ? 'selected' : '' }}>Eti-Osa</option>
                                                        <option value="Lagos Island" {{ old('lga') == 'Lagos Island' ? 'selected' : '' }}>Lagos Island</option>
                                                        <option value="Lagos Mainland" {{ old('lga') == 'Lagos Mainland' ? 'selected' : '' }}>Lagos Mainland</option>
                                                        <option value="Surulere" {{ old('lga') == 'Surulere' ? 'selected' : '' }}>Surulere</option>
                                                        <option value="Ojo" {{ old('lga') == 'Ojo' ? 'selected' : '' }}>Ojo</option>
                                                        <option value="Ajeromi-Ifelodun" {{ old('lga') == 'Ajeromi-Ifelodun' ? 'selected' : '' }}>Ajeromi-Ifelodun</option>
                                                        <option value="Amuwo-Odofin" {{ old('lga') == 'Amuwo-Odofin' ? 'selected' : '' }}>Amuwo-Odofin</option>
                                                        <option value="Badagry" {{ old('lga') == 'Badagry' ? 'selected' : '' }}>Badagry</option>
                                                        <option value="Ikorodu" {{ old('lga') == 'Ikorodu' ? 'selected' : '' }}>Ikorodu</option>
                                                        <option value="Ibeju-Lekki" {{ old('lga') == 'Ibeju-Lekki' ? 'selected' : '' }}>Ibeju-Lekki</option>
                                                        <option value="Epe" {{ old('lga') == 'Epe' ? 'selected' : '' }}>Epe</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col text-right">
                                                <button type="submit" class="btn btn-primary btn-lg">ADD NEW CUSTOMER</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            {{-- <div class="card-header pb-0">
                                <h6>Add New Customer
                                    <button id="toggleFormButton" class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add new customer
                                    </button>
                                </h6>
                            </div>
                            <div class="card-body" id="addNewCustomer" style="display: none;">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                <form id="customerForm" action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="container">
                                        <div class="row mb-3">
                                        <div class="col-xl-8">
                                            <div class="form-group">
                                            <label for="reg_mode" class="h6">Preferred Mode of Contact</label>
                                            <select class="form-select" name="mode_of_contact" id="reg_mode">
                                                <option value="email">Email</option>
                                                <option value="sms">SMS</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="form-group">
                                            <label for="reg_type" class="h6">Account Type</label>
                                            <select class="form-select" name="account_type" id="reg_type">
                                                <option value="individual">Individual</option>
                                                <option value="corporate">Corporate</option>
                                            </select>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="row mb-3">
                                        <div class="col-xl-12">
                                            <div class="form-group">
                                            <label for="reg_name" class="h6">Full Name</label>
                                            <input class="form-control" type="text" name="full_name" id="reg_name" required>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="row mb-3">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                            <label for="reg_phone" class="h6">Phone Number</label>
                                            <input class="form-control" type="text" maxlength="11" name="phone_number" id="reg_phone" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                            <label for="reg_mail" class="h6">Email Address</label>
                                            <input class="form-control" type="email" name="email" id="reg_mail" required>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="row mb-3">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                            <label for="reg_address" class="h6">Address</label>
                                            <input class="form-control" type="text" name="address" id="reg_address" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                            <label for="reg_lga" class="h6">Local Government Area</label>
                                            <select class="form-control" name="lga" id="reg_lga" required>
                                                <option value="Agege">Agege</option>
                                                <option value="Alimosho">Alimosho</option>
                                                <option value="Apapa">Apapa</option>
                                                <option value="Ifako-Ijaye">Ifako-Ijaye</option>
                                                <option value="Ikeja">Ikeja</option>
                                                <option value="Kosofe">Kosofe</option>
                                                <option value="Mushin">Mushin</option>
                                                <option value="Oshodi-Isolo">Oshodi-Isolo</option>
                                                <option value="Shomolu">Shomolu</option>
                                                <option value="Eti-Osa">Eti-Osa</option>
                                                <option value="Lagos Island">Lagos Island</option>
                                                <option value="Lagos Mainland">Lagos Mainland</option>
                                                <option value="Surulere">Surulere</option>
                                                <option value="Ojo">Ojo</option>
                                                <option value="Ajeromi-Ifelodun">Ajeromi-Ifelodun</option>
                                                <option value="Amuwo-Odofin">Amuwo-Odofin</option>
                                                <option value="Badagry">Badagry</option>
                                                <option value="Ikorodu">Ikorodu</option>
                                                <option value="Ibeju-Lekki">Ibeju-Lekki</option>
                                                <option value="Epe">Epe</option>
                                            </select>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="row">
                                        <div class="col text-right">
                                            <button type="submit" class="btn btn-primary btn-lg" onclick="addNewCustomer()">ADD NEW CUSTOMER</button>
                                        </div>
                                        </div>
                                    </div>
                                </form>
                            </div> --}}
                        </div>
                    </div>

                    {{-- <div class="col-xl-6 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row" id="shuffleCustomer">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">SEARCH APPOINTMENT </h5>
                                <p></p>
                                <div class="row">
                                <div class="col-xl-12">
                                    <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Enter Keyword here" type="text" onchange="searchAppointment(this.value)" onkeyup="searchAppointment(this.value)" onfocus="searchAppointment(this.value)">
                                    </div>
                                </div>
                                </div>

                                <br>
                                <div class="col text-center" style="background:;" id="customerSearch"></div>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-search"></i>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div> --}}

                </div>

            </div>
            </div>
        </div>
        <!-- Page content -->

        <div class="container-fluid py-4">
            <div class="row">
              <div class="col-12">
                <div class="card mb-4">
                  <div class="card-header pb-0">
                    <h6>Customer List
                        {{-- <a href="#schedule-new-tracking"><button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add new Item --}}
                        </button></a>
                    </h6>
                  </div>
                  <div class="card-body pt-0 pb-2">
                    <div class="table-responsive p-0">
                      <table class="table align-items-center mb-0">
                        <thead>
                          <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email Address</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phone Number</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Address</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">LGA</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mode</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Visibility</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
                            <th class="text-secondary opacity-7"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $index => $customer)
                                <tr>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{$index + 1}}
                                        </p>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $customer->cust_name  }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <h6 class="mb-0 text-sm">{{  $customer->cust_email  }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs text-secondary mb-0">{{ $customer->cust_mobile }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $customer->cust_address }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $customer->cust_lga }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $customer->cust_mode }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $customer->cust_view ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0 text-uppercase">{{ $customer->cust_type ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ \Carbon\Carbon::parse($customer->cust_reg_time)->toFormattedDateString() }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- <td class="align-middle text-center text-sm">
                                        @if ($customer->status == 'completed')
                                            <span class="badge badge-sm bg-gradient-success">{{ucfirst($customer->status)}}</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">{{$customer->status}}</span>
                                        @endif
                                    </td> --}}
                                    {{-- <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{date("M j, Y h:i A", strtotime($customer->cust_reg_time ))}}</span>
                                    </td> --}}
                                    <td class="align-middle">
                                        {{-- @if($question->status == 'active')
                                            <a href="{{route('inspectionquestions.disable', $question)}}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Disable Question">
                                                <i class="fa fa-ban" style="color: rgb(239, 167, 0); font-size:14px;" aria-hidden="true"></i>
                                            </a>
                                        @elseif ($question->status == 'disabled')
                                            <a href="{{route('inspectionquestions.enable', $question)}}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Enable Question">
                                                <i class="fa fa-check" style="color: green; font-size:14px;" aria-hidden="true"></i>
                                            </a>
                                        @endif --}}

                                        &nbsp;
                                        {{-- <a href="{{route('inspectionquestions.delete', $question)}}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete Question">
                                           <i class="fa fa-trash" style="color: red; font-size:14px;" aria-hidden="true"></i>
                                        </a> --}}

                                        {{-- <a href="{{route('inspectionquestions.edit', $question)}}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                            Edit
                                        </a> --}}
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Initialize DataTable with responsive features and export buttons
            $('.table').DataTable({
                responsive: true,
                pageLength: 25,
                dom: 'Bfrtip',
                buttons: [
                    'csvHtml5', 'excelHtml5', 'pdfHtml5'
                ]
            });

            // Get the appointment date input and time select elements
            const dateInput = document.getElementById('appointment_date');
            const timeSelect = document.getElementById('t_time');
            const timeOptions = timeSelect.querySelectorAll('option');

            // Event listener for date input changes
            dateInput.addEventListener('input', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();

                // Disable if selected date is in the past
                if (selectedDate < today.setHours(0, 0, 0, 0)) {
                    alert('Past dates are not allowed.');
                    this.value = ''; // Clear the input
                    return;
                }

                // Disable if the selected date is a Sunday
                if (selectedDate.getDay() === 6) {
                    alert('Sundays are not available for appointments.');
                    this.value = ''; // Clear the input
                    return;
                }

                // Enable all time options first
                timeOptions.forEach(option => option.disabled = false);

                // If the selected date is a Saturday (Day 6), disable times outside 8 AM - 12 PM
                if (selectedDate.getDay() === 5) {
                    timeOptions.forEach(option => {
                        if (option.value === "12-14" || option.value === "14-16") {
                            option.disabled = true;
                        }
                    });

                    // Reset the time select if an invalid time was selected
                    if (timeSelect.value === "12-14" || timeSelect.value === "14-16") {
                        timeSelect.value = "";
                    }
                }
            });

            // Disable past dates from being selected
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);

        });

        // Function to validate digits only in an input field
        function validateDigits(input) {
            // Replace any non-digit character with an empty string
            input.value = input.value.replace(/\D/g, '');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Get the button and the form elements
            var toggleFormButton = document.getElementById('toggleFormButton');
            var formContainer = document.getElementById('addNewCustomer');
            var customerForm = document.getElementById('customerForm');

            // Check if there are any validation errors and keep the form open if there are
            @if($errors->any())
                formContainer.style.display = 'block';
            @endif

            // Toggle the visibility of the form when the button is clicked
            toggleFormButton.addEventListener('click', function() {
                if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                    formContainer.style.display = 'block'; // Show the form
                } else {
                    formContainer.style.display = 'none'; // Hide the form
                }
            });
        });
    </script>
</x-app-layout>

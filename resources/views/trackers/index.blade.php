<x-app-layout>

    <x-slot name="title">
        SYC Trackers
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
                            <h6>SCHEDULE NEW APPOINTMENT
                                {{-- <a href="#schedule-new-tracking"><button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add new Item --}}
                                </button></a>
                            </h6>
                          </div>
                    <div class="card-body">

                        <form action="{{ route('trackers.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                        <input class="form-control" placeholder="Name" type="text" id="fullname" name="fullname" value="{{ old('fullname') }}" required>
                                    </div>
                                    @error('fullname')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        <input class="form-control" placeholder="Email Address" type="email" id="emailadd" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                        <input class="form-control" placeholder="Phone Number" maxlength="11" minlength="11" type="text" id="phone" name="phone" value="{{ old('phone') }}" required oninput="validateDigits(this)">
                                    </div>
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-bus-front-12"></i></span>
                                        <input class="form-control" placeholder="Vehicle Make" type="text" id="veh_make" name="veh_make" value="{{ old('veh_make') }}" required>
                                    </div>
                                    @error('veh_make')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-bus-front-12"></i></span>
                                        <input class="form-control" placeholder="Vehicle Model" type="text" id="veh_model" name="veh_model" value="{{ old('veh_model') }}" required>
                                    </div>
                                    @error('veh_model')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        <input class="form-control" placeholder="Vehicle Year" type="text" id="veh_year" name="veh_year" value="{{ old('veh_year') }}" minlength="4" maxlength="4" required oninput="validateDigits(this)">
                                    </div>
                                    @error('veh_year')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-bus-front-12"></i></span>
                                        <input class="form-control" placeholder="VIN" type="text" id="veh_vin" name="veh_vin" value="{{ old('veh_vin') }}">
                                    </div>
                                    @error('veh_vin')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        <input class="form-control" placeholder="Plate Number" type="text" id="plate_num" name="plate_num" value="{{ old('plate_num') }}" minlength="7" maxlength="8" required>
                                    </div>
                                    @error('plate_num')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        <input class="form-control datepicker" placeholder="Appointment Date" type="date" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                                    </div>
                                    @error('appointment_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="ni ni-time-alarm"></i></span>
                                        <select class="form-control" id="t_time" name="time" required>
                                            <option value="" selected disabled>Select Appointment Time</option>
                                            <option value="8-10" {{ old('time') == '8-10' ? 'selected' : '' }}>8:00 AM - 10:00 AM</option>
                                            <option value="10-12" {{ old('time') == '10-12' ? 'selected' : '' }}>10:00 AM - 12:00 PM</option>
                                            <option value="12-14" {{ old('time') == '12-14' ? 'selected' : '' }}>12:00 PM - 2:00 PM</option>
                                            <option value="14-16" {{ old('time') == '14-16' ? 'selected' : '' }}>2:00 PM - 4:00 PM</option>

                                            @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))
                                                <option disabled></option>
                                                <option disabled>Super Time Access</option>
                                                <option value="7-8" {{ old('time') == '7-8' ? 'selected' : '' }}>7:00 AM - 8:00 AM</option>
                                                <option value="8-9" {{ old('time') == '8-9' ? 'selected' : '' }}>8:00 AM - 9:00 AM</option>
                                                <option value="9-10" {{ old('time') == '9-10' ? 'selected' : '' }}>9:00 AM - 10:00 AM</option>
                                                <option value="10-11" {{ old('time') == '10-11' ? 'selected' : '' }}>10:00 AM - 11:00 AM</option>
                                                <option value="11-12" {{ old('time') == '11-12' ? 'selected' : '' }}>11:00 AM - 12:00 PM</option>
                                                <option value="12-13" {{ old('time') == '12-13' ? 'selected' : '' }}>12:00 PM - 1:00 PM</option>
                                                <option value="13-14" {{ old('time') == '13-14' ? 'selected' : '' }}>1:00 PM - 2:00 PM</option>
                                                <option value="14-15" {{ old('time') == '14-15' ? 'selected' : '' }}>2:00 PM - 3:00 PM</option>
                                                <option value="15-16" {{ old('time') == '15-16' ? 'selected' : '' }}>3:00 PM - 4:00 PM</option>
                                                <option value="16-17" {{ old('time') == '16-17' ? 'selected' : '' }}>4:00 PM - 5:00 PM</option>
                                                <option value="17-18" {{ old('time') == '17-18' ? 'selected' : '' }}>5:00 PM - 6:00 PM</option>
                                                <option value="18-19" {{ old('time') == '18-19' ? 'selected' : '' }}>6:00 PM - 7:00 PM</option>
                                            @endif


                                        </select>
                                    </div>
                                    @error('time')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Additional Comments/Requests</label>
                                    <div class="input-group input-group-alternative">
                                        <input type="text" class="form-control" id="t_comments" name="comments" value="{{ old('comments') }}">
                                    </div>
                                    @error('comments')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Referral Code</label>
                                    <div class="input-group input-group-alternative">
                                        @if(Auth::user()->user_role === 'CoporateUser' )
                                            <input type="text" class="form-control text-uppercase" id="t_referral_code" name="referral_code" value="{{ Auth::user()->user_name }}" readonly>
                                        @else
                                            <input type="text" class="form-control" id="t_referral_code" name="referral_code" value="{{ old('referral_code') }}">
                                        @endif
                                    </div>
                                    @error('referral_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-lgm btn-primary text-uppercase">Schedule Appointment</button>
                            </div>
                        </form>
                        </div>
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
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
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
                                <i class="fa fa-search"></i>
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
                    <h6>Appointment List
                        {{-- <a href="#schedule-new-tracking"><button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add new Item --}}
                        </button></a>
                    </h6>
                  </div>
                  <div class="card-body pt-0 pb-2">
                    <div class="table-responsive p-0">
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
                      <table class="table align-items-center mb-0" id="trackersTable">
                        <thead>
                          <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email Address</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phone Number</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">MAKE, MODEL & YEAR</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">PLATE</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">SCH. DATE</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">SCH. Time</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">COMMENTS</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">REF. CODE</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
                            <th class="text-secondary opacity-7"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @forelse ($appointments as $index => $appointment)
                                <tr>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{$index + 1}}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $appointment->fullname  }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{  $appointment->email  }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs text-secondary mb-0">{{ $appointment->phone }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $appointment->veh_make }}, {{ $appointment->veh_model }}, {{ $appointment->veh_year }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $appointment->plate_num }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_date)->toFormattedDateString() }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $appointment->appointment_time }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center" style="word-wrap: break-word; white-space: normal;">
                                                <p class="text-xs mb-0">{{ $appointment->comments ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center" style="word-wrap: break-word; white-space: normal;">
                                                <p class="text-xs mb-0 text-uppercase">{{ $appointment->referral_code ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if ($appointment->status == 'completed')
                                            <span class="badge badge-sm bg-gradient-success">{{ucfirst($appointment->status)}}</span>
                                        @elseif ($appointment->status == 'deleted')
                                            <span class="badge badge-sm bg-gradient-danger">{{$appointment->status}}</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-warning">{{ucfirst($appointment->status)}}</
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{date("M j, Y h:i A", strtotime($appointment->created_at ))}}</span>
                                    </td>
                                    <td class="align-middle">
                                        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))

                                            @if($appointment->status !== 'completed' && $appointment->status !== 'deleted')
                                                <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs edit-btn" data-id="{{ $appointment->id }}" data-toggle="modal" data-target="#editAppointmentModal" data-original-title="Edit Appointment">
                                                    <i class="fa fa-edit" style="color: rgb(255, 179, 0); font-size:14px;" aria-hidden="true"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs complete-btn" data-id="{{ $appointment->id }}" data-toggle="modal" data-target="#completeJobModal" data-original-title="Complete Job">
                                                    <i class="fa fa-check" style="color: green; font-size:14px;" aria-hidden="true"></i>
                                                </a>
                                                &nbsp;
                                                <a href="{{route('trackers.destroy', $appointment->id)}}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete Question">
                                                    <i class="fa fa-trash" style="color: red; font-size:14px;" aria-hidden="true"></i>
                                                 </a>
                                            @endif
                                        @endif
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

    <!-- Complete Job Modal -->
    <div class="modal fade" id="completeJobModal" tabindex="-1" role="dialog" aria-labelledby="completeJobModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completeJobModalLabel">Complete Job</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="completeJobForm" method="POST" action="{{ route('trackers.complete') }}">
                        @csrf
                        <input type="hidden" id="complete_appointment_id" name="id">

                        <div class="form-group">
                            <label for="complete_comments">Comments</label>
                            <textarea class="form-control" id="complete_comments" name="comments" rows="3" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Complete Job</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Appointment Modal -->
    <div class="modal fade" id="editAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAppointmentModalLabel">Edit Appointment</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editAppointmentForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_appointment_id" name="id">

                        <!-- Form inputs -->
                        <div class="form-group">
                            <label for="edit_fullname">Full Name</label>
                            <input type="text" class="form-control" id="edit_fullname" name="fullname" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_phone">Phone</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_veh_make">Vehicle Make</label>
                            <input type="text" class="form-control" id="edit_veh_make" name="veh_make" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_veh_model">Vehicle Model</label>
                            <input type="text" class="form-control" id="edit_veh_model" name="veh_model" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_veh_year">Vehicle Year</label>
                            <input type="text" class="form-control" id="edit_veh_year" name="veh_year" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_veh_vin">Vehicle VIN</label>
                            <input type="text" class="form-control" id="edit_veh_vin" name="veh_vin">
                        </div>
                        <div class="form-group">
                            <label for="edit_plate_num">Plate Number</label>
                            <input type="text" class="form-control" id="edit_plate_num" name="plate_num" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with responsive features and export buttons
            var table = $('#trackersTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },  // Keep the first column visible
                    { responsivePriority: 2, targets: -1 }  // Keep the buttons column visible
                ],
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


                // // Disable if selected date is in the past
                // if (selectedDate < today.setHours(0, 0, 0, 0)) {
                //     alert('Past dates are not allowed.');
                //     this.value = ''; // Clear the input
                //     return;
                // }

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

            // Complete Job button click handler
            // Delegate event for Complete Job button click
            $(document).on('click', '.complete-btn', function () {
                const appointmentId = $(this).data('id');
                $('#complete_appointment_id').val(appointmentId); // Set the appointment ID in the modal
                $('#completeJobModal').modal('show'); // Open the modal
            });

            // Handle Edit button click
            // Delegate event for Edit button click
            $(document).on('click', '.edit-btn', function () {
                const appointmentId = $(this).data('id');

                // AJAX request to fetch the appointment details
                $.ajax({
                    url: '/product/trackers/' + appointmentId + '/edit', // Adjust this URL to your route
                    method: 'GET',
                    success: function (data) {
                        // Populate the edit form with fetched appointment data
                        $('#edit_appointment_id').val(data.id);
                        $('#edit_fullname').val(data.fullname);
                        $('#edit_email').val(data.email);
                        $('#edit_phone').val(data.phone);
                        $('#edit_veh_make').val(data.veh_make);
                        $('#edit_veh_model').val(data.veh_model);
                        $('#edit_veh_year').val(data.veh_year);
                        $('#edit_plate_num').val(data.plate_num);

                        // Decode the metadata to retrieve veh_vin, handling null metadata
                        let metadata = data.metadata ? JSON.parse(data.metadata) : {};
                        $('#edit_veh_vin').val(metadata.veh_vin || '');

                        // Dynamically set the form action
                        $('#editAppointmentForm').attr('action', '/product/trackers/update/' + data.id);

                        // Show the Edit Modal
                        $('#editAppointmentModal').modal('show');
                    }
                });
            });

        });

        // Function to validate digits only in an input field
        function validateDigits(input) {
            // Replace any non-digit character with an empty string
            input.value = input.value.replace(/\D/g, '');
        }
    </script>
</x-app-layout>

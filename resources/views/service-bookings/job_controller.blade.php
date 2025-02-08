<x-app-layout>

    <x-slot name="title">
        Job Control
    </x-slot>
    <!-- Page content -->

    <div class="container-fluid py-8">
        {{-- ASSIGN TECHNICIAN --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Assign Technician
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
                            <table class="table align-items-center mb-0" id="assignTechnician">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle Details & Report</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Description</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jobs as $index => $job)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{$index + 1}}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $job->customer->cust_name  }} | {{ $job->vehicle->vec_make }} {{ $job->vehicle->vec_model }} - {{ $job->vehicle->vec_plate }}</p>
                                        </td>
                                        <td>
                                            <div class="d-flex ">
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-0 text-sm">{{  $job->description ?? " " }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex ">
                                                <div class="d-flex flex-column">
                                                    <p class="text-xs text-secondary mb-0">{{date("M j, Y h:i A", strtotime($job->created_at ))}}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))

                                                @if($job->status !== 'completed' && $job->status !== 'deleted')
                                                    <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs assign-btn" data-id="{{ $job->id }}" data-customer="{{ $job->customer->cust_name }}" data-vehicle="{{ $job->vehicle->vec_make }} {{ $job->vehicle->vec_model }} - {{ $job->vehicle->vec_plate }}" data-job_description="{{ $job->description }}" data-toggle="modal" data-target="#assignTechnicianModal">
                                                            <i class="fa fa-user" style="color: blue; font-size:14px;" aria-hidden="true"></i> Assign Technician
                                                    </a>
                                                @endif
                                            @endif
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

        {{-- AWAITING JOB CONTROLLER ADVISE --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Awaaiting JC Advise
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
                            <table class="table align-items-center mb-0" id="awaitingJobAdvise">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle Details & Report</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Description</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Workshop Findings</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Parts Required</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Comments</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            {{-- <tbody>
                                @forelse ($appointments as $index => $appointment)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{$index + 1}}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $job->fullname  }}</p>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{  $job->email  }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <p class="text-xs text-secondary mb-0">{{ $job->phone }}</p>
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
                                            </a> --
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FINALIZE JOB CARD --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Finalize Job Card
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
                            <table class="table align-items-center mb-0" id="finalizeJobTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle Details & Report</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Description</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Workshop Findings</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Parts Required</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Comments</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Carried Out</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            {{-- <tbody>
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
                                            </a> --
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- Assign Technician Modal -->
    <div class="modal fade" id="assignTechnicianModal" tabindex="-1" role="dialog" aria-labelledby="assignTechnicianLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignTechnicianLabel">Assign Technician</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="assignTechnicianForm" action="{{ route('service_booking.assign_technician') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="job_id" name="job_id">

                        <div class="form-group">
                            <label for="customer">Customer</label>
                            <input type="text" id="customer" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="vehicle">Vehicle</label>
                            <input type="text" id="vehicle" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="job_description">Description</label>
                            <input type="text" id="job_description" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="technician">Select Technician</label>
                            <select id="technician" name="technician_id" class="form-control" required>
                                <option value="">-- Select Technician --</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->id }}">{{ $technician->user_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#assignTechnician, #awaitingJobAdvise, #finalizeJobTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 }, // Keep the first column visible
                    { responsivePriority: 2, targets: -1 } // Keep the buttons column visible
                ],
                pageLength: 10,
            });

            // Handle Assign Technician button click
            $(document).on('click', '.assign-btn', function() {
                let jobId = $(this).data('id');
                let customer = $(this).data('customer');
                let vehicle = $(this).data('vehicle');
                let jobDescription = $(this).data('job_description');

                $('#job_id').val(jobId);
                $('#customer').val(customer);
                $('#vehicle').val(vehicle);
                $('#job_description').val(jobDescription);

                // Show the modal
                $('#assignTechnicianModal').modal('show');
            });

            // Complete Job button click handler
            $(document).on('click', '.complete-btn', function () {
                const appointmentId = $(this).data('id');
                $('#complete_appointment_id').val(appointmentId);
                $('#completeJobModal').modal('show');
            });

            // Handle Edit button click
            $(document).on('click', '.edit-btn', function () {
                const appointmentId = $(this).data('id');

                // AJAX request to fetch the appointment details
                $.ajax({
                    url: '/product/trackers/' + appointmentId + '/edit', // Adjust this URL to your route
                    method: 'GET',
                    success: function (data) {
                        $('#edit_appointment_id').val(data.id);
                        $('#edit_fullname').val(data.fullname);
                        $('#edit_email').val(data.email);
                        $('#edit_phone').val(data.phone);
                        $('#edit_veh_make').val(data.veh_make);
                        $('#edit_veh_model').val(data.veh_model);
                        $('#edit_veh_year').val(data.veh_year);
                        $('#edit_plate_num').val(data.plate_num);

                        let metadata = data.metadata ? JSON.parse(data.metadata) : {};
                        $('#edit_veh_vin').val(metadata.veh_vin || '');

                        $('#editAppointmentForm').attr('action', '/product/trackers/update/' + data.id);
                        $('#editAppointmentModal').modal('show');
                    }
                });
            });

            // Appointment date validation
            const dateInput = document.getElementById('appointment_date');
            const timeSelect = document.getElementById('t_time');
            const timeOptions = timeSelect.querySelectorAll('option');

            dateInput.addEventListener('input', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();

                if (selectedDate.getDay() === 6) {
                    alert('Sundays are not available for appointments.');
                    this.value = ''; // Clear the input
                    return;
                }

                timeOptions.forEach(option => option.disabled = false);

                if (selectedDate.getDay() === 5) {
                    timeOptions.forEach(option => {
                        if (option.value === "12-14" || option.value === "14-16") {
                            option.disabled = true;
                        }
                    });

                    if (timeSelect.value === "12-14" || timeSelect.value === "14-16") {
                        timeSelect.value = "";
                    }
                }
            });

            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);

        });

        // Function to validate digits only in an input field
        function validateDigits(input) {
            input.value = input.value.replace(/\D/g, '');
        }
        // $(document).ready(function() {
        //     // Initialize DataTable with responsive features and export buttons
        //     var table = $('#assignTechnician').DataTable({
        //         responsive: true,
        //         columnDefs: [
        //             { responsivePriority: 1, targets: 0 },  // Keep the first column visible
        //             { responsivePriority: 2, targets: -1 }  // Keep the buttons column visible
        //         ],
        //         pageLength: 10,
        //         // dom: 'Bfrtip'
        //     });

        //     var table = $('#awaitingJobAdvise').DataTable({
        //         responsive: true,
        //         columnDefs: [
        //             { responsivePriority: 1, targets: 0 },  // Keep the first column visible
        //             { responsivePriority: 2, targets: -1 }  // Keep the buttons column visible
        //         ],
        //         pageLength: 10,
        //         // dom: 'Bfrtip'
        //     });

        //     var table = $('#finalizeJobTable').DataTable({
        //         responsive: true,
        //         columnDefs: [
        //             { responsivePriority: 1, targets: 0 },  // Keep the first column visible
        //             { responsivePriority: 2, targets: -1 }  // Keep the buttons column visible
        //         ],
        //         pageLength: 10,
        //         // dom: 'Bfrtip'
        //     });




        //     // Get the appointment date input and time select elements
        //     const dateInput = document.getElementById('appointment_date');
        //     const timeSelect = document.getElementById('t_time');
        //     const timeOptions = timeSelect.querySelectorAll('option');

        //     // Event listener for date input changes
        //     dateInput.addEventListener('input', function() {
        //         const selectedDate = new Date(this.value);
        //         const today = new Date();


        //         // // Disable if selected date is in the past
        //         // if (selectedDate < today.setHours(0, 0, 0, 0)) {
        //         //     alert('Past dates are not allowed.');
        //         //     this.value = ''; // Clear the input
        //         //     return;
        //         // }

        //         // Disable if the selected date is a Sunday
        //         if (selectedDate.getDay() === 6) {
        //             alert('Sundays are not available for appointments.');
        //             this.value = ''; // Clear the input
        //             return;
        //         }

        //         // Enable all time options first
        //         timeOptions.forEach(option => option.disabled = false);

        //         // If the selected date is a Saturday (Day 6), disable times outside 8 AM - 12 PM
        //         if (selectedDate.getDay() === 5) {
        //             timeOptions.forEach(option => {
        //                 if (option.value === "12-14" || option.value === "14-16") {
        //                     option.disabled = true;
        //                 }
        //             });

        //             // Reset the time select if an invalid time was selected
        //             if (timeSelect.value === "12-14" || timeSelect.value === "14-16") {
        //                 timeSelect.value = "";
        //             }
        //         }
        //     });

        //     // Disable past dates from being selected
        //     const today = new Date().toISOString().split('T')[0];
        //     dateInput.setAttribute('min', today);

        //     // Complete Job button click handler
        //     // Delegate event for Complete Job button click
        //     $(document).on('click', '.complete-btn', function () {
        //         const appointmentId = $(this).data('id');
        //         $('#complete_appointment_id').val(appointmentId); // Set the appointment ID in the modal
        //         $('#completeJobModal').modal('show'); // Open the modal
        //     });

        //     // Handle Edit button click
        //     // Delegate event for Edit button click
        //     $(document).on('click', '.edit-btn', function () {
        //         const appointmentId = $(this).data('id');

        //         // AJAX request to fetch the appointment details
        //         $.ajax({
        //             url: '/product/trackers/' + appointmentId + '/edit', // Adjust this URL to your route
        //             method: 'GET',
        //             success: function (data) {
        //                 // Populate the edit form with fetched appointment data
        //                 $('#edit_appointment_id').val(data.id);
        //                 $('#edit_fullname').val(data.fullname);
        //                 $('#edit_email').val(data.email);
        //                 $('#edit_phone').val(data.phone);
        //                 $('#edit_veh_make').val(data.veh_make);
        //                 $('#edit_veh_model').val(data.veh_model);
        //                 $('#edit_veh_year').val(data.veh_year);
        //                 $('#edit_plate_num').val(data.plate_num);

        //                 // Decode the metadata to retrieve veh_vin, handling null metadata
        //                 let metadata = data.metadata ? JSON.parse(data.metadata) : {};
        //                 $('#edit_veh_vin').val(metadata.veh_vin || '');

        //                 // Dynamically set the form action
        //                 $('#editAppointmentForm').attr('action', '/product/trackers/update/' + data.id);

        //                 // Show the Edit Modal
        //                 $('#editAppointmentModal').modal('show');
        //             }
        //         });
        //     });

        //     $(document).ready(function() {
        //         $('.assign-btn').on('click', function() {
        //             let jobId = $(this).data('id');
        //             let customer = $(this).data('customer');
        //             let vehicle = $(this).data('vehicle');

        //             $('#job_id').val(jobId);
        //             $('#customer').val(customer);
        //             $('#vehicle').val(vehicle);
        //         });
        //     });

        // });


    </script>
</x-app-layout>

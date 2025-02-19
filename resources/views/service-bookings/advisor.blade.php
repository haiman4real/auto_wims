<x-app-layout>

    <x-slot name="title">
        Service Advisor
    </x-slot>
    <!-- Page content -->

    <div class="container-fluid py-8">
        {{-- Notification Section --}}
        <div id="alert-container"></div> <!-- Dynamic Notifications Here -->

        {{-- AWAITING ESTIMATION --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Awaiting Estimation
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
                            <table class="table align-items-center mb-0" id="awaitingEstimation">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle Details & Report</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Description</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Workshop Findings</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Parts Required</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
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
                                                    <h6 class="mb-0 text-sm">
                                                        {{ data_get(collect($job->workflow)->firstWhere('job_type', 'awaiting_job_advise'), 'details.workshop_findings', 'No workshop findings reported') }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex ">
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-0 text-sm">
                                                        {{ data_get(collect($job->workflow)->firstWhere('job_type', 'awaiting_job_advise'), 'details.required_spare_parts', 'No parts required yet') }}
                                                    </h6>
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
                                            @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin', 'serviceadvisor', 'customerservice']))

                                                @if($job->status !== 'completed' && $job->status !== 'deleted')
                                                    <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs assign-btn" data-id="{{ $job->id }}" data-customer="{{ $job->customer->cust_name }}" data-customer-phone="{{ $job->customer->cust_mobile }}" data-customer-email="{{ $job->customer->cust_email }}" data-customer-address="{{ $job->customer->cust_address }} {{ $job->customer->cust_lga }}" data-customer-type="{{ $job->customer->cust_type }}" data-vehicle-make-model="{{ $job->vehicle->vec_make }} {{ $job->vehicle->vec_model }}" data-vehicle-plate="{{ $job->vehicle->vec_plate }}" data-vehicle-vin="{{ $job->vehicle->vec_vin }}" data-vehicle-year="{{ $job->vehicle->vec_year }}" data-job_description="{{ $job->description }}" data-booking_ref="{{ $job->order_number}}" data-booking_date="{{ date('M j, Y h:i A', strtotime($job->created_at ))}}" data-toggle="modal" data-target="#awaitingEstimationModal">
                                                            <i class="fa fa-user" style="color: blue; font-size:14px;" aria-hidden="true"></i> Approve for Estimation
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


    <!-- Awaiting Estimation Modal -->
    <div class="modal fade" id="awaitingEstimationModal" tabindex="-1" role="dialog" aria-labelledby="awaitingEstimationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="h5">Update Job Booking</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="awaitingEstimationForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="job_id" name="job_id">

                        <div class="row pb-3">
                            <div class="col-md-6">
                                <p class="font-weight-bold mb-2">Customer Information</p>
                                <p class="mb-0 text-dark"><span class="text-muted">Name: </span> <span id="customer"></span></p>
                                <p class="mb-0 text-dark"><span class="text-muted">Phone: </span> <span id="customer_phone"></span></p>
                                <p class="mb-0 text-dark"><span class="text-muted">Email: </span> <span id="customer_email"></span></p>
                                <p class="mb-0 text-dark"><span class="text-muted">Address: </span> <span id="customer_address"></span></p>
                                <p class="mb-0 text-dark"><span class="text-muted">Customer Type: </span> <span id="customer_type"></span></p>
                            </div>
                            <div class="col-md-6 text-left">
                                <p class="font-weight-bold mb-2">Vehicle Details</p>
                                <p class="mb-0 text-dark"><span class="text-muted">Reg No: </span> <span id="vehicle_plate"></span></p>
                                <p class="mb-0 text-dark"><span class="text-muted">VIN/Chassis No: </span> <span id="vehicle_vin"></span></p>
                                <p class="mb-0 text-dark"><span class="text-muted">Make/Model: </span> <span id="vehicle_make_model"></span></p>
                                <p class="mb-0 text-dark"><span class="text-muted">Year: </span> <span id="vehicle_year"></span></p>
                            </div>
                        </div>

                        <div class="row pb-2">
                            <div class="col-md-6">
                                <p class="h6"><label>Booking Ref:</label> <span id="booking_ref"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="h6"><label>Booking Date:</label> <span id="booking_date"></span></p>
                            </div>
                        </div>

                        <div class="row pb-2">
                            <div class="col">
                                <label class="h6">Bookings Description</label>
                                <textarea rows="3" class="form-control" id="job_description" readonly></textarea>
                            </div>
                        </div>

                        <div class="row pb-1">
                            <div class="col">
                                <label class="h6">Service Advise</label>
                                <textarea rows="5" class="form-control" id="service_advise" name="service_advise" required></textarea>
                            </div>
                        </div>

                        <div class="row pb-1">
                            <div class="col">
                                <label class="h6">Comments</label>
                                <textarea rows="5" class="form-control" id="comments" name="comments"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#awaitingEstimation, #finalizeJobTable').DataTable({
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

                // Extract and set new customer details
                $('#customer').text(customer);
                $('#customer_phone').text($(this).data('customer-phone'));
                $('#customer_email').text($(this).data('customer-email'));
                $('#customer_address').text($(this).data('customer-address'));
                $('#customer_type').text($(this).data('customer-type'));

                // Extract and set new vehicle details
                $('#vehicle_plate').text($(this).data('vehicle-plate'));
                $('#vehicle_vin').text($(this).data('vehicle-vin'));
                $('#vehicle_make_model').text($(this).data('vehicle-make-model'));
                $('#vehicle_year').text($(this).data('vehicle-year'));

                // Booking information
                $('#booking_ref').text($(this).data('booking_ref'));
                $('#booking_date').text($(this).data('booking_date'));

                // Job description and other fields
                $('#job_id').val(jobId);
                $('#job_description').val(jobDescription);
                $('#service_advise').val(''); // Reset Findings input
                $('#comments').val(''); // Reset Spare Parts input

                // Show the modal
                $('#awaitingEstimationModal').modal('show');
            });

            $('#awaitingEstimationForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('service_booking.service_advisor.admin.updateJob') }}", // Ensure correct route name
                    type: "POST", // Explicitly set method to POST
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        $('#awaitingEstimationModal').modal('hide'); // Close modal
                        showNotification("success", response.message); // Show success notification
                        window.location.href = "{{ route('service_booking.bookings') }}";
                    },
                    error: function(xhr) {
                        let message = "Failed to approve job for estimation.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showNotification("danger", message);
                    }
                });
            });


            // Function to display popup notification
            function showNotification(type, message) {
                // Create notification container
                const notification = document.createElement('div');
                notification.classList.add('notification', type); // Add classes for styling
                notification.textContent = message;

                // Add the notification to the body
                document.body.appendChild(notification);

                // Remove the notification after 5 seconds
                setTimeout(() => {
                    notification.remove();
                }, 10000);

                location.reload(); // Reload page to reflect changes

            }

        });

    </script>
</x-app-layout>

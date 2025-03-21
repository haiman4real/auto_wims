<x-app-layout>

    <x-slot name="title">
        Job Bank
    </x-slot>
    <!-- Page content -->

    <div class="container-fluid py-8">
        {{-- Notification Section --}}
        <div id="alert-container"></div> <!-- Dynamic Notifications Here -->


        {{-- ASSIGN TECHNICIAN --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <h6>All Jobs
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
                            <table class="table align-items-center mb-0" id="awaitingTechnicalReview">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle Details & Report</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Description</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Advisor Notes</th>
                                    <th class="text-uppercase text-secondary align-middle text-center text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary  align-middle text-center text-xxs font-weight-bolder opacity-7 ps-2">Payment Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job Date</th>
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
                                                        {{ data_get(collect($job->workflow)->firstWhere('job_type', 'service_advisor_comments'), 'details.service_advise', ' ') }}
                                                        <br>
                                                        <small>
                                                            Additional Comments: {{ data_get(collect($job->workflow)->firstWhere('job_type', 'service_advisor_comments'), 'details.comments', 'N/A') }}
                                                        </small>
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    @if ($job->status == 'pending')
                                                        <span class="badge badge-sm bg-gradient-danger">
                                                            <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                        </span>
                                                    @elseif ($job->status == 'completed')
                                                        <span class="badge badge-sm bg-gradient-success">
                                                            <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                        </span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-info">
                                                            <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    @if ($job->status == 'pending')
                                                        <span class="badge badge-sm bg-gradient-danger">
                                                            <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                        </span>
                                                    @elseif ($job->status == 'completed')
                                                        <span class="badge badge-sm bg-gradient-success">
                                                            <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                        </span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-info">
                                                            <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                        </span>
                                                    @endif
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

                                                @if($job->status == 'estimate generated' || $job->status == 'completed')
                                                    <a href="{{ route('service_booking.estimate.invoice', ['job_id' => $job->id]) }}" class="text-secondary font-weight-bold text-xs assign-btn btn btn-sm btn-success" target="_blank">
                                                            <i class="fa fa-send" aria-hidden="true"></i>&nbsp;Print Job Card
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
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#awaitingTechnicalReview').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 }, // Keep the first column visible
                    { responsivePriority: 2, targets: -1 } // Keep the buttons column visible
                ],
                pageLength: 10,
            });

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
                $('#workshop_findings').val(''); // Reset Findings input
                $('#required_spare_parts').val(''); // Reset Spare Parts input

                // Show the modal
                $('#awaitingTechnicalReviewModal').modal('show');
            });

            $(document).ready(function () {
                $('#awaitingTechnicalReviewForm').submit(function (event) {
                    event.preventDefault(); // Prevent default form submission


                    let formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('service_booking.technician.admin.updateJob') }}",
                        type: "POST",
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            $('#awaitingTechnicalReviewModal').modal('hide'); // Close modal
                            showNotification("success", response.message);
                            location.reload(); // Reload page to reflect changes
                        },
                        error: function (xhr) {
                            let message = "Failed to update job.";
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
                    }, 5000);
                }
            });


            // function showNotification(type, message) {
            //     let alert = `
            //         <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            //             ${message}
            //             <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            //         </div>
            //     `;
            //     $("#alert-container").html(alert);
            //     setTimeout(() => $(".alert").fadeOut(), 3000);
            // }

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
                }, 5000);
            }

        });

    </script>
</x-app-layout>

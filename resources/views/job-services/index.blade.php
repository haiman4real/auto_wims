<x-app-layout>

    <x-slot name="title">
        Job Services
    </x-slot>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="header pb-4 pt-md-4">
            <div class="container-fluid">
            <div class="header-body" id="schedule-new-tracking">
                <!-- Card stats -->

            </div>
            </div>
        </div>
        <!-- Page content -->

        <div class="container-fluid py-4">
            <div class="row">
              <div class="col-12">
                <div class="card mb-4">
                  <div class="card-header pb-0">
                    <h6>Job Services List
                        <!-- Add New Service Button -->
                        <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                            <i class="fa fa-plus"></i> Add New Service
                        </button>
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
                        <table class="table align-items-center mb-0" id="servicesTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Service Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Duration</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount (₦)</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date Added</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jobServices as $index => $service)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $service['serv_name'] }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $service['serv_cat'] }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $service['serv_duration'] }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">₦{{ number_format($service['serv_amount'], 2) }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            @if ($service['serv_status'] == 'visible')
                                                <span class="badge badge-sm bg-gradient-success">{{ ucfirst($service['serv_status']) }}</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-danger">{{ ucfirst($service['serv_status']) }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ date("M j, Y h:i A", $service['serv_reg_time']) }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <!-- Edit Button -->
                                            <button class="btn btn-xs btn-warning edit-service-btn"
                                                data-id="{{ $service['serv_id'] }}"
                                                data-name="{{ $service['serv_name'] }}"
                                                data-category="{{ $service['serv_cat'] }}"
                                                data-duration="{{ $service['serv_duration'] }}"
                                                data-amount="{{ $service['serv_amount'] }}"
                                                data-status="{{ $service['serv_status'] }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editServiceModal">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <!-- Delete Button -->
                                            {{-- //make the delete button ask a modal question, do you really want to delete this service --}}
                                            <form action="{{ route('job.services.destroy', $service['serv_id']) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this service?');">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <p class="text-xs text-secondary mb-0">No services available</p>
                                        </td>
                                    </tr>
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

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addServiceForm" method="POST" action="{{ route('job.services.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="serv_name">Service Name</label>
                            <input type="text" class="form-control" id="serv_name" name="serv_name" required>
                        </div>

                        <div class="form-group">
                            <label for="serv_cat">Category</label>
                            <select class="form-select" name="serv_cat" id="serv_cat" required>
                                <option selected disabled>---Select One---</option>
                                <option>Fuel System Cleaning</option>
                                <option>Tyres Service</option>
                                <option>Transmission Services</option>
                                <option>Engine Service</option>
                                <option>Suspension Service</option>
                                <option>General Maintenance</option>
                                <option>Balancing Services</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="serv_duration">Duration</label>
                            {{-- <input type="text" class="form-control" id="serv_duration" name="serv_duration" required> --}}
                            <select class="form-select" name="serv_duration" id="serv_duration" required>
                                <option>Select Time Frame</option>
                                <option>Less than 30mins</option>
                                <option>30mins - 1hour</option>
                                <option>1-3hours</option>
                                <option>More than 3hours</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="serv_amount">Amount (₦)</label>
                            <input type="number" class="form-control" id="serv_amount" name="serv_amount" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Service</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editServiceForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_serv_id" name="serv_id">

                        <div class="form-group">
                            <label for="edit_serv_name">Service Name</label>
                            <input type="text" class="form-control" id="edit_serv_name" name="serv_name" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_serv_cat">Category</label>
                            <select class="form-select" name="serv_cat" id="edit_serv_cat" required>
                                <option selected disabled>---Select One---</option>
                                <option>Fuel System Cleaning</option>
                                <option>Tyres Service</option>
                                <option>Transmission Services</option>
                                <option>Engine Service</option>
                                <option>Suspension Service</option>
                                <option>General Maintenance</option>
                                <option>Balancing Services</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_serv_duration">Duration</label>
                            <select class="form-select" name="serv_duration" id="edit_serv_duration" required>
                                <option>Select Time Frame</option>
                                <option>Less than 30mins</option>
                                <option>30mins - 1hour</option>
                                <option>1-3hours</option>
                                <option>More than 3hours</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_serv_amount">Amount (₦)</label>
                            <input type="number" class="form-control" id="edit_serv_amount" name="serv_amount" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_serv_status">Status</label>
                            <select class="form-control" id="edit_serv_status" name="serv_status">
                                <option value="visible">Visible</option>
                                <option value="hidden">Hidden</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Service</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with responsive features and export buttons
            var table = $('#servicesTable').DataTable({
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

            // Handle Edit Button Click
            $(document).on('click', '.edit-service-btn', function () {
                let serviceId = $(this).data('id');
                let serviceName = $(this).data('name');
                let serviceCategory = $(this).data('category');
                let serviceDuration = $(this).data('duration');
                let serviceAmount = $(this).data('amount');
                let serviceStatus = $(this).data('status');

                // Populate the modal form fields
                $('#edit_serv_id').val(serviceId);
                $('#edit_serv_name').val(serviceName);
                $('#edit_serv_cat').val(serviceCategory);
                $('#edit_serv_duration').val(serviceDuration);
                $('#edit_serv_amount').val(serviceAmount);
                $('#edit_serv_status').val(serviceStatus);

                // Set the form action dynamically
                $('#editServiceForm').attr('action', `/workshop/services/${serviceId}`);
            });

            // Handle Form Submission
            $('#editServiceForm').on('submit', function (e) {
                e.preventDefault();

                let form = $(this);
                let formData = form.serialize();

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        alert('Service updated successfully!');
                        location.reload();
                    },
                    error: function (xhr) {
                        alert('Failed to update service. Please try again.');
                    }
                });
            });

            // Handle Add Service Form Submission
            $('#addServiceForm').on('submit', function (e) {
                e.preventDefault();

                let form = $(this);
                let formData = form.serialize();

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function () {
                        alert('Service added successfully!');
                        location.reload();
                    },
                    error: function () {
                        alert('Failed to add service. Please try again.');
                    }
                });
            });
        });

    </script>
</x-app-layout>

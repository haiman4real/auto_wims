<x-app-layout>

    <x-slot name="title">
        Vehicles
    </x-slot>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="header pb-4 pt-md-4">
            <div class="container-fluid">
            <div class="header-body" id="schedule-new-customer">
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-6 col-lg-12">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-header pb-0">
                                <h6>
                                    Add New Vehicle
                                    <button id="toggleFormButton" class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;">
                                        + Add new vehicle
                                    </button>
                                </h6>
                            </div>

                            <!-- Form starts here, initially hidden -->
                            <div class="card-body" id="addNewVehicle" style="display: none;">
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

                                <form id="vehicleForm" action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    {{-- <div class="container"> --}}

                                        <!-- Customer Dropdown Section -->
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label for="customer_id" class="h6">Select Customer</label>
                                                    <select class="form-control" name="customer_id" id="customer_id" required>
                                                        <option value="">-- Select Customer --</option>
                                                        @foreach($customers as $customer)
                                                            <option value="{{ $customer->cust_id }}">{{ $customer->cust_name }} ({{ $customer->cust_email ?? 'No Email' }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Vehicle Details Section -->
                                        <h5 class="mt-2">Vehicle Details</h5>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="vec_body" class="h6">Body Type</label>
                                                    <select class="form-control" name="body_type" id="vec_body">
                                                        <option>SUV</option>
                                                        <option>Salon/Sedan</option>
                                                        <option>Hatchback</option>
                                                        <option>Crossover</option>
                                                        <option>Sports Car</option>
                                                        <option>Convertible</option>
                                                        <option>Coupe</option>
                                                        <option>Vans</option>
                                                        <option>Trucks/Pick Up</option>
                                                        <option>Wagons</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="vec_year" class="h6">Vehicle Year</label>
                                                    <input class="form-control" type="text" name="vec_year" id="vec_year" value="{{ old('vec_year') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="vec_make" class="h6">Vehicle Make</label>
                                                    <input class="form-control" type="text" name="vec_make" id="vec_make" value="{{ old('vec_make') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="vec_model" class="h6">Vehicle Model</label>
                                                    <input class="form-control" type="text" name="vec_model" id="vec_model" value="{{ old('vec_model') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="vec_plate" class="h6">Vehicle Plate</label>
                                                    <input class="form-control" type="text" maxlength="8" name="vec_plate" id="vec_plate" value="{{ old('vec_plate') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="vec_vin" class="h6">VIN/Chassis No</label>
                                                    <input class="form-control" type="text" maxlength="17" name="vec_vin" id="vec_vin" value="{{ old('vec_vin') }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="row">
                                            <div class="col text-right">
                                                <button type="submit" class="btn btn-primary btn-lg">ADD NEW VEHICLE</button>
                                            </div>
                                        </div>
                                    {{-- </div> --}}
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-12">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-header pb-0">
                                <h6>Search Vehicles
                                    <button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </h6>
                            </div>
                        <div class="card-body">
                            <div class="row" id="shuffleCustomer">
                                <div class="col">
                                    <div class="input-group input-group-alternative">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input class="form-control" placeholder="Enter Keyword here" type="text" id="customSearch">
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

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
                    <h6>Vehicle List
                        {{-- <a href="#schedule-new-tracking"><button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add new Item --}}
                        </button></a>
                    </h6>
                  </div>
                  <div class="card-body pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <!-- Success and error messages -->
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

                      <table id="vehiclesTable" class="table align-items-center mb-0">
                        <thead>
                          <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Reg Number</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">VIN/Chassis No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Make</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Year</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Body Type</th>
                            <th class="text-secondary opacity-7"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicles as $index => $vehicle)
                                <tr>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{$index + 1}}
                                        </p>
                                    </td>

                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <h6 class="mb-0 text-sm">{{  $vehicle->customer->cust_name  }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $vehicle->vec_plate  }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $vehicle->vec_vin }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs text-secondary mb-0">{{ $vehicle->vec_make }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $vehicle->vec_model }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $vehicle->vec_year }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $vehicle->vec_body ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0 text-uppercase">{{ $vehicle->cust_type ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td> --}}
                                    {{-- <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ \Carbon\Carbon::parse($vehicle->vec_reg_time)->toFormattedDateString() }}</p>
                                            </div>
                                        </div>
                                    </td> --}}

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
                                        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))
                                            <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs edit-btn-vehicles" data-id="{{ $vehicle->vec_id }}" data-toggle="modal" data-target="#editVehicleModal" data-original-title="Edit Vehicle">
                                                <i class="fa fa-edit" style="color: rgb(255, 179, 0); font-size:14px;" aria-hidden="true"></i>
                                            </a>
                                            &nbsp;
                                            <a href="{{ route('vehicles.delete', $vehicle->vec_id) }}" class="text-secondary font-weight-bold text-xs">
                                                <i class="fa fa-ban" style="color: red; font-size:14px;" aria-hidden="true"></i>
                                            </a>
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
    </div>

    <!-- Edit Customer Modal -->
    {{-- <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCustomerForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_customer_id" name="customer_id">

                        <div class="form-group">
                            <label for="edit_name">Full Name</label>
                            <input type="text" class="form-control" id="edit_name" name="full_name" required>
                        </div>

                        {{-- <div class="form-group">
                            <label for="edit_email">Email Address</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div> -
                        <div class="form-group">
                            <label for="edit_email">Email Address</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="email_not_available" name="email_not_available">
                                <label class="form-check-label" for="email_not_available">Email Not Available</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_phone">Phone Number</label>
                            <input type="text" class="form-control" id="edit_phone" maxlength="11" name="phone_number" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_address">Address</label>
                            <input type="text" class="form-control" id="edit_address" name="address" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_lga">Local Government Area</label>
                            <select class="form-select" name="lga" id="edit_lga" required>
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

                        <button type="submit" class="btn btn-primary">Update Customer</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVehicleModalLabel">Edit Vehicle</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editVehicleForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_vehicle_id" name="vehicle_id">

                        <!-- Body Type -->
                        <div class="form-group">
                            <label for="edit_vec_body" class="h6">Body Type</label>
                            <select class="form-control" name="body_type" id="edit_vec_body" required>
                                <option>SUV</option>
                                <option>Salon/Sedan</option>
                                <option>Hatchback</option>
                                <option>Crossover</option>
                                <option>Sports Car</option>
                                <option>Convertible</option>
                                <option>Coupe</option>
                                <option>Vans</option>
                                <option>Trucks/Pick Up</option>
                                <option>Wagons</option>
                            </select>
                        </div>

                        <!-- Vehicle Year -->
                        <div class="form-group">
                            <label for="edit_vec_year" class="h6">Vehicle Year</label>
                            <input type="text" class="form-control" name="vec_year" id="edit_vec_year" required>
                        </div>

                        <!-- Vehicle Make -->
                        <div class="form-group">
                            <label for="edit_vec_make" class="h6">Vehicle Make</label>
                            <input type="text" class="form-control" name="vec_make" id="edit_vec_make" required>
                        </div>

                        <!-- Vehicle Model -->
                        <div class="form-group">
                            <label for="edit_vec_model" class="h6">Vehicle Model</label>
                            <input type="text" class="form-control" name="vec_model" id="edit_vec_model" required>
                        </div>

                        <!-- Vehicle Plate -->
                        <div class="form-group">
                            <label for="edit_vec_plate" class="h6">Vehicle Plate</label>
                            <input type="text" class="form-control" maxlength="8" name="vec_plate" id="edit_vec_plate" required>
                        </div>

                        <!-- VIN/Chassis No -->
                        <div class="form-group">
                            <label for="edit_vec_vin" class="h6">VIN/Chassis No</label>
                            <input type="text" class="form-control" maxlength="17" name="vec_vin" id="edit_vec_vin" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update Vehicle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#vehiclesTable').DataTable({
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

            // Custom search field linked to DataTable
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw(); // Trigger DataTable search with the input value
            });

            // // When the edit button is clicked, open the modal and fill the data
            // $('#vehiclesTable').on('click', '.edit-btn-customers', function() {
            // // $('.edit-btn-customers').on('click', function() {
            //     var vehicleId = $(this).data('id');
            //     // Fetch customer data via AJAX
            //     $.ajax({
            //         url: '/workshop/vehicles/' + vehicleId + '/edit',
            //         method: 'GET',
            //         success: function(response) {
            //             // Populate the form fields with the retrieved data
            //             $('#edit_customer_id').val(response.cust_id);
            //             $('#edit_name').val(response.cust_name);
            //             $('#edit_email').val(response.cust_email);
            //             $('#edit_phone').val(response.cust_mobile);
            //             $('#edit_address').val(response.cust_address);
            //             $('#edit_lga').val(response.cust_lga);

            //             // Set the form action dynamically
            //             $('#editCustomerForm').attr('action', '/workshop/customers/' + customerId);

            //             // Show the modal
            //             $('#editCustomerModal').modal('show');
            //         },
            //         error: function(xhr) {
            //             console.error('Error fetching customer data:', xhr);
            //             alert('Error fetching customer data. Please try again.');
            //         }
            //     });
            // });
            // When the edit button is clicked, open the modal and fill the data
            $('#vehiclesTable').on('click', '.edit-btn-vehicles', function() {
                var vehicleId = $(this).data('id');

                // Fetch vehicle data via AJAX
                $.ajax({
                    url: '/workshop/vehicles/' + vehicleId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        // Populate the form fields with the retrieved data
                        $('#edit_vehicle_id').val(response.vec_id);
                        $('#edit_vec_body').val(response.vec_body);
                        $('#edit_vec_year').val(response.vec_year);
                        $('#edit_vec_make').val(response.vec_make);
                        $('#edit_vec_model').val(response.vec_model);
                        $('#edit_vec_plate').val(response.vec_plate);
                        $('#edit_vec_vin').val(response.vec_vin);

                        // Set the form action dynamically
                        $('#editVehicleForm').attr('action', '/workshop/vehicles/' + vehicleId);

                        // Show the modal
                        $('#editVehicleModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('Error fetching vehicle data:', xhr);
                        alert('Error fetching vehicle data. Please try again.');
                    }
                });
            });

        });

        // Function to validate digits only in an input field
        function validateDigits(input) {
            // Replace any non-digit character with an empty string
            input.value = input.value.replace(/\D/g, '');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Get the button and the form elements
            var toggleFormButton = document.getElementById('toggleFormButton');
            var formContainer = document.getElementById('addNewVehicle');
            var customerForm = document.getElementById('vehicleForm');

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

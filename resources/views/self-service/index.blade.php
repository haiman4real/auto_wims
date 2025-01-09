<x-app-layout>

    <x-slot name="title">
        Self Service Bookings
    </x-slot>
    <div class="container-fluid py-4">
        <!-- Header -->
        <!-- Page content -->

        <div class="container-fluid py-4">
            <div class="row">
              <div class="col-12">
                <div class="card mb-4">
                  <div class="card-header pb-0">
                    <h6>Bookings List
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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email Address</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phone No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">VIN</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sch. Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sch. Time</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Service</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Make</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Year</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Number Plate</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Service Location</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                            {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Body Type</th> --}}
                            <th class="text-secondary opacity-7"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $index => $booking)
                                <tr>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{$index + 1}}
                                        </p>
                                    </td>

                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <h6 class="mb-0 text-sm">{{  $booking->name }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $booking->email  }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $booking->phone }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs text-secondary mb-0">{{ $booking->vin }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $booking->pickup_date }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $booking->pickup_time }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $booking->service ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $booking->vehicle_make ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $booking->vehicle_model ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $booking->vehicle_year ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $booking->license_plate ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $booking->service ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if ($booking->status == 'completed')
                                            <span class="badge badge-sm bg-gradient-success">{{ucfirst($booking->status)}}</span>
                                        @elseif ($booking->status == 'deleted')
                                            <span class="badge badge-sm bg-gradient-danger">{{$booking->status}}</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-warning">{{ucfirst($booking->status)}}</
                                        @endif
                                    </td>

                                    {{-- <td class="align-middle">
                                        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))
                                            <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs edit-btn-vehicles" data-id="{{ $booking->id }}" data-toggle="modal" data-target="#editVehicleModal" data-original-title="Edit Vehicle">
                                                <i class="fa fa-edit" style="color: rgb(255, 179, 0); font-size:14px;" aria-hidden="true"></i>
                                            </a>
                                            &nbsp;
                                            <a href="{{ route('vehicles.delete', $booking->id) }}" class="text-secondary font-weight-bold text-xs">
                                                <i class="fa fa-ban" style="color: red; font-size:14px;" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </td> --}}
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

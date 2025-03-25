<x-app-layout>
    <x-slot name="title">
        Self Service Bookings
    </x-slot>
    <div class="container-fluid py-4">
        <!-- Header and filter form omitted for brevity -->
         <!-- Header -->
         <div class="header pb-4 pt-md-4">
            <div class="container-fluid">
            <div class="header-body" id="schedule-new-tracking">
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-6 col-lg-12">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-header pb-0">
                            <h6>SCHEDULE NEW BOOKING
                                {{-- <a href="#schedule-new-tracking"><button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add new Item --}}
                                </button></a>
                            </h6>
                          </div>
                    <div class="card-body">

                        <form action="{{ route('self-service.store') }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                            @csrf
                            <!-- Customer Details -->
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                                    <div class="invalid-feedback">Name is required.</div>
                                </div>
                                <div class="col">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                    <div class="invalid-feedback">Valid email is required.</div>
                                </div>
                                <div class="col">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                    <div class="invalid-feedback">Phone number is required.</div>
                                </div>
                            </div>

                            <!-- Contact and Address -->

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="home" class="form-label">Home Address</label>
                                    <input type="text" id="home" name="home_address" class="form-control" value="{{ old('home_address') }}" required>
                                </div>

                                <!-- Service Location -->
                                <div class="col">
                                    <label for="service_location_type" class="form-label">Service Location</label>
                                    <select id="service_location_type" name="service_location[type]" class="form-select" required>
                                        <option value="">Select Location Type</option>
                                        <option value="Home" {{ old('service_location.type') == 'Home' ? 'selected' : '' }}>Home</option>
                                        <option value="Office" {{ old('service_location.type') == 'Office' ? 'selected' : '' }}>Office</option>
                                        <option value="Other" {{ old('service_location.type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col" id="service_location_address_container" style="display: none;">
                                    <label for="service_location_address" class="form-label">Service Location Address</label>
                                    <input type="text" id="service_location_address" name="service_location[address]" class="form-control" value="{{ old('service_location.address') }}">
                                </div>
                            </div>

                            <div class="mb-3" id="google_maps_container" style="display: none;">
                                <button id="pinLocationBtn" type="button" class="btn btn-secondary mb-2">Pin My Location</button>
                                <div id="google_map" style="height: 300px;"></div>
                                <input type="hidden" id="latitude" name="service_location[latitude]" value="{{ old('service_location.latitude') }}">
                                <input type="hidden" id="longitude" name="service_location[longitude]" value="{{ old('service_location.longitude') }}">
                            </div>

                            <!-- Service Details -->
                            <h5>Service Details</h5>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="service" class="form-label">Service</label>
                                    <select id="service" name="service" class="form-select" required>
                                        <option value="">Select a service</option>
                                        <option value="Brake Inspection and Replacement" {{ old('service') == 'Brake Inspection and Replacement' ? 'selected' : '' }}>Brake Inspection and Replacement</option>
                                        <option value="Battery Testing and Replacement" {{ old('service') == 'Battery Testing and Replacement' ? 'selected' : '' }}>Battery Testing and Replacement</option>
                                        <option value="Suspension Repairs" {{ old('service') == 'Suspension Repairs' ? 'selected' : '' }}>Suspension Repairs</option>
                                        <option value="Tire Replacement and Balancing" {{ old('service') == 'Tire Replacement and Balancing' ? 'selected' : '' }}>Tire Replacement and Balancing</option>
                                        <option value="General Diagnosis and Repair" {{ old('service') == 'General Diagnosis and Repair' ? 'selected' : '' }}>General Diagnosis and Repair</option>
                                        <option value="Other services" {{ old('service') == 'Other services' ? 'selected' : '' }}>Other services</option>
                                    </select>
                                    <div class="invalid-feedback">Service is required.</div>
                                </div>
                                <div class="col">
                                    <label for="pickup-date" class="form-label">Pickup Date</label>
                                    <input type="date" id="pickup-date" name="pickup_date" class="form-control" value="{{ old('pickup_date') }}" required>
                                </div>
                                <div class="col">
                                    <label for="pickup-time" class="form-label">Pickup Time</label>
                                    <input type="time" id="pickup-time" name="pickup_time" class="form-control" value="{{ old('pickup_time') }}" required>
                                </div>
                            </div>

                            <div class="mb-3" id="other_service_details_container" style="display: none;">
                                <label for="other_service_details" class="form-label">Please Provide Details for Other Services</label>
                                <textarea id="other_service_details" name="other_service_details" class="form-control" rows="4" placeholder="Describe the service you need...">{{ old('other_service_details') }}</textarea>
                            </div>


                            {{-- <div class="mb-3">
                                <label for="file" class="form-label">Upload Video or Picture <small style="color: red;">max file size - 5mb</small></label>
                                <input type="file" id="file" name="file" class="form-control" required>
                                <div class="invalid-feedback">File upload is required.</div>
                            </div> --}}

                            <!-- Vehicle Details -->
                            <h5>Vehicle Details</h5>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="vehicle-make" class="form-label">Vehicle Make</label>
                                    <select id="vehicle-make" name="vehicle_make" class="form-select select2" required>
                                        <option value="">Select Make</option>
                                        <option value="Other" {{ old('vehicle_make') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <input type="text" id="vehicle-make-manual" name="vehicle_make_manual" class="form-control mt-2" placeholder="Enter Vehicle Make" style="display: none;" value="{{ old('vehicle_make_manual') }}">
                                    <div class="invalid-feedback">Vehicle make is required.</div>
                                </div>
                                <div class="col">
                                    <label for="vehicle-model" class="form-label">Vehicle Model</label>
                                    <select id="vehicle-model" name="vehicle_model" class="form-select select2" required>
                                        <option value="">Select Model</option>
                                        <option value="Other" {{ old('vehicle_model') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <input type="text" id="vehicle-model-manual" name="vehicle_model_manual" class="form-control mt-2" placeholder="Enter Vehicle Model" style="display: none;" value="{{ old('vehicle_model_manual') }}">
                                    <div class="invalid-feedback">Vehicle model is required.</div>
                                </div>
                                <div class="col">
                                    <label for="vehicle-year" class="form-label">Vehicle Year</label>
                                    <select id="vehicle-year" name="vehicle_year" class="form-select" required>
                                        <option value="">Select Year</option>
                                        {{-- Populate dynamically or list options --}}
                                    </select>
                                    <div class="invalid-feedback">Vehicle year is required.</div>
                                </div>
                            </div>



                            <div class="row mb-3">
                                <div class="col">
                                    <label for="vin" class="form-label">VIN</label>
                                    <input type="text" id="vin" name="vin" class="form-control" maxlength="17" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" value="{{ old('vin') }}" required>
                                    <div class="invalid-feedback">VIN is invalid. Ensure it's 17 characters and doesn't include 'O' or 'I'.</div>
                                </div>
                                <div class="col">
                                    <label for="license_plate" class="form-label">License Plate</label>
                                    <input type="text" id="license_plate" name="license_plate" class="form-control" maxlength="8" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" value="{{ old('license_plate') }}" required>
                                    <div class="invalid-feedback">License Plate is invalid. Ensure it's 8 characters.</div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <h5>Additional Information</h5>
                            <div class="mb-3">
                                <label for="additional-details" class="form-label">Additional Details</label>
                                <textarea id="additional-details" name="additional_details" class="form-control" placeholder="Enter additional service details or description">{{ old('additional_details') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Book Now</button>
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
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Bookings List</h6>
                            <!-- Filter form here -->
                            <form method="GET" action="{{ route('self-service.index') }}" class="form-inline">
                                <div class="form-group mb-2" style="display: inline-block; margin-right: 10px;">
                                    <label for="start_date" class="sr-only">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                </div>
                                <div class="form-group mb-2" style="display: inline-block;">
                                    <label for="end_date" class="sr-only">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Filter</button>
                            </form>
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

                                <table id="selfServiceBookingsTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <!-- Table headings -->
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email Address</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phone No</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">VIN</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sch. Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sch. Time</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Service</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Car Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Number Plate</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Service Location</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($bookings as $index => $booking)
                                            <tr>
                                                <td class="align-middle text-center">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <h6 class="mb-0 text-sm">{{ $booking->name }}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $booking->email }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <p class="text-xs mb-0">{{ $booking->phone }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <p class="text-xs text-secondary mb-0">{{ $booking->vin }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <p class="text-xs mb-0">{{ $booking->pickup_date }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <p class="text-xs mb-0">{{ $booking->pickup_time }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <p class="text-xs mb-0">{{ $booking->service ?? 'N/A' }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <p class="text-xs mb-0 text-truncate" style="max-width: 150px;">
                                                            {{ $booking->vehicle_make ?? 'N/A' }} {{ $booking->vehicle_model ?? 'N/A' }} {{ $booking->vehicle_year ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <p class="text-xs mb-0">{{ $booking->license_plate ?? 'N/A' }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $location = is_array($booking->service_location) ? $booking->service_location : json_decode($booking->service_location, true);
                                                    @endphp
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            @if($location)
                                                                <p class="text-xs mb-0">{{ $location['address'] ?? 'N/A' }}</p>
                                                                @if(!empty($location['latitude']) && !empty($location['longitude']))
                                                                    <small class="text-muted">
                                                                        Lat: {{ $location['latitude'] }}, Lng: {{ $location['longitude'] }}
                                                                    </small>
                                                                @endif
                                                            @else
                                                                <p class="text-xs mb-0">N/A</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    @if ($booking->status == 'completed')
                                                        <span class="badge badge-sm bg-gradient-success">{{ ucfirst($booking->status) }}</span>
                                                    @elseif (in_array($booking->status, ['deleted', 'canceled']))
                                                        <span class="badge badge-sm bg-gradient-danger">{{ $booking->status }}</span>
                                                    @elseif (in_array($booking->status, ['initialized', 'in progress']))
                                                        <span class="badge badge-sm bg-gradient-info">{{ $booking->status }}</span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-warning">{{ ucfirst($booking->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <!-- Use a JavaScript trigger with a data attribute -->
                                                    <a href="javascript:void(0);" class="text-secondary font-weight-bold view-booking" data-id="{{ $booking->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="15" class="text-center">No bookings found</td>
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

    <!-- Booking Detail Modal -->
    <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingDetailModalLabel">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookingDetailContent">
                    <!-- Booking details will be loaded here via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // When the user clicks the eye icon, fetch booking details and show the modal.
        $(document).on('click', '.view-booking', function() {
            var bookingId = $(this).data('id');
            $.ajax({
                url: '/self-service/appointments/' + bookingId,
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    $('#bookingDetailContent').html(response);
                    $('#bookingDetailModal').modal('show');
                },
                error: function(xhr) {
                    alert('An error occurred while fetching booking details.');
                }
            });
        });

        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#selfServiceBookingsTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 }
                ],
                pageLength: 25,
                dom: 'Bfrtip',
                buttons: [
                    'csvHtml5', 'excelHtml5', 'pdfHtml5'
                ]
            });

            // Example custom search field (if needed)
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        });

        // Bootstrap validation
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // VIN Validation
        const vinInput = document.getElementById('vin');
        vinInput.addEventListener('input', function () {
            const invalidChars = /[OIoi]/; // Regex to detect O and I
            if (invalidChars.test(this.value) || this.value.length > 17) {
                this.setCustomValidity("VIN should not contain 'O' or 'I' and must be 17 characters max.");
            } else {
                this.setCustomValidity('');
            }
        });

        // Vehicle Year Validation
        const yearInput = document.getElementById('vehicle-year');
        yearInput.addEventListener('input', function () {
            const validYear = /^\d{4}$/; // Regex for 4-digit numeric year
            if (!validYear.test(this.value)) {
                this.setCustomValidity("Vehicle year must be a 4-digit number (e.g., 2002).");
            } else {
                this.setCustomValidity('');
            }
        });

        const locationType = document.getElementById('service_location_type');
        const addressContainer = document.getElementById('service_location_address_container');
        const mapsContainer = document.getElementById('google_maps_container');
        let map, marker;

        locationType.addEventListener('change', function () {
            const selectedValue = this.value;

            if (selectedValue === 'Home' || selectedValue === 'Office') {
                addressContainer.style.display = 'block';
                mapsContainer.style.display = 'none';
            } else if (selectedValue === 'Other') {
                addressContainer.style.display = 'block';
                mapsContainer.style.display = 'block';
                initGoogleMap();
            } else {
                addressContainer.style.display = 'none';
                mapsContainer.style.display = 'none';
            }
        });

        function initGoogleMap() {
            if (!map) {
                map = new google.maps.Map(document.getElementById('google_map'), {
                    center: { lat: -34.397, lng: 150.644 },
                    zoom: 8,
                });

                marker = new google.maps.Marker({
                    position: { lat: -34.397, lng: 150.644 },
                    map: map,
                    draggable: true,
                });

                google.maps.event.addListener(marker, 'dragend', function () {
                    const position = marker.getPosition();
                    document.getElementById('latitude').value = position.lat();
                    document.getElementById('longitude').value = position.lng();
                });
            }
        }

        // Function to pin user's current location with high accuracy
        function pinCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        const accuracy = position.coords.accuracy; // Accuracy in meters

                        // Update map center and marker position
                        const userLocation = { lat: userLat, lng: userLng };
                        map.setCenter(userLocation);
                        marker.setPosition(userLocation);

                        // Adjust zoom level dynamically based on accuracy
                        let zoomLevel = 15; // Default zoom
                        if (accuracy <= 2) {
                            zoomLevel = 20; // Very close zoom (best accuracy)
                        } else if (accuracy <= 5) {
                            zoomLevel = 18;
                        } else if (accuracy <= 10) {
                            zoomLevel = 16;
                        } else if (accuracy <= 20) {
                            zoomLevel = 14;
                        } else {
                            zoomLevel = 12;
                        }
                        map.setZoom(zoomLevel);

                        // Update input fields
                        document.getElementById('latitude').value = userLat;
                        document.getElementById('longitude').value = userLng;

                        // alert(`Location pinned with an accuracy of ${accuracy.toFixed(2)} meters.`);
                    },
                    function (error) {
                        alert('Geolocation failed: ' + error.message);
                    },
                    {
                        enableHighAccuracy: true, // Request high accuracy (GPS)
                        timeout: 10000, // Timeout after 10 seconds
                        maximumAge: 0, // No caching, always get fresh location
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        // Add event listener to button
        document.getElementById('pinLocationBtn').addEventListener('click', pinCurrentLocation);

        const serviceDropdown = document.getElementById('service');
        const otherServiceDetailsContainer = document.getElementById('other_service_details_container');

        serviceDropdown.addEventListener('change', function () {
            if (this.value === 'Other services') {
                otherServiceDetailsContainer.style.display = 'block';
            } else {
                otherServiceDetailsContainer.style.display = 'none';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // $('.select2').select2({
            //     placeholder: "Search or select an option",
            //     allowClear: true,
            // });

            const makeDropdown = document.getElementById('vehicle-make');
            const makeManualInput = document.getElementById('vehicle-make-manual');
            const modelDropdown = document.getElementById('vehicle-model');
            const modelManualInput = document.getElementById('vehicle-model-manual');

            // Fetch vehicle makes dynamically
            fetch('https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json')
                .then(response => response.json())
                .then(data => {
                    data.Results.forEach(make => {
                        const option = document.createElement('option');
                        option.value = make.Make_Name;
                        option.textContent = make.Make_Name;
                        makeDropdown.appendChild(option);
                    });
                });

            // Show manual input for make if "Other" is selected
            makeDropdown.addEventListener('change', function () {
                if (this.value === 'Other') {
                    makeManualInput.style.display = 'block';
                } else {
                    makeManualInput.style.display = 'none';
                    fetchModels(this.value);
                }
            });

            // Fetch models for the selected make
            function fetchModels(make) {
                console.log(make);
                fetch(`https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMake/${make}?format=json`)
                    .then(response => response.json())
                    .then(data => {
                        modelDropdown.innerHTML = '<option value="">Select Model</option>';
                        data.Results.forEach(model => {
                            const option = document.createElement('option');
                            option.value = model.Model_Name;
                            option.textContent = model.Model_Name;
                            modelDropdown.appendChild(option);
                        });
                        modelDropdown.insertAdjacentHTML('beforeend', '<option value="Other">Other</option>');
                    });
            }

            // Show manual input for model if "Other" is selected
            modelDropdown.addEventListener('change', function () {
                if (this.value === 'Other') {
                    modelManualInput.style.display = 'block';
                } else {
                    modelManualInput.style.display = 'none';
                }
            });

            const dateInput = document.getElementById("pickup-date");

            // Set the minimum date to today
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time to start of day
            const formattedToday = today.toISOString().split("T")[0];
            dateInput.setAttribute("min", formattedToday);

            // Prevent Sundays and past dates selection
            dateInput.addEventListener("input", function () {
                const selectedDate = new Date(this.value);
                const dayOfWeek = selectedDate.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

                if (dayOfWeek === 6) { // Check if Sunday
                    alert("Sundays are not allowed. Please select another date.");
                    this.value = ""; // Clear the input if Sunday is selected
                }
            });

            // Disable Sundays programmatically (for browsers that support `disabled`)
            function disableSundays() {
                const input = document.getElementById("pickup-date");
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                let daysDisabled = [];

                for (let i = 0; i < 365; i++) { // Iterate over the next year
                    let tempDate = new Date(today);
                    tempDate.setDate(tempDate.getDate() + i);
                    if (tempDate.getDay() === 0) { // 0 = Sunday
                        daysDisabled.push(tempDate.toISOString().split("T")[0]);
                    }
                }

                input.setAttribute("onfocus", "this.blur()"); // Prevent manual entry
                input.addEventListener("change", function () {
                    if (daysDisabled.includes(this.value)) {
                        alert("Sundays are not allowed. Please select another date.");
                        this.value = "";
                    }
                });
            }

            disableSundays();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const yearDropdown = document.getElementById('vehicle-year');
            const currentYear = new Date().getFullYear();

            for (let year = 1990; year <= currentYear; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearDropdown.appendChild(option);
            }
        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsqBiViDWyNOV48P0aMZpWOIF1m9Ocp9s"></script>
</x-app-layout>
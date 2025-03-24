<x-app-layout>
    <x-slot name="title">
        Self Service Bookings
    </x-slot>
    <div class="container-fluid py-4">
        <!-- Header and filter form omitted for brevity -->
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
                                                        $location = $booking->service_location ? json_decode($booking->service_location, true) : null;
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
    </script>
</x-app-layout>
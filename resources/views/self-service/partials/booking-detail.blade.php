<div>
    @php
        // Decode the booking response to check for service proof details.
        // $decodedResponse = $booking->response ? json_decode(json_decode($booking->response, true), true) : null;
        $decodedResponse = is_array($booking->response) ? $booking->response : ($booking->response ? json_decode($booking->response, true) : null);
    @endphp

    <!-- Basic Booking Information -->
    <p><strong>Transaction ID:</strong> {{ $decodedResponse['transaction_id'] ?? "N/A" }}</p>
    <p><strong>Customer Name:</strong> {{ $booking->name }}</p>
    <p><strong>Email:</strong> {{ $booking->email }}</p>
    <p><strong>Phone:</strong> {{ $booking->phone }}</p>
    <p><strong>Home Address:</strong> {{ $booking->home_address ?? 'N/A' }}</p>

    <!-- Schedule Information -->
    <p><strong>Scheduled Date:</strong> {{ $booking->pickup_date }}</p>
    <p><strong>Scheduled Time:</strong> {{ $booking->pickup_time }}</p>

    <!-- Vehicle & Service Information -->
    <p>
        <strong>Vehicle:</strong>
        {{ $booking->vehicle_make ?? 'N/A' }}
        {{ $booking->vehicle_model ?? 'N/A' }}
        {{ $booking->vehicle_year ?? 'N/A' }}
    </p>
    <p><strong>VIN:</strong> {{ $booking->vin }}</p>
    <p><strong>License Plate:</strong> {{ $booking->license_plate ?? 'N/A' }}</p>
    <p><strong>Service:</strong> {{ $booking->service ?? 'N/A' }}</p>
    <p><strong>Other Service Details:</strong> {{ $booking->other_service_details ?? 'N/A' }}</p>
    <p><strong>Additional Details:</strong> {{ $booking->additional_details ?? 'N/A' }}</p>

    <!-- Service Location -->
    @php
        // $location = $booking->service_location ? json_decode($booking->service_location, true) : null;
        $location = is_array($booking->service_location) ? $booking->service_location : json_decode($booking->service_location, true);

    @endphp
    <p>
        <strong>Service Location:</strong>
        @if($location)
            Type: {{ $location['type'] ?? 'N/A' }}<br>
            Address: {{ $location['address'] ?? 'N/A' }}<br>
            @if(!empty($location['latitude']) || !empty($location['longitude']))
                Lat: {{ $location['latitude'] ?? 'N/A' }}, Lng: {{ $location['longitude'] ?? 'N/A' }}
            @endif
        @else
            N/A
        @endif
    </p>

    <!-- Status and Timestamps -->
    <p><strong>Status:</strong> {{ $booking->status }}</p>
    <p><strong>Created At:</strong> {{ $booking->created_at }}</p>
    <p><strong>Updated At:</strong> {{ $booking->updated_at }}</p>



    @if($decodedResponse && isset($decodedResponse['file_url']))
        <button type="button" class="btn btn-info mt-3" id="viewServiceProof" data-file-path="{{ asset($decodedResponse['file_url']) }}">
            View Service Proof
        </button>
    @endif

    <!-- Additional booking details can go here -->
</div>

<script>
    document.getElementById('viewServiceProof').addEventListener('click', function() {
        var filePath = this.getAttribute('data-file-path');
        var modalContent = document.getElementById('serviceProofContent');

        // Clear previous content
        modalContent.innerHTML = '';

        // Create an image or video element based on the file type
        if (filePath.match(/\.(jpeg|jpg|gif|png)$/) != null) {
            var img = document.createElement('img');
            img.src = filePath;
            img.className = 'img-fluid';
            modalContent.appendChild(img);
        } else if (filePath.match(/\.(mp4|webm|ogg)$/) != null) {
            var video = document.createElement('video');
            video.src = filePath;
            video.controls = true;
            video.className = 'img-fluid';
            modalContent.appendChild(video);
        } else {
            modalContent.innerHTML = '<p>Unsupported file type.</p>';
        }

        // Show the modal
        var serviceProofModal = new bootstrap.Modal(document.getElementById('serviceProofModal'));
        serviceProofModal.show();
    });
</script>

<!-- Service Proof Modal -->
<div class="modal fade" id="serviceProofModal" tabindex="-1" aria-labelledby="serviceProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
              <div class="modal-header">
                   <h5 class="modal-title" id="serviceProofModalLabel">Service Proof</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="serviceProofContent">
                   <!-- Service proof image or video will be loaded here -->
              </div>
         </div>
    </div>
</div>
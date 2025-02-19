<x-app-layout>

    <x-slot name="title">
        Customers
    </x-slot>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="header pb-4 pt-md-4">
            <div class="container-fluid">
            <div class="header-body" id="schedule-new-customer">
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-6 col-lg-12" id="customerDetailsContainer">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-header pb-0">
                                <h6>Search Customer
                                    {{-- <button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;">
                                        +
                                    </button> --}}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    <input class="form-control" type="text" id="customerSearch" placeholder="Enter Customer Name">
                                </div>
                                <ul id="customerList" class="list-group mt-3"></ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-12" id="vehicleDetailsContainer">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-header pb-0">
                                <h6>Search Vehicle
                                    {{-- <button id="addVehicleBtn" class="btn btn-primary btn-sm float-end">+</button> --}}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    <input class="form-control" type="text" id="vehicleSearch" placeholder="Enter Vehicle Number Plate">
                                </div>
                                <ul id="vehicleList" class="list-group mt-3"></ul>
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
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="mb-0">Service Order Details</h6>
                                </div>
                                {{-- <div class="col text-right" style="float: right;">
                                    <div class="btn btn-sm btn-danger" id="clearBookingBtn">Clear Job Order</div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <!-- Primary Details -->
                                <div class="col-12">
                                    <div class="row text-black">
                                        <div class="col-xl-3 mb-3">
                                            <label for="bookingdriver" class="h6">Brought By:</label>
                                            <input class="form-control" type="text" id="bookingdriver" name="bookingdriver" value="" readonly>
                                            <input type="text" id="bookingCustomerId" hidden readonly>
                                            <input type="text" id="bookingVehicleId" hidden readonly>
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label for="bookingorder" class="h6">Order Number:</label>
                                            <input class="form-control" type="text" name="bookingorder" id="bookingorder" value="WIMS/" readonly style="font-weight:bold;">
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label for="bookingdate" class="h6">Date:</label>
                                            <input class="form-control" type="text" name="bookingdate" id="bookingdate" value="" readonly>
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="h5 d-block">&nbsp;</label>
                                            <button id="bookVehicleBtn" class="form-control btn btn-primary">BOOK VEHICLE</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Service Description -->
                                <div class="col-12 mb-4">
                                    <label for="bookingsDesc" class="h6">Service Description/Complaints:</label>
                                    <textarea class="form-control" name="bookingsDesc" id="bookingsDesc" rows="5" required></textarea>
                                </div>

                                <!-- Other Details -->
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <h6 class="mb-3">Other Details</h6>
                                            <div class="mb-3">
                                                <label for="umbrella" class="form-label">Current Odometer Reading:</label>
                                                <input class="form-control" type="number" id="umbrella" name="servCategory" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="servicebooklet" class="form-label">Service Booklet:</label>
                                                <select class="form-select" id="servicebooklet" name="servCategory" required>
                                                    <option></option>
                                                    <option>Yes</option>
                                                    <option selected>No</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="sparewheel" class="form-label">Spare Wheel & Cover:</label>
                                                <select class="form-select" id="sparewheel" name="servCategory" required>
                                                    <option></option>
                                                    <option>Yes</option>
                                                    <option selected>No</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jack" class="form-label">Jack & Handle:</label>
                                                <select class="form-select" id="jack" name="servCategory" required>
                                                    <option></option>
                                                    <option selected>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="toolkit" class="form-label">A/C:</label>
                                                <input class="form-control" type="text" id="toolkit" name="servCategory" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="mats" class="form-label">Horn:</label>
                                                <select class="form-select" id="mats" name="servCategory" required>
                                                    <option></option>
                                                    <option selected>Okay</option>
                                                    <option>Not working Properly</option>
                                                    <option>Not Working</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="stereo" class="form-label">Stereo & Speakers:</label>
                                                <select class="form-select" id="stereo" name="servCategory" required>
                                                    <option></option>
                                                    <option>Yes</option>
                                                    <option selected>No</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="cdplayer" class="form-label">CD Player:</label>
                                                <select class="form-select" id="cdplayer" name="servCategory" required>
                                                    <option></option>
                                                    <option>Yes</option>
                                                    <option selected>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Details -->
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="clock" class="form-label">Clock:</label>
                                                <select class="form-select" id="clock" name="servCategory" required>
                                                    <option></option>
                                                    <option>Yes</option>
                                                    <option selected>No</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="windscreen" class="form-label">Wind Screen:</label>
                                                <select class="form-select" id="windscreen" name="servCategory" required>
                                                    <option></option>
                                                    <option selected>In Good Condition</option>
                                                    <option>Cracked</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="wcap" class="form-label">Dashboard Lights:</label>
                                                <input class="form-control" type="text" id="wcap" name="servCategory" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="bodydamages" class="form-label">Body Damages:</label>
                                                <input class="form-control" type="text" id="bodydamages" name="servCategory" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="monograms" class="form-label">Monograms:</label>
                                                <select class="form-select" id="monograms" name="servCategory" required>
                                                    <option></option>
                                                    <option>Yes</option>
                                                    <option selected>No</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fire" class="form-label">Fire Extinguisher:</label>
                                                <select class="form-select" id="fire" name="servCategory" required>
                                                    <option></option>
                                                    <option selected>Yes</option>
                                                    <option>No</option>
                                                    <option>Expired</option>
                                                </select>
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
    </div>


    <script>
        // Function to validate digits only in an input field
        function validateDigits(input) {
            // Replace any non-digit character with an empty string
            input.value = input.value.replace(/\D/g, '');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const customerSearch = document.getElementById('customerSearch');
            const vehicleSearch = document.getElementById('vehicleSearch');
            const customerList = document.getElementById('customerList');
            const vehicleList = document.getElementById('vehicleList');
            const broughtBy = document.getElementById('bookingdriver');
            const orderNumber = document.getElementById('bookingorder');
            const orderDate = document.getElementById('bookingdate');
            const addVehicleBtn = document.getElementById('addVehicleBtn');
            const addVehicleForm = document.getElementById('addVehicleForm');
            const newVehiclePlate = document.getElementById('newVehiclePlate');
            const newVehicleModel = document.getElementById('newVehicleModel');
            const saveVehicle = document.getElementById('saveVehicle');

            const apiUrl = '/api/customers'; // Update with your API endpoint

            customerSearch.addEventListener('input', function () {
                const query = customerSearch.value;
                if (query.length > 2) {
                    fetch(`${apiUrl}?search=${query}`)
                        .then(res => res.json())
                        .then(data => {
                            customerList.innerHTML = '';
                            data.forEach(customer => {
                                const li = document.createElement('li');
                                li.classList.add('list-group-item');
                                li.textContent = customer.cust_name;
                                li.dataset.id = customer.cust_id;
                                li.addEventListener('click', () => {
                                    selectCustomer(customer);
                                    // loadCustomerDetails(customer);
                                });
                                customerList.appendChild(li);
                            });
                        });
                }
            });

            // Handle customer selection
            function selectCustomer(customer) {
                // Fill "Brought By" field based on customer type
                broughtBy.value = customer.cust_type === 'individual' ? 'Owner' : customer.cust_name;
                document.getElementById('bookingCustomerId').value = customer.cust_id;

                const customerDetailsCard = `
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-body">
                            <h6>Customer Details
                                <button class="btn btn-danger btn-sm d-none d-sm-inline-block" id="deselectCustomerBtn" type="button" style="float: right; margin-left: -50%;">
                                        x
                                    </button>
                            </h6>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-flush">
                                    <tbody>
                                        <tr style="color:black;">
                                            <td>
                                                ${customer.cust_name}<br>
                                                ${customer.cust_address},<br>
                                                ${customer.cust_lga}
                                            </td>
                                            <td>
                                                ${customer.cust_mobile}<br>
                                                ${customer.cust_email || 'N/A'}<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Account Type: <span style="text-transform:uppercase; color:black;">
                                                    ${customer.cust_type}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;

                // Replace the customer search card with the customer details card
                document.getElementById('customerDetailsContainer').innerHTML = customerDetailsCard;


                // Load associated vehicles
                loadVehicles(customer.cust_id);

                document.getElementById('deselectCustomerBtn').addEventListener('click', () => {
                        deselectCustomer();
                });

                document.getElementById('clearBookingBtn').addEventListener('click', () => {
                        deselectCustomer();
                });
            }

            function deselectCustomer(){
                // Reload the page after 1 seconds
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }

            function loadVehicles(customerId) {
                const apiUrl = `/api/vehicles?customer_id=${customerId}`; // Endpoint to fetch vehicles for a customer

                // Fetch vehicles from the server
                fetch(apiUrl)
                    .then((response) => response.json())
                    .then((vehicles) => {
                        vehicleList.innerHTML = ''; // Clear previous results

                        if (vehicles.length === 0) {
                            // Show a message if no vehicles are found
                            const noVehicleMessage = document.createElement('li');
                            noVehicleMessage.classList.add('list-group-item', 'text-muted');
                            noVehicleMessage.textContent = 'No vehicles found for this customer.';
                            vehicleList.appendChild(noVehicleMessage);
                            return;
                        }

                        // Populate the list with fetched vehicles
                        vehicles.forEach((vehicle) => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item');
                            li.textContent = `${vehicle.vec_plate} - ${vehicle.vec_year} ${vehicle.vec_make} ${vehicle.vec_model}`;
                            li.dataset.id = vehicle.vec_id; // Attach vehicle ID to the element

                            li.addEventListener('click', () => {
                                selectVehicle(vehicle);
                            });

                            vehicleList.appendChild(li);
                        });
                    })
                    .catch((error) => {
                        console.error('Error fetching vehicles:', error);
                        alert('Failed to load vehicles. Please try again.');
                    });
            }

            // Handle vehicle selection (if needed)
            function selectVehicle(vehicle) {
                // Generate order number
                // orderNumber.value = `WIMS/${Date.now()}`;

                // // Fill the date with the current date
                // orderDate.value = new Date().toLocaleDateString();
                generateOrderNumber();
                document.getElementById('bookingVehicleId').value = vehicle.vec_id;



                const vehicleDetailsCard = `
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-body">
                            <h6>Vehicle Details
                                <button class="btn btn-sm btn-danger float-end" id="deselectVehicleBtn">x</button>
                            </h6>
                            <hr>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <tbody>
                                        <tr style="color:black;">
                                            <td>
                                                ${vehicle.vec_make || 'N/A'}<br>${vehicle.vec_model || 'N/A'}
                                            </td>
                                            <td>
                                                ${vehicle.vec_body || 'N/A'}<br>${vehicle.vec_year || 'N/A'}
                                            </td>
                                            <td>
                                                ${vehicle.vec_vin || 'N/A'}<br>${vehicle.vec_plate || 'N/A'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                Registered:
                                                <span style="text-transform:uppercase; color:black;">
                                                    ${new Date(vehicle.vec_reg_time * 1000).toLocaleDateString() || 'N/A'}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;

                // Replace the vehicle search card with the vehicle details card
                const vehicleDetailsContainer = document.getElementById('vehicleDetailsContainer');
                vehicleDetailsContainer.innerHTML = vehicleDetailsCard;

                // Add functionality to the deselect button
                document.getElementById('deselectVehicleBtn').addEventListener('click', () => {
                    deselectVehicle(vehicle.cust_id);
                });

                // alert(`Vehicle selected: ${vehicle.number_plate} (${vehicle.model})`);
            }

            function deselctCustomer(){
                // Reload the page after 5 seconds
                setTimeout(() => {
                    location.reload();
                }, 5000);
            }

            async function generateOrderNumber() {
                try {
                    // Fetch the total number of jobs from the server (replace with your API endpoint)
                    const response = await fetch('/api/jobs/count');
                    const data = await response.json();

                    // Extract the current year and calculate the next year and previous year timestamps
                    const currentYear = new Date().getFullYear();
                    const nextYearTimestamp = new Date(currentYear + 1, 0, 1).getTime();
                    const previousYearTimestamp = new Date(currentYear - 1, 11, 31).getTime();

                    // Get the job count (replace `data.count` with the actual response field)
                    const jobCount = data.count || 0;

                    // Generate the unique number
                    const yearShort = currentYear.toString().slice(-2); // Last two digits of the year
                    const paddedJobCount = String(jobCount + 1).padStart(9 - yearShort.length, '0'); // Pad job count

                    // Generate the order number
                    const orderNumber = `${yearShort}${paddedJobCount}`;
                    console.log('Generated Order Number:', orderNumber);

                    // Fill the order number field
                    document.getElementById('bookingorder').value = `WIMS/${orderNumber}`;

                    // Fill the date with the current date
                    // document.getElementById('bookingdate').value = new Date().toLocaleDateString();
                    // Set the order date with timestamp
                    const now = new Date();
                    const formattedDate = now.toLocaleDateString(); // Format the date
                    const formattedTime = now.toLocaleTimeString(); // Format the time
                    document.getElementById('bookingdate').value = `${formattedDate} ${formattedTime}`;
                } catch (error) {
                    console.error('Error generating order number:', error);
                    alert('Failed to generate order number. Please try again.');
                }
            }

            function loadCustomerDetails(customer) {
                broughtBy.value = customer.cust_type === 'individual' ? 'Owner' : customer.cust_name;
                orderNumber.value = `WIMS/${Date.now()}`;
                orderDate.value = new Date().toLocaleDateString();
                vehicleList.innerHTML = '';
                customer.vehicles.forEach(vehicle => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = vehicle.number_plate;
                    li.dataset.id = vehicle.id;
                    vehicleList.appendChild(li);
                });
            }



            function deselectVehicle(customerId) {
                const vehicleDetailsContainer = document.getElementById('vehicleDetailsContainer');
                loadVehicles(customerId);

                // Restore the vehicle search card
                vehicleDetailsContainer.innerHTML = `
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">
                            <h6>Search Vehicle</h6>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input class="form-control" type="text" id="vehicleSearch" placeholder="Enter Vehicle Number Plate">
                            </div>
                            <ul id="vehicleList" class="list-group mt-3"></ul> <!-- Vehicle List -->
                        </div>
                    </div>
                `;

                // Reinitialize the vehicle search functionality
                initVehicleSearch(customerId);
            }

            function initVehicleSearch(customerId) {
                const vehicleSearch = document.getElementById('vehicleSearch');
                const vehicleList = document.getElementById('vehicleList');

                vehicleSearch.addEventListener('input', function () {
                    const query = vehicleSearch.value.trim();

                    if (query.length > 2) {
                        const apiUrl = `/api/vehicles?search=${query}&cust_id=${customerId}`;
                        fetch(apiUrl)
                            .then((response) => response.json())
                            .then((vehicles) => {
                                vehicleList.innerHTML = ''; // Clear previous results

                                vehicles.forEach((vehicle) => {
                                    const li = document.createElement('li');
                                    li.classList.add('list-group-item');
                                    li.textContent = `${vehicle.vec_plate} - ${vehicle.vec_year} ${vehicle.vec_make} ${vehicle.vec_model}`;
                                    li.dataset.id = vehicle.vec_id;

                                    li.addEventListener('click', () => {
                                        selectVehicle(vehicle);
                                    });

                                    vehicleList.appendChild(li);
                                });
                            });
                    } else {
                        vehicleList.innerHTML = ''; // Clear results if query is too short
                    }
                });
            }

            document.getElementById('bookVehicleBtn').addEventListener('click', function () {
                bookVehicle();
            });

            function bookVehicle() {
                const customerId = document.getElementById('bookingCustomerId').value;
                const vehicleId = document.getElementById('bookingVehicleId').value;
                const orderNumber = document.getElementById('bookingorder').value;
                const description = document.getElementById('bookingsDesc').value;
                const csrfToken = '{{ csrf_token() }}';

                console.log(customerId, vehicleId, orderNumber, csrfToken);

                const otherDetails = {
                    odometer: document.getElementById('umbrella').value,
                    service_booklet: document.getElementById('servicebooklet').value,
                    spare_wheel: document.getElementById('sparewheel').value,
                    jack: document.getElementById('jack').value,
                    ac: document.getElementById('toolkit').value,
                    horn: document.getElementById('mats').value,
                    stereo: document.getElementById('stereo').value,
                    cd_player: document.getElementById('cdplayer').value,
                    clock: document.getElementById('clock').value,
                    wind_screen: document.getElementById('windscreen').value,
                    dashboard_lights: document.getElementById('wcap').value,
                    body_damages: document.getElementById('bodydamages').value,
                    monograms: document.getElementById('monograms').value,
                    fire_extinguisher: document.getElementById('fire').value,
                    bookingdriver: document.getElementById('bookingdriver').value,
                };

                fetch('/workshop/service-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, // Include the CSRF token
                    },
                    body: JSON.stringify({
                        customer_id: customerId,
                        vehicle_id: vehicleId,
                        order_number: orderNumber,
                        description: description,
                        other_details: otherDetails,
                    }),
                })
                    .then((response) => response.json())
                    .then((job) => {
                        // Display success popup
                        showNotification('success', 'Job created successfully!');
                        console.log(job);

                        // Reload the page after 5 seconds
                        setTimeout(() => {
                            location.reload();
                        }, 5000);
                    })
                    .catch((error) => {
                        console.error('Error:', error);

                        // Display error popup
                        showNotification('error', 'Failed to create job.');
                    });
            }

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

            // Initialize vehicle search on page load
            document.addEventListener('DOMContentLoaded', initVehicleSearch);

            addVehicleBtn.addEventListener('click', function () {
                addVehicleForm.style.display = addVehicleForm.style.display === 'none' ? 'block' : 'none';
            });

        });
    </script>
</x-app-layout>

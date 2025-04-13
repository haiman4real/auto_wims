<x-app-layout>
    <x-slot name="title">
        Generate Estimate
    </x-slot>

    <div class="container-fluid py-6">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header pb-1">
                <h5 style="font-weight:bold; text-transform:capitalize; margin-top:5px; padding-left:10px;">Bookings Informations for {{ $job->customer->cust_name }} | {{ $job->vehicle->vec_make }} {{ $job->vehicle->vec_model }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-12" id="customerDetailsContainer">
                        <div class="card mb-4 mb-xl-0">
                            <div class="card-body">
                                <h6>Customer Details</h6>
                                <div class="table-responsive">
                                    <table class="table table-flush">
                                        <tbody>
                                            <tr style="color:black; font-size: 8px;">
                                                <td>
                                                    {{ $job->customer->cust_name}}<br>
                                                    {{ $job->customer->cust_address}}, {{ $job->customer->cust_lga}}
                                                </td>
                                                <td>
                                                    {{ $job->customer->cust_mobile}}<br>
                                                    {{ $job->customer->cust_email ?? 'N/A'}}<br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-size: 10px;">
                                                    Account Type: <span style="text-transform:uppercase; color:black;">
                                                        {{ $job->customer->cust_type}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-12" id="vehicleDetailsContainer">
                        <div class="card mb-4 mb-xl-0">
                            <div class="card-body">
                                <h6>Vehicle Details</h6>
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <tbody>
                                            <tr style="color:black; font-size: 8px;">
                                                <td>
                                                    {{ $job->vehicle->vec_make ?? 'N/A'}}<br>{{ $job->vehicle->vec_model ?? 'N/A'}}
                                                </td>
                                                <td>
                                                    {{ $job->vehicle->vec_body ?? 'N/A'}}<br>{{ $job->vehicle->vec_year ?? 'N/A'}}
                                                </td>
                                                <td>
                                                    {{ $job->vehicle->vec_vin ?? 'N/A'}}<br>{{ $job->vehicle->vec_plate ?? 'N/A'}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="font-size: 10px;">
                                                    Registered:
                                                    <span style="text-transform:uppercase; color:black; font-size: 10px;">
                                                        {{ date("M j, Y h:i A", strtotime($job->vehicle->vec_reg_time)) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Vehicle & Booking Info Section -->
                <div class="info-container">
                    <!-- Vehicle & Booking Info Section -->
                    <div class="info-section">
                        <h6 class="section-heading">Service Advisor:</h6>
                        <div class="bg-lighter">
                            <span class="info-box">
                                <label>Vehicle brought by:</label>
                                <p>{{ data_get($job, 'other_details.bookingdriver', 'N/A') }}</p>
                            </span>
                            <span class="info-box">
                                <label>Bookings Ref:</label>
                                <p id="bookings_id">{{ $job->order_number }}</p>
                            </span>
                            <span class="info-box">
                                <label>Bookings Time:</label>
                                <p>{{ date("M j, Y h:i A", strtotime($job->created_at)) }}</p>
                            </span>
                            <span class="info-box">
                                <label>Bookings Details:</label>
                                <p>{{ $job->description }}</p>
                            </span>
                        </div>
                    </div>

                    <!-- Work Notes Section -->
                    <div class="info-section">
                        <h6 class="section-heading">Work Notes:</h6>
                        <div class="bg-lighter">
                            <span class="info-box">
                                <label>Service Advisor:</label>
                                <p>{{ optional(App\Models\User::find(data_get(collect($job['workflow'])->firstWhere('job_type', 'service_advisor_comments'), 'performer')))->user_name ?? 'N/A' }}</p>
                            </span>
                            <span class="info-box">
                                <label>Advisor Note:</label>
                                <p>{{ data_get(collect($job->workflow)->firstWhere('job_type', 'service_advisor_comments'), 'details.service_advise', 'No Service advise') }}</p>
                            </span>
                            <span class="info-box advisor-comment">
                                <label>Advisor Comment:</label>
                                <p>{{ data_get($job, 'other_details.bookingdriver', 'N/A') }}</p>
                            </span>
                            <span class="info-box">
                                <label>Technician on Job:</label>
                                <p>{{ optional(App\Models\User::find(data_get(collect($job['workflow'])->firstWhere('job_type', 'technician_assignment'), 'performer')))->user_name ?? 'N/A' }}</p>
                            </span>
                            <span class="info-box">
                                <label>Workshop Findings:</label>
                                <p>{{ data_get(collect($job->workflow)->firstWhere('job_type', 'awaiting_job_advise'), 'details.workshop_findings', 'No workshop findings reported') }}</p>
                            </span>
                            <span class="info-box">
                                <label>Workshop Recommendations:</label>
                                <p>{{ data_get(collect($job->workflow)->firstWhere('job_type', 'awaiting_job_advise'), 'details.required_spare_parts', 'No parts required yet') }}</p>
                            </span>
                            <span class="info-box">
                                <label>Status:</label>
                                <p>{{ data_get($job, 'status', 'N/A') }}</p>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Table Section -->
                <div class="table-responsive">
                    <form id="estimateForm">
                        @csrf
                        <input type="hidden" name="job_id" id="job_id" value="{{ $job->id }}">

                        <table class="table align-items-center table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PRODUCTS</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">DESCRIPTION</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">UNIT PRICE</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">QUANTITY</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="text-align:center;">TOTAL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="text-align:center;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="estimateTable">
                                <tr class="estimate-row">
                                    <td>
                                        <select class="form-select item-type">
                                            <option value="service">SERVICE</option>
                                            <option value="spare_parts">SPARE PARTS</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select item-desc select2">
                                            <option selected>---Select Item---</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control unit-price" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity" min="1" value="1">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control total-price" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary add-row">+</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>TOTAL</strong></td>
                                    <td>
                                        <input type="text" class="form-control" id="sumTotal" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>DISCOUNT</strong></td>
                                    <td>
                                        <input type="number" class="form-control" id="discount" placeholder="%" min="0" max="100">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        {{-- <button class="btn btn-primary" type="button" onclick="saveEstimate()">SAVE FOR LATER</button> --}}
                                        <button class="btn btn-success" type="submit">GENERATE ESTIMATE</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        /* General Styling */
        .info-section {
            padding: 10px 0;
        }

        /* Two-column Layout for Large Screens */
        .info-container {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two equal columns */
            gap: 10px; /* Space between columns */
        }

        /* On Smaller Screens, Stack the Sections */
        @media (max-width: 768px) {
            .info-container {
                grid-template-columns: 1fr; /* Single column on small screens */
            }
        }

        /* Box Styling */
        .bg-lighter {
            background-color: #f8f9fa; /* Light grey */
            padding: 10px;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Individual Info Boxes */
        .info-box {
            color: grey;
            background-color: white;
            height: auto;
            padding-left: 10px;
            padding-top: 5px;
            padding-bottom: 5px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 4px;
            display: flex;
            flex-direction: column;
        }

        /* Label Styling */
        .info-box label {
            font-size: 8px;
            font-weight: bold;
            text-transform: capitalize;
            color: black;
            margin-bottom: 3px;
        }

        /* Data Values */
        .info-box p {
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 5px;
            color: #343a40; /* Dark grey */
        }

        /* Special Formatting for Advisor Comments */
        .advisor-comment p {
            font-style: italic;
            color: rgb(65, 36, 36);
        }

        /* Section Headings */
        .section-heading {
            font-weight: bold;
            text-transform: capitalize;
            margin-top: 5px;
            padding-left: 10px;
            color: #495057;
        }

        /* General Table Styling */
        .table-responsive {
            overflow-x: auto; /* Enables horizontal scrolling on small screens */
        }

        /* Table Header Styling */
        .thead-light {
            background-color: #e9ecef !important; /* Light grey background */
            color: #495057 !important; /* Dark text */
        }

        /* Table Cells */
        .table td, .table th {
            padding: 8px;
            vertical-align: middle;
            text-align: justify;
        }

        /* Responsive Table: Ensure Proper Spacing */
        @media (max-width: 768px) {
            .table td, .table th {
                font-size: 10px; /* Reduce font size for small screens */
            }
        }
    </style>

    <!-- Include Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <!-- JavaScript -->
    {{-- <script>
        $(document).ready(function () {
            let services = @json($services);
            let spareParts = @json($spareParts);
            let custType = "{{ strtolower($job->customer->cust_type) }}"; // "individual" or "corporate"

            // Spare Part Markup Rules
            let markupRules = [
                { min: 0, max: 59999, individual: 35, corporate: 25 },
                { min: 60000, max: 90999, individual: 30, corporate: 25 },
                { min: 91000, max: 209999, individual: 25, corporate: 25 },
                { min: 210000, max: 999999, individual: 20, corporate: 20 },
                { min: 1000000, max: 1509999, individual: 15, corporate: 15 },
                { min: 1510000, max: 200000000, individual: 10, corporate: 15 }
            ];

            function getMarkup(price) {
                let markup = 0;
                markupRules.forEach(rule => {
                    if (price >= rule.min && price <= rule.max) {
                        markup = custType === "corporate" ? rule.corporate : rule.individual;
                    }
                });
                return markup;
            }

            function updatePrice(row) {
                let unitPrice = parseFloat(row.find(".item-desc option:selected").data("price")) || 0;
                let quantity = parseInt(row.find(".quantity").val()) || 1;
                let itemType = row.find(".item-type").val();

                // Apply markup only for spare parts
                if (itemType === "spare_parts") {
                    let markup = getMarkup(unitPrice);
                    unitPrice = unitPrice + (unitPrice * (markup / 100));
                }

                let totalPrice = unitPrice * quantity;
                row.find(".unit-price").val(unitPrice.toFixed(2));
                row.find(".total-price").val(totalPrice.toFixed(2));

                updateGrandTotal();
            }

            function updateGrandTotal() {
                let sumTotal = 0;
                $(".total-price").each(function () {
                    sumTotal += parseFloat($(this).val()) || 0;
                });

                let discount = $("#discount").val() || 0;
                sumTotal -= (sumTotal * (discount / 100));

                $("#sumTotal").val(sumTotal.toFixed(2));
            }

            function loadItems(row) {
                let type = row.find(".item-type").val();
                let descSelect = row.find(".item-desc");

                descSelect.empty();
                descSelect.append('<option disabled selected>--- Select Item ---</option>'); // Default option

                let items = type === "service" ? services : spareParts;

                items.forEach(item => {
                    let id = type === "service" ? item.serv_id : item.ID;  // Ensure correct ID is used
                    let price = type === "service" ? item.serv_amount : item.price;
                    let title = type === "service" ? item.serv_name : item.post_title;

                    // Ensure option contains data-id
                    descSelect.append(`<option value="${title}" data-id="${id}" data-price="${price}">${title}</option>`);
                });

                updatePrice(row);
            }

            function addNewRow() {
                let newRow = `
                    <tr class="estimate-row">
                        <td>
                            <select class="form-select item-type">
                                <option value="service">SERVICE</option>
                                <option value="spare_parts">SPARE PARTS</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select item-desc select2">
                                <option disabled selected>Select Item</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control unit-price" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control quantity" min="1" value="1">
                        </td>
                        <td>
                            <input type="text" class="form-control total-price" readonly>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger remove-row">-</button>
                        </td>
                    </tr>
                `;
                $("#estimateTable").append(newRow);
                loadItems($("#estimateTable tr:last"));
            }

            $(document).on("change", ".item-type", function () {
                loadItems($(this).closest("tr"));
            });

            $(document).on("change", ".item-desc, .quantity", function () {
                updatePrice($(this).closest("tr"));
            });

            $(document).on("click", ".add-row", function () {
                addNewRow();
            });

            $(document).on("click", ".remove-row", function () {
                $(this).closest("tr").remove();
                updateGrandTotal();
            });

            $("#estimateForm").submit(function (e) {
                e.preventDefault();

                // let jobId = $("input[name='job_id']").val();
                let jobId = $("#job_id").val(); // Ensure job ID is fetched correctly
                if (!jobId) {
                    alert("Error: Job ID is missing. Please refresh the page.");
                    return;
                }

                let items = [];
                let grandTotal = 0;

                $(".estimate-row").each(function () {
                    let type = $(this).find(".item-type").val();
                    let id = $(this).find(".item-desc option:selected").data("id"); // Get data-id
                    let name = $(this).find(".item-desc option:selected").text();
                    let price = parseFloat($(this).find(".unit-price").val());
                    let quantity = parseInt($(this).find(".quantity").val());
                    let discount = parseFloat($(this).find(".discount").val()) || 0; // Default discount is 0%

                    if (!id || !name || isNaN(price) || isNaN(quantity) || quantity <= 0) {
                        return; // Skip if invalid item
                    }

                    let discountAmount = (price * quantity) * (discount / 100);
                    let totalPrice = (price * quantity) - discountAmount;

                    items.push({
                        id,
                        name,
                        type,
                        price,
                        quantity,
                        discount,
                        total_price: totalPrice
                    });

                    grandTotal += totalPrice; // Add to grand total
                });

                if (items.length === 0) {
                    alert("Please add at least one service or spare part.");
                    return;
                }

                // Send AJAX request using POST method
                $.ajax({
                    url: "{{ route('service_booking.estimate.save') }}",
                    type: "POST",
                    contentType: "application/json",
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    data: JSON.stringify({
                        job_id: jobId,
                        items: items,
                        grand_total: grandTotal
                    }),
                    success: function (response) {
                        console.log(response);
                        alert(response.message + "\nGrand Total: ₦" + response.grand_total.toFixed(2));
                        window.location.href = "{{ route('service_booking.job_bank.admin') }}";
                    },
                    error: function (xhr) {
                        console.log("AJAX Error:", xhr.responseJSON);
                        alert("Error: " + (xhr.responseJSON?.message || "An unknown error occurred."));
                    }
                });
            });

            loadItems($(".estimate-row")); // Load default row items
        });
    </script> --}}

    <script>
        $(document).ready(function () {
            let services = @json($services);
            let spareParts = @json($spareParts);
            let custType = "{{ strtolower($job->customer->cust_type) }}"; // "individual" or "corporate"

            // Spare Part Markup Rules
            let markupRules = [
                { min: 0, max: 59999, individual: 35, corporate: 25 },
                { min: 60000, max: 90999, individual: 30, corporate: 25 },
                { min: 91000, max: 209999, individual: 25, corporate: 25 },
                { min: 210000, max: 999999, individual: 20, corporate: 20 },
                { min: 1000000, max: 1509999, individual: 15, corporate: 15 },
                { min: 1510000, max: 200000000, individual: 10, corporate: 15 }
            ];

            function getMarkup(price) {
                let markup = 0;
                markupRules.forEach(rule => {
                    if (price >= rule.min && price <= rule.max) {
                        markup = custType === "corporate" ? rule.corporate : rule.individual;
                    }
                });
                return markup;
            }

            function updatePrice(row) {
                // Retrieve unit price from the selected option's data attribute.
                let unitPrice = parseFloat(row.find(".item-desc option:selected").data("price")) || 0;
                let quantity = parseInt(row.find(".quantity").val()) || 1;
                let itemType = row.find(".item-type").val();

                // Apply markup only for spare parts.
                if (itemType === "spare_parts") {
                    let markup = getMarkup(unitPrice);
                    unitPrice = unitPrice + (unitPrice * (markup / 100));
                }

                // Calculate row total without discount.
                let totalPrice = unitPrice * quantity;
                row.find(".unit-price").val(unitPrice.toFixed(2));
                row.find(".total-price").val(totalPrice.toFixed(2));

                updateGrandTotal();
            }

            function updateGrandTotal() {
                let overallTotal = 0;
                let serviceTotal = 0;
                $(".estimate-row").each(function () {
                    let rowTotal = parseFloat($(this).find(".total-price").val()) || 0;
                    overallTotal += rowTotal;
                    let itemType = $(this).find(".item-type").val();
                    if (itemType === "service") {
                        serviceTotal += rowTotal;
                    }
                });
                // Global discount percentage from discount input.
                let discountValue = parseFloat($("#discount").val()) || 0;
                // Discount applies only on the service subtotal.
                let discountAmount = serviceTotal * (discountValue / 100);
                let finalTotal = overallTotal - discountAmount;
                $("#sumTotal").val(finalTotal.toFixed(2));
            }

            function loadItems(row) {
                let type = row.find(".item-type").val();
                let descSelect = row.find(".item-desc");

                descSelect.empty();
                descSelect.append('<option disabled selected>--- Select Item ---</option>');

                // Load items based on type.
                let items = type === "service" ? services : spareParts;
                items.forEach(item => {
                    let id = type === "service" ? item.serv_id : item.ID;
                    let price = type === "service" ? item.serv_amount : item.price;
                    let title = type === "service" ? item.serv_name : item.post_title;
                    descSelect.append(`<option value="${title}" data-id="${id}" data-price="${price}">${title}</option>`);
                });

                updatePrice(row);
            }

            function addNewRow() {
                let newRow = `
                    <tr class="estimate-row">
                        <td>
                            <select class="form-select item-type">
                                <option value="service">SERVICE</option>
                                <option value="spare_parts">SPARE PARTS</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select item-desc select2">
                                <option disabled selected>Select Item</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control unit-price" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control quantity" min="1" value="1">
                        </td>
                        <td>
                            <input type="text" class="form-control total-price" readonly>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger remove-row">-</button>
                        </td>
                    </tr>
                `;
                $("#estimateTable").append(newRow);
                loadItems($("#estimateTable tr:last"));
            }

            // Event handlers.
            $(document).on("change", ".item-type", function () {
                loadItems($(this).closest("tr"));
            });

            $(document).on("change", ".item-desc, .quantity", function () {
                updatePrice($(this).closest("tr"));
            });

            $(document).on("click", ".add-row", function () {
                addNewRow();
            });

            $(document).on("click", ".remove-row", function () {
                $(this).closest("tr").remove();
                updateGrandTotal();
            });

            $("#estimateForm").submit(function (e) {
                e.preventDefault();

                let jobId = $("#job_id").val();
                if (!jobId) {
                    alert("Error: Job ID is missing. Please refresh the page.");
                    return;
                }

                let items = [];
                let overallTotal = 0;
                let serviceTotal = 0;
                let discountValue = parseFloat($("#discount").val()) || 0;

                $(".estimate-row").each(function () {
                    let type = $(this).find(".item-type").val();
                    let selectedOption = $(this).find(".item-desc option:selected");
                    let id = selectedOption.data("id");
                    let name = selectedOption.text();
                    let price = parseFloat($(this).find(".unit-price").val()) || 0;
                    let quantity = parseInt($(this).find(".quantity").val());
                    if (!id || !name || isNaN(price) || isNaN(quantity) || quantity <= 0) {
                        return; // Skip invalid row.
                    }
                    let totalPrice = price * quantity;
                    overallTotal += totalPrice;
                    if (type === "service") {
                        serviceTotal += totalPrice;
                    }
                    items.push({
                        id: id,
                        name: name,
                        type: type,
                        price: price,
                        quantity: quantity,
                        discount: 0, // Discount is managed globally.
                        total_price: totalPrice
                    });
                });

                if (items.length === 0) {
                    alert("Please add at least one service or spare part.");
                    return;
                }

                // Calculate discount amount based solely on service items.
                let discountAmount = serviceTotal * (discountValue / 100);
                let finalTotal = overallTotal - discountAmount;

                $.ajax({
                    url: "{{ route('service_booking.estimate.save') }}",
                    type: "POST",
                    contentType: "application/json",
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    data: JSON.stringify({
                        job_id: jobId,
                        items: items,
                        grand_total: finalTotal,
                        discount: discountValue,
                        discount_amount: discountAmount
                    }),
                    success: function (response) {
                        console.log(response);
                        alert(response.message + "\nGrand Total: ₦" + finalTotal.toFixed(2));
                        window.location.href = "{{ route('service_booking.job_bank.admin') }}";
                    },
                    error: function (xhr) {
                        console.log("AJAX Error:", xhr.responseJSON);
                        alert("Error: " + (xhr.responseJSON?.message || "An unknown error occurred."));
                    }
                });
            });

            // Load items for any default existing rows.
            $(".estimate-row").each(function () {
                loadItems($(this));
            });
        });
    </script>
</x-app-layout>

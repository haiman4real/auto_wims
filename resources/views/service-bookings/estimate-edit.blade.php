<x-app-layout>
    <x-slot name="title">
        Edit Estimate for {{ $job->customer->cust_name }} - {{ $job->vehicle->vec_make }} {{ $job->vehicle->vec_model }}
    </x-slot>

    <div class="container-fluid py-6">
        <div class="card mb-4">
            <div class="card-header pb-1">
                <h5 style="font-weight:bold; text-transform:capitalize; margin-top:5px; padding-left:10px;">
                    Editing Estimate for {{ $job->customer->cust_name }} | {{ $job->vehicle->vec_make }} {{ $job->vehicle->vec_model }}
                </h5>
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
                <!-- Estimate Form -->
                <div class="table-responsive">
                    <form id="editEstimateForm">
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
                                <!-- Existing Services -->
                                @foreach($estimatedJobs['services'] as $service)
                                    <tr class="estimate-row">
                                        <td>
                                            <input type="text" class="form-control item-type" value="SERVICE" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control item-desc" value="{{ $service['name'] }}" data-id="{{ $service['id'] }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control unit-price" value="{{ $service['price'] }}" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity" min="1" value="{{ $service['quantity'] }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control total-price" value="{{ $service['total_price'] }}" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger remove-row">-</button>
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Existing Spare Parts -->
                                @foreach($estimatedJobs['spare_parts'] as $part)
                                    <tr class="estimate-row">
                                        <td>
                                            <input type="text" class="form-control item-type" value="SPARE_PART" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control item-desc" value="{{ $part['name'] }}" data-id="{{ $part['id'] }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control unit-price" value="{{ $part['price'] }}" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity" min="1" value="{{ $part['quantity'] }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control total-price" value="{{ $part['total_price'] }}" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger remove-row">-</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <button type="button" class="btn btn-primary add-row">+ Add Item</button>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" class="text-right"><strong>Discount (%)</strong></td>
                                    <td>
                                        <input type="number" class="form-control" id="discount"
                                               value="{{ isset($estimatedJobs['discount']) ? $estimatedJobs['discount'] : 0 }}"
                                               min="0" max="100">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Discount Amount</strong></td>
                                    <td>
                                        <input type="text" class="form-control" id="discountAmount"
                                               value="{{ isset($estimatedJobs['discount_amount']) ? number_format($estimatedJobs['discount_amount'], 2) : 0 }}"
                                               readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                                    <td>
                                        <input type="text" class="form-control" id="grandTotal"
                                               value="{{ number_format($estimatedJobs['grand_total'], 2) }}" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <button class="btn btn-success" type="submit">UPDATE ESTIMATE</button>
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

    <script>
        $(document).ready(function () {
            let services = @json($services);
            let spareParts = @json($spareParts);

            function updateGrandTotal() {
                let sumTotal = 0;
                $(".total-price").each(function () {
                    sumTotal += parseFloat($(this).val()) || 0;
                });

                let discountValue = parseFloat($("#discount").val()) || 0;
                let discountAmount = sumTotal * (discountValue / 100);
                let discountedTotal = sumTotal - discountAmount;

                $("#sumTotal").val(sumTotal.toFixed(2));
                $("#discountAmount").val(discountAmount.toFixed(2));
                $("#grandTotal").val(discountedTotal.toFixed(2));
            }

            function updatePrice(row) {
                let unitPrice;
                let quantity = parseInt(row.find(".quantity").val()) || 1;
                let descField = row.find(".item-desc");
                let itemType = row.find(".item-type").val();


                if (descField.is("select")) {
                    unitPrice = parseFloat(descField.find("option:selected").data("price")) || 0;
                } else {
                    unitPrice = parseFloat(row.find(".unit-price").val()) || 0;
                }

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

            function loadItems(row) {
                let type = row.find(".item-type").val();
                let descSelect = row.find(".item-desc");

                descSelect.empty();
                descSelect.append('<option disabled selected>--- Select Item ---</option>');

                let items = type === "service" ? services : spareParts;
                items.forEach(item => {
                    let id = type === "service" ? item.serv_id : item.ID;
                    let price = type === "service" ? item.serv_amount : item.price;
                    let title = type === "service" ? item.serv_name : item.post_title;

                    descSelect.append(`<option value="${title}" data-id="${id}" data-price="${price}">${title}</option>`);
                });

                updatePrice(row);
            }

            $(document).on("input", "#discount", function () {
                updateGrandTotal();
            });

            $(document).on("change", ".item-type", function () {
                loadItems($(this).closest("tr"));
            });

            $(document).on("change", ".item-desc, .quantity", function () {
                updatePrice($(this).closest("tr"));
            });

            $(document).on("click", ".remove-row", function () {
                $(this).closest("tr").remove();
                updateGrandTotal();
            });

            $(".add-row").click(function () {
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
                                <option disabled selected>---Select Item---</option>
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
            });

            $("#editEstimateForm").submit(function (e) {
                e.preventDefault();

                let jobId = $("#job_id").val();
                let items = [];
                let grandTotal = 0;
                let discountValue = parseFloat($("#discount").val()) || 0;

                $(".estimate-row").each(function () {
                    let row = $(this);
                    let typeField = row.find(".item-type");
                    let nameField = row.find(".item-desc");

                    let id = nameField.is("select") ? nameField.find("option:selected").data("id") : nameField.data("id");
                    let name = nameField.is("select") ? nameField.find("option:selected").text() : nameField.val();
                    let type = typeField.is("select") ? typeField.val() : typeField.val().toLowerCase(); // ✅ Fix: Ensure correct type
                    let price = parseFloat(row.find(".unit-price").val()) || 0;
                    let quantity = parseInt(row.find(".quantity").val()) || 1;
                    let total = parseFloat(row.find(".total-price").val()) || 0;

                    if (name && !isNaN(price) && !isNaN(quantity)) {
                        items.push({ id, name, type, price, quantity, total_price: total });
                        grandTotal += total;
                    }
                });

                let discountAmount = grandTotal * (discountValue / 100);
                let totalAfterDiscount = grandTotal - discountAmount;

                $.ajax({
                    url: "{{ route('service_booking.estimate.update') }}",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    contentType: "application/json",
                    data: JSON.stringify({
                        job_id: jobId,
                        items: items,
                        grand_total: totalAfterDiscount,
                        discount: discountValue,
                        discount_amount: discountAmount
                    }),
                    success: function (response) {
                        alert(response.message);
                        window.location.href = "{{ route('service_booking.job_bank.admin') }}";
                    },
                    error: function (xhr) {
                        alert("Error: " + xhr.responseJSON.message);
                    }
                });
            });


            // let services = @json($services);
            // let spareParts = @json($spareParts);

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
                let custType = "{{ strtolower($job->customer->cust_type) }}"; // "individual" or "corporate"
                let markup = 0;

                markupRules.forEach(rule => {
                    if (price >= rule.min && price <= rule.max) {
                        markup = custType === "corporate" ? rule.corporate : rule.individual;
                    }
                });

                return markup;
            }


        });
    </script>
</x-app-layout>

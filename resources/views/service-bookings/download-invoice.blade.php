<html>
    <head>
        <title>Invoice - {{ $job->order_number }}</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div id="invoice">
            <div class="container">
                        <div class="row pt-5 p-2 pb-4">
                            <div class="col-md-6">
                                <img src="{{ asset('img/clients/auto-main.png') }}" alt="logo">
                                <h3>AUTOPOINTE QUICK FIT</h3>
                                <p class="font-weight-bold">
                                    Fleet Management, Vehicle Diagnosis, Tire Services, Engine Service/Repair, Brake System,
                                    Suspension & Steering, A/C Services, Post RWC Inspection Repairs, Carbon Cleaning, Motor Third Party Insurance, etc.
                                </p>

                                @if($job->status == "completed" || $job->customer->cust_type == "corporate")
                                    <h1 style="color: green;">INVOICE</h1>
                                @else
                                    <h1 style="color: red;">ESTIMATE</h1>
                                @endif
                            </div>

                            <div class="col-md-6 text-right d-flex flex-column align-items-end">
                                <p class="font-weight-light">
                                    <span class="text-muted text-right">
                                        Head Office: Plot 102, Ogunnusi Road, Ojodu, Lagos, Nigeria.<br>
                                        Egbeda Office: 97, Idimu Road, Egbeda, Lagos, Nigeria.<br>
                                        Telephone: +2348188204111, 09065000041<br>
                                        Email: info@autopointe.com.ng<br>
                                        www.autopointe.com.ng
                                    </span>
                                </p>
                                <p class="font-weight-bold text-right">
                                    <span class="h6 text-grey">Order No:<br />
                                    <h3 class="text-right">{{ $job->order_number }}</h3></span>
                                </p>
                                <p class="text-muted text-right">Booking Date: {{ \Carbon\Carbon::parse($job->created_at)->format('M d, Y H:i A') }}</p>
                            </div>
                        </div>

                        <div class="row pb-2 p-2">
                            <div class="col-md-6">
                                <p class="font-weight-bold mb-2">Customer Information</p>
                                <p class="mb-1">{{ $job->customer->cust_name ?? 'N/A' }}</p>
                                <p class="mb-1">{{ $job->customer->cust_address ?? 'N/A' }}, {{ $job->customer->cust_lga ?? 'N/A' }}</p>
                                <p class="mb-1">{{ $job->customer->cust_mobile ?? 'N/A' }}</p>
                            </div>

                            <div class="col-md-6 text-right d-flex flex-column align-items-end">
                                <p class="font-weight-bold mb-2">Vehicle Details</p>
                                <p class="mb-1"><span class="text-muted">Reg No: </span> {{ $job->vehicle->vec_plate ?? 'N/A' }}</p>
                                <p class="mb-1"><span class="text-muted">VIN/Chasis No: </span> {{ $job->vehicle->vec_vin ?? 'N/A' }}</p>
                                <p class="mb-1"><span class="text-muted">Vehicle Make/Model: </span> {{ $job->vehicle->vec_make ?? 'N/A' }} {{ $job->vehicle->vec_model ?? 'N/A' }}</p>
                                <p class="mb-1"><span class="text-muted">Vehicle Year: </span> {{ $job->vehicle->vec_year ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @php
                            $estimatedJobs = json_decode($job->estimated_jobs, true);
                            $serviceTotal = collect($estimatedJobs['services'])->sum('total_price');
                            $sparePartsTotal = collect($estimatedJobs['spare_parts'])->sum('total_price');
                            $grandTotal = $estimatedJobs['grand_total'];
                            $discountApplied = ($serviceTotal + $sparePartsTotal) > $grandTotal
                                ? ($serviceTotal + $sparePartsTotal) - $grandTotal
                                : 0;
                            $vatRate = $estimatedJobs['vat'];
                            $vatAmount = $estimatedJobs['vat_amount'];
                            $grandTotalWithVat = $estimatedJobs['total_cost'];
                        @endphp

                        <div class="row p-3">
                            <div class="col">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th class="border-0 text-uppercase small font-weight-bold">S/N</th>
                                                <th class="border-0 text-uppercase small font-weight-bold">Type</th>
                                                <th class="border-0 text-uppercase small font-weight-bold">Description</th>
                                                <th class="border-0 text-uppercase small font-weight-bold">Quantity</th>
                                                <th class="border-0 text-uppercase small font-weight-bold">Unit Cost (₦)</th>
                                                <th class="border-0 text-uppercase small font-weight-bold">Total (₦)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $estimatedJobs = json_decode($job->estimated_jobs, true);
                                                $index = 1;
                                            @endphp

                                            @foreach($estimatedJobs['services'] as $service)
                                                <tr>
                                                    <td>{{ $index++ }}</td>
                                                    <td>Service</td>
                                                    <td>{{ $service['name'] }}</td>
                                                    <td>{{ $service['quantity'] }}</td>
                                                    <td>₦{{ number_format($service['price'], 2) }}</td>
                                                    <td>₦{{ number_format($service['total_price'], 2) }}</td>
                                                </tr>
                                            @endforeach

                                            @foreach($estimatedJobs['spare_parts'] as $part)
                                                <tr>
                                                    <td>{{ $index++ }}</td>
                                                    <td>Spare Part</td>
                                                    <td>{{ $part['name'] }}</td>
                                                    <td>{{ $part['quantity'] }}</td>
                                                    <td>₦{{ number_format($part['price'], 2) }}</td>
                                                    <td>₦{{ number_format($part['total_price'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse">
                            <div class="py-1 px-5 text-right">
                                <div class="mb-2">Service Total</div>
                                <div class="h2 font-weight-bold">₦{{ number_format($serviceTotal, 2) }}</div>
                            </div>
                            &nbsp;
                            <div class="py-1 px-5 text-right">
                                <div class="mb-2">Spare Parts Total</div>
                                <div class="h2 font-weight-bold">₦{{ number_format($sparePartsTotal, 2) }}</div>
                            </div>
                        </div>


                        <div class="d-flex flex-row-reverse bg-success text-black p-4">
                            <div class="py-3 px-5 text-right">
                                <div class="mb-2">Grand Total</div>
                                <div class="h2 font-weight-bold">₦{{ number_format($grandTotalWithVat, 2) }}</div>
                            </div>
                            <div class="py-3 px-5 text-right">
                                <div class="mb-2">VAT Amount</div>
                                <div class="h2 font-weight-bold">₦{{ number_format($vatAmount, 2) }}</div>
                            </div>
                            <div class="py-3 px-5 text-right">
                                <div class="mb-2">VAT</div>
                                <div class="h2 font-weight-bold">{{ number_format($vatRate, 1) }}%</div>
                            </div>
                            <div class="py-3 px-5 text-right">
                                <div class="mb-2">Sub Total</div>
                                <div class="h2 font-weight-bold">₦{{ number_format($grandTotal, 2) }}</div>
                            </div>


                            @if($discountApplied > 0)
                            <div class="py-3 px-5 text-right">
                                <div class="mb-2">Discount Applied</div>
                                <div class="h2 font-weight-light">₦{{ number_format($discountApplied, 2) }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="col pt-2" >
                            <table class="table">
                                 <thead>
                                     <tr style="text-align-center">
                                     <p><strong>TERMS AND CONDITIONS</strong><br></p>
                                     </tr>
                                     <tr>
                                         <i><p style="font-size: 8px;">
                                             1. The company shall not be responsible for any defect discovered in the customer/s vehicle at anytime where such defect(s) are not traceable to aspects of the work done on the vehicle as per the job card.<br>
                                             2. The company shall not be responsible for damage(s) done to customers vehicle in the cause of unauthorized repairs carried out by any staff of the company under PRIVATE ARRANGEMENT with the customer.<br>
                                             3. Customers are advised not to circumvent the normal company procedure for getting their vehicles repaired as the company disclaims any liability for the consequence of such action.<br>
                                             4. The company shall not be responsible to supply Parts/Items for repairs of customer/s vehicle where payment for such item has not been fully paid to the company except as otherwise agreed.<br>
                                             5. Completed estimates must be approved by the customer within seven (7) days.<br>
                                             6. 100% deposit of the approved estimate shall be made by the customer before commencement of any repair(s).<br>
                                             7.  Vehicles with estimates not approved after seven (7) days must be removed from the premises.<br>
                                             8. Vehicles with estimates not approved and removed from the premises within seven (7) days shall attract a daily demurrage charge of ₦5,000:00. Similarly, ready vehicles not collected within 72hours of communicating with the customer shall attract a daily demurrage charge of ₦5,000:00.<br>
                                             9. Vehicles not removed from the premises after six (6) months shall be auctioned without further notice.<br>
                                             10. Old vehicle parts not collected by customers after one (1) week of vehicle delivery shall be disposed of by the company.
                                             11. This Estimate/Proforma invoice is valid for the price(s) herein is/are subject to change without notice in view of the changes is Forex, therefore price(s) at the time of confirmed order will be charged.
                                             12. This Proforma invoice/estimate may be subject to any further additional job which couldn’t be physically noticed during inspection. Where such occurs, a new job instruction/order duly authorized and signed by the customer shall be required to carry it out.
                                         </p></i>
                                     </tr>
                                 </thead>
                             </table>
                             <!-- <button id="printInvoice" class="btn btn-success"><i class="fa fa-print"></i> Print</button> -->
                        </div>

                        <div class="row pb-2 p-5">
                            <div class="col-md-4 text-center">
                                <p>__________________________________</p>
                                <p>Technical Assistant/Date</p>
                            </div>

                            <div class="col-md-4 text-center">
                                <p>__________________________________</p>
                                <p>Account/Date</p>
                            </div>

                            @if($job->status !== "completed")
                                <div class="col-md-4 text-center">
                                    <p>__________________________________</p>
                                    <p>Customer Signature/Date</p>
                                </div>
                            @endif
                        </div>



            </div>
        </div>

    <style type="text/css">
        .table td, .table th {
            padding: 10px !important;
        }
    </style>




    {{-- Print CSS for Letter-Sized Page--}}
    <style>
        @media print {
            /* Force rows to stay in one line */
            .row {
                display: flex;
                flex-wrap: nowrap !important;
            }
            /* Ensure two-column layout */
            .col-md-6 {
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }
            /* Optionally, reduce padding to allow more horizontal space */
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                width: 100%;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            /* Hide buttons and other non-print elements as needed */
            .btn {
                display: none;
            }
        }
    </style>

    </body>
</html>

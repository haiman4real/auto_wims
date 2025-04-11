<x-app-layout>
    <x-slot name="title">
        Invoice - {{ $job->order_number }}
    </x-slot>

    <style type="text/css">
        /* Regular styling */
        .table td, .table th {
            padding: 10px !important;
        }

        /* Print styling */
        @media print {
            /* Reset body and general print settings */
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin: 0;
                padding: 0;
                font-size: 10pt;
            }

            /* Container sizing - allow natural flow rather than fixed dimensions */
            #invoice {
                width: 100%;
                height: auto;
                padding: 0.5in;
                margin: 0;
                background: white;
            }

            /* Reduce padding on all invoice sections */
            #invoice .p-5 {
                padding: 1rem !important;
            }

            #invoice .p-3, #invoice .p-4 {
                padding: 0.75rem !important;
            }

            #invoice .py-1, #invoice .py-3 {
                padding-top: 0.25rem !important;
                padding-bottom: 0.25rem !important;
            }

            #invoice .px-5 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            /* Heading sizes */
            h1 {
                font-size: 18pt;
            }

            h2 {
                font-size: 16pt;
            }

            h3 {
                font-size: 14pt;
            }

            /* Table styling */
            .table {
                border-collapse: collapse !important;
                width: 100%;
                font-size: 10pt;
            }

            .table td, .table th {
                border: 1px solid black !important;
                padding: 6px !important;
            }

            /* Control page breaks */
            .card-body {
                page-break-before: avoid;
            }

            /* Force page breaks at logical sections */
            .terms-section {
                page-break-before: always;
            }

            /* Prevent page breaks inside these elements */
            tr, .row {
                page-break-inside: avoid;
            }

            /* Signatures section */
            .signatures-section {
                page-break-before: auto;
                page-break-inside: avoid;
            }

            /* Hide buttons and navigation */
            .btn, nav, .modal {
                display: none !important;
            }

            /* Ensure background colors print */
            .bg-success {
                background-color: #28a745 !important;
                color: #000 !important;
            }
        }
    </style>

    <div id="invoice" class="container" style="padding: 20px;">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="row p-5">
                            <div class="col-md-6">
                                <img src="https://www.autopointe.com.ng/wp-content/uploads/2019/07/auto-main.png" alt="logo">
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

                        <div class="row pb-5 p-5">
                            <div class="col-md-6">
                                <p class="font-weight-bold mb-4">Customer Information</p>
                                <p class="mb-1">{{ $job->customer->cust_name ?? 'N/A' }}</p>
                                <p class="mb-1">{{ $job->customer->cust_address ?? 'N/A' }}, {{ $job->customer->cust_lga ?? 'N/A' }}</p>
                                <p class="mb-1">{{ $job->customer->cust_mobile ?? 'N/A' }}</p>
                            </div>

                            <div class="col-md-6 text-right d-flex flex-column align-items-end">
                                <p class="font-weight-bold mb-4">Vehicle Details</p>
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
                            <div class="col-md-12">
                                <table class="table table-hover table-striped table-responsive">
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

                        <div class="col terms-section" style="padding: 20px;">
                            <table class="table">
                                 <thead>
                                     <tr style="text-align-center">
                                     <p><strong>TERMS AND CONDITIONS</strong><br></p>
                                     </tr>
                                     <tr>
                                         <i><p>
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
                                             12. This Proforma invoice/estimate may be subject to any further additional job which couldn't be physically noticed during inspection. Where such occurs, a new job instruction/order duly authorized and signed by the customer shall be required to carry it out.
                                         </p></i>
                                     </tr>
                                 </thead>
                             </table>
                             <!-- <button id="printInvoice" class="btn btn-success"><i class="fa fa-print"></i> Print</button> -->
                        </div>

                        <div class="row pb-5 p-5 signatures-section">
                            <div class="col-md-6 text-center">
                                <p>__________________________________</p>
                                <p>Technical Assistant/Date</p>
                            </div>

                            <div class="col-md-6 text-center">
                                <p>__________________________________</p>
                                <p>Account/Date</p>
                            </div>
                        </div>

                        @if($job->status !== "completed")
                            <div class="row pb-5 p-5 signatures-section">
                                <div class="col text-center">
                                    <p>__________________________________</p>
                                    <p>Customer Signature/Date</p>
                                </div>
                            </div>

                            <div class="text-center my-4">
                                <button class="btn btn-primary" onclick="printInvoice()">
                                    <i class="fa fa-print"></i> Print Invoice
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="makePayment" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="h5">PAYMENT INFO</span>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><strong>Total Amount:</strong> ₦{{ number_format($estimatedJobs['grand_total'], 2) }}</p>
                    <button type="button" class="btn btn-primary" onclick="makePayment('{{ $job->order_number }}')">PAY</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printInvoice() {
            var invoice = document.getElementById('invoice').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = invoice;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</x-app-layout>
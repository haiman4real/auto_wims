<x-app-layout>
    <x-slot name="title">
        Estimate Generator
    </x-slot>

    <div class="container-fluid py-8">
        <div id="alert-container"></div> <!-- Dynamic Notifications Here -->

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <h6>Awaiting Estimate Generation</h6>
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="bookingsList">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job Description</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Advisor Notes</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job Date</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jobs as $index => $job)
                                        <tr>
                                            <td class="text-xs text-center">{{ $index + 1 }}</td>
                                            <td class="text-xs">{{ $job->customer->cust_name }} | {{ $job->vehicle->vec_make }} {{ $job->vehicle->vec_model }} - {{ $job->vehicle->vec_plate }}</td>
                                            <td class="text-xs">{{ $job->description ?? "N/A" }}</td>
                                            <td class="text-xs">{{ data_get(collect($job->workflow)->firstWhere('job_type', 'service_advisor_comments'), 'details.service_advise', 'N/A') }}</td>
                                            <td class="text-xs text-center">
                                                <span class="badge badge-sm bg-gradient-{{ $job->status == 'pending' ? 'danger' : ($job->status == 'completed' ? 'success' : 'info') }}">
                                                    {{ ucfirst($job->status) }}
                                                </span>
                                            </td>
                                            <td class="text-xs">{{ date("M j, Y h:i A", strtotime($job->created_at)) }}</td>
                                            <td class="text-xs">
                                                @if(Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))
                                                    @if($job->status !== 'estimate generated')
                                                        <a href="{{ route('service_booking.estimate.generate', ['job_id' => $job->id]) }}" class="btn btn-success">
                                                            <i class="fa fa-file-invoice-dollar"></i> Generate Estimate
                                                        </a>

                                                        {{-- <a href="javascript:void(0);" class="text-success generate-estimate" data-job="{{ json_encode($job) }}">
                                                            <i class="fa fa-file-invoice-dollar"></i> Generate Estimate
                                                        </a> --}}
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="text-center">No jobs available.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estimate Generator Modal -->
    <div class="modal fade" id="estimateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Generate Estimate</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="estimateForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="job_id" name="job_id">

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Customer:</strong> <span id="customer"></span></p>
                                <p><strong>Phone:</strong> <span id="customer_phone"></span></p>
                                <p><strong>Email:</strong> <span id="customer_email"></span></p>
                                <p><strong>Customer Type:</strong> <span id="customer_type"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Vehicle:</strong> <span id="vehicle"></span></p>
                                <p><strong>Plate:</strong> <span id="vehicle_plate"></span></p>
                                <p><strong>VIN:</strong> <span id="vehicle_vin"></span></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Service or Spare Parts</label>
                            <select class="form-control" id="item_type" name="item_type">
                                <option value="service">Service</option>
                                <option value="spare_parts">Spare Parts</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <select class="form-control" id="item_desc" name="item_desc"></select>
                        </div>

                        <div class="form-group">
                            <label>Unit Price</label>
                            <input type="text" class="form-control" id="unit_price" name="unit_price" readonly>
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1">
                        </div>

                        <div class="form-group">
                            <label>Total Price</label>
                            <input type="text" class="form-control" id="total_price" name="total_price" readonly>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Generate Estimate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        $(document).ready(function() {
            $('#bookingsList').DataTable();

            let markupRates = { "standard": 1.1, "premium": 1.2, "vip": 1.3 };

            $(document).on('click', '.generate-estimate', function() {
                let job = $(this).data('job');

                $('#job_id').val(job.id);
                $('#customer').text(job.customer.cust_name);
                $('#customer_phone').text(job.customer.cust_mobile);
                $('#customer_email').text(job.customer.cust_email);
                $('#customer_type').text(job.customer.cust_type);
                $('#vehicle').text(`${job.vehicle.vec_make} ${job.vehicle.vec_model}`);
                $('#vehicle_plate').text(job.vehicle.vec_plate);
                $('#vehicle_vin').text(job.vehicle.vec_vin);

                $('#estimateModal').modal('show');
            });

            $('#item_type').change(function() {
                let type = $(this).val();
                $('#item_desc').empty();

                let items = type === 'service' ? ['Oil Change', 'Brake Inspection', 'Wheel Alignment'] : ['Brake Pads', 'Air Filter', 'Battery'];
                items.forEach(item => $('#item_desc').append(`<option value="${item}" data-price="${Math.floor(Math.random() * 100) + 50}">${item}</option>`));

                updatePrice();
            });

            $('#item_desc, #quantity').change(updatePrice);

            function updatePrice() {
                let unitPrice = $('#item_desc option:selected').data('price') || 0;
                let quantity = $('#quantity').val() || 1;
                let custType = $('#customer_type').text().trim().toLowerCase();
                let markup = markupRates[custType] || 1;

                let totalPrice = unitPrice * quantity * markup;
                $('#unit_price').val(unitPrice.toFixed(2));
                $('#total_price').val(totalPrice.toFixed(2));
            }

            $('#estimateForm').submit(function(e) {
                e.preventDefault();
                alert('Estimate generated successfully!');
                $('#estimateModal').modal('hide');
            });
        });
    </script>
</x-app-layout>

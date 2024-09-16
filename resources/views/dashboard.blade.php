<x-app-layout>
    <x-slot name="title">
        Index
    </x-slot>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Customers</p>
                    <h5 class="font-weight-bolder">
                      {{ $totalCustomerCount ?? "0" }}
                    </h5>
                    @if($customerGrowth > 0)
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">+{{ $customerGrowth }}%</span>
                            since last week
                        </p>
                    @elseif($customerGrowth <= 0)
                        <p class="mb-0">
                            <span class="text-danger text-sm font-weight-bolder">{{ $customerGrowth }}%</span>
                            since last week
                        </p>
                    @else
                        <p class="mb-0">
                            <span class="text-muted text-sm font-weight-bolder">No change</span>
                            since last week
                        </p>
                    @endif
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Vehicles</p>
                    <h5 class="font-weight-bolder">
                      {{ $totalVehicleCount ?? "0" }}
                    </h5>
                    @if($vehicleGrowth > 0)
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">+{{ $vehicleGrowth }}%</span>
                            since last week
                        </p>
                    @elseif($vehicleGrowth <= 0)
                        <p class="mb-0">
                            <span class="text-danger text-sm font-weight-bolder">{{ $vehicleGrowth }}%</span>
                            since last week
                        </p>
                    @else
                        <p class="mb-0">
                            <span class="text-muted text-sm font-weight-bolder">No change</span>
                            since last week
                        </p>
                    @endif
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Jobs</p>
                    <h5 class="font-weight-bolder">
                        {{ $totalJobCount ?? "0" }}
                    </h5>
                    @if($jobGrowth > 0)
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">+{{ $jobGrowth }}%</span>
                            since last week
                        </p>
                    @elseif($jobGrowth <= 0)
                        <p class="mb-0">
                            <span class="text-danger text-sm font-weight-bolder">{{ $jobGrowth }}%</span>
                            since last week
                        </p>
                    @else
                        <p class="mb-0">
                            <span class="text-muted text-sm font-weight-bolder">No change</span>
                            since last week
                        </p>
                    @endif
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Invoices</p>
                    <h5 class="font-weight-bolder">
                        {{ $totalInvoiceCount ?? "0" }}
                    </h5>
                    @if($invoiceGrowth > 0)
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">+{{ $invoiceGrowth }}%</span>
                            since last week
                        </p>
                    @elseif($invoiceGrowth <= 0)
                        <p class="mb-0">
                            <span class="text-danger text-sm font-weight-bolder">{{ $invoiceGrowth }}%</span>
                            since last week
                        </p>
                    @else
                        <p class="mb-0">
                            <span class="text-muted text-sm font-weight-bolder">No change</span>
                            since last week
                        </p>
                    @endif
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Transaction overview</h6>
            </div>
            <div class="card-body p-3">
                @include('dashboards.partials.dashboard-chart')
              <div class="chart">
                <canvas id="dashboard-chart-line" class="chart-canvas" height="350"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                  <h6 class="text-capitalize">Customers overview</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="customerLgaPieChart" class="chart-canvas" height="300"></canvas>
                      </div>
                </div>
              </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card ">
            <div class="card-header pb-0">
                <h6>Job Status
                    {{-- <a href="#schedule-new-tracking"><button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add new Item --}}
                    </button></a>
                </h6>
              </div>
              <div class="card-body pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Station</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Description</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Advisor Note</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Payment Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookingJobs as $index => $bookingJob)
                                <tr>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{$index + 1}}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $bookingJob['station'] ?? "" }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{  $bookingJob['cust_name'] ?? "" }} | {{  $bookingJob['vec_make'] ?? "" }} {{  $bookingJob['vec_model'] ?? "" }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs text-secondary mb-0" style="word-wrap: break-word; white-space: normal;">
                                                    {{ $bookingJob['bookings_desc'] ?? "" }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0" style="word-wrap: break-word; white-space: normal;">{{ $bookingJob['advisor_note'] ?? "" }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                @if ($bookingJob['bookings_status'] !== 'completed')
                                                    <span class="badge badge-sm bg-gradient-info">
                                                        <p class="text-xs mb-0">{{ $bookingJob['bookings_status'] ?? ""}}</p>
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <p class="text-xs mb-0">{{ $bookingJob['bookings_status'] ?? ""}}</p>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                @if ($bookingJob['payment_status'] !== 'completed')
                                                    <span class="badge badge-sm bg-gradient-danger">
                                                        <p class="text-xs mb-0">{{ $bookingJob['payment_status'] ?? ""}}</p>
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <p class="text-xs mb-0">{{ $bookingJob['payment_status'] ?? ""}}</p>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-xs mb-0">{{ $bookingJob['bookings_time'] ?? "" }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="align-middle">
                                        {{-- Action buttons go here --}}
                                    </td>
                                </tr>
                            @empty
                                <!-- Handle empty state here -->
                            @endforelse
                        </tbody>
                    </table>
                </div>
              </div>
          </div>
        </div>
      </div>

    </div>

    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true,
                pageLength: 25,
                dom: 'Bfrtip',
                buttons: [
                    'csvHtml5', 'excelHtml5', 'pdfHtml5'
                ]
            });
        });
    </script>

</x-app-layout>

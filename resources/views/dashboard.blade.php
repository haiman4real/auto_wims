<x-app-layout>
    <x-slot name="title">
        Index
    </x-slot>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2">
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="form-group">
                        <label style="color: white" for="filter">Filter by:</label>
                        <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All</option>
                            <option value="day" {{ request('filter') == 'day' ? 'selected' : '' }}>Day</option>
                            <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>Week</option>
                            <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>Month</option>
                            <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>Year</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Customers</p>
                    <h5 class="font-weight-bolder">
                      {{ $customerCount ?? "0" }}
                    </h5>
                    @if($customerGrowth > 0)
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">+{{ $customerGrowth }}%</span>
                            since last {{ request('filter') }}
                        </p>
                    @elseif($customerGrowth <= 0)
                        <p class="mb-0">
                            <span class="text-danger text-sm font-weight-bolder">{{ $customerGrowth }}%</span>
                            since last {{ request('filter') }}
                        </p>
                    @else
                        <p class="mb-0">
                            <span class="text-muted text-sm font-weight-bolder">No change</span>
                            since last {{ request('filter') }}
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
                      {{ $vehicleCount ?? "0" }}
                    </h5>
                    @if($vehicleGrowth > 0)
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">+{{ $vehicleGrowth }}%</span>
                            since last {{ request('filter') }}
                        </p>
                    @elseif($vehicleGrowth <= 0)
                        <p class="mb-0">
                            <span class="text-danger text-sm font-weight-bolder">{{ $vehicleGrowth }}%</span>
                            since last {{ request('filter') }}
                        </p>
                    @else
                        <p class="mb-0">
                            <span class="text-muted text-sm font-weight-bolder">No change</span>
                            since last {{ request('filter') }}
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
                        {{ $jobCount ?? "0" }}
                    </h5>
                    @if($jobGrowth > 0)
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">+{{ $jobGrowth }}%</span>
                            since last {{ request('filter') }}
                        </p>
                    @elseif($jobGrowth <= 0)
                        <p class="mb-0">
                            <span class="text-danger text-sm font-weight-bolder">{{ $jobGrowth }}%</span>
                            since last {{ request('filter') }}
                        </p>
                    @else
                        <p class="mb-0">
                            <span class="text-muted text-sm font-weight-bolder">No change</span>
                            since last {{ request('filter') }}
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
                        {{ $invoiceCount ?? "0" }}
                    </h5>
                    @if($invoiceGrowth > 0)
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">+{{ $invoiceGrowth }}%</span>
                            since last {{ request('filter') }}
                        </p>
                    @elseif($invoiceGrowth <= 0)
                        <p class="mb-0">
                            <span class="text-danger text-sm font-weight-bolder">{{ $invoiceGrowth }}%</span>
                            since last {{ request('filter') }}
                        </p>
                    @else
                        <p class="mb-0">
                            <span class="text-muted text-sm font-weight-bolder">No change</span>
                            since last {{ request('filter') }}
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
        {{-- <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
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
                                        since last {{ request('filter') }}
                                    </p>
                                @elseif($customerGrowth <= 0)
                                    <p class="mb-0">
                                        <span class="text-danger text-sm font-weight-bolder">{{ $customerGrowth }}%</span>
                                        since last {{ request('filter') }}
                                    </p>
                                @else
                                    <p class="mb-0">
                                        <span class="text-muted text-sm font-weight-bolder">No change</span>
                                        since last {{ request('filter') }}
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
                                        since last {{ request('filter') }}
                                    </p>
                                @elseif($vehicleGrowth <= 0)
                                    <p class="mb-0">
                                        <span class="text-danger text-sm font-weight-bolder">{{ $vehicleGrowth }}%</span>
                                        since last {{ request('filter') }}
                                    </p>
                                @else
                                    <p class="mb-0">
                                        <span class="text-muted text-sm font-weight-bolder">No change</span>
                                        since last {{ request('filter') }}
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
        </div> --}}
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
                <h6>Jobs Status
                    {{-- <a href="#schedule-new-tracking"><button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add new Item --}}
                    </button></a>
                </h6>
              </div>
              <div class="card-body pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="awaitingTechnicalReview">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle Details & Report</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job Description</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Advisor Notes</th>
                                <th class="text-uppercase text-secondary align-middle text-center text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                <th class="text-uppercase text-secondary  align-middle text-center text-xxs font-weight-bolder opacity-7 ps-2">Payment Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job Date</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jobs as $index => $job)
                                <tr>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{$index + 1}}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $job->customer->cust_name  }} | {{ $job->vehicle->vec_make }} {{ $job->vehicle->vec_model }} - {{ $job->vehicle->vec_plate }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-0 text-sm">{{  $job->description ?? " " }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-0 text-sm">
                                                    {{ data_get(collect($job->workflow)->firstWhere('job_type', 'service_advisor_comments'), 'details.service_advise', ' ') }}
                                                    <br>
                                                    <small>
                                                        Additional Comments: {{ data_get(collect($job->workflow)->firstWhere('job_type', 'service_advisor_comments'), 'details.comments', 'N/A') }}
                                                    </small>
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="d-flex flex-column align-items-center">
                                                @if ($job->status == 'pending')
                                                    <span class="badge badge-sm bg-gradient-danger">
                                                        <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                    </span>
                                                @elseif ($job->status == 'completed')
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-info">
                                                        <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="d-flex flex-column align-items-center">
                                                @if ($job->status == 'pending')
                                                    <span class="badge badge-sm bg-gradient-danger">
                                                        <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                    </span>
                                                @elseif ($job->status == 'completed')
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-info">
                                                        <h6 class="mb-0 text-sm">{{ $job->status ?? " " }}</h6>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div class="d-flex flex-column">
                                                <p class="text-xs text-secondary mb-0">{{date("M j, Y h:i A", strtotime($job->created_at ))}}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="align-middle">
                                        @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin', 'serviceadvisor', 'customerservice']))

                                            @if($job->status == 'estimate generated' || $job->status == 'completed')
                                                <a href="{{ route('service_booking.estimate.invoice', ['job_id' => $job->id]) }}" class="text-primary font-weight-bold text-xs assign-btn btn btn-sm btn-info" target="_blank">
                                                        <i class="fa fa-send" aria-hidden="true"></i>&nbsp;Print Job Card
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                        </table>
                </div>
              </div>
          </div>
        </div>
      </div>
      {{-- archived jobs --}}
      <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card ">
            <div class="card-header pb-0">
                <h6>Archived Jobs
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

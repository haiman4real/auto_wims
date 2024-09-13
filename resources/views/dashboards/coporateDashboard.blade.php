<x-app-layout>
    <x-slot name="title">
        {{ __('Dashboard') }}
    </x-slot>
    <div class="container-fluid py-4">
        {{-- tile headers --}}
        <div class="row">
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
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Sent Repair Jobs</p>
                        <h5 class="font-weight-bolder">
                            {{ $repairJobs ?? "0" }}
                        </h5>
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
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Awaiting Repair Approval</p>
                        <h5 class="font-weight-bolder">
                            {{ $awaitingApproval ?? "0" }}
                        </h5>
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
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Sent Tracker Jobs</p>
                        <h5 class="font-weight-bolder">
                          {{ $trackerAppt ?? "0" }}
                        </h5>
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
        {{-- chart section --}}
        <div class="row mt-4">
            <div class="col-lg-8 mb-lg-0 mb-4">
              <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                  <h6 class="text-capitalize">Tracker Sales Overview</h6>
                </div>
                <div class="card-body p-3">
                  <div class="chart">
                    <canvas id="tracker-chart-line" class="chart-canvas" height="400"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
                <!-- Recent Activity -->
            <div class="card">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Recent Activity</h6>
                  </div>
                <div class="card-body">

                  <div class="activity">
                    @foreach($recentActivities as $activity)
                        @php
                            $scheduledTime = \Carbon\Carbon::parse($activity->created_at);
                            $now = \Carbon\Carbon::now();
                            $timeDiff = $scheduledTime->diffForHumans($now, true); // Get time difference in a human-readable format
                            $badgeColor = 'text-success'; // You can customize this based on your logic
                            $customMessage = 'Scheduled for ';
                        @endphp

                        <div class="activity-item d-flex">
                            <div class="activity-label">{{ $timeDiff }}</div>
                            <i class="bi bi-circle-fill activity-badge {{ $badgeColor }} align-self-start"></i>
                            <div class="activity-content">
                                {{ $customMessage }} {{ $activity->fullname }} on {{ $activity->appointment_date }} at {{ $activity->appointment_time }} <!-- This should be the tracking information text -->
                            </div>
                        </div><!-- End activity item-->
                    @endforeach
                  </div>

                </div>
              </div>
              <!-- End Recent Activity -->
              {{-- <div class="card card-carousel overflow-hidden h-100 p-0">
                <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                  <div class="carousel-inner border-radius-lg h-100">
                    <div class="carousel-item h-100 active" style="background-image: url('./assets/img/carousel-1.jpg'); background-size: cover;">
                      <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                        <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                          <i class="ni ni-camera-compact text-dark opacity-10"></i>
                        </div>
                        <h5 class="text-white mb-1">Get started with Argon</h5>
                        <p>There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>
                      </div>
                    </div>
                    <div class="carousel-item h-100" style="background-image: url('./assets/img/carousel-2.jpg'); background-size: cover;">
                      <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                        <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                          <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                        </div>
                        <h5 class="text-white mb-1">Faster way to create web pages</h5>
                        <p>That’s my skill. I’m not really specifically talented at anything except for the ability to learn.</p>
                      </div>
                    </div>
                    <div class="carousel-item h-100" style="background-image: url('./assets/img/carousel-3.jpg'); background-size: cover;">
                      <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                        <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                          <i class="ni ni-trophy text-dark opacity-10"></i>
                        </div>
                        <h5 class="text-white mb-1">Share with us your design tips!</h5>
                        <p>Don’t be afraid to be wrong because you can’t learn anything from a compliment.</p>
                      </div>
                    </div>
                  </div>
                  <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                </div>
              </div> --}}
            </div>
        </div>
    </div>
    {{-- @include('dashboards.partials.tracker-chart') --}}
    <script>
        // Fetch the transaction data from the backend
        fetch('/get-chart-data')
            .then(response => response.json())
            .then(data => {
                var ctx1 = document.getElementById("tracker-chart-line").getContext("2d");

                var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

                gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
                gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
                gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');

                new Chart(ctx1, {
                    type: "line",
                    data: {
                        labels: data.labels, // Labels for the X-axis (Months)
                        datasets: [{
                            label: "Transactions",
                            tension: 0.4,
                            borderWidth: 0,
                            pointRadius: 0,
                            borderColor: "#5e72e4",
                            backgroundColor: gradientStroke1,
                            borderWidth: 3,
                            fill: true,
                            data: data.months, // Transaction counts per month
                            maxBarThickness: 6
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        scales: {
                            y: {
                                grid: {
                                    drawBorder: false,
                                    display: true,
                                    drawOnChartArea: true,
                                    drawTicks: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    display: true,
                                    padding: 10,
                                    color: '#fbfbfb',
                                    font: {
                                        size: 11,
                                        family: "Open Sans",
                                        style: 'normal',
                                        lineHeight: 2
                                    },
                                }
                            },
                            x: {
                                grid: {
                                    drawBorder: false,
                                    display: false,
                                    drawOnChartArea: false,
                                    drawTicks: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    display: true,
                                    color: '#ccc',
                                    padding: 20,
                                    font: {
                                        size: 11,
                                        family: "Open Sans",
                                        style: 'normal',
                                        lineHeight: 2
                                    },
                                }
                            },
                        },
                    },
                });
            });
    </script>

</x-app-layout>

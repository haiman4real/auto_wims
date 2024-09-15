<x-app-layout>
    <x-slot name="title">
        Users
    </x-slot>
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
          <div class="row gx-4">
            <div class="col-auto">
              <div class="avatar avatar-xl position-relative">
                <i class="ni ni-user-run text-warning text-sm opacity-10"></i>
              </div>
            </div>
            <div class="col-auto my-auto">
              <div class="h-100">
                <h5 class="mb-1">
                    User List
                </h5>
                <p class="mb-0 font-weight-bold text-sm">
                    @if(session("status"))
                        <div class="alert alert-success" role="alert">
                            {{session("message")}}
                        </div>
                    @elseif(session("error"))
                        <div class="alert alert-danger" role="alert">
                            {{session("message")}}
                        </div>
                    @endif


                </p>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
              <div class="nav-wrapper position-relative end-0">

              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
          <div class="col-12">
            <div class="card mb-4">
              <div class="card-header pb-0">
                <h6>User List
                    {{-- @can('add users') --}}
                        <a href="{{ route('users.add') }}"><button class="btn btn-primary btn-sm d-none d-sm-inline-block" type="button" style="float: right; margin-left: -50%;"> + Add New User
                        </button></a>
                    {{-- @endcan --}}
                </h6>
              </div>
              @php
                $serial = 1;
              @endphp
              <div class="card-body pt-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0 ml-3">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phone Number</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Station</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
                        <th class="text-secondary opacity-7"></th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $serial++ }}
                                    </p>
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{$user->user_name}}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{$user->email}}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{$user->user_phone}}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $user->user_role }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{$user->user_station}}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if ($user->user_status == 'active')
                                        <span class="badge badge-sm bg-gradient-success">{{$user->user_status}}</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-danger">{{$user->user_status}}</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{date("M j, Y h:i A", strtotime($user->created_at ))}}</span>
                                </td>
                                <td class="">
                                    @if (Auth::check() && in_array(strtolower(trim(Auth::user()->user_role)), ['superadmin', 'masteradmin']))
                                        <a href="#" class="text-secondary font-weight-bold text-xs complete-btn" data-id="{{ $user->id }}" data-toggle="modal" data-target="#completeJobModal" data-original-title="Delete Customer">
                                            <i class="fa fa-ban" style="color: green; font-size:14px;" aria-hidden="true"></i>delete
                                        </a>
                                        &nbsp;
                                        <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs edit-btn" data-id="{{ $user->id }}" data-toggle="modal" data-target="#editCustomerModal" data-original-title="Edit Customer">
                                            <i class="fa fa-edit" style="color: rgb(255, 179, 0); font-size:14px;" aria-hidden="true"></i>
                                        </a>
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

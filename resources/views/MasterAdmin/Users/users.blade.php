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
                                        {{-- <a href="#" class="text-secondary font-weight-bold text-xs complete-btn" data-id="{{ $user->id }}" data-toggle="modal" data-target="#completeJobModal" data-original-title="Delete Customer">
                                            <i class="fa fa-ban" style="color: green; font-size:14px;" aria-hidden="true"></i>delete
                                        </a>
                                        &nbsp;
                                        <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs edit-btn" data-id="{{ $user->id }}" data-toggle="modal" data-target="#editCustomerModal" data-original-title="Edit Customer">
                                            <i class="fa fa-edit" style="color: rgb(255, 179, 0); font-size:14px;" aria-hidden="true"></i>
                                        </a> --}}

                                        @if($user->user_status == 'active')
                                            <a href="{{route('user.disable', $user)}}" class="btn btn-sm btn-warning">
                                                <i class="fa fa-ban" style="color: white; font-size:14px;" aria-hidden="true"></i>
                                            </a>
                                        @elseif ($user->user_status == 'disabled')
                                            <a href="{{route('user.enable', $user)}}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-check" style="color: white; font-size:14px;" aria-hidden="true"></i>
                                            </a>
                                        @else
                                            <a href="{{route('user.enable', $user)}}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-check" style="color: white; font-size:14px;" aria-hidden="true"></i>
                                                Unblock
                                            </a>
                                        @endif

                                        {{-- <button class="btn btn-sm btn-warning editUser" data-id="{{ $user->id }}" data-toggle="modal" data-target="#editUserModal">
                                            <i class="fa fa-edit"></i> Edit
                                        </button> --}}
                                        &nbsp;

                                        <a href="javascript:void(0);" class="btn btn-sm btn-info text-secondary font-weight-bold text-xs edit-btn" data-id="{{ $user->id }}" data-toggle="modal" data-target="#editUserModal" data-original-title="Edit User">
                                            <i class="fa fa-edit" style="color: white; font-size:14px;" aria-hidden="true"></i>
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
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        @csrf
                        <input type="hidden" id="edit_user_id" name="user_id">

                        <!-- Name -->
                        <div>
                            <label for="edit_user_name" class="form-control-label">Name</label>
                            <input id="edit_user_name" class="form-control" type="text" name="user_name" required>
                        </div>

                        <!-- Phone Number -->
                        <div class="mt-3">
                            <label for="edit_user_phone" class="form-control-label">Phone Number</label>
                            <input id="edit_user_phone" class="form-control" type="text" name="user_phone" required minlength="11" maxlength="11">
                        </div>

                        <!-- Role -->
                        <div class="mt-3">
                            <label for="edit_user_role" class="form-control-label">Role</label>
                            <select id="edit_user_role" class="form-select" name="user_role" required>
                                <option value="SuperAdmin">Super Admin</option>
                                <option value="AdminOne">Admin One</option>
                                <option value="AdminTwo">Admin Two</option>
                                <option value="AdminThree">Admin Three</option>
                                <option value="CustomerService">Customer Service</option>
                                <option value="FrontDesk">Front Desk</option>
                                <option value="Technician">Technician</option>
                                <option value="ServiceAdvisor">Service Advisor</option>
                                <option value="JobController">Job Controller</option>
                                <option value="AccountsAdmin">Accounts Admin</option>
                                <option value="BusinessView">Business View</option>
                                <option value="GuestUser">Guest User</option>
                                <option value="CoporateUser">Corporate User</option>
                            </select>
                        </div>

                        <!-- Station -->
                        <div class="mt-3">
                            <label for="edit_user_station" class="form-control-label">Station</label>
                            <select id="edit_user_station" class="form-select" name="user_station" required>
                                <option value="HQ">HQ</option>
                                <option value="Ojodu">Ojodu</option>
                                <option value="Abuja">Abuja</option>
                                <option value="Asaba">Asaba</option>
                            </select>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
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

            // Load user data into modal
            $(document).on('click', '.edit-btn', function () {
            // $('.editUser').on('click', function() {
                var userId = $(this).data('id');

                $.ajax({
                    url: "{{ route('user.edit', 'userId') }}",
                    type: "GET",
                    data: { id: userId },
                    success: function(response) {
                        $('#edit_user_id').val(response.id);
                        $('#edit_user_name').val(response.user_name);
                        $('#edit_user_phone').val(response.user_phone);
                        $('#edit_user_role').val(response.user_role);
                        $('#edit_user_station').val(response.user_station);

                        $('#editUserModal').modal('show');
                    },
                    error: function(xhr) {
                        alert("Error fetching user details!");
                    }
                });
            });

            // Submit updated user data
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();

                var userId = $('#edit_user_id').val(); // Get user ID
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "/ma/users/" + userId, // Dynamic route
                    type: "PATCH",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for security
                    },
                    success: function(response) {
                        alert("User updated successfully!");
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Error updating user! Check console for details.");
                    }
                });
            });
        });
    </script>

</x-app-layout>

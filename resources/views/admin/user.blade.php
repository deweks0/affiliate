@extends('layouts.app', ['activePage' => 'admin', 'titlePage' => __('User Management')])

@section('content')
<div id="preloaders" class="preloader"></div>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Admin</h4>
                        <p class="card-category"> Admin management tabels</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 col-sm-4">
                                @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{session('status')}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif
                                @if (session('statusAdmin'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{session('statusAdmin')}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif
                            </div>
                            <div class="col-12 text-right">
                                <a href="" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#createUserModal">Add new Admin</a>
                            </div>
                        </div>
                        <div class="">
                            <table class="table" id="table_admin">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Create Date</th>
                                        <th class="text-right no-sort">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->phone}}</td>
                                        <td>
                                            {{$user->role == '1' ? ' Admin' : 'Reseller'}}
                                            <span
                                                class="ml-2 badge badge-{{$user->register_status == '1' ? 'success' : 'warning'}}">{{$user->register_status == '1' ? 'Activated' : 'Not Activated'}}</span>
                                        </td>
                                        <td>{{  Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}}</td>
                                        <td class="td-actions text-right">
                                            <a rel="tooltip" class="btn btn-danger btn-fab btn-fab-mini btn-round"
                                                href="" data-placement="bottom" title="Delete" data-toggle="modal"
                                                data-target="#deleteModal{{$user->id}}">
                                                <i class="material-icons">delete</i>
                                                <div class="ripple-container"></div>
                                            </a>
                                            <a rel="tooltip" class="btn btn-primary btn-fab btn-fab-mini btn-round"
                                                href="" data-placement="bottom" title="Edit" data-toggle="modal"
                                                data-target="#editUserModal{{$user->id}}">
                                                <i class="material-icons">edit</i>
                                                <div class="ripple-container"></div>
                                            </a>
                                        </td>
                                    </tr>



                                    {{-- modal delete --}}
                                    <div class="modal fade" id="deleteModal{{$user->id}}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="/admin/user/{{$user->id}}" method="POST">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete user</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="h5">Are you sure want to permanently remove this user?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-danger">Yes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- modal edit --}}
                                    <div class="modal fade" id="editUserModal{{$user->id}}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="/admin/user/{{ $user->id }}" method="POST">
                                                    @method('patch')
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Admin</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div
                                                            class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="material-icons">face</i>
                                                                    </span>
                                                                </div>
                                                                <input type="text" name="name" class="form-control"
                                                                    placeholder="{{ __('Name...') }}"
                                                                    value="{{$user->name}}" required>
                                                            </div>
                                                            @if ($errors->has('name'))
                                                            <div id="name-error" class="error text-danger pl-3"
                                                                for="name" style="display: block;">
                                                                <strong>{{ $errors->first('name') }}</strong>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div
                                                            class="bmd-form-group{{ $errors->has('phone') ? ' has-danger' : '' }} mt-3">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="material-icons">phone</i>
                                                                    </span>
                                                                </div>
                                                                <input type="number" name="phone" class="form-control"
                                                                    placeholder="{{ __('081231923479...') }}"
                                                                    value="{{$user->phone}}" required>
                                                            </div>
                                                            @if ($errors->has('phone'))
                                                            <div id="phone-error" class="error text-danger pl-3"
                                                                for="phone" style="display: block;">
                                                                <strong>{{ $errors->first('phone') }}</strong>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <input type="hidden" name="role" value="1">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- {{ $users->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal create --}}
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/admin/user" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add new Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">face</i>
                                </span>
                            </div>
                            <input type="text" name="name" class="form-control" placeholder="{{ __('Name...') }}"
                                value="{{ old('name') }}" required>
                        </div>
                        @if ($errors->has('name'))
                        <div id="name-error" class="error text-danger pl-3" for="name" style="display: block;">
                            <strong>{{ $errors->first('name') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }} mt-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">email</i>
                                </span>
                            </div>
                            <input type="email" name="email" class="form-control" placeholder="{{ __('Email...') }}"
                                value="{{ old('email') }}" required>
                        </div>
                        @if ($errors->has('email'))
                        <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('phone') ? ' has-danger' : '' }} mt-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">phone</i>
                                </span>
                            </div>
                            <input type="number" name="phone" class="form-control" placeholder="{{ __('08123456789') }}"
                                value="{{ old('phone') }}" required>
                        </div>
                        @if ($errors->has('phone'))
                        <div id="phone-error" class="error text-danger pl-3" for="phone" style="display: block;">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">lock_outline</i>
                                </span>
                            </div>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="{{ __('Password') }}" required>
                        </div>
                        @if ($errors->has('password'))
                        <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }} mt-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">lock_outline</i>
                                </span>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="{{ __('Confirm Password...') }}" required>
                        </div>
                        @if ($errors->has('password_confirmation'))
                        <div id="password_confirmation-error" class="error text-danger pl-3" for="password_confirmation"
                            style="display: block;">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </div>
                        @endif
                    </div>
                    <input type="hidden" name="role" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Regist</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#table_admin').DataTable({
            columnDefs: [{ 'targets': 4, type: 'date-euro' }],
            "order": [
                [4, "desc"]
            ],
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': ['no-sort']
            }]
        });
        $("#preloaders").fadeOut(1000);
        
        $('#edit-user').tooltip();

        $(".modal").on("hidden.bs.modal", function () {
            $("#createUserModal").html("");
        });
        $('.custom-select').selectpicker();
    });

</script>
@endpush

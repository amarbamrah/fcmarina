@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Stadiums</a></li>
            <li class="breadcrumb-item"><a href="#">Manage Users</a></li>

        </ol>
    </nav>

    <div class="row">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/stadiums/manage-users" method="post">
                        @csrf

                        <input type="hidden" name="stadium_id" value="{{$stadium->id}}">

                        <div class="form-group mb-3">
                            <label for="">
                                Name
                            </label>

                            <input type="text" name="name" required class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">
                                Email
                            </label>

                            <input type="text" name="email" required class="form-control">
                        </div>


                        

                        <button type="submit" class="btn btn-primary">ADD PHONE NO</button>
                    </form>
                </div>
            </div>
        </div>



        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>

                                    <th>Status</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $i=>$user)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$user->name}}</td>

                                    <td>{{$user->email}}</td>
                                    <td>{{$user->password}}</td>


                                    <td>
                                    <span class="badge {{$user->status==1?'bg-primary':'bg-danger'}}">  
                                    {{$user->status==1?'Enabled':'Disabled'}}
</span>
                                    </td>

                                    <td>
                                        <form action="/admin/stadiums/change-user-status" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{$user->id}}">
                                            <input type="hidden" name="status" value="{{$user->status==1?0:1}}">

                                            <button type="submit" class="btn p-0">
                                             @if($user->status==1)   
                                            <i data-feather="x" class="text-danger"></i>
                                            @else 
                                            <i data-feather="check" class="text-primary"></i>

                                            @endif
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
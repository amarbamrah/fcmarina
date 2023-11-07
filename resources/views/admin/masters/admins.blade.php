@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Admin Logins</a></li>
            <li class="breadcrumb-item"><a href="#">Manage Admin Logins</a></li>

        </ol>
    </nav>

    <div class="row">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/admin-logins" method="post">
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


                        

                        <button type="submit" class="btn btn-primary">ADD USER</button>
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
                                    <td>{{$user->fpassword}}</td>


                                    <td>
                                    <span class="badge {{$user->status==1?'bg-primary':'bg-danger'}}">  
                                    {{$user->status==1?'Enabled':'Disabled'}}
</span>
                                    </td>

                                    <td>
                                       
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
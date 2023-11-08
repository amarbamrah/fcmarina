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

        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/stadiums/manage-users" method="post">
                        @csrf
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


                        <div class="form-group mb-3">
                            <label for="">
                                Stadiums
                            </label>

                            <select name="stadiums[]" class="form-select multiplebox" multiple id="">
                                @foreach($stadiums as $stadium)
                                   <option value="{{$stadium->id}}">{{$stadium->name}}</option>
                                @endforeach 
                            </select>
                        </div>


                        




                        <button type="submit" class="btn btn-primary">ADD USER</button>
                    </form>
                </div>
            </div>
        </div>



        <div class="col-md-8 grid-margin stretch-card">
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
                                        <a href="/admin/admin-stadiums?uid={{$user->id}}">Manage Stadiums</a>
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

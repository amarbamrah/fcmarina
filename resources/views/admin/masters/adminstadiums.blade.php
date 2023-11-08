@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Admin Logins</a></li>
            <li class="breadcrumb-item"><a href="#">Manage Admin Stadiums</a></li>

        </ol>
    </nav>

    <div class="row">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/admin-logins" method="post">
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
                                    <th>Stadium</th>
                                    <th>Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stadiums as $i=>$stadium)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$stadium->name}}</td>

                                    
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

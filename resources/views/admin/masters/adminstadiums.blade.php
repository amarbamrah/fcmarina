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
                                Stadiums
                            </label>

                            <select name="stadium" class="form-select" id="">
                                @foreach($stadiums as $stadium)
                                   <option value="{{$stadium->id}}">{{$stadium->name}}</option>
                                @endforeach 
                            </select>
                        </div>

                       


                        <button type="submit" class="btn btn-primary">ASSIGN STADIUM</button>
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
                                    <td>
                                        <a href="" class="btn btn-danger">Remove</a>
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

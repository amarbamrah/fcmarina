@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Stadiums</a></li>
            <li class="breadcrumb-item"><a href="#">Manage Phone Numbers</a></li>

        </ol>
    </nav>

    <div class="row">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/stadiums/phno" method="post">
                        @csrf

                        <input type="hidden" name="stadium_id" value="{{$stadium->id}}">
                        <div class="form-group mb-3">
                            <label for="">
                                Phone No
                            </label>

                            <input type="text" name="phone" class="form-control">
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
                                    <th>Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sp as $i=>$s)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$s->phone}}</td>
                                    <td>
                                        <form action="/admin/stadiums/delete-phno" method="POST">
                                            @csrf
                                            <input type="hidden" name="phno" value="{{$s->id}}">
                                            <button type="submit" class="btn p-0">
                                                <i data-feather="x" class="text-danger"></i>
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
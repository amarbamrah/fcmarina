@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Masters</a></li>
            <li class="breadcrumb-item"><a href="#">Cancel Reasons</a></li>

        </ol>
    </nav>

    <div class="row">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/cancel-reasons" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="">
                                Title
                            </label>

                            <input type="text" name="title" class="form-control" placeholder="Cancel Reason">
                        </div>

                        <button type="submit" class="btn btn-primary">ADD CANCEL REASON</button>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($crs as $i=>$loc)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$loc->title}}</td>
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
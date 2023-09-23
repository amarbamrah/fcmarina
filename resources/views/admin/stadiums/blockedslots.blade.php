@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Stadiums</a></li>
            <li class="breadcrumb-item"><a href="#">Manage Blocked Slots</a></li>

        </ol>
    </nav>

    <div class="row">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/stadiums/blocked-slots" class="row" method="post">
                        @csrf

                        <div class="form-group mb-3 col-md-12">
                            <label for="">
                                Select Stadium
                            </label>

                            <select name="stadium_id" class="form-select" id="">
                                @foreach($stadiums as $st)

                              <option value="{{$st->id}}">{{$st->name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group mb-3 col-md-6">
                            <label for="">
                               (Timing) Date
                            </label>

                            <input type="date" name="from" class="form-control">
                        </div>


                        <div class="form-group mb-3 col-md-6">
                            <label for="">
                                To
                            </label>

                            <input type="date" name="to" class="form-control">
                        </div>



                        <div class="form-group mb-3 col-md-6">
                            <label for="">
                               (Timing) From
                            </label>

                            <input type="time" name="timing_from" class="form-control">
                        </div>


                        <div class="form-group mb-3 col-md-6">
                            <label for="">
                                To
                            </label>

                            <input type="time" name="timing_to" class="form-control">
                        </div>

                        <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Block Slots</button>
                        </div>
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

                                    <th>Date(From-To)</th>
                                    <th>Time(From-To)</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bs as $i=>$s)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$s->stadium->name}}</td>
                                    <td>{{\Carbon\Carbon::create($s->from)->format('d M Y')}} - {{\Carbon\Carbon::create($s->to)->format('d M Y')}}</td>
                                    <td>{{\Carbon\Carbon::create($s->timing_from)->format('h:i a')}} - {{\Carbon\Carbon::create($s->timing_to)->format('h:i a')}}</td>



                                    <td>
                                        <form action="/admin/stadiums/delete-blocked-slots" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$s->id}}">
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
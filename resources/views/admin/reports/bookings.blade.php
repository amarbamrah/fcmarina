@extends('admin.layouts.admin')

@section('content')
<div class="page-content">

    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-2 mt-2">Filters</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>

        </div>
        <div class="card-body">
            <form method="get" action="">
                <div class="row">

                    <div class="form-group  col-md-6 mb-3">
                        <label class="form-label">Stadium:</label>
                        <select name="stadium" class="form-select" id="">
                            <option value="all">All</option>
                            @foreach($stds as $st)
                            <option value="{{$st->id}}" {{Request::get('stadium')==$st->id?'selected':''}}>{{$st->name}}
                            </option>
                            @endforeach

                        </select>
                    </div>


                    <div class="form-group  col-md-6 mb-3">
                        <label class="form-label">Time Period:</label>
                        <select name="period" id="periodbox" class="form-select">
                            <option value="curr" {{Request::get('period')=='month'?'selected':''}}>This Month</option>
                            <option value="custom" {{Request::get('period')=='custom'?'selected':''}}>Custom</option>
                        </select>


                    <div class="row" id="custombox" style="display:none;">

                            <div class="form-group  col-md-6 mb-3">
                                <label class="">From:</label>
                                <input type="date" name="from"
                                    value="{{Request::get('from')==null?\Carbon\Carbon::now()->format('Y-m-d'):Request::get('from')}}"
                                    class="form-control" id="">
                            </div>

                            <div class="form-group  col-md-6 mb-3">
                                <label class="">To:</label>
                                <input type="date" name="to"
                                    value="{{Request::get('to')==null?\Carbon\Carbon::now()->format('Y-m-d'):Request::get('to')}}"
                                    class="form-control" id="">
                            </div>
                        </div>

                    </div>
                    






                </div>
                <input type="submit" class="btn btn-primary" value="Filter Data" />
            </form>
        </div>
    </div>






    <div class="card mb-3 mt-3">
        <div class="card-header pb-0">
            <div class="d-flex  gap-4 align-items-center">
                <h4 class="flex-grow-1 card-title mg-b-2 mt-2">Reports</h4>

                <div>
                    Total Bookings: <strong>{{$total_bookings}} </strong>
                </div>

                <div>
                    Total Hours: <strong>{{$total_hours}} </strong>
                </div>

                <div>
                    Total Rev: <strong>Rs {{$total_revs}} </strong>
                </div>

                <div>
                    <form action="/admin/export-report" method="post">
                        @csrf
                        <input type="hidden" name="sid" value="">
                        <input type="hidden" name="from" value="">
                        <input type="hidden" name="to" value="">
                        <input type="hidden" name="period" value="">

                        <button type="submit" class="btn btn-primary">Export Data</button>
                    </form>
                </div>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>

        </div>


        <div class="accordion" id="accordionExample">

            @foreach($days as $i=>$day)

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed d-flex" type="button" data-bs-toggle="collapse"
                        data-bs-target="#boxx{{$i}}" aria-expanded="true" aria-controls="boxx{{$i}}">
                        <div class="col-md-4">
                            {{$day['date']}}
                        </div>

                        <div class="flex-grow-1">
                            Revenue Rs {{$day['rev']}} &nbsp;&nbsp; |&nbsp;&nbsp; Total Hours: {{$day['hours']}}
                        </div>

                        <div class="mr-3" style="margin-right:10px;">
                            Total Bookings: {{count($day['bookings'])}}
                        </div>
                    </button>
                </h2>
                <div id="boxx{{$i}}" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>

                                    <th>User</th>
                                    <th>User Phone</th>
                                    <th>Stadium Name</th>
                                    <th>Stadium Type</th>
                                    <th>Booking Date</th>
                                    <th>Booking Time</th>
                                    <th>Total Amount</th>
                                    <th>Advance</th>
                                    <th>Rem Amount</th>


                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($day['bookings'] as $i=>$stdbook)
                                <tr>
                                    <td>{{$stdbook->booking_id}}</td>
                                    <td>{{$stdbook['user']==null?$stdbook->name:$stdbook['user']['name']}}</td>
                                    <td>{{$stdbook['user']==null?$stdbook->Phone:$stdbook['user']['phonenumber']}}</td>

                                    <td>{{$stdbook['stadium']['name']}}</td>
                                    <td>{{$stdbook->stadium_type}}</td>
                                    <td>{{$stdbook->date}}</td>
                                    <td> {{Carbon\Carbon::create($stdbook->from)->format('g:i A')}} --
                                        {{Carbon\Carbon::create($stdbook->to)->format('g:i A')}}</td>
                                    <td>Rs {{$stdbook->total_amount}}</td>
                                    <td>Rs {{$stdbook->advance}}</td>

                                    <td>Rs {{$stdbook->rem_amount}}</td>


                                    <td>
                                       @if($stdbook->status=='Confirmed')
                                       <span class="badge bg-primary">
                                            {{$stdbook->status}}
                                        </span>
                                       @elseif($stdbook->status=='Completed')
                                       <span class="badge bg-success">
                                            {{$stdbook->status}}
                                        </span>
                                        @elseif($stdbook->status=='Cancelled')
                                       <span class="badge bg-danger">
                                            {{$stdbook->status}}
                                        </span> 

                                        @elseif($stdbook->status=='Processing')
                                       <span class="badge bg-info">
                                            {{$stdbook->status}}
                                        </span> 

                                        @else
                                       @endif
                                    </td>
                                    <td>
                                        <a href="/admin/stadium-bookings/{{$stdbook->id}}">
                                            View <i style="width:17px;" data-feather="arrow-right"></i>
                                        </a>
                                    </td>


                                </tr>
                                @endforeach



                            </tbody>
                        </table>

                        @if(count($day['bookings'])==0)
                        <div class="text-center mt-2">
                            <small>No Booking found</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @endforeach



        </div>


    </div>
</div>
@endsection
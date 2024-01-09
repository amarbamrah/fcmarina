@extends('admin.layouts.admin')

@section('content')
<div class="page-content">


    <div class="card mb-3 mt-3">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-2 mt-2"> Bookings of {{$user->name}}</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="bookingTable" class="table">
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
                            <th>Discount</th>
                            <th>Advance</th>
                            <th>Rem Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stadiumbooking as $i=>$stdbook)
                        <tr>
                            <td>{{$stdbook->booking_id}}</td>
                            <td>{{$stdbook['user']==null?$stdbook->name:$stdbook['user']['name']}}</td>
                            <td>{{$stdbook['user']==null?$stdbook->Phone:$stdbook['user']['phonenumber']}}</td>

                            <td>{{$stdbook['stadium']['name']}}</td>
                            <td>{{$stdbook->stadium_type}}</td>
                            <td>{{$stdbook->date}}</td>
                            <td> {{Carbon\Carbon::create($stdbook->from)->format(  'g:i A')}} --
                                {{Carbon\Carbon::create($stdbook->to)->format(' g:i A')}}</td>
                            <td>Rs {{$stdbook->total_amount}}</td>
                            <td>Rs {{$stdbook->discount}}</td>
                            <td>Rs {{$stdbook->advance}}</td>
                            <td>Rs {{$stdbook->rem_amount}}</td>


                            <td>
                                <span class="badge {{$stdbook->status=='Confirmed'?'bg-primary':'bg-danger'}}">
                                    {{$stdbook->status}}
                                </span>
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
            </div>
        </div>
    </div>
</div>
@endsection
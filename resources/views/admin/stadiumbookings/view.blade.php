@extends('admin.layouts.admin')

@section('content')
<div class="page-content">




    <div class="card mb-3">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-2 mt-2">Booking Details:</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">

                    <tr>
                        <th>Booking Status:</th>
                        <td>

                            <span class="badge {{$booking->status=='Confirmed'?'bg-primary':'bg-danger'}}">
                                {{$booking->status}}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Booking ID:</th>
                        <td>{{$booking->booking_id}}</td>
                    </tr>
                    <tr>
                        <th>User Name:</th>
                        <td>{{$booking['user']==null?$booking->name:$booking['user']['name']}}</td>
                    </tr>

                    <tr>
                        <th>User Phone No:</th>
                        <td>{{$booking['user']==null?$booking->phone:$booking['user']['phonenumber']}}</td>
                    </tr>

                    <tr>
                        <th>User Email:</th>
                        <td>{{$booking['user']==null?$booking->email:$booking['user']['email']}}</td>
                    </tr>

                    <tr>
                        <th>Stadium:</th>
                        <td>{{$booking['stadium']['name']}}</td>
                    </tr>

                    <tr>
                        <th>Booked Slot:</th>
                        <td> {{Carbon\Carbon::create($booking->date)->format(  'd M Y')}} |
                            {{Carbon\Carbon::create($booking->from)->format(  'g:i A')}} --
                            {{Carbon\Carbon::create($booking->to)->format(' g:i A')}}</td>
                    </tr>

                    <tr>
                        <th>Booked On:</th>
                        <td> {{Carbon\Carbon::create($booking->created_at)->format(  'd M Y')}}</td>
                    </tr>


                    <tr>
                        <th>Booked By:</th>
                        <td>{{$booking->faculity_id!=null?'Venue':'User'}}</td>
                    </tr>

                    <tr>
                        <th>Booking Amount:</th>
                        <td>Rs {{$booking->total_amount}}</td>
                    </tr>

                    <tr>
                        <th>Discount:</th>
                        <td>Rs {{$booking->discount}}</td>
                    </tr>

                    <tr>
                        <th>Payable Amount:</th>
                        <td>Rs {{$booking->payable_amount}}</td>
                    </tr>

                    <tr>
                        <th>Advance Paid:</th>
                        <td>Rs {{$booking->advance}}</td>
                    </tr>

                    <tr>
                        <th>Remaining Amount to be paid:</th>
                        <td><span class="text-danger">Rs {{$booking->rem_amount}}</span></td>
                    </tr>

                    <tr>
                        <th>Amount Breakdown</th>
                        <td>
                            @if(count($booking->booking_payments)>0)
                              @foreach($booking->booking_payments as $bp)
                                {{$bp->payment_mode}} - {{$bp->amount}} <br>
                              @endforeach
                            @else
                            {{$bp->payment_mode}} - {{$bp->amount==0?$booking->payable_amount-$booking->advance:$bp->amount}} 
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
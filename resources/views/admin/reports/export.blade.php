@foreach($days as $i=>$day)


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
            <th>Booking Amount</th>
            <th>Discount</th>


            <th>Advance</th>
            <th>Rem Amount</th>

            <th>Total Amount</th>
            <th>UPI</th>
            <th>CASH</th>

            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach($day['bookings'] as $i=>$stdbook)
                                    <tr>
                                        <td>{{$stdbook->booking_id}}</td>
                                        <td>{{$stdbook['user']==null?$stdbook->name:$stdbook['user']['name']}}</td>
                                        <td>{{$stdbook['user']==null?$stdbook->Phone:$stdbook['user']['phonenumber']}}
                                        </td>

                                        <td>{{$stdbook['stadium']['name']}}</td>
                                        <td>{{$stdbook->stadium_type}}</td>
                                        <td>{{$stdbook->date}}</td>
                                        <td> {{Carbon\Carbon::create($stdbook->from)->format('g:i A')}} --
                                            {{Carbon\Carbon::create($stdbook->to)->format('g:i A')}}</td>
                                        <td>Rs {{$stdbook->total_amount}}</td>
                                        <td>Rs {{$stdbook->discount}}</td>

                                        
                                        <td>Rs {{$stdbook->advance}}</td>

                                        <td>Rs {{$stdbook->rem_amount}}</td>

                                        <td>Rs {{$stdbook->payable_amount}}</td>

                                        <td>
                                        @if($stdbook->status=='Completed')
                                        Rs {{$stdbook->upi}}
                                        @endif
                                       </td>

                                        <td>
                                        @if($stdbook->status=='Completed')
                                        Rs {{$stdbook->cash}}
                                        @endif
                                      </td>

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
                                       


                                    </tr>
                                    @endforeach



    </tbody>
</table>



@endforeach
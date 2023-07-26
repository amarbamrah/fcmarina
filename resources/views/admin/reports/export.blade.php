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
                                    <th>Total Amount</th>
                                    <th>Status</th>
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
                                    <td> {{Carbon\Carbon::create($stdbook->from)->format(  'g:i A')}} --
                                        {{Carbon\Carbon::create($stdbook->to)->format(' g:i A')}}</td>
                                    <td>Rs {{$stdbook->total_amount}}</td>
                                    <td>
                                        <span class="badge {{$stdbook->status=='Confirmed'?'bg-primary':'bg-danger'}}">
                                            {{$stdbook->status}}
                                        </span>
                                    </td>
                                   

                                </tr>
                                @endforeach



                            </tbody>
                        </table>

                

            @endforeach
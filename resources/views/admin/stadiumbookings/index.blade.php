@extends('admin.layouts.admin')

@section('content')
<div class="page-content">

             <div class="card">
                <div class="card-body">
                    <form method="get" action="/stadium-bookings">
                        <div class="row">

                        <div class="form-group  col-md-6 mb-3">
                            <label class="form-label">Stadium:</label>
                            <select name="stadium" class="form-select" id="">
                              <option value="all">All</option>
                              @foreach($stds as $st)
                                 <option value="{{$st->id}}" {{Request::get('stadium')==$st->id?'selected':''}}>{{$st->name}}</option>
                              @endforeach

                            </select>
                        </div>
                        <div class="form-group  col-md-6 mb-3">
                            <label class="form-label">Date:</label>
                            <input type="date" class="form-control" value="{{Request::get('date')}}" name="date">
                        </div>

                      
                    
                    </div>
                    <input type="submit" class="btn btn-primary" value="Filter" />
                      </form>
                    </div>
               </div>




        <div class="card mb-3">
        <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-2 mt-2">All Stadium Bookings</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>

                </div>
            <div class="card-body">
             <div class="table-responsive">
               <table id="dataTableExample" class="table">
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
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($stadiumbooking as $i=>$stdbook)
                                <tr>
                                  <td>{{$stdbook->booking_id}}</td>
                                <td>{{$stdbook['user']['name']}}</td> 
                                <td>{{$stdbook['user']['phonenumber']}}</td> 

                                <td>{{$stdbook['stadium']['name']}}</td> 
                                    <td>{{$stdbook->stadium_type}}</td>
                                    <td>{{$stdbook->date}}</td>
                                   <td> {{Carbon\Carbon::create($stdbook->from)->format(  'g:i A')}} -- {{Carbon\Carbon::create($stdbook->to)->format(' g:i A')}}</td>
                                    <td>Rs {{$stdbook->total_amount}}</td>
                                        
                                </tr>
                                @endforeach



                    </tbody>
                  </table>
                </div>
              </div>
            </div>
</div>
@endsection
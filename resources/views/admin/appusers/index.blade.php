@extends('admin.layouts.admin')

@section('content')
<div class="page-content">
        <div class="card mb-3">
        <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-2 mt-2">All App Users</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>

                </div>
            <div class="card-body">
             <div class="table-responsive">
               <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Points</th>
                        <th>Joined On</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($appusers as $i=>$appuser)
                                <tr>
                                    <td>{{$appuser->name}}</td>
                                    <td>{{$appuser->email}}</td>
                                    <td>{{$appuser->phonenumber}}</td>  
                                    <td>{{$appuser->age}}</td>  
                                    <td>{{$appuser->gender}}</td>  
                                    <td>{{$appuser->points}}</td>  

                                    <td>{{Carbon\Carbon::create($appuser->created_at)->format('d M Y')}}</td>

                                </tr>
                                @endforeach



                    </tbody>
                  </table>
                </div>
              </div>
            </div>
</div>
@endsection
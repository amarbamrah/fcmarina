@extends('admin.layouts.admin')

@section('content')
<div class="page-content">
    <div class="card mb-3">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-2 mt-2">Redeem Points [{{$user->name.' - '.$user->phonenumber}}] &nbsp; &nbsp; | Total Points: {{$user->points}}</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableExample" class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Remarks</th>

                            <th>Points</th>
                            <th>Type</th>
                            <th>Total Points</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trans as $i=>$tra)
                        <tr>
                            <td>{{$tra->created_at}}</td>
                            <td>{{$tra->remarks}}</td>
                            <td>{{$tra->points}}</td>
                        </tr>
                        @endforeach



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
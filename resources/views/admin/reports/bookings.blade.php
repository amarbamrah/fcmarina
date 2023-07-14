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
            <form method="get" action="/admin/stadium-bookings">
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
                       <input type="month" name="month" value="2023-07" class="form-control" id="">
                    </div>






                </div>
                <input type="submit" class="btn btn-primary" value="Filter Data" />
            </form>
        </div>
    </div>




    <div class="card mb-3 mt-3">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-2 mt-2">Reports</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>

                            <th>Hours</th>
                            <th>Revenue (in Rs)</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($days as $i=>$day)
                        <tr>
                            


                        </tr>
                        @endforeach



                    </tbody>
                </table>



              
            </div>
        </div>
    </div>
</div>
@endsection
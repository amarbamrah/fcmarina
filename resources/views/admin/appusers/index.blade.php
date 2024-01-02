@extends('admin.layouts.admin')

@section('content')
<div class="page-content">

    <div class="card mb-3">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-2 mt-2">Filter Data</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>

        </div>
        <div class="card-body">
          <form action="" class="row align-items-end">
            <div class="form-group col-md-10">
              <label for="" class="form-label">Date:</label>
              <select name="period" class="form-select" id="periodbox">
                 <option value="All" {{Request::get('period')=='All'?'selected':''}}>All Time</option>
                <option value="Today" {{Request::get('period')=='Today'?'selected':''}}>Today</option>
                <option value="Month" {{Request::get('period')=='Month'?'selected':''}}>This Month</option>
                <option value="custom" {{Request::get('period')=='custom'?'selected':''}}>Custom</option>
              </select>

              <div class="row mt-2" style="display:none;" id="custombox">
                <div class="col-md-6">
                  <input type="date" name="from" class="form-control" id="">
                </div>
                <div class="col-md-6">
                  <input type="date" name="to" class="form-control" id="">
                </div>
              </div>
            </div>

            <div class="col-md-2">
              <button type="submit" class="btn btn-primary form-control">SUBMIT</button>
            </div>
          </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-2 mt-2">All App Users</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="" class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Age</th>
                            <th>Stadium</th>
                            <th>Sport</th>

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
                            <td>{{$appuser->fstadium}}</td>
                            <td>{{$appuser->sport}}</td>


                            <td>{{$appuser->gender}}</td>
                            <td>
                                <a href="/admin/points?user_id={{$appuser->id}}">{{$appuser->points}}</a>
                            </td>

                            <td>{{Carbon\Carbon::create($appuser->created_at)->format('d M Y')}}</td>

                        </tr>
                        @endforeach



                    </tbody>
                </table>

                <br>

                {{$appusers->withQueryString()->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
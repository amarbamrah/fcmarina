@extends('admin.layouts.admin')

@section('content')
<div class="page-content">
    <div class="card mb-3">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-2 mt-2">Happy Hours</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i data-feather="plus" style="width:20px;font-size:13px;"></i> Add new</button>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableExample" class="table">
                    <thead>
                        <tr>
                            <th>Stadium Name</th>
                            <th>Timing</th>
                            <th>Min Hours</th>

                            <th>Discount</th>
                            <th>Applicable for</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hrs as $i=>$hr)
                        <tr>

                        <td>{{$hr->stadium->name}}</td>
                        <td>{{\Carbon\Carbon::create($hr->from)->format('h:i a')}} - {{\Carbon\Carbon::create($hr->to)->format('h:i a')}}</td>
                        <td>{{$hr->hours}}hrs</td>

                        <td>{{$hr->discount}} %</td>
                        <td>{{$hr->user_id!=null?'Specific':'All User'}}</td>



                        </tr>
                        @endforeach



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>








<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new Happy Hours Offer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/happy-hours" method="post">
                    @csrf
                    <div class="row mb-3">
                        <label for=""> Timing [From-To]</label>
                        <div class="col-md-6">
                            <input type="time"  name="from" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <input type="time"  name="to" class="form-control">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Discount</label>
                        <input type="number" name="discount" class="form-control" id="">
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Min Hours</label>
                        <input type="number" name="hours" class="form-control" id="">
                    </div>


                    <div class="form-group mb-3">
                        <label class="form-label">Select Stadium:</label>
                        <select name="stadium" class="form-select" id="">
                            @foreach($stds as $st)
                            <option value="{{$st->id}}" {{Request::get('stadium')==$st->id?'selected':''}}>{{$st->name}}
                            </option>
                            @endforeach

                        </select>
                    </div>


                    <div class="form-group mb-3">
                        <label class="form-label">Applicable For:</label>
                        <select name="applicable" class="form-select" id="applicable">
                            <option value="all">All Users</option>
                            <option value="spec">Specific User</option>4
                          

                        </select>
                    </div>

                    <div class="form-group mb-3" id="userbox" style="display:none;">
                        <label for="">User's Phone No</label>
                        <input type="phone" name="phone" class="form-control" id="">
                    </div>



                    <div class="form-group mb-3">
                        <label class="form-label">Select Days:</label>
                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked
                            type="checkbox" value="Mon" id="Mon">
                        <label class="form-check-label" for="Mon">
                            Monday
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked
                            type="checkbox" value="Tue" id="Tue">
                        <label class="form-check-label" for="Tue">
                            Tuesday
                        </label>
                    </div>


                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked
                            type="checkbox" value="Wed" id="Wed">
                        <label class="form-check-label" for="Wed">
                            Wednesday
                        </label>
                    </div>


                   

                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked
                            type="checkbox" value="Thu" id="Thu">
                        <label class="form-check-label" for="Thu">
                            Thursday
                        </label>
                    </div>


                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked
                            type="checkbox" value="Fri" id="Fri">
                        <label class="form-check-label" for="Fri">
                            Friday
                        </label>
                    </div>


                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked
                            type="checkbox" value="Sat" id="Sat">
                        <label class="form-check-label" for="Sat">
                            Saturday
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked
                            type="checkbox" value="Sun" id="Sun">
                        <label class="form-check-label" for="Sun">
                            Sunday
                        </label>
                    </div>

                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>


                </form>

            </div>
        </div>
    </div>
</div>
@endsection
@extends('admin.layouts.admin')

@section('content')
<div class="page-content">
    <div class="card mb-3">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-2 mt-2">Edit Happy Hours</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i>

       
            </div>

        </div>
        <div class="card-body">
            <form action="/admin/happy-hours" method="post">
                @csrf
                <div class="row mb-3">
                    <label for=""> Timing [From-To]</label>
                    <div class="col-md-6">
                        <input type="time" name="from" class="form-control" value="{{$happyHour->from}}">
                    </div>

                    <div class="col-md-6">
                        <input type="time" name="to" class="form-control" value="{{$happyHour->to}}">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="">Discount</label>
                    <input type="number" name="discount" class="form-control" id="" value="{{$happyHour->discount}}">
                </div>

                <div class="form-group mb-3">
                    <label for="">Min Hours</label>
                    <input type="number" name="hours" class="form-control" id="" value="{{$happyHour->hours}}">
                </div>


                <div class="form-group mb-3">
                    <label class="form-label">Select Stadium:</label>
                    <select name="stadium" class="form-select" id="">
                        @foreach($stds as $st)
                        <option value="{{$st->id}}" {{$happyHour->stadium_id==$st->id?'selected':''}}>{{$st->name}}
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
                        <input class="form-check-input" name="days[]" checked type="checkbox" value="Mon" id="Mon">
                        <label class="form-check-label" for="Mon">
                            Monday
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked type="checkbox" value="Tue" id="Tue">
                        <label class="form-check-label" for="Tue">
                            Tuesday
                        </label>
                    </div>


                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked type="checkbox" value="Wed" id="Wed">
                        <label class="form-check-label" for="Wed">
                            Wednesday
                        </label>
                    </div>




                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked type="checkbox" value="Thu" id="Thu">
                        <label class="form-check-label" for="Thu">
                            Thursday
                        </label>
                    </div>


                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked type="checkbox" value="Fri" id="Fri">
                        <label class="form-check-label" for="Fri">
                            Friday
                        </label>
                    </div>


                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked type="checkbox" value="Sat" id="Sat">
                        <label class="form-check-label" for="Sat">
                            Saturday
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" name="days[]" checked type="checkbox" value="Sun" id="Sun">
                        <label class="form-check-label" for="Sun">
                            Sunday
                        </label>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>




@endsection
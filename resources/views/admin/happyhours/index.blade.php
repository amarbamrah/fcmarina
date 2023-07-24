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
                            <th>Discount</th>
                            <th>Specific for User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hrs as $i=>$hr)
                        <tr>


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
                    <div class="row mb-3">
                        <label for=""> Timing [From-To]</label>
                        <div class="col-md-6">
                            <input type="time" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <input type="time" class="form-control">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Discount</label>
                        <input type="number" name="discount" class="form-control" id="">
                    </div>


                    <div class="form-group mb-3">
                            <label class="form-label">Select Stadium:</label>
                            <select name="stadium" class="form-select" id="">
                              <option value="all">All</option>
                              @foreach($stds as $st)
                                 <option value="{{$st->id}}" {{Request::get('stadium')==$st->id?'selected':''}}>{{$st->name}}</option>
                              @endforeach

                            </select>
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
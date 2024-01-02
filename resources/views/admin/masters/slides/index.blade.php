@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Masters</a></li>
            <li class="breadcrumb-item"><a href="#">App Slides</a></li>

        </ol>
    </nav>

    <div class="row">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/slides" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="">
                                Image
                            </label>

                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">
                                Stadium
                            </label>

                           <select name="stadium" class="form-select" id="">
                               @foreach($stadiums as $stadium)
                                  <option value="{{$stadium->id}}">{{$stadium->name}}</option>
                               @endforeach
                           </select>
                        </div>

                        <button type="submit" class="btn btn-primary">ADD LOCATION</button>
                    </form>
                </div>
            </div>
        </div>



        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($slides as $i=>$slide)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>
                                        <img src="{{url('/').$slide->image}}" alt="" srcset="">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
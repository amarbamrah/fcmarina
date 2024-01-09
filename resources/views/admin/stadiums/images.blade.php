@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Stadiums</a></li>
            <li class="breadcrumb-item"><a href="#">Manage Images For {{$stadium->name}}</a></li>

        </ol>
    </nav>

    <div class="row">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/stadiums/images" method="post">
                        @csrf

                        <input type="hidden" name="stadium_id" value="{{$stadium->id}}">

                        <div class="form-group mb-3">
                            <label for="">
                                Image
                            </label>

                            <input type="file" name="image" required class="form-control">
                        </div>

                       


                        

                        <button type="submit" class="btn btn-primary">ADD USER</button>
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
                                    <th>Images</th>


                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($images as $i=>$image)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>
                                        <img src="{{url($image->image)}}" alt="">
                                    </td>


                             

                                    <td>
                                        <form action="/admin/stadiums/images/delete" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{$image->id}}">

                                            <button type="submit" class="btn p-0">
                                            
                                            <i data-feather="x" class="text-danger"></i>
                                         
                                            </button>
                                        </form>
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
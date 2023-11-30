@extends('admin.layouts.admin')


@section('content')
<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Create New Stadium</a></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{url('/admin/stadiums')}}" enctype="multipart/form-data">
                        @csrf
                        <h6 class="card-title">Create Stadium Form</h6>

                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="mb-3">
                                    <label class="form-label"> Stadium Name <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter name
                                                    " required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span> </label>
                                    <textarea id="tinymceExample" class="form-control" name="description"></textarea>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label"> Location <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="location" id="location">
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Stadium Type <span class="text-danger">*</span> </label>
                                    <select name="type" id="stypebox" class="form-select" required>
                                        <option value="both">Both (5s+7s)</option>

                                        <option value="5s">5s</option>
                                        <option value="7s">7s</option>
                                    </select>
                                </div>
                            </div><!-- Col -->

                        </div><!-- Row -->
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="address" placeholder="Enter Address"
                                        required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Contact Number <span class="text-danger">*</span> </label>
                                    <input type="number" class="form-control" maxlength="10" name="contactno"
                                        placeholder="Enter Mobile.no" required>
                                </div>
                            </div>
                        </div><!-- Row -->

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Featured Image <span class="text-danger">*</span> </label>
                                    <input id="myDropify" type="file" class="form-control" name="image" required>
                                </div>
                            </div><!-- Col -->
                        </div>

                        <hr>

                        <h4>Pricing</h4>
                        <br>

                        <div class="row mb-3">
                            <div class="form-group col-4 5sprices">
                                <label class="form-label">Monday (5s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="mon5s">
                            </div>


                            <div class="form-group col-4 7sprices">
                                <label class="form-label">Monday (7s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="mon7s">
                            </div>

                            <div class="form-group col-4 9sprices">
                                <label class="form-label">Monday (9s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="mon9s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-4 5sprices">
                                <label class="form-label">Tuesday (5s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="tue5s">
                            </div>

                            <div class="form-group col-4 7sprices">
                                <label class="form-label">Tuesday (7s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="tue7s">
                            </div>

                            <div class="form-group col-4 9sprices">
                                <label class="form-label">Tuesday (9s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="tue9s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-4 5sprices">
                                <label class="form-label">Wednesday (5s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="wed5s">
                            </div>

                            <div class="form-group col-4 7sprices">
                                <label class="form-label">Wednesday (7s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="wed7s">
                            </div>

                            <div class="form-group col-4 9sprices">
                                <label class="form-label">Wednesday (9s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="wed9s">
                            </div>

                        </div>


                        <div class="row mb-3">
                            <div class="form-group col-4 5sprices">
                                <label class="form-label">Thursday (5s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="thu5s">
                            </div>

                            <div class="form-group col-4 7sprices">
                                <label class="form-label">Thursday (7s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="thu7s">
                            </div>

                            <div class="form-group col-4 9sprices">
                                <label class="form-label">Thursday (9s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="thu9s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-4 5sprices">
                                <label class="form-label">Friday (5s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="fri5s">
                            </div>

                            <div class="form-group col-4 7sprices">
                                <label class="form-label">Friday (7s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="fri7s">
                            </div>

                            <div class="form-group col-4 9sprices">
                                <label class="form-label">Friday (9s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="fri9s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-4 5sprices">
                                <label class="form-label">Saturday (5s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="sat5s">
                            </div>

                            <div class="form-group col-4 7sprices">
                                <label class="form-label">Saturday (7s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="sat7s">
                            </div>

                            <div class="form-group col-4 9sprices">
                                <label class="form-label">Saturday (9s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="sat9s">
                            </div>

                        </div>


                        <div class="row mb-3">
                            <div class="form-group col-4 5sprices">
                                <label class="form-label">Sunday (5s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="sun5s">
                            </div>

                            <div class="form-group col-4 7sprices">
                                <label class="form-label">Sunday (7s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="sun7s">
                            </div>

                            <div class="form-group col-4 9sprices">
                                <label class="form-label">Sunday (9s) <span class="text-danger">*</span> </label>

                                <input type="number" class="form-control" required name="sun9s">
                            </div>

                        </div>


                        <hr>
                        <div class="row mb-3">

                            <h4 class="mb-3">Amenities</h4>

                            <br>
                            @foreach($ameneties as $am)
                            <div class="form-check">
                                <input class="form-check-input" name="ams[]" type="checkbox" value="{{$am->id}}"
                                    id="flexCheckDefault{{$am->id}}">
                                <label class="form-check-label" for="flexCheckDefault{{$am->id}}">
                                    {{$am->title}}
                                </label>
                            </div>

                            @endforeach
                        </div>



                        <hr>



                        <input type="submit" class="btn btn-primary" value="Submit" />
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>



@endsection
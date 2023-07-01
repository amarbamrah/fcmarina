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
                    <form method="post" action="/update-stadiums/{{$st->id}}" enctype="multipart/form-data">
                        @csrf
                        <h6 class="card-title">Create Stadium Form</h6>

                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="mb-3">
                                    <label class="form-label"> Stadium Name</label>
                                    <input type="text" class="form-control" value="{{$st->name}}" name="name" placeholder="Enter name
                                                    " required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea id="tinymceExample" class="form-control" name="description"
                                        required>{{$st->description}}</textarea>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label"> Location</label>
                                    <select class="form-control" name="location" id="location">
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}" {{$location->id==$st->location_id?'selected':''}}>{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Stadium Type </label>
                                    <select name="type" class="form-select" required>
                                        <option value="5s" {{$st->location_id=='5s'?'selected':''}}>5s</option>
                                        <option value="7s" {{$st->location_id=='7s'?'selected':''}}>7s</option>
                                        <option value="both" {{$st->location_id=='both'?'selected':''}}>Both</option>
                                    </select>
                                </div>
                            </div><!-- Col -->

                        </div><!-- Row -->
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" value="{{$st->address}}" class="form-control" name="address" placeholder="Enter Address"
                                        required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="number" value="{{$st->contactno}}" class="form-control" maxlength="10" name="contactno"
                                        placeholder="Enter Mobile.no" required>
                                </div>
                            </div>
                        </div><!-- Row -->

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">5S Price</label>
                                    <input type="number" value="{{$st->price_5s}}" class="form-control" name="price_5s" placeholder="Enter price"
                                        required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">7S Price</label>
                                    <input type="number" value="{{$st->price_7s}}" class="form-control" name="price_7s" placeholder="Enter price"
                                        required>
                                </div>
                            </div>


                        </div>
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Featured Image</label>
                                    <input type="file" id="myDropify" data-default-file="{{url($st->images[0]->image)}}"  class="form-control" name="image">
                                </div>
                            </div><!-- Col -->
                        </div>


                        <input type="submit" class="btn btn-primary" value="Update Stadium" />
                    </form>

                </div>
            </div>
        </div>
    </div>

    </div>



    @endsection
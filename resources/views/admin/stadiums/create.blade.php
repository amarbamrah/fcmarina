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
                                    <label class="form-label"> Stadium Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter name
                                                    " required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea id="tinymceExample" class="form-control" name="description"></textarea>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label"> Location</label>
                                    <select class="form-control" name="location" id="location">
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Stadium Type </label>
                                    <select name="type" class="form-select" required>
                                        <option value="5s">5s</option>
                                        <option value="7s">7s</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>
                            </div><!-- Col -->

                        </div><!-- Row -->
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" placeholder="Enter Address"
                                        required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="number" class="form-control" maxlength="10" name="contactno"
                                        placeholder="Enter Mobile.no" required>
                                </div>
                            </div>
                        </div><!-- Row -->

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">5S Price</label>
                                    <input type="number" class="form-control" name="price_5s" placeholder="Enter price"
                                        required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">7S Price</label>
                                    <input type="number" class="form-control" name="price_7s" placeholder="Enter price"
                                        required>
                                </div>
                            </div>


                        </div>
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Featured Image</label>
                                    <input id="myDropify" type="file" class="form-control" name="image" required>
                                </div>
                            </div><!-- Col -->
                        </div>


                        <input type="submit" class="btn btn-primary" value="Submit" />
                    </form>

                </div>
            </div>
        </div>
    </div>

    </div>



    @endsection
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
                                    <select name="type"  id="stypebox" class="form-select" required>
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
                                    <label class="form-label">Featured Image</label>
                                    <input id="myDropify" type="file" class="form-control" name="image" required>
                                </div>
                            </div><!-- Col -->
                        </div>

                        <hr>

                        <h4>Pricing</h4>
                        <br>

                        <div class="row mb-3">
                            <div class="form-group col-6 5sprices">
                                <label class="form-label">Monday (5s)</label>

                                <input type="number" class="form-control" required name="mon5s">
                            </div>


                            <div class="form-group col-6 7sprices">
                                <label class="form-label">Monday (7s)</label>

                                <input type="number" class="form-control" required name="mon7s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-6 5sprices">
                                <label class="form-label">Tuesday (5s)</label>

                                <input type="number" class="form-control" required name="tue5s">
                            </div>

                            <div class="form-group col-6 7sprices">
                                <label class="form-label">Tuesday (7s)</label>

                                <input type="number" class="form-control" required name="tue7s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-6 5sprices">
                                <label class="form-label">Wednesday (5s)</label>

                                <input type="number" class="form-control" required name="wed5s">
                            </div>

                            <div class="form-group col-6 7sprices">
                                <label class="form-label">Wednesday (7s)</label>

                                <input type="number" class="form-control" required name="wed7s">
                            </div>

                        </div>


                        <div class="row mb-3">
                            <div class="form-group col-6 5sprices">
                                <label class="form-label">Thursday (5s)</label>

                                <input type="number" class="form-control" required name="thu5s">
                            </div>

                            <div class="form-group col-6 7sprices">
                                <label class="form-label">Thursday (7s)</label>

                                <input type="number" class="form-control" required name="thu7s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-6 5sprices">
                                <label class="form-label">Friday (5s)</label>

                                <input type="number" class="form-control" required name="fri5s">
                            </div>

                            <div class="form-group col-6 7sprices">
                                <label class="form-label">Friday (7s)</label>

                                <input type="number" class="form-control" required name="fri7s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-6 5sprices">
                                <label class="form-label">Saturday (5s)</label>

                                <input type="number" class="form-control" required name="sat5s">
                            </div>

                            <div class="form-group col-6 7sprices">
                                <label class="form-label">Saturday (7s)</label>

                                <input type="number" class="form-control" required name="sat7s">
                            </div>

                        </div>


                        <div class="row mb-3">
                            <div class="form-group col-6 5sprices">
                                <label class="form-label">Sunday (5s)</label>

                                <input type="number" class="form-control" required name="sun5s">
                            </div>

                            <div class="form-group col-6 7sprices">
                                <label class="form-label">Sunday (7s)</label>

                                <input type="number" class="form-control" required name="sun7s">
                            </div>

                        </div>




                        <input type="submit" class="btn btn-primary" value="Submit" />
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>



@endsection
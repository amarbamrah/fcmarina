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
                    <form method="post" action="/admin/stadiums/{{$st->id}}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="_method" value="PUT">
                        <h6 class="card-title">Create Stadium Form</h6>

                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="mb-3">
                                    <label class="form-label"> Stadium Name</label>
                                    <input type="text" class="form-control" value="{{$st->name}}" name="name"
                                        placeholder="Enter name
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
                                        <option value="{{$location->id}}"
                                            {{$location->id==$st->location_id?'selected':''}}>{{$location->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Stadium Type </label>
                                    <select name="type" class="form-select" required>
                                    <option value="9s" {{$st->type=='9s'?'selected':''}}>9s</option>

                                        <option value="both" {{$st->type=='both'?'selected':''}}>Both (5s+7s)
                                        </option>

                                        <option value="5s" {{$st->type=='5s'?'selected':''}}>5s</option>
                                        <option value="7s" {{$st->type=='7s'?'selected':''}}>7s</option>
                                    </select>
                                </div>
                            </div><!-- Col -->

                        </div><!-- Row -->
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" value="{{$st->address}}" class="form-control" name="address"
                                        placeholder="Enter Address" required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="number" value="{{$st->contactno}}" class="form-control" maxlength="10"
                                        name="contactno" placeholder="Enter Mobile.no" required>
                                </div>
                            </div>
                        </div><!-- Row -->


                        <div class="row">

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Featured Image</label>
                                    @if(count($st->images)==0)
                                    <input type="file" id="myDropify" class="form-control" name="image">

                                    @else
                                    <input type="file" id="myDropify"
                                        data-default-file="{{url(count($st->images)==0?'': $st->images[0]->image)}}"
                                        class="form-control" name="image">

                                    @endif
                                </div>
                            </div><!-- Col -->
                        </div>


                        <hr>

                        <h4>Pricing</h4>
                        <br>

                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label for="inputPassword6" class="form-label">Monday (5s)</label>

                                <input type="number" class="form-control" value="{{$st->mon5s}}" name="mon5s">
                            </div>


                            <div class="form-group col-4">
                                <label class="form-label">Monday (7s)</label>

                                <input type="number" class="form-control" value="{{$st->mon7s}}" name="mon7s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Monday (9s)</label>

                                <input type="number" class="form-control" value="{{$st->mon9s}}" name="mon9s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label class="form-label">Tuesday (5s)</label>

                                <input type="number" class="form-control" value="{{$st->tue5s}}" name="tue5s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Tuesday (7s)</label>

                                <input type="number" class="form-control" value="{{$st->tue7s}}" name="tue7s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Tuesday (9s)</label>

                                <input type="number" class="form-control" value="{{$st->tue9s}}" name="tue9s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label class="form-label">Wednesday (5s)</label>

                                <input type="number" class="form-control" value="{{$st->wed5s}}" name="wed5s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Wednesday (7s)</label>

                                <input type="number" class="form-control" value="{{$st->wed7s}}" name="wed7s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Wednesday (9s)</label>

                                <input type="number" class="form-control" value="{{$st->wed9s}}" name="wed9s">
                            </div>

                        </div>


                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label class="form-label">Thursday (5s)</label>

                                <input type="number" class="form-control" value="{{$st->thu5s}}" name="thu5s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Thursday (7s)</label>

                                <input type="number" class="form-control" value="{{$st->thu7s}}" name="thu7s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Thursday (9s)</label>

                                <input type="number" class="form-control" value="{{$st->thu9s}}" name="thu9s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label class="form-label">Friday (5s)</label>

                                <input type="number" class="form-control" value="{{$st->fri5s}}" name="fri5s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Friday (7s)</label>

                                <input type="number" class="form-control" value="{{$st->fri7s}}" name="fri7s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Friday (9s)</label>

                                <input type="number" class="form-control" value="{{$st->fri9s}}" name="fri9s">
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label class="form-label">Saturday (5s)</label>

                                <input type="number" class="form-control" value="{{$st->sat5s}}" name="sat5s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Saturday (7s)</label>

                                <input type="number" class="form-control" value="{{$st->sat7s}}" name="sat7s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Saturday (9s)</label>

                                <input type="number" class="form-control" value="{{$st->sat9s}}" name="sat9s">
                            </div>

                        </div>


                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label class="form-label">Sunday (5s)</label>

                                <input type="number" class="form-control" value="{{$st->sun5s}}" name="sun5s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Sunday (7s)</label>

                                <input type="number" class="form-control" value="{{$st->sun7s}}" name="sun7s">
                            </div>

                            <div class="form-group col-4">
                                <label class="form-label">Sunday (9s)</label>

                                <input type="number" class="form-control" value="{{$st->sun9s}}" name="sun9s">
                            </div>

                        </div>


                        <hr>
                        <div class="row mb-3">

                            <h4 class="mb-3">Amenities</h4>

                            <br>
                            @foreach($ameneties as $am)
                            <div class="form-check">
                                <input class="form-check-input" name="ams[]" {{in_array($am->id,$sas)?'checked':''}} type="checkbox" value="{{$am->id}}"
                                    id="flexCheckDefault{{$am->id}}">
                                <label class="form-check-label" for="flexCheckDefault{{$am->id}}">
                                    {{$am->title}}
                                </label>
                            </div>

                            @endforeach
                        </div>



                        <hr>


                        <input type="submit" class="btn btn-primary" value="Update Stadium" />
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>



@endsection
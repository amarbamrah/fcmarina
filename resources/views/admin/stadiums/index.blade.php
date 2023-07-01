@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
<nav class="page-breadcrumb">
	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="#">All Stadiums</a></li>
	</ol>
</nav>

<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
             <div class="table-responsive">
               <table id="dataTableExample" class="table">
                    <thead>
                      <tr>
                      <th> Stadium Image</th>
                        <th>Title</th>
                        <th>Stadium Type</th>
                        <th>Address</th>
                        <th>Contact_Number</th>
                        <th> 5S Price</th>
                        <th> 7S Price</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($stadium as $std)
                      <tr>
                        <td>
                      <img src=" {{url($std->images[0]->image)}}" width="100" alt="" srcset="">    
                      </td>
                        <td>{{$std->name}}</td>
                        <td>{{$std->type}}</td>
                        <td>{{$std->address}}</td>
                        <td>{{$std->contactno}}</td>
                        <td>{{$std->price_5s}}</td>
                        <td>{{$std->price_7s}}</td>
                        <td>
                          <a href="/edit-stadiums/{{$std->id}}">
                            <i class="fas fa-edit"></i> Edit
                          </a>
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
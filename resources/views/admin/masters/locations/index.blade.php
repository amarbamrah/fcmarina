@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
<nav class="page-breadcrumb">
	<ol class="breadcrumb">
    	<li class="breadcrumb-item"><a href="#">Masters</a></li>
    	<li class="breadcrumb-item"><a href="#">Locations</a></li>

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
                        <th>#</th>
                      <th>Title</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($locations as $i=>$loc)
                      <tr>
                      <td>{{$i+1}}</td>
                      <td>{{$loc->name}}</td>
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
@extends('admin.layouts.admin')


@section('content')
<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Admin Logins</a></li>
            <li class="breadcrumb-item"><a href="#">Manage Admin Stadiums</a></li>

        </ol>
    </nav>

    <div class="row">

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="/admin/assign-admin-stadiums" method="post">
                        @csrf

                        <input type="hidden" name="uid" value="{{$user->id}}">
                        <div class="form-group mb-3">
                            <label for="">
                                Stadiums
                            </label>

                            <select name="sid" class="form-select" id="">
                                @foreach($stadiums as $stadium)

                               <?php  $i=0; ?>
                                @foreach($astadiums as $stad){
                                    if($stad->id==$stadium->id){
                                        $i++;
                                    }
                                }
                                @if($i=0)
                                <option value="{{$stadium->id}}">{{$stadium->name}}</option>

                                @endif
                                @endforeach 
                            </select>
                        </div>

                       


                        <button type="submit" class="btn btn-primary">ASSIGN STADIUM</button>
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
                                    <th>Stadium</th>
                                    <th>Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($astadiums as $i=>$stadium)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$stadium->name}}</td>
                                    <td>
                                     <form action="/admin/remove-assign-user" method="post">
                                        @csrf
                                        <input type="hidden" value="{{$stadium->id}}" name="sid">
                                        <input type="hidden" value="{{$user->id}}" name="uid">

                                        <button type="submit" class="btn text-danger p-0">REMOVE</button>

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

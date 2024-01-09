
                <table id="" class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Age</th>
                            <th>Stadium</th>
                            <th>Sport</th>

                            <th>Gender</th>

                            <th>Points</th>
                            <th>Joined On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appusers as $i=>$appuser)
                        <tr>
                            <td>{{$appuser->name}}</td>
                            <td>{{$appuser->email}}</td>
                            <td>{{$appuser->phonenumber}}</td>
                            <td>{{$appuser->age}}</td>
                            <td>{{$appuser->fstadium}}</td>
                            <td>{{$appuser->sport}}</td>


                            <td>{{$appuser->gender}}</td>
                            <td>
                                <a href="/admin/points?user_id={{$appuser->id}}">{{$appuser->points}}</a>
                            </td>

                            <td>{{Carbon\Carbon::create($appuser->created_at)->format('d M Y')}}</td>

                        </tr>
                        @endforeach



                    </tbody>
                </table>

               
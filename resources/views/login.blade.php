<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iMerge | FCMARINA </title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo3/style.css')}}">


    <!-- Plugin css for this page -->
</head>
<body>
    <div class="col-md-3 m-auto mt-5 d-flex">
        <form action="" method="post">
            <h3>Login</h3>
            <small>Login using email & password</small>
            <div class="form-group mb-3 mt-3">
                <label for="email" class="form-label">Email/Username</label>
                <input type="email" name="email" class="form-control"  id="">
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control"  id="">
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>


</body>
</html>
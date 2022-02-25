<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="jumbotron text-center">
        <h1>Axel company counts</h1>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button class="">Logout</button>
        </form>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <a href="{{route('income')}}"><h3>Incomes sheet</h3></a>
            </div>
            <div class="col-sm-4">
                <a href="{{route('expense')}}"><h3>Expense sheet</h3></a>
            </div>
            <div class="col-sm-4">
                <a href="{{route('balance')}}"><h3>Balance sheet</h3></a>
            </div>
            <div class="col-sm-4">
                <a href="{{route('categories')}}"><h3>Categories</h3></a>
            </div>
            <div class="col-sm-4">
                <a href="{{route('payment-methods')}}"><h3>Payment Methods</h3></a>
            </div>
        </div>
    </div>
</body>
</html>

</body>
</html>

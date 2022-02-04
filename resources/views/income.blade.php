<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://mdbootstrap.com/docs/jquery/tables/additional/">

</head>
<body>
<!-- Table with panel -->
<div class="card card-cascade narrower">

    <!--Card image-->
    <div
        class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">

        <div>
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                <i class="fas fa-th-large mt-0"></i>
            </button>
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                <i class="fas fa-columns mt-0"></i>
            </button>
        </div>

        <a href="" class="white-text mx-3">Income table</a>

        <div>

        </div>


    </div>
    <!--/Card image-->

    <div class="px-4">

        <div class="table-wrapper">
            <!--Table-->
            <table class="table table-hover mb-0">

                <!--Table head-->
                <thead>
                <tr>
                    <th>
                        <input class="form-check-input" type="checkbox" id="checkbox">
                        <label class="form-check-label" for="checkbox" class="mr-2 label-table"></label>
                    </th>
                    <th class="th-lg">
                        <a>Date
                            <i class="fas fa-sort ml-1"></i>
                        </a>
                    </th>
                    <th class="th-lg">
                        <a href="">From
                            <i class="fas fa-sort ml-1"></i>
                        </a>
                    </th>
                    <th class="th-lg">
                        <a href="">Description
                            <i class="fas fa-sort ml-1"></i>
                        </a>
                    </th>
                    <th class="th-lg">
                        <a href="">Amount
                            <i class="fas fa-sort ml-1"></i>
                        </a>
                    </th>
                    <th class="th-lg">
                        <a href="">Actions
                            <i class="fas fa-sort ml-1"></i>
                        </a>
                    </th>
                </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                <div class="budget-section">
                    <form action="{{ route('income-insert') }}" method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                @foreach($incomes as $value)
                    <tr>
                        <th scope="row">
                            <input class="form-check-input" type="checkbox" id="checkbox1">
                            <label class="form-check-label" for="checkbox1" class="label-table"></label>
                        </th>
                        <td>{{ $value->date }}</td>
                        <td>{{ $value->from }}</td>
                        <td>
                            {{$out = strlen( $value->description ) > 30 ? substr( $value->description ,0,30)." ..." :  $value->description }}
                        </td>
                        <td>{{ $value->amount }}</td>
                        <td>
                            <div>
                                <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                                    <i class="fas fa-pencil-alt mt-0"></i>
                                </button>
                                <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                                    <i class="far fa-trash-alt mt-0"></i>
                                </button>
                                <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                                    <i class="fas fa-info-circle mt-0"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <!--Table body-->
            </table>
            <!--Table-->
        </div>

    </div>

</div>
<!-- Table with panel -->
</body>
</html>

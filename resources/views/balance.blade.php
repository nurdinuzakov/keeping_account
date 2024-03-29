<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://mdbootstrap.com/docs/jquery/tables/additional/">

    <style>
        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content/Box */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }

        /* The Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Modal Header */
        .modal-header {
            padding: 2px 16px;
            background-color: #5cb85c;
            color: white;
        }

        /* Modal Body */
        .modal-body {padding: 2px 16px;}

        /* Modal Footer */
        .modal-footer {
            padding: 2px 16px;
            background-color: #5cb85c;
            color: white;
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        /* Add Animation */
        @keyframes animatetop {
            from {top: -300px; opacity: 0}
            to {top: 0; opacity: 1}
        }
    </style>

</head>
<body>
<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create new category</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form action="{{ route('category-create') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="from">Category name</label>
                    <input type="text" class="form-control" id="inputFrom" placeholder="Please enter the new category name" name="category_name" required>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeBtn">Cancel</button>
            </form>
        </div>
    </div>
</div>
<!-- The Pencil Modal -->
<div id="myPencilModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Change the category</h2>
            <span class="pencil close">&times;</span>
        </div>
        <div class="modal-body">
            <form action="{{ route('category-update') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="from">Category name</label>
                    <input type="hidden" id="hiddenPencil" name="category_id" value="">
                    <input type="text" class="form-control" id="inputPencilFrom" value="" name="category_name" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closePencilBtn">Cancel</button>
            </form>
        </div>
    </div>
</div>
<!-- Table with panel -->
<div class="card card-cascade narrower">

    <!--Card image-->
    <div
        class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">

        <div>
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" onclick="location.href = '{{ route('home') }}';">
                <i class="fas fa-th-large mt-0"></i>
            </button>
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                <i class="fas fa-columns mt-0"></i>
            </button>
            <a href="" class="white-text mx-3">Total balance: {{ $totalBalance }}</a>
            @foreach($paymentMethods as $paymentMethod)
                <a href="" class="white-text mx-3">{{ $paymentMethod->name }}: {{ $paymentMethod->balance ? $paymentMethod->balance : 0}}</a>
            @endforeach
        </div>

        <a href="" class="white-text mx-3">Balance table</a>

        <div>
            <!-- Trigger/Open The Modal -->
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="btn btn-primary">Logout</button>
            </form>
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
                    <th class="th-lg">
                        <a>Payment type</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Date</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Payment method</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Responsible person</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Amount</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Balance history</a>
                    </th>
                </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                @foreach($paymentHistory as $value)
                    <tr>
                        <td>{{ $value->balanceable_type }}</td>
                        <td>{{ $value->date }}</td>
                        <td>{{ $value->balanceable->paymentMethods->name}}</td>
                        <td>{{ $value->balanceable->responsible_person }}</td>
                        <td>{{ $value->amount }}</td>
                        <td>{{ $value->balance_history }}</td>

                    </tr>
                @endforeach
                </tbody>
                <!--Table body-->
                <tfoot>
                <tr>
                    <td>
                        <a>Category Id</a>
                    </td>
                    <td>
                        <div class="pagination-wrapper">
                            {{ $paymentHistory->links() }}
                        </div>
                    </td>
                    <td>

                    </td>
                </tr>
                </tfoot>
            </table>
            <!--Table-->
        </div>

    </div>

</div>
<!-- Table with panel -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    var btn1 = document.getElementById("closeBtn");

    // When the user clicks on the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    btn1.onclick = function() {
        modal.style.display = "none";
        $(this).parents().find('.form-control').val("");
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<script>
    // Get the Pencil modal
    var modal1 = document.getElementById("myPencilModal");

    // Get the button that opens the modal
    var btn3 = document.getElementsByClassName("incomeUpdateButtons");

    // Get the <span> element that closes the modal
    var span1 = document.getElementsByClassName("pencil close")[0];
    var btn2 = document.getElementById("closePencilBtn");

    // When the user clicks on the button, open the modal
    $('.incomeUpdateButtons').click(function(){
        modal1.style.display = "block";
    })

    // When the user clicks on <span> (x), close the modal
    span1.onclick = function() {
        modal1.style.display = "none";
    }

    btn2.onclick = function() {
        modal1.style.display = "none";
        $(this).parents().find('.form-control').val("");
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal1.style.display = "none";
        }
    }
</script>
<script>

    $('.incomeUpdateButtons').click(function(){
        let from = $(this).parents('tr').find('td:eq(1)').text();

        $('#hiddenPencil').val(this.id)
        $('#inputPencilFrom').val(from)
    })
</script>

<script>
    function myFunction() {
        const answer = confirm("Do you really want to delete this item?");
        if (!answer){
            alert('Item deletion was canceled!');
        }else{
            alert('Item successfully deleted!')
        }
    }
</script>
</body>
</html>

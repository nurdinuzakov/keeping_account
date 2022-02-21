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


    <style>
        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 90%; /* Full width */
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
            <h2>Please enter expenses you made</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form action="{{ route('expense-insert') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="inputDate" name="date" required>
                </div>
                <div class="form-group">
                    <label for="from">Responsible person</label>
                    <input type="text" class="form-control" id="inputResponsiblePerson" placeholder="Who made the expense" name="responsible_person" required>
                </div>
                <div class="form-group">
                    <label for="category">Categories: </label>
                    <select name="category_id" id="category" class="form-control" style="width:250px">
                            <option value="">--- Select category ---</option>
                        @foreach($categories as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>

                    <label for="items">Items: </label>
                    <select name="item_id" id="item" class="form-control" style="width:250px">
                        <option value="">--- Select item ---</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Expense amount</label>
                    <input type="number" class="form-control" id="expense_amount" placeholder="Expense amount" min="0" name="expense_amount" required>
                    <small id="amountHelp" class="form-text text-muted">Numbers only</small>
                </div>
                <div class="form-group">
                    <label for="category">Funds flow </label>
                    <select name="flow_id" id="category" class="form-control" style="width:250px">
                        <option value="">--- Select Funds flow type ---</option>
                        @foreach($flows as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Insert expense</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeBtn">Close</button>
            </form>
        </div>
    </div>
</div>
<!-- The Pencil Modal -->
<div id="myPencilModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Please enter incoming funds</h2>
            <span class="pencil close">&times;</span>
        </div>
        <div class="modal-body">
            <form action="{{ route('expense-update') }}" method="post">
                @csrf
                <div class="form-group">
                    <input type="hidden" id="inputPencilId" name="inputPencilId" value="">
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="inputPencilDate" name="date" value="" required>
                </div>
                <div class="form-group">
                    <label for="from">Responsible person</label>
                    <input type="text" class="form-control" id="inputPencilFrom" value="" name="from" required>
                </div>
                <div class="form-group">
                    <label for="category">Categories: </label>
                    <select name="category_id" id="category" class="form-control" style="width:250px">
                        <option value="">--- Select category ---</option>
                        @foreach($categories as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>

                    <label for="items">Items: </label>
                    <select name="item_id" id="item" class="form-control" style="width:250px">
                        <option value="">--- Select item ---</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Expense amount</label>
                    <input type="number" class="form-control" id="inputPencilAmount" value="" min="0" name="amount" required>
                    <small id="amountHelp" class="form-text text-muted">Numbers only</small>
                </div>

                <div class="form-group">
                    <label for="category">Funds flow </label>
                    <select name="flow_id" id="category" class="form-control" style="width:250px">
                        <option value="">--- Select Funds flow type ---</option>
                        @foreach($flows as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update income</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closePencilBtn">Close</button>
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
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" onclick="location.href = '{{ route('income') }}';">
                <i class="fas fa-columns mt-0"></i>
            </button>

            <a href="" class="white-text mx-3">Balance: {{ $balance }}</a>
        </div>

        <a href="" class="white-text mx-3">Expense table</a>

        <div>
            <!-- Trigger/Open The Modal -->
            <button class="btn btn-primary" id="myBtn">Create expense</button>
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
                        <a>Date</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Responsible person</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Category</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Item</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Expense amount</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Funds flow type</a>
                    </th>
                    <th class="th-lg">
                        <a href="">Actions
                            <i class="fas fa-sort ml-1"></i>
                        </a>
                    </th>
                </tr>
                </thead>
                <!--Table head-->
{{--                {{dd($categories)}}--}}
                <!--Table body-->
                <tbody>
                @foreach($expenses as $value)
{{--                    {{dd($value)}}--}}
                    <tr>
                        <th scope="row">
                            <input class="form-check-input" type="checkbox" id="checkbox1">
                            <label class="form-check-label" for="checkbox1" class="label-table"></label>
                        </th>
                        <td>{{ $value->date }}</td>
                        <td>{{ $value->responsible_person }}</td>
                        <td data-id="">{{ $value->category ? $value->category->name : null }}</td>
                        <td>{{ $value->item ? $value->item->name : null }}</td>
                        <td>{{ $value->expense_amount }}</td>
                        <td>{{ $value->flow->name  }}</td>
                        <td>
                            <div style="display: flex">
                                <button type="submit" class="btn btn-outline-white btn-rounded btn-sm px-2 incomeUpdateButtons" id="{{$value->id}}">
                                    <i class="fas fa-pencil-alt mt-0"></i>
                                </button>
                                <form action="{{ route('expense-delete', $value->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-white btn-rounded btn-sm px-2 incomeDeleteButtons" onclick="myFunction()" id="{{$value->id}}">
                                        <i class="far fa-trash-alt mt-0"></i>
                                    </button>
                                </form>
                                <form action="">
                                    <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                                        <i class="fas fa-info-circle mt-0" src="{{ $value->receipt_photo }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <!--Table body-->
                <tfoot>
                <tr>
                    <td>
                        <input class="form-check-input" type="checkbox" id="checkbox1">
                        <label class="form-check-label" for="checkbox1" class="label-table"></label>
                    </td>
                    <td>
                        <a>Date</a>
                    </td>
                    <td>

                    </td>
                    <td>
                        <a href="">Category</a>
                    </td>
                    <td>
                        <a href="">Item</a>
                    </td>
                    <td>
                        <h5>Total amount: {{$total}}</h5>
                    </td>
                    <td>
                        <a href="">Comments</a>
                    </td>
                    <td>
                        <a href="">Actions</a>
                    </td>
                </tr>
                </tfoot>
            </table>
            <!--Table-->
        </div>
        <div class="pagination-wrapper" style="margin: 0 auto;">
            {{ $expenses->links() }}
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
    document.getElementById('inputDate').value = new Date().toISOString().substring(0, 10);
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
        let date = $(this).parents('tr').find('td:eq(0)').text();
        let from = $(this).parents('tr').find('td:eq(1)').text();
        let category = $(this).parents('tr').find('td:eq(2)').data('id');
        let item = $(this).parents('tr').find('td:eq(3)').text();
        let amount = $(this).parents('tr').find('td:eq(4)').text();
        let comments = $(this).parents('tr').find('td:eq(5)').text();


        $('#inputPencilId').val(this.id)
        $('#inputPencilDate').val(date)
        $('#inputPencilFrom').val(from)
        // $('select[name="category_id"]').val(category)
        $('#inputPencilAmount').val(amount)
        $('#inputPencilDescription').val(comments)


        // let id = $(this).attr('id');
        // let route = '';
        //
        // alert(route);
        // alert(id);
    })
</script>

<script type="text/javascript">
    jQuery(document).ready(function ()
    {
        jQuery('select[name="category_id"]').on('change',function(){
            var categoryID = jQuery(this).val();

            if(categoryID)
            {
                jQuery.ajax({
                    url : '/get-categories/getitems/' +categoryID,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        console.log(data);
                        jQuery('select[name="item_id"]').empty();
                        $('select[name="item_id"]').append('<option value="">--- Select item ---</option>');
                        jQuery.each(data, function(key,value){
                            $('select[name="item_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            }
            else
            {
                $('select[name="item_id"]').empty();
            }
        });
    });
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

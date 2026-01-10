<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Drag and Drop Table Rows In PHP Mysql</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style type="text/css">
        body{
            background:#d1d1d2;
        }
        .mian-section{
            padding:20px 60px;
            margin-top:100px;
            background:#fff;
        }
        .title{
            margin-bottom:50px;
        }
        .label-success{
            position: relative;
            top:20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 mian-section">
                <h3 class="text-center title">Dynamic Drag and Drop Table Rows</h3>
                <table class="table table-bordered">
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                    <tbody class="row_position">
                        <tr id="1">
                            <td>1</td>
                            <td>test 1</td>
                            <td>description 1</td>
                        </tr>
                        <tr id="2">
                            <td>2</td>
                            <td>test 2</td>
                            <td>description 2</td>
                        </tr>
                        <tr id="3">
                            <td>3</td>
                            <td>test 3</td>
                            <td>description 3</td>
                        </tr>
                        <tr id="4">
                            <td>4</td>
                            <td>test 4</td>
                            <td>description 4</td>
                        </tr>
                        <tr id="5">
                            <td>5</td>
                            <td>test 5</td>
                            <td>description 5</td>
                        </tr>
                        <tr id="6">
                            <td>6</td>
                            <td>test 6</td>
                            <td>description 6</td>
                        </tr>
                        <tr id="7">
                            <td>7</td>
                            <td>test 7</td>
                            <td>description 7</td>
                        </tr>
                        <tr id="8">
                            <td>8</td>
                            <td>test 8</td>
                            <td>description 8</td>
                        </tr>
                        <tr id="9">
                            <td>9</td>
                            <td>test 9</td>
                            <td>description 9</td>
                        </tr>
                        <tr id="10">
                            <td>10</td>
                            <td>test 10</td>
                            <td>description 10</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript">
        $(".row_position").sortable({
            delay: 150,
            stop: function() {
                var selectedData = new Array();
                $('.row_position>tr').each(function() {
                    selectedData.push($(this).attr("id"));
                });
                updateOrder(selectedData);
            }
        });
        function updateOrder(data) {
            console.log('data',data);
        }
    </script>
</body>
</html>

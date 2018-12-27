<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title class="text-center"> Table Users</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>

    <div class="container" style="margin-top: 30px;">

        <div id="tableManager" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">User Name</h2>
                    </div>

                    <div class="modal-body">
                        <input type="text" class="form-control" placeholder="User Name..." id="Nameuser"><br>
                       <input type="password" id="pass" placeholder="password" class="form-control"><br>
                       <input type="email" id="email" placeholder="Email" class="form-control"><br>
                       <input type="number" id="phone" placeholder="phoneNumber" class="form-control"><br>
                       <select id="Role">
                           <option value="admin">Admin</option>
                           <option value="user">User</option>
                           <option value="moderator">Moderator</option>
                       </select>
                       <input type="hidden" id="editRowId" value="0">

                    </div>

                    <div class="modal-footer">
                        <input type="button" id="manageBtn" onclick="manageData('addNew')" value="Save" class="btn btn-success">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>Table User</h2>

                <input style="float: right" type="button" class="btn btn-success" id="addNew" value="Add New">
                <br><br>
                
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <td>ID </td>
                            <td>User Name</td>
                            <td>Email</td>
                            <td>Phone</td>
                            <td>Role</td>
                            <td>Options</td>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <hr><br>
                <input type="hidden" id="start" name="start" value="0">
                <input type="hidden" id="limit" name="limit" value="10">
                <button class="btn btn-success" id="back">Back</button>
                <button class="btn btn-success" id="next">Next</button>
            </div>
        </div>
    </div>

    <script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#addNew").on('click', function () {
               $("#tableManager").modal('show');
            });
                
        
            getExistingData($('#start').val(), $('#limit').val());
        });

        

        function checkBack() {
            if (parseInt($('#start').val()) == 0) {
                $("#back").addClass("disabled")
                return false;
            }
            $("#back").removeClass("disabled")
            return true;
        }
        function checkNext() {
            if ($('tr').length <= 10) {
                $("#next").addClass("disabled")
                return false;
            }
            $("#next").removeClass("disabled")
            return true;
        }

        $(document).on('click', '#next', function() {
            checkBack();
            if (!checkNext()) {
                return;
            }
            $('tbody').html("Loading ...");
            var start = parseInt($('#limit').val());
            var end = parseInt(start) + 10;
            getExistingData(start, end);
            $('#start').val(start)
            $('#limit').val(start + 10)
            checkBack();
            checkNext();
        });

        $(document).on('click', '#back', function() {
            checkNext();
            if (!checkBack()) {
                return;
            }
            $('tbody').html("Loading ...");
            var start = parseInt($('#start').val()) - 10;
            var end = parseInt(start) + 10;
            getExistingData(start, end);
            $('#start').val(start)
            $('#limit').val(start + 10)
            checkNext();
        });

        function deleteUser(id){
            $.ajax({
                url: 'ajax.php',
                method: 'POST',
                data: {
                    key: 'DeleteUser',
                    ID: id
                }, 
                success: function (response) {
                    console.log(response);
                    getExistingData($('#start').val(), $('#limit').val());
                }
            })

        }

        function edit(rowId){
            $.ajax({
                   url: 'ajax.php',
                   method: 'POST',
                   dataType: 'json',
                   data: {
                       key: 'getRowData',
                        IDrow: rowId
                   }, success: function (response) {
                        $('#editRowId').val(rowId);
                        $("#Nameuser").val(response.thename);
                        $("#email").val(response.theemail);
                        $('#pass').val(response.thepass);
                        $("#phone").val(response.thephone);
                        $("#tableManager").modal('show');
                        $('#manageBtn').attr('value','Edit').attr('onclick',"manageData('updatRow')");
                    }
                });
        }

        function getExistingData(start, limit) {
            $.ajax({
                url: 'ajax.php',
                method: 'POST',
                data: {
                    key: 'getExistingData',
                    start: start,
                    limit: limit
                }, success: function (response) {
                    if ($.trim(response) != "reachedMax") {
                        $('tbody').html(response);
                        checkNext();
                    }
                }
            });
        }

        function manageData(key) {
            var name = $("#Nameuser");
            var email = $("#email");
            var phone = $("#phone");
            var pass = $("#pass");
            var Role = $("#Role");
            var EditRowID = $('#editRowId');

            if (isNotEmpty(name) && isNotEmpty(email) && isNotEmpty(phone) && isNotEmpty(pass)&& isNotEmpty(Role)) {
                $.ajax({
                   url: 'ajax.php',
                   method: 'POST',
                   dataType: 'text',
                   data: {
                       key: key,
                       name: name.val(),
                       email: email.val(),
                       phone: phone.val(),
                       pass:pass.val(),
                       Role:Role.val(),
                       EditRow:EditRowID.val()
                   }, success: function (response) {
                   
                        if ($.trim(response) != "Updated") 
                            alert(response);
                        else{
                            $("#user_"+EditRowID.val()).html(name.val());
                            $("#theEmail_"+EditRowID.val()).html(email.val());
                            $("#thePhone_"+EditRowID.val()).html(phone.val());
                            $("#theRole_"+EditRowID.val()).html(Role.val());
                            name.val('');
                            email.val('');
                            phone.val('');
                            pass.val('');
                            
                            $("#tableManager").modal('hide');
                            $('#manageBtn').attr('value','Add').attr('onclick',"manageData('addNew')");
                        }

                   }
                });
            }
        }

        function isNotEmpty(caller) {
            if (caller.val() == '') {
                caller.css('border', '1px solid red');
                return false;
            } else
                caller.css('border', '');

            return true;
        }
    </script>
</body>
</html>
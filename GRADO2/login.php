<?php
session_start();
if(isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0){
    header("Location:./");
    exit;
}
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN | Student Grading System</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
        html, body{
            height:100%;
        }
    
      .loginform {
       background-color: #346b67;
       background-image: url('Univ.jpg');
       background-repeat: no-repeat, repeat;
       background-size: cover;
        }
    </style>
</head>
<body class="loginform">
   <div class="h-75 d-flex justify-content-center align-items-center">
       <div class='w-75'>
        <h3 class="py-5 text-center text-light">Student Grading System</h3>
        <div class="card my-1 col-md-4 offset-md-4">
            <div class="card-body">
                <form action="" id="login-form">

                    <div class="form-group">
                    <center><label for="username" class="control-label">Username</label></center>
                        <input type="text" id="username" autofocus name="username" class="form-control form-control-sm rounded-0" required>
                        <br>
                    </div>
                    <div class="form-group">
                    <center><label for="password" class="control-label">Password</label></center>
                        <input type="password" id="password" name="password" class="form-control form-control-sm rounded-0" required>
                        <br>
                    </div>
                    <div class="form-group d-flex w-100 justify-content-end">
                        <br>
                       <button class="btn btn-success text-center">Login</button>
                    </div>
                  
                    
                </form>
            </div>
        </div>
       </div>
   </div>
</body>
<script>
    $(function(){
        $('#login-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('Loging in...')
            $.ajax({
                url:'./Actions.php?a=login',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        setTimeout(() => {
                            location.replace('./');
                        }, 2000);
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>
</html>
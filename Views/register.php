

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register | PHP Chat Application using Websocket</title>

    <!-- Bootstrap core CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>

    <div class="containter">
        <br />
        <br />
        <h1 class="text-center">Chat Application in PHP & MySql using WebSocket - Email Verification</h1>
        
        <div class="row justify-content-md-center">
            <div class="col col-md-4 mt-5">
                
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">

                        <form method="post" action="index.php?controller=register&action=register" id="register_form" >

                            
                            <div class="form-group">
                                <label>Enter Your Email</label>
                                <input type="text" name="user_email" id="user_email" class="form-control" data-parsley-type="email" required />
                            </div>
                            
                            <div class="form-group">
                                <label>Enter Your Password</label>
                                <input type="password" name="user_password" id="user_password" class="form-control"  data-parsley-maxlength="12"  required />
                            </div>
                            <div class="form-group">
                                <label>Enter Your Name</label>
                                <input type="text" name="user_name" id="user_name" class="form-control" data-parsley-pattern="/^[a-zA-Z\s]+$/" required />
                            </div>

                            <div class="form-group text-center">
                                <input type="submit" name="register" class="btn btn-success" value="Register" />
                            </div>

                        </form>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</body>

</html>

<script>

$(document).ready(function(){

    $('#register_form').parsley();

});

</script>


<body>
    <div class="container">
        <br />
        <br />
        <h1 class="text-center">PHP Chat Application using Websocket</h1>
        <br />
        <br />
        <?php echo $message;
        ?>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">Profile</div>
                    <div class="col-md-6 text-right"><a href="index.php?controller=chatroom" class="btn btn-warning btn-sm">Go to Chat</a></div>
                </div>
            </div>
            <div class="card-body">
                <form method="post" id="profile_form" action="index.php?controller=profile&action=edit" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" data-parsley-pattern="/^[a-zA-Z\s]+$/" required value="<? echo $_SESSION['user']['name'] ?>" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="user_email" id="user_email" class="form-control" data-parsley-type="email" required readonly value="<? echo $_SESSION['user']['email'] ?>" />
                    </div>
                   
                    <div class="form-group">
                        <label>Profile</label><br />
                        <input type="file" name="user_profile" id="user_profile" value="people.png" require/>
                        <br />
                        <img src="content/images/<? echo $_SESSION['user']['profile'] ?>" class="img-fluid img-thumbnail mt-3" width="100" />
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" name="edit" class="btn btn-primary" value="Edit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function() {

        $('#profile_form').parsley();
        $('#show_password').click(function() {
            if ($('#user_password').attr('type') == 'password') {
                $('#user_password').attr('type', 'text');
            } else {
                $('#user_password').attr('type', 'password');
            }
        });


    });
    
</script>
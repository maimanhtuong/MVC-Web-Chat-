<?php
    $action=isset($_GET['action'])?$_GET['action']:'home';
    switch ($action){
        case 'home':
            include 'Views/login.php';
            break;
        case 'login':
          
           
            // if (isset($_SESSION['user'])) {
            //     echo '<script>window.location.href="index.php?controller=chatroom"</script>';

           
            $user = new user;
            if (isset($_POST['login'])) {
                $user->setUserEmail($_POST['user_email']);
                $user->setUserPassword($_POST['user_password']);
                $user_data = $user->getUserByEmail();
                
                
                
            
                if ( $user_data['password'] == md5($_POST['user_password']) ) {
                   
                    $user->setUserId($user_data['id']);
                    $user->setUserLoginStatus('login');
                    $user->setConnectionId(0);
                    $user_token = md5(uniqid());
                    $user->setUserToken($user_token);
                    if ($user->updateUserLogin()>0) {
                        $_SESSION['user']['id'] = $user_data['id'];
                        $_SESSION['user']['email'] = $_POST['user_email'];
                        $_SESSION['user']['name'] = $user_data['name'];
                        $_SESSION['user']['password'] = $user_data['password'];
                        $_SESSION['user']['profile'] = $user_data['profile'];
                        $_SESSION['user']['token'] = $user_token;
                        $user_data['status'] = 'online';
                       echo '<script>window.location.href="index.php?controller=chatroom"</script>';
                    }else{
                        echo "error";
                    }
                } else {
                    echo '<div class="alert alert-danger">Wrong email or password</div>';
                }
            }
        
          
            break;
        case 'register':
            include 'Controllers/register.php';
            break;
        default:
            include 'Views/404.php';
    }
?>
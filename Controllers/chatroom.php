<?php
$action = (isset($_GET['action'])) ? $_GET['action'] : 'home';

switch ($action) {
    case 'home':
        include 'Views/chatroom.php';
        break;
    case 'logout':
        

            $user_object = new user;

            $user_object->setUserId($_SESSION['user']['id']);

            $user_object->setUserLoginStatus('logout');



            if ($user_object->updateUserLogin()) {
                unset($_SESSION['user']);

                session_destroy();
                echo '<script>window.location.href="index.php"</script>';

                
               
                

            }
        
    default:
        include 'Views/404.php';
}

<?php
$action = (isset($_GET['action'])) ? $_GET['action'] : 'home';

switch ($action) {
    case 'home':
        include 'Views/register.php';
        break;

    case 'register':
        if (isset($_POST['register'])) {
            if (isset($_SESSION['user'])) {
                echo '<script>window.location.href="index.php?controller=chatroom"</script>';
            } else {
                $user = new user();
                $user->setUserName($_POST['user_name']);
                $user->setUserEmail($_POST['user_email']);
                $user->setUserPassword($_POST['user_password']);
                $user->setUserStatus('offline');
                $user->setUserCreatedOn(date('Y-m-d H:i:s'));
                $user->setUserVerificationCode(md5(uniqid()));
                $user->setUserProfile('people.png');
                $result = $user->getUserByEmail();
                if ($result) {
                    echo "<div class='alert alert-warning'>Email already exit</div>";
                } else {
                    if ($user->addUser()) {
                        echo "<div class='alert alert-success'>Registration Successful</div>";
                        echo "<script>window.location.href='index.php?controller=login'</script>";
                    }
                }
            }
        }


    default:
        include 'Views/404.php';
}

<?php
$action = (isset($_GET['action'])) ? $_GET['action'] : 'home';

switch ($action) {
    case 'home':
        include 'Views/profile.php';
        break;
    case 'edit':
        $message = '';
        if (!isset($_SESSION['user'])) {
            header('location:index.php');
        } else {

            if (isset($_POST['edit'])) {
                $user_object = new user;
                $user_object->setUserId($_SESSION['user']['id']);
                $user_object->setUserName($_POST['user_name']);
                $user_object->setUserProfile($_FILES['user_profile']['name']);
                $result = $user_object->updateUserById();
                if ($result) {
                    $_SESSION['user']['name'] = $_POST['user_name'];
                    $_SESSION['user']['email'] = $_POST['user_email'];
                    //$_SESSION['user']['password'] = $_POST['user_password'];
                    $_SESSION['user']['profile'] = $_FILES['user_profile']['name'];
                    $message = '<div class="alert alert-success">Profile Details Updated</div>';
                    include 'Views/profile.php';
                } else {
                    $message = '<div class="alert alert-danger">Profile Details Not Updated</div>';
                }
            }
        }
}

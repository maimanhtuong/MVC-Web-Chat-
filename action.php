<?php
    if(isset($_POST['action']) && $_POST['action'] == 'leave')
    {   
        require('Models/user.php');

        $user_object = new user;

        $user_object->setUserId($_SESSION['user']['id']);

        $user_object->setUserLoginStatus('logout');

        

        if($user_object->updateUserLogin())
        {
            unset($_SESSION['user']);

            session_destroy();

            echo json_encode(['status'=>1]);
        }
    }

    if(isset($_POST['action']) && $_POST['action'] == 'fetch_chat')
    {
       require ('Models/ChatPrivate.php');
           
            $chat_object = new ChatPrivate;
    
            $chat_object->setToUserId($_POST['from_user_id']);
    
            $chat_object->setFromUserId($_POST['to_user_id']);

           
            $chat_object->change_chat_status();

            echo json_encode($chat_object->getAllChatData());
           
            


    }

?>
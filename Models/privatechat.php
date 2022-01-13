<?php
    $action= (isset($_GET['action'])) ? $_GET['action'] : 'home';

    switch ($action) {
        case 'home':
            include 'Views/privatechat.php';
            break;
        
        }
?>
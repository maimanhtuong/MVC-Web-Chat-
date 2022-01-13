<?php
    class connect_database{
        public function connect(){
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "mychat_db";
            $conn =new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }   
    }
?>
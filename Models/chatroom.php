<?php
    class chatroom{
       private $chatroom_id;
       private $chatroom_id_user; 
       private $chatroom_message;
         private $chatroom_created_on;

         
        public function __construct(){
            require_once 'connect_database.php';
            $database_connect = new connect_database();

            $this->connect = $database_connect->connect();
        }
        public function setChatRoomId($chatroom_id)
        {
            $this->chatroom_id = $chatroom_id;
        }
        public function getChatRoomId()
        {
            return $this->chatroom_id;
        }
        public function setChatRoomIdUser($chatroom_id_user)
        {
            $this->chatroom_id_user = (int)$chatroom_id_user;
        }
        public function getChatRoomIdUser()
        {
            return $this->chatroom_id_user;
        }
        public function setChatRoomMessage($chatroom_message)
        {
            $this->chatroom_message = $chatroom_message;
        }
        public function getChatRoomMessage()
        {
            return $this->chatroom_message;
        }
        public function setChatRoomCreatedOn($chatroom_created_on)
        {
            $this->chatroom_created_on = ($chatroom_created_on);
        }
        public function getChatRoomCreatedOn()
        {
            return $this->chatroom_created_on;
        }

        public function save_chat()
        {
            $query="INSERT INTO chatrooms(user_id,message,created_on) VALUES(:user_id,:message,:created_on)";
            $statement = $this->connect->prepare($query);
            $statement->bindParam(':user_id',$this->chatroom_id_user);
            $statement->bindParam(':message',$this->chatroom_message);
            $statement->bindParam(':created_on',$this->chatroom_created_on);
            $statement->execute();
        }
        public function getAllChat()
        {
            $query="SELECT * FROM chatrooms INNER JOIN users ON chatrooms.user_id = users.id ORDER BY chatrooms.created_on ASC";
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }
    }
?>
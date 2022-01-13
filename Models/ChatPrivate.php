<?php
class ChatPrivate{
    private $message_id;
    private $to_user_id;
    private $from_user_id;
    private $timestamp;
    private $message;
    private $status;

    public function __construct(){
       
        require_once 'connect_database.php';
        $database_connect = new connect_database();

        $this->connect = $database_connect->connect();
    }
    public function setMessageId($message_id)
    {
        $this->message_id = $message_id;
    }
    public function getMessageId()
    {
        return $this->message_id;
    }
    public function setToUserId($to_user_id)
    {
        $this->to_user_id = (int)$to_user_id;
    }
    public function getToUserId()
    {
        return $this->to_user_id;
    }
    public function setFromUserId($from_user_id)
    {
        $this->from_user_id = (int)$from_user_id;
    }
    public function getFromUserId()
    {
        return $this->from_user_id;
    }
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    public function setMessage($message)
    {
        $this->message = $message;
    }
    public function getMessage()
    {
        return $this->message;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
    }

    public function getAllChatData(){
        // $query="SELECT * FROM chat_private WHERE (to_user_id = :to_user_id AND from_user_id = :from_user_id) OR (to_user_id = :from_user_id AND from_user_id = :to_user_id) ORDER BY timestamp ASC";
        // $statement = $this->connect->prepare($query);
        // $statement->bindParam(':to_user_id',$this->to_user_id);
        // $statement->bindParam(':from_user_id',$this->from_user_id);
        // $statement->execute();
        // $result = $statement->fetchAll();
        // return $result;

        // $query="
        //     SELECT a.name as from_user_name, b.name as to_user_name, message, timestamp, status,
        //     to_user_id, from_user_id
        //     FROM messages 
        //     INNER JOIN users a ON messages.from_user_id = a.id
        //     INNER JOIN users b ON messages.to_user_id = b.id
        //     WHERE (message.from_user_id = ? AND message.to_user_id = ?)

        //     ";

        $query="
        SELECT a.name as from_user_name, b.name as to_user_name, message, timestamp,
        to_user_id, from_user_id
        FROM messages
        INNER JOIN users a ON messages.from_user_id = a.id
        INNER JOIN users b ON messages.to_user_id = b.id
        WHERE (messages.from_user_id = :from_user_id AND messages.to_user_id = :to_user_id)
        OR (messages.from_user_id = :to_user_id AND messages.to_user_id = :from_user_id)
        ORDER BY timestamp ASC
            ";
            $statement = $this->connect->prepare($query);
            $statement->bindParam(':from_user_id',$this->from_user_id);
            $statement->bindParam(':to_user_id',$this->to_user_id);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;

    }

    public function save_chat(){
        $query="INSERT INTO messages(to_user_id, from_user_id, message, timestamp, status) VALUES(?,?,?,?,?)";
        $statement = $this->connect->prepare($query);
        $statement->execute([$this->to_user_id, $this->from_user_id, $this->message, $this->timestamp, $this->status]);
        return $this->connect->lastInsertId();
    }

    public function update_status(){
        $query="UPDATE messages SET status = ? WHERE message_id = ?";
        $statement = $this->connect->prepare($query);
        $statement->execute([$this->status, $this->message_id]);
    }

    function change_chat_status()
	{
		$query = "
		UPDATE messages 
			SET status = 'Yes' 
			WHERE from_user_id = :from_user_id 
			AND to_user_id = :to_user_id 
			AND status = 'No'
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':from_user_id', $this->from_user_id);

		$statement->bindParam(':to_user_id', $this->to_user_id);

		$statement->execute();

        if($statement->rowCount() > 0){
            return true;
	}else{
            return false;
    }
    }

}

?>
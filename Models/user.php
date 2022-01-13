<?php
    class user{
    private $user_id;
	private $user_name;
	private $user_email;
	private $user_password;
	private $user_profile;
	private $user_status;
	private $user_created_on;
	private $user_verify_code;
	private $user_login_status;
	private $user_token;
    private $user_connection_id;
	public $connect;

    public function __construct()
    {
        
     
    
        $database_connect = new connect_database();

        $this->connect = $database_connect->connect();
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }
    public function getUserName()
    {
        return $this->user_name;
    }
    public function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }
    public function getUserEmail()
    {
        return $this->user_email;
    }
    public function setUserPassword($user_password)
    {
        $this->user_password = md5($user_password);
    }
    public function getUserPassword()
    {
        return $this->user_password;
    }
    public function setUserProfile($user_profile)
    {
        $this->user_profile = $user_profile;
    }
    public function getUserProfile()
    {
        return $this->user_profile;
    }
    public function setUserStatus($user_status)
    {
        $this->user_status = (int)($user_status);
    }
    public function getUserStatus()
    {
        return $this->user_status;
    }
    public function setUserCreatedOn($user_created_on)
    {
        $this->user_created_on = $user_created_on;
    }
    public function getUserCreatedOn()
    {
        return $this->user_created_on;
    }
    public function setUserVerificationCode($user_verify_code)
    {
        $this->user_verification_code = $user_verify_code;
    }
    public function getUserVerificationCode()
    {
        return $this->user_verify_code;
    }
    public function setUserLoginStatus($user_login_status)
    {
        $this->user_login_status = $user_login_status;
    }
    public function getUserLoginStatus()
    {
        return $this->user_login_status;
    }
    public function setUserToken($user_token)
    {
        $this->user_token = $user_token;
    }
    public function getUserToken()
    {
        return $this->user_token;
    }
    public function setConnectionId($connection_id)
    {
        $this->user_connection_id = $connection_id;
    }
    public function getConnectionId()
    {
        return $this->user_connection_id;
    }
    // public function getUserDetails()
    public function addUser(){
        
        $query = "
		INSERT INTO users (name,email,password,profile,status,created_on,verify_code) 
		VALUES (? , ? , ? , ? , ? , ? , ?)
		";

        $statement = $this->connect->prepare($query);
        $statement->execute(
            array(
                $this->user_name,
                $this->user_email,
                $this->user_password,
                $this->user_profile,
                $this->user_status,
                $this->user_created_on,
                $this->user_verify_code
            )

        );

        if($statement->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function getUserByEmail(){
        $query = "
        SELECT * FROM users WHERE email = ?
        ";
        $statement=$this->connect->prepare($query);
        $statement->execute(
            array(
                $this->user_email
            )
        );
        $result = $statement->fetch();
       
        return $result;
        

    }
    
    public function getUserById(){
        $query = "
        SELECT * FROM users WHERE id = ?
        ";
        $statement=$this->connect->prepare($query);
        $statement->execute(
            array(
                $this->user_id
            )
        );
        $result = $statement->fetch();
        return $result;

    }
    
    public function updateUserByEmail($email){
        $query="UPDATE users SET name = ?, password = ?, profile = ?, status = ? WHERE email = ?";
        $statement=$this->connect->prepare($query);
        if($statement->execute(
            array(
                $this->user_name,
                $this->user_password,
                $this->user_profile,
                $this->user_status,
                $email
            )
        )){
            return true;
        }
        else{
            return false;
        }


    }
    public function updateUserById(){
        $query="UPDATE users SET name = ?, profile = ? WHERE id = ?";
        $statement=$this->connect->prepare($query);
       
        
        
        if($statement->execute(
            array(
                $this->user_name,
                $this->user_profile,
                $this->user_id
            )
        )){
            return true;
        }
        else{
            return false;
        }
    }
    public function updateUserLogin(){
        $query="UPDATE users SET login_status = ?,token = ? WHERE id = ?";
        $statement=$this->connect->prepare($query);
        if($statement->execute(
            array(
                $this->user_login_status,
                $this->user_token,
                $this->user_id
            )
        )){
            return true;
        }
        else{
            return false;
        }
    }
    public function getAllUser(){
        $query = "
        SELECT * FROM users
        ";
        $statement=$this->connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
       
        return $result;
    }
    public function getUserOnline(){
        $query = "
        SELECT * FROM users WHERE login_status = login
        ";
        $statement=$this->connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }
    public function getAllUserWithStatusCount(){
        $query="
        SELECT id, name, profile, login_status, 
		(SELECT COUNT(*) FROM messages WHERE to_user_id = ? AND from_user_id = users.id AND status = 'No') 
		AS count_status FROM users
        ";
        
        $statement=$this->connect->prepare($query);
        $statement->execute(
            array(
                $this->user_id
            )
        );
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }
    public function updateUserConnectionId(){
        $query="UPDATE users SET connection_id = ? WHERE id = ?";
        $statement=$this->connect->prepare($query);
        if($statement->execute(
            array(
                $this->user_connection_id,
                $this->user_id
            )
        )){
            return true;
        }
        else{
            return false;
        }
    }

    public function getUserIdByToken(){
        $query = "
        SELECT id FROM users WHERE token = ?
        ";
        $statement=$this->connect->prepare($query);
        $statement->execute(
            array(
                $this->user_token
            )
        );
        $result = $statement->fetch();
        return $result;
    }


    

    
}

?>
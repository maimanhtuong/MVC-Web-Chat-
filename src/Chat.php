<?php

namespace MyApp;

require "../Models/user.php";
require "../Models/chatroom.php";
require "../Models/ChatPrivate.php";

use ChatPrivate;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryarray);

        if(isset($queryarray['token']))
        {

            $user_object = new \user;

            $user_object->setUserToken($queryarray['token']);

            $user_object->setConnectionId($conn->resourceId);

            $user_object->updateUserConnectionId();

            $user_data = $user_object->getUserIdByToken();
            
            $user_id = $user_data['id'];

            $data['status_type'] = 'Online';

            $data['user_id_status'] = $user_id;

            // first, you are sending to all existing users message of 'new'
            foreach ($this->clients as $client)
            {
                $client->send(json_encode($data)); //here we are sending a status-message
            }
        }

        // $user_object->setConnectionId($conn->resourceId);
        // $user_object->updateUserConnectionId();

        echo "New connection! ({$conn->resourceId})\n";
        //---------
        
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s' . "\n",
            $from->resourceId,
            $msg,
            $numRecv,
            $numRecv == 1 ? '' : 's'
        );

        $data = json_decode($msg, true);
        
        if ($data['command'] == 'private_chat') {

            //private chat
            $private_chat_object = new \ChatPrivate;
            $private_chat_object -> setToUserId($data['friend_user_id']);
            $private_chat_object -> setFromUserId($data['user_id']);
            $private_chat_object -> setMessage($data['message']);
            $private_chat_object -> setTimestamp(date('Y-m-d H:i:s'));
            $private_chat_object -> setStatus('yes');

            $message_id=$private_chat_object -> save_chat();

            
            $user_object= new \user;
            $user_object->setUserId($data['user_id']);
            $user_data = $user_object->getUserById();
            $user_object->setUserId($data['friend_user_id']);
            $friend_user_data = $user_object->getUserById();
            $user_name = $user_data['name'];
            $data['datetime'] = date('Y-m-d H:i:s');
            $friend_user_connection_id = $friend_user_data['connection_id'];

            foreach($this->clients as $client)
            {
             //   $client->send(json_encode($data));
              echo 'resourceId'.$client->resourceId;
              echo '<br>';
              echo 'friend_user_connection_id'.$friend_user_connection_id;
                if($from == $client)
                {
                    $data['from'] = 'Me';
                }
                else
                {
                    $data['from'] = $user_name;
                }

                $client->send(json_encode($data));
                
                
                
            }



        } else {
            //chat room
            $user_object = new \chatroom;
            $user_object->setChatRoomIdUser($data['user_id']);
            $user_object->setChatRoomMessage($data['message']);
            $user_object->setChatRoomCreatedOn($data['datetime']);
            $user_object->save_chat();
            $user_object = new \user();
            $user_object->setUserId($data['user_id']);
            $user_object->setUserName($data['user_name']);



            foreach ($this->clients as $client) {

                if ($from == $client) {
                    $data['from'] = 'Me';

                } else {
                    $data['from'] = $data['user_name'];

                }
                
                $client->send(json_encode($data));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

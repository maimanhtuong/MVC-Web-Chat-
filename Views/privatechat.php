<?php

//privatechat.php
if (!isset($_SESSION['user'])) {
	header('location:index.php');
} else {
	require_once('Models/user.php');
}


?>

<!DOCTYPE html>
<html>

<head>
	<title>Chat application in php using web scocket programming</title>
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<style type="text/css">
		html,
		body {
			height: 100%;
			width: 100%;
			margin: 0;
		}

		#wrapper {
			display: flex;
			flex-flow: column;
			height: 100%;
		}

		#remaining {
			flex-grow: 1;
		}

		#messages {
			height: 200px;
			background: whitesmoke;
			overflow: auto;
		}

		#chat-room-frm {
			margin-top: 10px;
		}

		#user_list {
			height: 450px;
			overflow-y: auto;
		}

		#messages_area {
			height: 75vh;
			overflow-y: auto;
			/*background-color:#e6e6e6;*/
			/*background-color: #EDE6DE;*/
		}
	</style>
</head>

<body>
	<div class="container-fluid">

		<div class="row">

			<div class="col-lg-3 col-md-4 col-sm-5" style="background-color: #f1f1f1; height: 100vh; border-right:1px solid #ccc;">

				<input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $_SESSION['user']['id']; ?>" />

				<input type="hidden" name="is_active_chat" id="is_active_chat" value="No" />

				<div class="mt-3 mb-3 text-center">
					<img src="content/images/<?php echo $_SESSION['user']['profile']; ?>" class="img-fluid rounded-circle img-thumbnail" width="150" />
					<h3 class="mt-2"><?php echo $_SESSION['user']['name']; ?></h3>
					<a href="profile.php" class="btn btn-secondary mt-2 mb-2">Edit</a>
					<input type="button" class="btn btn-primary mt-2 mb-2" id="logout" name="logout" value="Logout" />
				</div>

				<div class="list-group" style=" max-height: 100vh; margin-bottom: 10px; overflow-y:scroll; -webkit-overflow-scrolling: touch;">
					<?php
					$user_object = new user;
					$user_object->setUserId($_SESSION['user']['id']);
					$user_friend_data = $user_object->getAllUserWithStatusCount();
					foreach ($user_friend_data as $user_friend) {
						$icon = '<i class="fa fa-circle text-danger"></i>';
						if ($user_friend['login_status'] == 'login') {
							$icon = '<i class="fa fa-circle text-success"></i>';
						}
						if ($_SESSION['user']['id'] != $user_friend['id']) {
							if ($user_friend['count_status'] > 0) {
								$total_unread_message = '<span id="unread" class="badge badge-danger badge-pill">' . $user_friend['count_status'] . '</span>';
							
							} else {
							
								$total_unread_message = '';
							}
							echo "
							<a class='list-group-item list-group-item-action select_user ' style='cursor:pointer' data-friend_user_id = '" . $user_friend['id'] . "'>
								<img src='content/images/" . $user_friend["profile"] . "' class='img-fluid rounded-circle img-thumbnail' width='50' />
								<span class='ml-1'>
									<strong>
										<span id='list_user_name_" . $user_friend["id"] . "'>" . $user_friend['name'] . "</span>
										<span id='friend_id" .$user_friend['id'] . "' value='".$user_friend['id']."'>" . $total_unread_message . "</span>
									</strong>
								</span>
								<span class='mt-2 float-right' id='users_status" . $user_friend['id'] . "'>" . $icon . "</span>
							</a>
							";
						}
					}
					?>
				</div>
			</div>

			<div class="col-lg-9 col-md-8 col-sm-7">
				<br />
				<h3 class="text-center">Realtime One to One Chat App using Ratchet WebSockets with PHP Mysql - Online Offline Status - 8</h3>
				<hr />
				<br />
				<div id="chat_area"></div>
			</div>

		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		var friend_user_id = $('#login_user_id').val();
		var conn = new WebSocket('ws://localhost:8080');
		conn.onopen = function(e) {
			console.log("Connection established!");
		};

		conn.onmessage = function(event) {
			//lấy dữ liệu từ ratchet server
			var data = (event.data);

			data = JSON.parse(data);
			console.log(data);
			
			var row_class = "";
			var background_class = "";
			if (data.from == 'Me') {
				row_class = 'row justify-content-start';
				background_class = 'alert-primary';
			} else {
				row_class = 'row justify-content-end';
				background_class = 'alert-success';
			}

			if (friend_user_id == data.friend_user_id || data.from == 'Me') {
				if ($('#is_active_chat').val() == 'Yes') {
					var html_data = `
						<div class="` + row_class + `">
							<div class="col-sm-10">
								<div class="shadow-sm alert ` + background_class + `">
									<b>` + data.from + ` - </b>` + data.message + `<br />
									<div class="text-right">
										<small><i>` + data.datetime + `</i></small>
									</div>
								</div>
							</div>
						</div>
						`;
	
					$('#messages_area').append(html_data);
	
					$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight);
	
					$('#chat_message').val("");

				}
			
				
			}
			 
			if($('#chat_user_name')!=data.from){
			var count_chat = $('#unread').text();
			if (count_chat == '') {
				count_chat = 0;
			}
			count_chat = parseInt(count_chat);
			count_chat = count_chat + 1;
			
			$('#unread').text(count_chat);
			$('#unread').show();
			}
			


			
				
			
		}
	

		


		//Hàm tạo và tải tin nhắn xuống hiển thị từ server mysql và nằm trong ($(document).on('click', '.select_user', function() {..})
		function makeChatBox(friend_user_name, friend_user_id) {
			var html_data = `
		<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col col-sm-6">
							<b>Chat with <span class="text-danger" id="chat_user_name">` + friend_user_name + `</span></b>

							</div>
						<div class="col col-sm-6 text-right">
							<a href="chatroom.php" class="btn btn-success btn-sm">Group Chat</a>&nbsp;&nbsp;&nbsp;
							<button type="button" class="close" id="close_chat_area" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</div>
				<div class="card-body" id="messages_area">

				</div>
			</div>

			<form id="chat_form" method="POST" data-parsley-errors-container="#validation_error">
			<input type="hidden" id="chat_user_id" value="` + friend_user_id + `">

				<div class="input-group mb-3" style="height:7vh">
					<textarea class="form-control" id="chat_message" name="chat_message" placeholder="Type Message Here" data-parsley-maxlength="1000"  required></textarea>
					<div class="input-group-append">
						<button type="submit" name="send" id="send" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
					</div>
				</div>
				<div id="validation_error"></div>
				<br />
			</form>
		`;
			$('#chat_area').html(html_data);

			$('#chat_form').parsley();
		}

		$(document).on('click', '.select_user', function() {
			var friend_user_id = $(this).data('friend_user_id');
			var friend_user_name = $('#list_user_name_' + friend_user_id).text();

			var user_id = $('#login_user_id').val();

			$('.select_user.active').removeClass('active');
			$(this).addClass('active');
			makeChatBox(friend_user_name, friend_user_id);
			$('#is_active_chat').val('Yes');

			//gửi dữ liệu Post lên sever mysql để cập nhật status của tin nhắn đã đọc
			$.ajax({
				url: "action.php",
				method: "POST",
				data: {
					action: 'fetch_chat',
					to_user_id: friend_user_id,
					from_user_id: user_id
				},
				dataType: "json",

				//nhận dữ liệu từ server ratchet gửi xuống và hiển thị lên
				success: function(data) {
					if (data.length > 0) {
						var html_data = '';
						for (var i = 0; i < data.length; i++) {
							var row_class = '';
							var background_class = '';
							var user_name = '';

							if (data[i].from_user_id == user_id) {
								row_class = 'row justify-content-start';
								background_class = 'alert-primary';
								user_name = 'Me';
							} else {
								row_class = 'row justify-content-end';
								background_class = 'alert-success';
								user_name = data[i].from_user_name;
							}
							html_data += `
							<div class="` + row_class + `">
								<div class="col-sm-10">
									<div class="shadow alert ` + background_class + `">
										<b>` + user_name + ` - </b>
										` + data[i].message + `<br />
										<div class="text-right">
											<small><i>` + data[i].timestamp + `</i></small>
										</div>
									</div>
								</div>
							</div>
							`;
						}
					}
					$('#friend_id' + friend_user_id).html(0);
					$('#messages_area').html(html_data);
					$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight);
				}

			})

		})

		//Hàm gửi tin nhắn phía client lên server ratchet
		$(document).on('submit', '#chat_form', function(event) {
			event.preventDefault();
			if ($('#chat_form').parsley().isValid()) {
				var user_id = $('#login_user_id').val();
				var message = $('#chat_message').val();
				var friend_user_id = $(chat_user_id).val();
				var data = {
					user_id: user_id,
					message: message,
					friend_user_id: friend_user_id,
					command: 'private_chat'
				}
				conn.send(JSON.stringify(data));
			}
		})

		$(document).on('click', '#close_chat_area', function(){

$('#chat_area').html('');

$('.select_user.active').removeClass('active');

$('#is_active_chat').val('No');

receiver_userid = '';

});
	})
</script>

</html>
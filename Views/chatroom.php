<?php
	
	
	
	$user_object = new user();
	$user_data = $user_object->getAllUser();
	$chat_object = new chatroom();
	$chat_data = $chat_object->getAllChat();
?>


<div>
	<div class="container">
		<br />
        <h3 class="text-center">Realtime One to One Chat App using Ratchet WebSockets with PHP Mysql - Online Offline Status - 8</h3>
        <br />
		<div class="row">
			
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col col-sm-6">
								<h3>Chat Room</h3>
							</div>
							<div class="col col-sm-6 text-right">
								<a href="index.php?controller=privatechat" class="btn btn-success btn-sm">Private Chat</a>
							</div>
						</div>
					</div>
					<div class="card-body" id="messages_area">
						<?php
						
					foreach($chat_data as $chat)
					{	
						if($_SESSION['user']['id']==$chat['user_id'])
						{	
							$from = 'Me';
							$row_class = 'row justify-content-start';
							$background_class = 'text-dark alert-light';
						}
						else
						{	
							$from = $chat['name'];
							$row_class = 'row justify-content-end';
							$background_class = 'alert-success';
						}

						echo '
						<div class="'.$row_class.'">
						<img src="./content/images/'.$chat['profile'].'" width="30" height="30" class="img-fluid rounded-circle "></img>
							<div class="col-sm-10">
								<div class="shadow-sm alert '.$background_class.'">
									<b>'.$from.' - </b>'.$chat["message"].'
									<br />
									<div class="text-right">
										<small><i>'.$chat["created_on"].'</i></small>
									</div>
								</div>
							</div>
						</div>
						';
					}
						?>
					</div>
				</div>

				<form method="post" id="chat_form" data-parsley-errors-container="#validation_error">
					<div class="input-group mb-3">
						<textarea class="form-control" id="chat_message" name="chat_message" placeholder="Type Message Here" data-parsley-maxlength="1000" data-parsley-pattern="/^[a-zA-Z0-9\s]+$/" required></textarea>
						<div class="input-group-append">
							<button type="submit" name="send" id="send" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
						</div>
					</div>
					<div id="validation_error"></div>
				</form>
			</div>
			<div class="col-lg-4">
				
			<?php
				$login_user_id = $_SESSION['user']['id'];
				$login_user_name = $_SESSION['user']['name'];
				

			?>
				<div class="mt-3 mb-3 text-center">
					<input type="hidden" id="login_user_profile" value="<?php echo $_SESSION['user']['profile']?>">
					<img src="./content/images/<?php echo $_SESSION['user']['profile']?>" width="150"  class="img-fluid rounded-circle img-thumbnail" />
					<h3 class="mt-2"><?php echo $_SESSION['user']['name']; ?></h3>
					<input type="hidden" name="login_user_name" id="login_user_name" value="<?php echo $login_user_name?>">
					<input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $login_user_id; ?>" ></input>
					<a href="index.php?controller=profile" class="btn btn-secondary mt-2 mb-2">Edit</a>
					<a type="button" href="index.php?controller=chatroom&action=logout" class="btn btn-primary mt-2 mb-2" name="logout" id="logout" >Logout</a>
				</div>
				
				<div class="card mt-3">
					
					<div class="card-header">User List</div>
					<div class="card-body" id="user_list">
						<div class="list-group list-group-flush">
						<?php
							if(count($user_data)>0){
								foreach($user_data as $user){

									$icon='<i class="fa fa-circle text-danger"></i>';
									if($user['login_status']=='login'){
										$icon='<i class="fa fa-circle text-success"></i>';
									}else{
										$icon='<i class="fa fa-circle text-danger"></i>';
									}
									
									if($user['id']!==$login_user_id){
										echo '
									<a class="list-group-item list-group-item-action">
										<img src="./content/images/'.$user["profile"].'" class="img-fluid rounded-circle img-thumbnail" width="50" height="50" />
										<span class="ml-1"><strong>'.$user["name"].'</strong></span>
										<span class="mt-2 float-right">'.$icon.'</span>
									</a>
									';
									}
							}
						}
						?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	
	$(document).ready(function(){

		var conn = new WebSocket('ws://localhost:8080');
		conn.onopen = function(e) {
		    console.log("Connection established!");
		};

		conn.onmessage = function(e) {

		    //lấy dữ liệu ratchet server 
			var data = JSON.parse(e.data);
			console.log(data);
			var row_class = '';

		    var background_class = '';

		    if(data.from == 'Me')
		    {
		    	row_class = 'row justify-content-start';
		    	background_class = 'text-dark alert-light';
		    }
		    else
		    {
		    	row_class = 'row justify-content-end';
		    	background_class = 'alert-success';
		    }

		    //var html_data = "<div class='"+row_class+"'><div class='col-sm-10'><div class='shadow-sm alert "+background_class+"'><b>"+data.from+" - </b>"+data.message+"<br /><div class='text-right'><small><i>"+data.dt+"</i></small></div></div></div></div>";
			var html_data = "";
			html_data += "<div class='"+row_class+"'>";
			html_data += "<img src='./content/images/"+data.user_profile+"' width='50' height='30' class='img-fluid rounded-circle '></img>"
			html_data += "<div class='col-sm-5'>";
			html_data += "<div class='shadow-sm alert "+background_class+"'>";
			html_data += "<b>"+data.from+" - </b>";
			html_data += data.message;
			html_data += "<br />";
			html_data += "<div class='text-right'>";
			
			html_data += "<small><i>"+data.datetime+"</i></small>";
			html_data += "</div>";
			html_data += "</div>";
			html_data += "</div>";
			html_data += "</div>";

			$('#messages_area').append(html_data);
			$('#chat_message').val('');
			

		};

		$('#chat_form').parsley();
		$('#chat_form').on('submit', function(e){
			e.preventDefault();
			if($('#chat_form').parsley().isValid())
			{

				var user_id = $('#login_user_id').val();
				var user_profile= $('#login_user_profile').val();
				

				
				var datetime= new Date();
				var dt = datetime.getFullYear()+'-'+(datetime.getMonth()+1)+'-'+datetime.getDate()+' '+datetime.getHours()+':'+datetime.getMinutes()+':'+datetime.getSeconds();
				var message = $('#chat_message').val();
				var user_name= $('#login_user_name').val();
				var data = {
					user_id : user_id,
					message : message,
					user_name:user_name,
					datetime : dt,
					user_profile:user_profile

				};

				conn.send(JSON.stringify(data));
				$('#chat_message').val('');




			}
		}
		);

	

	});
	
</script>
</html>
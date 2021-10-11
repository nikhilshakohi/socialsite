<?php 

include 'header.php';
if (empty($_SESSION['id'])) {	//To logout when no session
	header('Location: index.php'); exit();
}


$userId=$_SESSION['id'];
$currentUsername=getUserNameFromId($conn,$userId);
if(isset($_SESSION['id'])){
	echo'<div id="pageLoader" class="loaderButton loaderButtonBig"></div>';
	echo'<div id="content" style="display:none">';	
		
		/*Friends List Side*/
		/*Check for Messages*/
		$checkCurrentUserMessageCount=mysqli_query($conn,"SELECT * FROM messages WHERE currentUserId='$userId' OR friendUserId='$userId'");
		$totalCurrentUserMessageCount=mysqli_num_rows($checkCurrentUserMessageCount);
		echo'<span id="newMessageCount" style="display:none">'.$totalCurrentUserMessageCount.'</span>';
		echo'<div id="contentLeftPart">
			<h3>
				Chats <span id="headerNewMsg" class="newMsgTag" style="font-size: small;font-weight:100;';
				/*Check new messages for user in new tag at header*/
				$checkUnreadStatusOfUser=mysqli_query($conn,"SELECT * FROM messages WHERE friendUserId='$userId' AND readStatus='0'");
				if(mysqli_num_rows($checkUnreadStatusOfUser)>0){
					echo' display:inline-block';
				}else{
					echo' display:none ';
				}	
			echo'" >New</span></h3><hr>';
			$checkFriends=mysqli_query($conn,"SELECT * FROM connections WHERE (userB='$currentUsername' OR userA='$currentUsername') AND connectionStatus='2' ORDER BY id ASC");
			echo'<div>';
				
			if(mysqli_num_rows($checkFriends)>0){
				while($rowFriends=mysqli_fetch_assoc($checkFriends)){
					if($rowFriends['userA']==$currentUsername){
						echo'<div id="selectFriendButton'.getUserIdFromUsername($conn,$rowFriends['userB']).'">';
					}else if($rowFriends['userB']==$currentUsername){
						echo'<div id="selectFriendButton'.getUserIdFromUsername($conn,$rowFriends['userA']).'">';
					}


						/*Dummy button for script having username button*/
						echo'<button class="friendLinkDiv" name="getFriendMessages" type="button" id="selectFriendButton'.$currentUsername.'" style="display:none">';
						echo'<button class="friendLinkDiv" name="getFriendMessages" type="button"'; 
						if($rowFriends['userA']==$currentUsername){
							echo'  onclick="checkMessages(\''.$currentUsername.'\',\''.$rowFriends['userB'].'\')" 
							 id="selectFriendButton'.$rowFriends['userB'].'">';
							echo '<input type="hidden" name="friendUsername" value="'.$rowFriends['userB'].'">
							<img class="profilePic" style="margin:0 10px" src="'.getProfilePictureLocation($conn,$rowFriends['userB']).'">'.getFriendsFullName($rowFriends['userB'],$conn);
							echo'<div id="'.$rowFriends['userB'].'Messageloader"></div>';
							/*Check Unread Status*/
							$friendUsernameId=getUserIdFromUsername($conn,$rowFriends['userB']);
							$checkUnreadStatus=mysqli_query($conn,"SELECT * FROM messages WHERE currentUserId='$friendUsernameId' AND friendUserId='$userId' AND readStatus='0'");
							/*Display new message*/
							echo'<small id="'.$rowFriends['userB'].'newMsgTag'.$currentUsername.'" class="newMsgTag"';
							if(mysqli_num_rows($checkUnreadStatus)>0){
								echo' style="display:block"';
							}else{echo'style="display:none"';}
							echo'>New Message</small>';
						}
						elseif($rowFriends['userB']==$currentUsername){
							echo'  onclick="checkMessages(\''.$currentUsername.'\',\''.$rowFriends['userA'].'\')" 
							 id="selectFriendButton'.$rowFriends['userA'].'">';
							echo '<input type="hidden" name="friendUsername" value="'.$rowFriends['userA'].'">
							<img class="profilePic" style="margin:0 10px" src="'.getProfilePictureLocation($conn,$rowFriends['userA']).'">'.getFriendsFullName($rowFriends['userA'],$conn);
							echo'<div id="'.$rowFriends['userA'].'Messageloader"></div>';
							/*Check Unread Status*/
							$friendUsernameId=getUserIdFromUsername($conn,$rowFriends['userA']);
							$checkUnreadStatus=mysqli_query($conn,"SELECT * FROM messages WHERE currentUserId='$friendUsernameId' AND friendUserId='$userId' AND readStatus='0'");
							/*Display new message*/
							echo'<small id="'.$rowFriends['userA'].'newMsgTag'.$currentUsername.'" class="newMsgTag"';
							if(mysqli_num_rows($checkUnreadStatus)>0){
								echo' style="display:block"';
							}else{echo'style="display:none"';}
							echo'>New Message</small>';
						}
						/*Current Username to js*/
						echo'<span id="currentUsernameDetails" style="display:none">'.$currentUsername.'</span>';
						echo'</button>
					</div>';
				}
			}else{
				echo'<div>No friends added yet..</div><br>
				<form method="POST" action="search.php">
					<input type="hidden" name="query" value="">
					<input class="coolButton" type="submit" name="search" value="Find Friends">
				</form>';
			}
			echo'</div>';
		echo'</div>';

		/*To get messages from selected friend*/
		echo'<div id="contentRightPart">
			<h1>Select a chat</h1>	
		</div>';
		
	echo'</div>';		

	?><script>
		
		setInterval(checkForNewMessages,2000,<?php echo $userId ?>,<?php echo $totalCurrentUserMessageCount ?>,<?php $currentUsername ?>);
		
		function checkForNewMessages(userId){
			var currentUserId=userId;
			var messageCount=document.getElementById("newMessageCount").innerHTML;
			var currentUserName=document.getElementById("currentUsernameDetails").innerHTML;
			
			//Declare XML Request
			/*Check new Messages for header Tag*/
			if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhrForTag = new XMLHttpRequest();} 
			else if (window.ActiveXObject) { /*IE 8 and older*/ var xhrForTag = new ActiveXObject("Microsoft.XMLHTTP");}
			var checkForNewMessagesHeaderTag="DummyText";//Declaring variables for POST Submit
			var data = "checkForNewMessagesHeaderTag="+checkForNewMessagesHeaderTag+"&currentUserId="+currentUserId;	
			xhrForTag.open("POST", "conditions.php", true);
			xhrForTag.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhrForTag.send(data);
			xhrForTag.onreadystatechange = function(){
				if(xhrForTag.readyState == 4) {
			      if (xhrForTag.status == 200) {
			      	if(this.responseText=='new'){
			      		document.getElementById("headerNewMsg").style.display="inline-block";
			      	}else if(this.responseText=='old'){
			      		document.getElementById("headerNewMsg").style.display="none";
			      	}
			      } else {
			          alert("There was a problem with the request.");
			      }
			    }
			}
			

			if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
			else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
			var checkForNewMessages="DummyText";//Declaring variables for POST Submit
			var data = "checkForNewMessages="+checkForNewMessages+"&currentUserId="+currentUserId+"&messageCount="+messageCount;	
			xhr.open("POST", "conditions.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(data);
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4) {
			      if (xhr.status == 200) {
			      	eachResponse=this.responseText.split("-period-");
			      	document.getElementById(eachResponse[0]+"newMsgTag"+currentUserName).style.display="block";
			      } else {
			          alert("There was a problem with the request.");
			      }
			    }
			}
		}
	</script><?php
}

include 'footer.php';
?>
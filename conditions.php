<?php 

include 'db.php';
session_start();

//CheckSignup
if(isset($_POST['signupCheck'])){
	$username=mysqli_real_escape_string($conn,$_POST['username']);
	$mobile=mysqli_real_escape_string($conn,$_POST['mobile']);
	$email=mysqli_real_escape_string($conn,$_POST['email']);
	$firstName=mysqli_real_escape_string($conn,$_POST['firstName']);
	$lastName=mysqli_real_escape_string($conn,$_POST['lastName']);
	$password=mysqli_real_escape_string($conn,$_POST['password']);
	$checkUserAvail=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' OR email='$email'");
	if(mysqli_num_rows($checkUserAvail)<1){
		mysqli_query($conn,"INSERT INTO users (username, email, phone, firstName, lastName, password) 
		VALUES ('$username', '$email', '$mobile', '$firstName', '$lastName', '$password')");
		echo'<span class="successMessage">User Added Successfully..</span><div class = "loaderButton"></div>
		-period-success';
	}else{
		echo'<span class="errorMessage">Username/E-mail already registered</span>
		-period-error';
	}
}

//Check Login
if(isset($_POST['loginCheck'])){
	$username=mysqli_real_escape_string($conn,$_POST['username']);
	$password=mysqli_real_escape_string($conn,$_POST['password']);
	$checkUser=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' OR email='$username'");
	if(mysqli_num_rows($checkUser)>0){
		while($rowUser=mysqli_fetch_assoc($checkUser)){
			if($password==$rowUser['password']){
				$_SESSION['id']=$rowUser['id'];
				echo'LoginSuccess
				-period-success';
			}else{
				echo'<span class="errorMessage">Incorrect username or password</span>
				-period-error';
			}
		}
	}else{
		echo'<span id="signupPromoMessage" class="infoMessage">No Users Found.. <a class="miniButtons" href="#signupForm"'; ?> onclick='document.getElementById("signupPromoMessage").style.display="none"' <?php echo'>Sign Up</a> to Create an Account</span><br>';
	}
}


//like Button
if(isset($_POST['likeSubmit'])){
	$postId=$_POST['postId'];
	$userId=$_POST['userId'];
	$userFullName=$_POST['userFullName'];
	mysqli_query($conn,"INSERT INTO likes (postId, userId, userFullName) VALUES ('$postId', '$userId', '$userFullName')");
	$checkLikes=mysqli_query($conn,"SELECT * FROM likes WHERE postId='$postId' ORDER BY id ASC");
	$likesCount=mysqli_num_rows($checkLikes);
	if($likesCount>=1){echo $likesCount.' Likes';}
	else{echo '';}
	echo'-period-';
	if(mysqli_num_rows($checkLikes)>0){
		while($rowLikesDetail=mysqli_fetch_assoc($checkLikes)){
			echo'<form target="_blank" class="accountLinkForm" method="GET" action="userprofile.php">
				<input type="hidden" name="userName" value="'.getUserNameFromIdInConditionsPage($conn,$rowLikesDetail['userId']).'">
				<button type="submit" class="likeUserDetails">'.$rowLikesDetail['userFullName'].'</button>
			</form><br>';
		}
	}else{echo'';}
	exit();	
}

function getUserNameFromIdInConditionsPage($conn,$userId){
	$checkUserFromUserId=mysqli_query($conn,"SELECT * FROM users WHERE id='$userId' LIMIT 1");
	if(mysqli_num_rows($checkUserFromUserId)>0){
		while($rowUserFromUserId=mysqli_fetch_assoc($checkUserFromUserId)){
			$username=$rowUserFromUserId['username'];
		}
	}
	return $username;
}
/*Get UserId from UserName*/
function getUserIdFromUsernameInConditionsPage($conn,$username){
	$checkUserFromUsername=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' LIMIT 1");
	$userId='';
	if(mysqli_num_rows($checkUserFromUsername)>0){
		while($rowUserFromUsername=mysqli_fetch_assoc($checkUserFromUsername)){
			$userId=$rowUserFromUsername['id'];
		}
	}
	return $userId;
}

//UnLike Button
if(isset($_POST['unLikeSubmit'])){
	$postId=$_POST['postId'];
	$userId=$_POST['userId'];
	$userFullName=$_POST['userFullName'];
	mysqli_query($conn,"DELETE FROM likes WHERE (userId='$userId') AND (postId='$postId')");
	$checkLikes=mysqli_query($conn,"SELECT * FROM likes WHERE postId='$postId'");
	$likesCount=mysqli_num_rows($checkLikes);
	if($likesCount>=1){echo $likesCount.' Likes';}
	else{echo '';}
	echo'-period-';
	if(mysqli_num_rows($checkLikes)>0){
		while($rowLikesDetail=mysqli_fetch_assoc($checkLikes)){
			echo'<form target="_blank" class="accountLinkForm" method="GET" action="userprofile.php">
				<input type="hidden" name="userName" value="'.getUserNameFromIdInConditionsPage($conn,$rowLikesDetail['userId']).'">
				<button type="submit" class="likeUserDetails">'.$rowLikesDetail['userFullName'].'</button>
			</form><br>';
		}
	}else{echo'';}
	exit();
}

//Add Connection 
if(isset($_POST['addConnection'])){
	$userA=$_POST['userA'];
	$userB=$_POST['userB'];
	$connectionStatus=$_POST['connectionStatus'];
	$checkConnectionReloadStatus=mysqli_query($conn,"SELECT * FROM connections WHERE connectionStatus='1' AND userA='$userA' AND userB='$userB'");
	if(mysqli_num_rows($checkConnectionReloadStatus)>0){
	}else{
		$addConnection=mysqli_query($conn,"INSERT INTO connections (userA, userB, connectionStatus) VALUES ('$userA', '$userB', '$connectionStatus')");
	}
	exit();
}

//Update Connection
if(isset($_POST['updateConnection'])){
	$connectionId=$_POST['connectionId'];
	$connectionStatus=$_POST['connectionStatus'];
	$userA=$_POST['userA'];
	$userB=$_POST['userB'];
	mysqli_query($conn,"UPDATE connections SET connectionStatus='$connectionStatus' WHERE id='$connectionId'");
	/*AJAX Inputs*/
	$friendsArray=$_SESSION['friendsArray'];
	array_push($friendsArray, $userB);
	echo'<form method="GET" action="userprofile.php" style="display:flex;">
		<button class="friendLinkDiv" type="submit">
			<img class="profilePic" style="margin:0 10px" src="'.getProfilePictureLocationInConditionsPage($conn,$userB).'">'.getFriendsFullNameInConditionsPage($userB,$conn).'
		</button>
		<input type="hidden" name="userName" value="'.$userB.'">
	</form>';
	exit();
}

//Add Comments
if(isset($_POST['addComment'])){
	$postId=$_POST['postId'];
	$userId=$_POST['userId'];
	$userFullName=$_POST['userFullName'];
	$userComment=mysqli_real_escape_string($conn,$_POST['userComment']);
	date_default_timezone_set('Asia/Kolkata');
	$commentDateTime=date('Y-m-d H:i:s');
	mysqli_query($conn,"INSERT INTO comments (postId, userId, userFullName, comment, commentDateTime) VALUES ('$postId', '$userId', '$userFullName', '$userComment', '$commentDateTime')");
	$checkComments=mysqli_query($conn,"SELECT * FROM comments WHERE postId='$postId'");
	$newCommentCount=mysqli_num_rows($checkComments);
	if($newCommentCount>0){
		while($rowCommentId=mysqli_fetch_assoc($checkComments)){
			$newCommentId=$rowCommentId['id'];
			$newComment=$rowCommentId['comment'];
		}
	}
	echo $newCommentCount.' Comments-period-
	<div id="commentDivOf'.$newCommentId.'" title="Comment added on '.date('d-M-y h:ia',strtotime($commentDateTime)).'">
	<strong>'.$userFullName.'</strong>
	<span id="postComment'.$newCommentId.'">'.$newComment.'</span>';
	/*Delete or Edit Comment*/
	echo'<div id="menuIconForCommentOf'.$newCommentId.'" class="hamburgerMenuIcon hamburgerCommentsMenuIcon" onclick="showMenuOptionsOfComment('.$newCommentId.')" style="display:inline-flex">
		<div class="dotA"></div>
		<div class="dotB"></div>
		<div class="dotC"></div>
	</div>
	<span id="menuOptionsForCommentOf'.$newCommentId.'" class="menuOptionsOfComments" style="display:none">
		<div title="Edit Comment?" id="'.$newCommentId.'commentEditButton" class="likeUserDetails" onclick="showCommentEditDiv(\''.$newCommentId.'\',\''.$newComment.'\')">edit</div>
		<div title="Delete the Comment?" id="'.$newCommentId.'commentDeleteButton" class="likeUserDetails" onclick="showCommentDeleteDiv(\''.$newCommentId.'\')">delete</div>
	</span>';

	echo'</div>
	';
	exit();
}
$asd='asd';

//Post with Only Caption and no Image
if(isset($_POST['uploadPostOnlyCaption'])){
	$username=$_POST['username'];
	$caption=$_POST['caption'];
	$type=$_POST['type'];
	$postPrivacy=$_POST['postPrivacy'];
	$photo='';
	date_default_timezone_set('Asia/Kolkata');
	$dateUploaded=date('Y-m-d H:i:s');
	mysqli_query($conn,"INSERT INTO posts (username,caption,type,dateTimeUploaded,photo,privacy) VALUES ('$username','$caption','$type','$dateUploaded','$photo','$postPrivacy')");
	$checkPostUpdated=mysqli_query($conn,"SELECT * FROM posts WHERE username='$username' ORDER BY id DESC LIMIT 1");
	if(mysqli_num_rows($checkPostUpdated)>0){
		while($rowPostUpdated=mysqli_fetch_assoc($checkPostUpdated)){
			$postId=$rowPostUpdated['id'];/*For Further usage*/
		}
	}
	$userId=$_SESSION['id'];
	echo'<div id="postDivOf'.$postId.'" class="postDiv">';
		echo '<div class="postHeading">
			<div style="display:flex">';	
				getProfilePictureInConditionsPage($conn,$username);
				echo '<div class="postProfileDetails">'.getFriendsFullNameInConditionsPage($username,$conn);
				echo'<div class="postDate" title="Posted on '.date('d-M-Y H:i a, D',strtotime($dateUploaded)).'">'.date('d-M H:i',strtotime($dateUploaded));
					echo'<span id = "postPrivacyShow'.$postId.'">';/*For after editing update*/
					if($postPrivacy=='public'){
						echo '<span title="This Post is visible to anyone" class = "postPrivacyStyle">&nbsp;&nbsp;&#127758;</span>';
					}else if($postPrivacy=='private'){
						echo '<span title="This Post is visible to only you" class = "postPrivacyStyle">&nbsp;&nbsp;&#128274;</span>';
					}else if($postPrivacy=='friends'){
						echo '<span title="This Post is visible to only your friends" class = "postPrivacyStyle">&nbsp;&nbsp;&#128101;</span>';
					}
					echo'</span>';
				echo'</div></div>
			</div>
			<div class="postMenuDiv">';
				echo'<div id="menuIconFor'.$postId.'" class="hamburgerMenuIcon" onclick="showMenuOptions('.$postId.')">
					<div class="dotA"></div>
					<div class="dotB"></div>
					<div class="dotC"></div>
				</div>
				<div id="menuOptionsFor'.$postId.'" class="menuOptions" style="display:none">
					<div id="'.$postId.'EditButton" class="likeUserDetails" onclick="showPostEditDiv(\''.$postId.'\')">edit</div>
					<div id="'.$postId.'DeleteButton" class="likeUserDetails" onclick="showPostDeleteDiv(\''.$postId.'\')">delete</div>
					<div id="'.$postId.'PrivacyButton" class="likeUserDetails" onclick="showPostPrivacyDiv(\''.$postId.'\')">privacy</div>
				</div>';
			echo'</div>
		</div>';
		echo'<div id="postCaption'.$postId.'" class="PostCaption">'.$caption.'</div>';
		echo'<div class="postActionsDetails">';
			$likesCount=getLikesCountInConditionsPage($conn,$postId);
			$commentsCount=getCommentsCountInConditionsPage($conn,$postId);
			if($likesCount>0){echo'<span id="postLikesCount'.$postId.'" class="postLikesDetails">'.getLikesCountInConditionsPage($conn,$postId).' Likes </span>';}
			else{
				echo'<span id="postLikesCount'.$postId.'" class="postLikesDetails"></span>';
			}
			if($commentsCount>0){echo'<span id="postCommentsCount'.$postId.'" class="postCommentsDetails">'.getCommentsCountInConditionsPage($conn,$postId).' Comments</span>';}
			else{
				echo'<span id="postCommentsCount'.$postId.'" class="postCommentsDetails"></span>';
			}
		echo'</div>
		<div class="postActions">';
			likeActionButtonInConditionsPage($conn,$postId,$userId,$username);
			$showComments=1;
			commentActionButtonInConditionsPage($conn,$postId,$userId,$username,$showComments);
		echo'</div>
	</div>';
}

//Change ProfilePicture
if(isset($_POST['uploadPost'])){
	$username=$_POST['username'];
	$caption=$_POST['caption'];
	$type=$_POST['type'];
	$postPrivacy=$_POST['postPrivacy'];
	$_SESSION['profilePicUsername']=$username;
	$_SESSION['profilePicCaption']=$caption;
	$_SESSION['profilePicType']=$type;
}

if(!empty($_FILES['postUploadFile'] ?? null)) {
	$username=$_SESSION['profilePicUsername'];
	$caption=$_SESSION['profilePicCaption'];
	$type=$_SESSION['profilePicType'];
	if(!empty($_FILES['postUploadFile'])){
		if($type=='profilePicture'){
			if(!file_exists("posts/".$username."/profilePictures")){	//To check if folder is already present when uploaded new file
				mkdir("posts/".$username."/profilePictures", 0777, true);//2nd argument is 0777 is accessibility permission,true is used to add subdirectories 
			}
		}else{
			if(!file_exists("posts/".$username."/posts")){	//To check if folder is already present when uploaded new file
				mkdir("posts/".$username."/posts", 0777, true);//2nd argument is 0777 is accessibility permission,true is used to add subdirectories 
			}
		}
		date_default_timezone_set('Asia/Kolkata');
		$dateUploaded=date('Y-m-d H:i:s');
		if($type=='profilePicture'){
			$path="posts/".$username."/profilePictures/".basename($_FILES['postUploadFile']['name']);
		}else{
			$path="posts/".$username."/posts/".basename($_FILES['postUploadFile']['name']);
		}
		if(move_uploaded_file($_FILES['postUploadFile']['tmp_name'], $path)){
			mysqli_query($conn,"INSERT INTO posts (username, photo, caption, type, dateTimeUploaded,privacy) VALUES ('$username', '$path', '$caption', '$type', '$dateUploaded','$postPrivacy')");
			/*Get ProfilePic Location*/
			$checkPostProfilePic=mysqli_query($conn,"SELECT * FROM posts WHERE username='$username' ORDER BY id DESC LIMIT 1");
			if(mysqli_num_rows($checkPostProfilePic)>0){
				while($rowPostProfilePicture=mysqli_fetch_assoc($checkPostProfilePic)){
					$profilePicLocation=$rowPostProfilePicture['photo'];
					$postId=$rowPostProfilePicture['id'];/*For Further usage*/
				}
			}else{
				$profilePicLocation='posts/basics/profileDefault.jpg';
			}
			echo $profilePicLocation.'-period-';

			/*Add Post to posts div after profilePic Upload*/
			$userId=$_SESSION['id'];
			if($type=='profilePicture'){
				echo'<div id="postDivOf'.$postId.'" class="smallPostsContainer postDiv">
					<div class="postHeading">
							<div style="display:flex">';
							/*Profile Picture Start*/
							echo'<form class="accountLinkForm" method="GET" action="userprofile.php">
								<input type="hidden" name="userName" value="'.$username.'">
							';
							echo'<button type="submit" class="profilePictureButton"><img src="'.$profilePicLocation.'" class="profilePic"></button>';
							echo'</form>';
							/*Profile Picture End*/
							/*User Full Name*/
							$getFriendDetails=mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
							if(mysqli_num_rows($getFriendDetails)>0){
								while($rowFriendDetails=mysqli_fetch_assoc($getFriendDetails)){
									$friendFirstName=$rowFriendDetails['firstName'];
									$friendlastName=$rowFriendDetails['lastName'];
									$combinedName=$friendFirstName.' '.$friendlastName;
								}
							}
							echo '<div class="postProfileDetails">'.ucwords($combinedName);
							if($type=='post'){echo' has added a new post';}
							elseif($type=='profilePicture'){echo' has changed Profile Picture';}
							echo'<div class="postDate" title="Posted on '.date('d-M-Y H:i a, D',strtotime($dateUploaded)).'">'.date('d-M H:i',strtotime($dateUploaded));
									echo'<span id = "postPrivacyShow'.$postId.'">';/*For after editing update*/
									if($postPrivacy=='public'){
										echo '<span title="This Post is visible to anyone" class = "postPrivacyStyle">&nbsp;&nbsp;&#127758;</span>';
									}else if($postPrivacy=='private'){
										echo '<span title="This Post is visible to only you" class = "postPrivacyStyle">&nbsp;&nbsp;&#128274;</span>';
									}else if($postPrivacy=='friends'){
										echo '<span title="This Post is visible to only your friends" class = "postPrivacyStyle">&nbsp;&nbsp;&#128101;</span>';
									}
									echo'</span>';
								echo'</div></div>
						</div>
						<div class="postMenuDiv">';
							echo'<div id="menuIconFor'.$postId.'" class="hamburgerMenuIcon" onclick="showMenuOptions('.$postId.')">
								<div class="dotA"></div>
								<div class="dotB"></div>
								<div class="dotC"></div>
							</div>
							<div id="menuOptionsFor'.$postId.'" class="menuOptions" style="display:none">
								<div id="'.$postId.'EditButton" class="likeUserDetails" onclick="showPostEditDiv(\''.$postId.'\',\''.$caption.'\')">edit</div>
								<div id="'.$postId.'DeleteButton" class="likeUserDetails" onclick="showPostDeleteDiv(\''.$postId.'\')">delete</div>
								<div id="'.$postId.'PrivacyButton" class="likeUserDetails" onclick="showPostPrivacyDiv(\''.$postId.'\')">privacy</div>
							</div>';
						echo'</div>
					</div>';
					echo'<div id="postCaption'.$postId.'" class="PostCaption">'.$caption.'</div>';
					$encryptedPostId=(149118912*$postId)+149118912;
					echo'<form method="GET" action="posts.php"><input type="hidden" name="postId" value="'.$encryptedPostId.'"><button type="submit" class="postImageDiv"><img class="smallPosts" src="'.$profilePicLocation.'"></button></form>';
					echo'<div class="postActionsDetails">';
						$likesCount=getLikesCountInConditionsPage($conn,$postId);
						$commentsCount=getCommentsCountInConditionsPage($conn,$postId);
						if($likesCount>0){echo'<span id="postLikesCount'.$postId.'">'.getLikesCountInConditionsPage($conn,$postId).' Likes </span>';}
						else{echo'<span id="postLikesCount'.$postId.'"></span>';}
						if($commentsCount>0){echo'<span id="postCommentsCount'.$postId.'" >'.getCommentsCountInConditionsPage($conn,$postId).' Comments</span>';}
						else{echo'<span id="postCommentsCount'.$postId.'"></span>';}
					echo'</div>
					<div class="postActions">';
						likeActionButtonInConditionsPage($conn,$postId,$userId,$username);
						$showComments=1;
						commentActionButtonInConditionsPage($conn,$postId,$userId,$username,$showComments);
					echo'</div>
				</div>';
			}else if($type=='post'){
				echo'<div id="postDivOf'.$postId.'" class="postDiv">';
					echo '<div class="postHeading">
						<div style="display:flex">';	
							getProfilePictureInConditionsPage($conn,$username);
							echo '<div class="postProfileDetails">'.getFriendsFullNameInConditionsPage($username,$conn);
							echo'<div class="postDate" title="Posted on '.date('d-M-Y H:i a, D',strtotime($dateUploaded)).'">'.date('d-M H:i',strtotime($dateUploaded));
									echo'<span id = "postPrivacyShow'.$postId.'">';/*For after editing update*/
									if($postPrivacy=='public'){
										echo '<span title="This Post is visible to anyone" class = "postPrivacyStyle">&nbsp;&nbsp;&#127758;</span>';
									}else if($postPrivacy=='private'){
										echo '<span title="This Post is visible to only you" class = "postPrivacyStyle">&nbsp;&nbsp;&#128274;</span>';
									}else if($postPrivacy=='friends'){
										echo '<span title="This Post is visible to only your friends" class = "postPrivacyStyle">&nbsp;&nbsp;&#128101;</span>';
									}
									echo'</span>';
								echo'</div></div>
						</div>
						<div class="postMenuDiv">';
							echo'<div id="menuIconFor'.$postId.'" class="hamburgerMenuIcon" onclick="showMenuOptions('.$postId.')">
								<div class="dotA"></div>
								<div class="dotB"></div>
								<div class="dotC"></div>
							</div>
							<div id="menuOptionsFor'.$postId.'" class="menuOptions" style="display:none">
								<div id="'.$postId.'EditButton" class="likeUserDetails" onclick="showPostEditDiv(\''.$postId.'\',\''.$caption.'\')">edit</div>
								<div id="'.$postId.'DeleteButton" class="likeUserDetails" onclick="showPostDeleteDiv(\''.$postId.'\')">delete</div>
								<div id="'.$postId.'PrivacyButton" class="likeUserDetails" onclick="showPostPrivacyDiv(\''.$postId.'\')">privacy</div>
							</div>';
						echo'</div>
					</div>';
					echo'<div id="postCaption'.$postId.'" class="PostCaption">'.$caption.'</div>';
					$encryptedPostId=(149118912*$postId)+149118912;
					echo'<form method="GET" action="posts.php"><input type="hidden" name="postId" value="'.$encryptedPostId.'"><button type="submit" class="postImageDiv"><img class="postImage" src="'.$profilePicLocation.'"></button></form>';
					echo'<div class="postActionsDetails">';
						$likesCount=getLikesCountInConditionsPage($conn,$postId);
						$commentsCount=getCommentsCountInConditionsPage($conn,$postId);
						if($likesCount>0){echo'<span id="postLikesCount'.$postId.'" class="postLikesDetails">'.getLikesCountInConditionsPage($conn,$postId).' Likes </span>';}
						else{
							echo'<span id="postLikesCount'.$postId.'" class="postLikesDetails"></span>';
						}
						if($commentsCount>0){echo'<span id="postCommentsCount'.$postId.'" class="postCommentsDetails">'.getCommentsCountInConditionsPage($conn,$postId).' Comments</span>';}
						else{
							echo'<span id="postCommentsCount'.$postId.'" class="postCommentsDetails"></span>';
						}
					echo'</div>
					<div class="postActions">';
						likeActionButtonInConditionsPage($conn,$postId,$userId,$username);
						$showComments=1;
						commentActionButtonInConditionsPage($conn,$postId,$userId,$username,$showComments);
					echo'</div>
				</div>';
			}
			exit();
		}else{
			echo 'UploadError-period-UploadError';
		}
	}
}
function getProfilePictureInConditionsPage($conn,$username){
	$checkPostProfilePic=mysqli_query($conn,"SELECT * FROM posts WHERE type='profilePicture' AND username='$username' ORDER BY id DESC LIMIT 1");
	echo'<form class="accountLinkForm" method="GET" action="userprofile.php">
		<input type="hidden" name="userName" value="'.$username.'">
	';
	if(mysqli_num_rows($checkPostProfilePic)>0){
		while($rowPostProfilePicture=mysqli_fetch_assoc($checkPostProfilePic)){
			echo'<button type="submit" class="profilePictureButton"><img src="'.$rowPostProfilePicture['photo'].'" class="profilePic"></button>';
		}
	}else{
		echo'<button class="profilePictureButton" type="submit"><img src="posts/basics/profileDefault.jpg" class="profilePic"></button>';
	}
	echo'</form>';
}
function getLikesCountInConditionsPage($conn,$postId){
	$checkLikes=mysqli_query($conn,"SELECT * FROM likes WHERE postId='$postId'");
	return mysqli_num_rows($checkLikes);
}
/*Get Comments count of posts*/
function getCommentsCountInConditionsPage($conn,$postId){
	$checkComments=mysqli_query($conn,"SELECT * FROM comments WHERE postId='$postId'");
	return mysqli_num_rows($checkComments);
}
function likeActionButtonInConditionsPage($conn,$postId,$userId,$username){
	$checkLikeStatus=mysqli_query($conn,"SELECT * FROM likes WHERE postId='$postId' AND userId='$userId'");
	if(mysqli_num_rows($checkLikeStatus)<1){
		echo'<form method="POST" class="likeButtonForm">
			<input type="hidden" name="postId" value="'.$postId.'">
			<input id="userFullName'.$postId.'" type="hidden" name="userFullName" value="'.getFriendsFullNameInConditionsPage($username,$conn).'">
			<input id="userId'.$postId.'" type="hidden" name="userId" value="'.$userId.'">
			<div type="button" class="likeButton" id="likeDiv'.$postId.'" onclick="likeSubmit('.$postId.',1)" name="likeSubmit">Like</div>
			<div type="button" class="likedButton" id="unLikeDiv'.$postId.'" onclick="likeSubmit('.$postId.',0)" name="unlikeSubmit" style="display:none">Liked!</div>
		</form>';
	}else{
		echo'<form method="POST" class="likedButtonForm">
		<input type="hidden" name="postId" value="'.$postId.'">
		<input id="userFullName'.$postId.'" type="hidden" name="userFullName" value="'.getFriendsFullNameInConditionsPage($username,$conn).'">
		<input id="userId'.$postId.'" type="hidden" name="userId" value="'.$userId.'">
		<div type="button" class="likeButton" id="likeDiv'.$postId.'" onclick="likeSubmit('.$postId.',1)" name="likeSubmit" style="display:none">Like</div>
		<div type="button" class="likedButton" id="unLikeDiv'.$postId.'" onclick="likeSubmit('.$postId.',0)" name="unlikeSubmit">Liked!</div>
	</form>';
	}
}
function commentActionButtonInConditionsPage($conn,$postId,$userId,$username,$showComments){
	echo'<div class="commentButtonForm"><button class="commentButton" ';?> onclick="
		if(document.getElementById('post<?php echo $postId;?>commentsDiv').style.display=='none'){
			document.getElementById('post<?php echo $postId;?>commentsDiv').style.display='block';
		}else if(document.getElementById('post<?php echo $postId;?>commentsDiv').style.display=='block'){
			document.getElementById('post<?php echo $postId;?>commentsDiv').style.display='none';
		}
		"  <?php echo'>Comments</button></div>';
		$encryptedPostId=(149118912*$postId)+149118912;
		echo'<input type="hidden" id="link'.$encryptedPostId.'" value="https://socialsite14911.000webhostapp.com/posts.php?postId='.$encryptedPostId.'">
		<div class="shareButtonForm"><button class="shareButton" onclick="copy('.'link'.$encryptedPostId.')">Share</button>
		<div id="messagePopuplink'.$encryptedPostId.'" class="messagePopup" style="display:none">Link Copied!</div></div>
	<div id="post'.$postId.'commentsDiv" style="display:none">';
		if($showComments==1){
			$commentsCount=getCommentsCountInConditionsPage($conn,$postId);
			if($commentsCount>0){
				echo'<div class="userCommentsDiv">';
					$postComments=mysqli_query($conn,"SELECT * FROM comments WHERE postId='$postId'");
					while($rowComment=mysqli_fetch_assoc($postComments)){
						echo'
						<div title="Comment added on '.date('d-M-y h:ia',strtotime($rowComment['commentDateTime'])).'">
						<strong>'.$rowComment['userFullName'].'</strong>
						<span>'.$rowComment['comment'].'</span>
						</div>
						';
					}
					echo'<div id="newCommentOf'.$userId.$postId.'"></div>';
				echo'</div>';
			}else{
				echo'<div class="userCommentsDiv"><div id="newCommentOf'.$userId.$postId.'"></div></div>';
			}
		}
		echo'<form method="POST">
			<textarea id="postOf'.$userId.$postId.'" name="comment" placeholder="Add a Comment" class="commentBox"></textarea><br>
			<button id="commentSubmitOf'.$userId.$postId.'" class="miniButtons" style="margin:0px;display:inline-flex" type="button" name="submitComment" onclick="addComment(\''.$postId.'\',\''.$userId.'\',\''.getFriendsFullNameInConditionsPage($username,$conn).'\')">
				Submit
			</button>
		</form>
	</div>';
}
function getFriendsFullNameInConditionsPage($friendUsername,$conn){
	$getFriendDetails=mysqli_query($conn,"SELECT * FROM users WHERE username='$friendUsername'");
	if(mysqli_num_rows($getFriendDetails)>0){
		while($rowFriendDetails=mysqli_fetch_assoc($getFriendDetails)){
			$friendFirstName=$rowFriendDetails['firstName'];
			$friendlastName=$rowFriendDetails['lastName'];
			$combinedName=$friendFirstName.' '.$friendlastName;
		}
	}
	return ucwords($combinedName);
}
function getProfilePictureLocationInConditionsPage($conn,$username){
	/*Get user ProfilePicture*/
	$checkPostProfilePic=mysqli_query($conn,"SELECT * FROM posts WHERE type='profilePicture' AND username='$username' ORDER BY id DESC LIMIT 1");
	if(mysqli_num_rows($checkPostProfilePic)>0){
		while($rowPostProfilePicture=mysqli_fetch_assoc($checkPostProfilePic)){
			$profilePicLocation=$rowPostProfilePicture['photo'];
		}
	}else{
		$profilePicLocation='posts/basics/profileDefault.jpg';
	}
	return $profilePicLocation;
}

/*Show Post Delete Confirmation*/
if(isset($_POST['showPostDelete'])){
	$postId=$_POST['postId'];
	echo'<div class="editPostDivOuter">
		<div class="editPostInnerDiv" style="max-height:30%;min-height:30%">
			<div class="headingName">Delete Post</div><hr><br><br>
			<div>Do you really want to delete this post?</div><br><br><br>
			<button id="confirmDeletePost'.$postId.'" class="miniButtons cancelButton" type="button" onclick="confirmDeletePost(\''.$postId.'\')">Delete</button>
			<button class="miniButtons" type="button" onclick="closeDeleteDiv()">Cancel</button>
		</div>
	</div>';
}
/*Delete Post*/
if(isset($_POST['confirmedDeletePost'])){
	$postId=$_POST['postId'];
	mysqli_query($conn,"DELETE FROM posts WHERE id='$postId'");
	mysqli_query($conn,"DELETE FROM comments WHERE postId='$postId'");
	mysqli_query($conn,"DELETE FROM likes WHERE postId='$postId'");
	echo'Done';
}

/*Show Post Privacy Confirmation*/
if(isset($_POST['showPostPrivacy'])){
	$postId=$_POST['postId'];
	$privacyStat = 'public';
	$checkPrivacy = mysqli_query($conn, "SELECT * FROM posts WHERE id = '$postId'");
	if($checkPrivacy>0){
		while($rowPrivacyStatus = mysqli_fetch_assoc($checkPrivacy)){
			$privacyStat = $rowPrivacyStatus['privacy'];
		}
	}
	echo'<div class="editPostDivOuter">
		<div class="editPostInnerDiv" style="max-height:50%;min-height:50%">
			<div class="headingName">Post Privacy Details</div><hr><br><br>
			<div>
				Current Privacy Status of this post: <span class = "successMessage">';
				echo $privacyStat;
				if($privacyStat == 'public'){echo '&#127758;';}
				elseif($privacyStat == 'private'){echo '&#128274;';}
				elseif($privacyStat == 'friends'){echo '&#128101;';}
			echo'</span></div><br>
			This means this post is Visible to ';
			if($privacyStat == 'public'){echo 'Everyone!';}
			elseif($privacyStat == 'private'){echo 'only You!';}
			elseif($privacyStat == 'friends'){echo 'only your friends!';}
			echo '<br><br><br>
			Update Privacy Option: 
			<select id = "UpdatedPrivacyStatus'.$postId.'">
				<option value = "">Select</option>
				<option value = "public">Public (Visible to all)</option>
				<option value = "friends">Friends (Visible to Friends)</option>
				<option value = "private">Private (Visible to only you)</option>
				</select><br><br><br>
			<button id="confirmUpdatePrivacyPost'.$postId.'" class="miniButtons cancelButton" type="button" onclick="confirmUpdatePrivacyPost(\''.$postId.'\')">Update</button>
			<button class="miniButtons" type="button" onclick="closePrivacyDiv()">Cancel</button>
		</div>
	</div>';
}
/*Update Privacy Post*/
if(isset($_POST['confirmUpdatePrivacyPost'])){
	$postId=$_POST['postId'];
	$UpdatedPrivacyStatus=$_POST['UpdatedPrivacyStatus'];
	if($UpdatedPrivacyStatus=='public'||$UpdatedPrivacyStatus=='private'||$UpdatedPrivacyStatus=='friends'){
		mysqli_query($conn,"UPDATE posts SET privacy='$UpdatedPrivacyStatus' WHERE id='$postId'");
		echo 'Updated..!';
	}else{
		echo 'No Change';
	}
}


/*Show Comment Delete Confirmation*/
if(isset($_POST['showCommentDelete'])){
	$commentId=$_POST['commentId'];
	echo'<div class="editPostDivOuter">
		<div class="editPostInnerDiv" style="max-height:30%;min-height:30%">
			<div class="headingName">Delete Comment</div><hr><br><br>
			<div>Do you really want to delete this Comment?</div>';
			$checkCommentDetail=mysqli_query($conn,"SELECT * FROM comments WHERE id='$commentId'");
			if(mysqli_num_rows($checkCommentDetail)>0){
				while($rowCommentDetail=mysqli_fetch_assoc($checkCommentDetail)){
					$comment=$rowCommentDetail['comment'];
				}
			}
			echo'<div style="padding:10px 20px">'.$comment.'</div><br>';
			echo'<button id="confirmDeleteComment'.$commentId.'" class="miniButtons cancelButton" type="button" onclick="confirmDeleteComment(\''.$commentId.'\')">Delete</button>
			<button class="miniButtons" type="button" onclick="closeDeleteCommentDiv()">Cancel</button>
		</div>
	</div>';
}
/*Delete Comment*/
if(isset($_POST['confirmedDeleteComment'])){
	$commentId=$_POST['commentId'];
	$checkPostId=mysqli_query($conn,"SELECT * FROM comments WHERE id='$commentId'");
	if(mysqli_num_rows($checkPostId)>0){
		while($rowPostIdOfComment=mysqli_fetch_assoc($checkPostId)){
			$commentPostId=$rowPostIdOfComment['postId'];
		}
	}
	mysqli_query($conn,"DELETE FROM comments WHERE id='$commentId'");
	if(getCommentsCountInConditionsPage($conn,$commentPostId)>0){
		$commentsCount=getCommentsCountInConditionsPage($conn,$commentPostId).' Comments';
	}else{
		$commentsCount='';
	}
	echo $commentPostId.'-period-'.$commentsCount;
}

if(isset($_POST['showPostCaption'])){
	$postId=$_POST['postId'];
	$caption=$_POST['caption'];
	echo'<div class="editPostDivOuter">
		<div class="editPostInnerDiv">
			<div class="headingName">Edit Caption</div><hr><br><br>
			<textarea id="editedCaption'.$postId.'" class="editPostCaption"></textarea><br><br>
			<button id="editedCaptionConfirmButton'.$postId.'" class="miniButtons" type="button" onclick="editedPostCaption(\''.$postId.'\')">Confirm</button>
			<button class="miniButtons cancelButton" type="button" onclick="closeEditDiv()">Cancel</button>
		</div>
	</div>';
}
if(isset($_POST['editPost'])){
	$postId=$_POST['postId'];
	$caption=$_POST['caption'];
	$editCaption=mysqli_query($conn,"UPDATE posts SET caption='$caption' WHERE id='$postId'");
	$getUpdatedCaption=mysqli_query($conn,"SELECT * FROM posts WHERE id='$postId' LIMIT 1");
	if(mysqli_num_rows($getUpdatedCaption)>0){
		while($rowPostDetails=mysqli_fetch_assoc($getUpdatedCaption)){
			$newCaption=$rowPostDetails['caption'];
		}
	}
	echo $newCaption;
}

/*Edit Comment*/
if(isset($_POST['showComment'])){
	$commentId=$_POST['commentId'];
	$comment=$_POST['comment'];
	echo'<div class="editPostDivOuter">
		<div class="editPostInnerDiv">
			<div class="headingName">Edit Comment</div><hr><br><br>
			<textarea id="editedComment'.$commentId.'" class="editPostCaption"></textarea><br><br>
			<button id="editedCommentConfirmButton'.$commentId.'" class="miniButtons" type="button" onclick="editedComment(\''.$commentId.'\')">Confirm</button>
			<button class="miniButtons cancelButton" type="button" onclick="closeEditCommentDiv()">Cancel</button>
		</div>
	</div>';
}
if(isset($_POST['editComment'])){
	$commentId=$_POST['commentId'];
	$comment=$_POST['comment'];
	$editComment=mysqli_query($conn,"UPDATE comments SET comment='$comment' WHERE id='$commentId'");
	$getUpdatedComment=mysqli_query($conn,"SELECT * FROM comments WHERE id='$commentId' LIMIT 1");
	if(mysqli_num_rows($getUpdatedComment)>0){
		while($rowCommentDetails=mysqli_fetch_assoc($getUpdatedComment)){
			$newComment=$rowCommentDetails['comment'];
		}
	}
	echo $newComment;
}

/*Fetch Friends Messages*/
if(isset($_POST['checkMessages'])){
	$currentUsername=$_POST['currentUsername'];
	$friendUsername=$_POST['friendUsername'];
	$currentUsernameId=getUserIdFromUsernameInConditionsPage($conn,$currentUsername);
	$friendUsernameId=getUserIdFromUsernameInConditionsPage($conn,$friendUsername);
	$readStatus='1';
	$checkReadStatus=mysqli_query($conn,"UPDATE messages SET readStatus='$readStatus' WHERE currentUserId='$friendUsernameId' AND friendUserId='$currentUsernameId'");
	/*Chat Side*/
	$selectedFriend=mysqli_query($conn,"SELECT * FROM connections WHERE ((userB='$currentUsername' AND userA='$friendUsername') OR (userA='$currentUsername' AND userB='$friendUsername')) AND connectionStatus='2' ORDER BY id ASC LIMIT 1");
	if(mysqli_num_rows($selectedFriend)>0){
		while($rowFriendSelected=mysqli_fetch_assoc($selectedFriend)){
			if($currentUsername==$rowFriendSelected['userB']){
				$selectedFriendUserName=$rowFriendSelected['userA'];
			}else{
				$selectedFriendUserName=$rowFriendSelected['userB'];
			}
		}
	}
	//Back button in mobile view
	echo'<button id="messagesBackButton" class="headerButtons" onclick="showMessagesDiv()">Back to Chats</button>';
	echo'<form method="GET" action="userprofile.php" target="_blank" class="messageTopPart">
		<button class="friendLinkChatDiv" type="submit">
		<img class="profilePic" style="margin:0 10px" src="'.getProfilePictureLocationInConditionsPage($conn,$selectedFriendUserName).'">'.getFriendsFullNameInConditionsPage($selectedFriendUserName,$conn);
		echo '<input type="hidden" name="userName" value="'.$selectedFriendUserName.'">
		</button>';
	echo'</form>';	
	
	echo'<div class="messageMiddlePart" id="messageMiddlePart'.$friendUsernameId.'">
		<div class="chatArea">';
			/*For maintaining width*/
			echo'<div class="rightChatUser" style="visibility:hidden">
					------------------------------------------------------------------Type and send a message------------------------------------------------------------------
			</div>';
			$friendUserId=getUserIdFromUsernameInConditionsPage($conn,$selectedFriendUserName);
			$currentUserId=getUserIdFromUsernameInConditionsPage($conn,$currentUsername);
			$checkMessages=mysqli_query($conn,"SELECT * FROM messages WHERE ((currentUserId='$currentUserId' AND friendUserId='$friendUserId') OR (currentUserId='$friendUserId' AND friendUserId='$currentUserId')) ORDER BY id DESC");
			if(mysqli_num_rows($checkMessages)>0){
				while($rowMessage=mysqli_fetch_assoc($checkMessages)){
					if($rowMessage['currentUserId']==$currentUserId){
						echo'<div class="rightChatUser">
							<div class="rightChatUserMessage">
								<div class="messageDateTime">'.date('d-m-y H:ia',strtotime($rowMessage['messageDateTime'])).'</div>
								<div class="userMessageDiv">'.$rowMessage['message'].'</div>
							</div>
						</div>';			
					}else if($rowMessage['currentUserId']==$friendUserId){
						echo'<div class="leftChatUser">
							<div class="leftChatUserMessage">
								<div class="messageDateTime">'.date('d-m-y H:ia',strtotime($rowMessage['messageDateTime'])).'</div>
								<div class="userMessageDiv">'.$rowMessage['message'].'</div>
							</div>
						</div>';
					}
				}
			}
			
		echo'</div>
	</div>';

	echo'<div class="messageBottomPart" method="POST" action="messenger.php">
		<textarea class="chatInput" name="message" id="'.getUserIdFromUsernameInConditionsPage($conn,$currentUsername).'chatInput'.getUserIdFromUsernameInConditionsPage($conn,$friendUsername).'" placeholder="Enter a message"></textarea>
		<button name="sendMessage" type="button" id="'.getUserIdFromUsernameInConditionsPage($conn,$currentUsername).'chatInputSend'.getUserIdFromUsernameInConditionsPage($conn,$friendUsername).'" class="coolButton chatInputSend" 
		onclick="sendMessage(\''.getUserIdFromUsernameInConditionsPage($conn,$currentUsername).'\',\''.getUserIdFromUsernameInConditionsPage($conn,$friendUsername).'\')">Send</button>
	</div>';
	//Send id to js
	echo'<span id="'.$currentUsername.'getCurrentUserId'.$friendUsername.'" style="display:none">'.getUserIdFromUsernameInConditionsPage($conn,$currentUsername).'</span>';
	echo'<span id="'.$currentUsername.'getFriendUserId'.$friendUsername.'" style="display:none">'.getUserIdFromUsernameInConditionsPage($conn,$friendUsername).'</span>';
	/*Update count for Messages*/
	$checkNewCurrentUserMessageCount=mysqli_query($conn,"SELECT * FROM messages WHERE currentUserId='$currentUsernameId' OR friendUserId='$currentUsernameId'");
	$totalNewCurrentUserMessageCount=mysqli_num_rows($checkNewCurrentUserMessageCount);
	echo'-period-'.$totalNewCurrentUserMessageCount;
}

/*Send Message to database*/
if(isset($_POST['sendMessage'])){
	$currentUserId=$_POST['currentUserId'];
	$friendUserId=$_POST['friendUserId'];
	$message=$_POST['message'];
	$readStatus='0';
	date_default_timezone_set('Asia/Kolkata');
	$dateUploaded=date('Y-m-d H:i:s');
	mysqli_query($conn,"INSERT INTO messages (currentUserId, friendUserId, message, readStatus, messageDateTime) VALUES ('$currentUserId','$friendUserId','$message','$readStatus','$dateUploaded')");
	/*Displaying Message*/
	echo'
	<div class="chatArea">';
		/*For maintaining width*/
		echo'<div class="rightChatUser" style="visibility:hidden">
				------------------------------------------------------------------Type and send a message------------------------------------------------------------------
		</div>';
		$checkMessages=mysqli_query($conn,"SELECT * FROM messages WHERE ((currentUserId='$currentUserId' AND friendUserId='$friendUserId') OR (currentUserId='$friendUserId' AND friendUserId='$currentUserId')) ORDER BY id DESC");
		if(mysqli_num_rows($checkMessages)>0){
			while($rowMessage=mysqli_fetch_assoc($checkMessages)){
				if($rowMessage['currentUserId']==$currentUserId){
					echo'<div class="rightChatUser">
						<div class="rightChatUserMessage">
							<div class="messageDateTime">'.date('d-m-y H:ia',strtotime($rowMessage['messageDateTime'])).'</div>
							<div class="userMessageDiv">'.$rowMessage['message'].'</div>
						</div>
					</div>';			
				}else if($rowMessage['currentUserId']==$friendUserId){
					echo'<div class="leftChatUser">
						<div class="leftChatUserMessage">
							<div class="messageDateTime">'.date('d-m-y H:ia',strtotime($rowMessage['messageDateTime'])).'</div>
							<div class="userMessageDiv">'.$rowMessage['message'].'</div>
						</div>
					</div>';
				}
			}
		}
		
	echo'</div>
	';
}

if(isset($_POST['checkMessagesContinuosly'])){
	$currentUserId=$_POST['currentUserId'];
	$friendUserId=$_POST['friendUserId'];
	/*Displaying Message*/
	echo'
	<div class="chatArea">';
		/*For maintaining width*/
		echo'<div class="rightChatUser" style="visibility:hidden">
				------------------------------------------------------------------Type and send a message------------------------------------------------------------------
		</div>';
		$checkMessages=mysqli_query($conn,"SELECT * FROM messages WHERE ((currentUserId='$currentUserId' AND friendUserId='$friendUserId') OR (currentUserId='$friendUserId' AND friendUserId='$currentUserId')) ORDER BY id DESC");
		if(mysqli_num_rows($checkMessages)>0){
			while($rowMessage=mysqli_fetch_assoc($checkMessages)){
				if($rowMessage['currentUserId']==$currentUserId){
					echo'<div class="rightChatUser">
						<div class="rightChatUserMessage">
							<div class="messageDateTime">'.date('d-m-y H:ia',strtotime($rowMessage['messageDateTime'])).'</div>
							<div class="userMessageDiv">'.$rowMessage['message'].'</div>
						</div>
					</div>';			
				}else if($rowMessage['currentUserId']==$friendUserId){
					echo'<div class="leftChatUser">
						<div class="leftChatUserMessage">
							<div class="messageDateTime">'.date('d-m-y H:ia',strtotime($rowMessage['messageDateTime'])).'</div>
							<div class="userMessageDiv">'.$rowMessage['message'].'</div>
						</div>
					</div>';
				}
			}
		}
		
	echo'</div>
	';
}

if(isset($_POST['checkForNewMessages'])){
	$userId=$_POST['currentUserId'];
	$messageCount=$_POST['messageCount'];
	$checkLastMessages=mysqli_query($conn,"SELECT * FROM messages WHERE currentUserId='$userId' OR friendUserId='$userId' ORDER BY id LIMIT $messageCount");
	$lastMessageId=0;//If message sent is first time
	if(mysqli_num_rows($checkLastMessages)>0){
		while($rowgetLastMessageId=mysqli_fetch_assoc($checkLastMessages)){
			$lastMessageId=$rowgetLastMessageId['id'];
		}
	}
	$checkNewMessages=mysqli_query($conn,"SELECT * FROM messages WHERE currentUserId='$userId' OR friendUserId='$userId' ORDER BY id");
	if(mysqli_num_rows($checkNewMessages)>$messageCount){
		$getNewMessagesCount=mysqli_num_rows($checkNewMessages)-$messageCount;
		$getNewMessagesCurrentUser=mysqli_query($conn,"SELECT * FROM messages WHERE currentUserId='$userId' AND id>'$lastMessageId' ORDER BY id DESC");
		$getNewMessagesFriendUser=mysqli_query($conn,"SELECT * FROM messages WHERE friendUserId='$userId' AND id>'$lastMessageId' ORDER BY id DESC");
		if(mysqli_num_rows($getNewMessagesCurrentUser)>0){
			while($rowNewMessage=mysqli_fetch_assoc($getNewMessagesCurrentUser)){
				echo getUserNameFromIdInConditionsPage($conn,$rowNewMessage['friendUserId']);
				echo'-period-';
			}
		}else if(mysqli_num_rows($getNewMessagesFriendUser)>0){
			while($rowNewMessageFriend=mysqli_fetch_assoc($getNewMessagesFriendUser)){
				echo getUserNameFromIdInConditionsPage($conn,$rowNewMessageFriend['currentUserId']);
				echo'-period-';
			}
		}else{
			echo'-period-';
		}
	}
}

if(isset($_POST['checkForNewMessagesHeaderTag'])){
	$userId=$_POST['currentUserId'];
	$checkCurrentMessages=mysqli_query($conn,"SELECT * FROM messages WHERE friendUserId='$userId' AND readStatus='0'");
	if(mysqli_num_rows($checkCurrentMessages)>0){
		echo'new';
	}else{
		echo'old';
	}
}

if(isset($_POST['sendFeedback'])){
	$userId=$_POST['userId'];
	$userFullName=$_POST['userFullName'];
	$userFeedback=$_POST['userFeedback'];
	mysqli_query($conn,"INSERT INTO userFeedback (userId, userFullName, userFeedback) VALUES ('$userId','$userFullName','$userFeedback')");
	echo'Feedback sent..<br><br>Thanks for the feedback.. <br>We will check it out soon..<br><br>
	<button class="coolMiniButton" type="button" onclick="toggleFeedbackForm()">Close Form</button>';
}


if(isset($_POST['forgotPasswordSendEmail'])){
	$emailId=$_POST['emailId'];
	$checkMail=mysqli_query($conn,"SELECT * FROM users WHERE email='$emailId' ORDER BY id DESC LIMIT 1");

	if(mysqli_num_rows($checkMail)>0){
		while($rowMail=mysqli_fetch_assoc($checkMail)){
			$password= $rowMail['password'];
			$encItem = $emailId.$password;//To encrypt the key to send to reset password which contains both email and old password
			$encMethod="AES-128-CTR";
			$encKey="149118912";
			$encOptions=0;
			$encIv="socialSytEncyrpt";
			$encryption = openssl_encrypt($encItem, $encMethod ,$encKey , $encOptions, $encIv);
			$to      = $emailId;
		    $subject = 'Social Site Password Reset Link';
		    $message = 'Hello '.$rowMail['firstName'].' '.$rowMail['lastName'].'<br><br><a href="socialsite14911.000webhostapp.com?emailId='.$emailId.'&key='.$encryption.'">Reset</a>';
		    $message.='To reset your account, please click here<br>
		    <a href="socialsite14911.000webhostapp.com?emailId='.$emailId.'&key='.$encryption.'">Reset</a><br><br>
		    If you have not requested to reset the password, please ignore this mail.';
		    $headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: <webmaster@example.com>' . "\r\n";
			$headers .= 'Cc: nshakohi4@gmail.com' . "\r\n";

		    mail($to, $subject, $message,$headers);
		    if(mail($to, $subject, $message, $headers))
            {
            	echo'<span class="successMessage">Password Reset link has been sent to the registered Email ID if valid.</span><br>
				Please check your mail and follow the instructions to reset your password..<br><br>
				<button class="coolMiniButton" type="button" onclick="forgotPassword()">Close Form</button>
				-period-successMessage';
            }else{
            	echo'<span class="errorMessage">There was an error..</span><br>
				Try Again after some time..<br><br>
				<button class="coolMiniButton" type="button" onclick="forgotPassword()">Close Form</button>
				-period-errorMessage';
            }
		}
	}else{
		echo '<br><br><span class="errorMessage">Email ID not found..</span><br><br>
		Try Again
		-period-errorMessage';
	}
}

?>
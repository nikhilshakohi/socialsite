<?php 
	include 'db.php';//To include connection to the Database
	session_start(); //To check for session activity if user logged in
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta name="google-site-verification" content="M6Wbpyq5eHZattL1_fqsV8HxSLz8T_U1UlUppkvfLtU" /> <!--Google Verification-->
	<meta name="viewport" content="width=device-width, intial-scale=1.0">
	<title>Social Site</title>
</head>
<body onload="showContent()">
<?php
	
	/*Disable Form Resubmission*/
	/*Removed later as this is causing the page to stay at index.php only and unable to change to other pages*/
	/*echo'<script>
	if(window.history.replaceState){window.history.replaceState(null,null,"index.php");}
	</script>';*/

	//Check if already logged in using SESSION.. Show Login page if not logged in
	if(isset($_SESSION['id'])){//Session is started when user logged in, as we store attributes in $_SESSION
		$userId=$_SESSION['id'];
		//Fetch Data from Database and Assign user Data into variables
		$checkUser=mysqli_query($conn,"SELECT * FROM users WHERE id='$userId'");
		if(mysqli_num_rows($checkUser)>0){
			while($rowUser=mysqli_fetch_assoc($checkUser)){
				/*Declaring user data in variables for use in other divs*/
				$firstName=$rowUser['firstName'];
				$lastName=$rowUser['lastName'];
				$username=$rowUser['username'];
			}
		}
		//The best thing about PHP is that it can be ended or initilized anywhere
		echo'<div id="headerDiv">
			<div id="headerFirst">
				<a title="v1.5" href="index.php"><div id="headerSiteName">Social Site</div></a>
				<a id="headerHomeDiv" href="index.php"><div class="headerButtons headerButtonsNew">Home</div></a>
				<a id="headerMessengerDiv" href="messenger.php"><div class="headerButtons headerButtonsNew">Messenger
					<span id="headerNewMsgTag" class="newMsgTagHeader" style="font-size: small;font-weight:100;';
					/*Check new messages for user in new tag at header*/
					$checkUnreadStatusOfUser=mysqli_query($conn,"SELECT * FROM messages WHERE friendUserId='$userId' AND readStatus='0'");
					if(mysqli_num_rows($checkUnreadStatusOfUser)>0){
						echo' display:inline-block';
					}else{
						echo' display:none ';
					}	
					echo'" >new</span>
				</div></a>
			</div>
			<div id="headerMiddle"><form method="POST" action="search.php" id="headerSearchForm">
				<input class="headerSearchBar" type="search" name="query" placeholder="Search for friends" required>
				<button class="headerSearchSubmit" type="submit" name="search"><img class="utilityIcon" src="posts/basics/searchIcon.png"></button>
			</form></div>
			<div id="headerLast">
				<a id="headerProfileButton" href="profile.php">
					<div title="'.getFriendsFullName($username,$conn).' - Profile Page" class="headerProfileLink"><img id="headerUserProfilePicture" src="'.getProfilePictureLocation($conn,$username).'" class="headerprofilePic"></div>
					<div class="headerFullName headerProfileLink">'.getFriendsFullName($username,$conn).'</div>
					</a>
				<form method="POST" action="logout.php">
					<input id="headerLogoutButton" class="headerButtons headerButtonsNew" type="submit" value="Logout" name="logout">
				</form>
				<div id="hamburgerIcon"';?> onclick="document.getElementById('headerSideDivModal').style.display='flex'"
						<?php echo' >
						<div id="lineA"></div>
						<div id="lineB"></div>
						<div id="lineC"></div>
					</div>
			</div>
		</div>';
		echo'<div id="headerSideDivModal" style="display: none;">
			<div id="headerSideDivModalEmpty"';?> onclick="document.getElementById('headerSideDivModal').style.display='none'"
						<?php echo' ></div>
			<div id="headerSideDiv">
				<a href="profile.php"><div id="headerSideDivHeading">
					<img id="headerUserProfilePicture" src="'.getProfilePictureLocation($conn,$username).'" class="sideDivProfilePic">
					<div id="headerUserProfilePicture">Hello, <br>'.getFriendsFullName($username,$conn).'</div>
				</div></a>
				<form method="POST" action="search.php">
					<input class="headerSearchBar" style="width: 70%;" type="search" name="query" placeholder="Search for friends" required>
					<button class="headerSearchSubmit" type="submit" name="search"><img class="utilityIcon" src="posts/basics/searchIcon.png"></button>
				</form>
				<a href="index.php"><div class="headerSideButtons">Home</div></a>
				<a href="profile.php"><div class="headerSideButtons">My Profile</div></a>
				<a href="messenger.php"><div class="headerSideButtons">Messenger
					<span id="headerNewMsgTag" class="newMsgTagHeader" style="font-size: small;font-weight:100;';
					/*Check new messages for user in new tag at header*/
					$checkUnreadStatusOfUser=mysqli_query($conn,"SELECT * FROM messages WHERE friendUserId='$userId' AND readStatus='0'");
					if(mysqli_num_rows($checkUnreadStatusOfUser)>0){
						echo' display:inline-block';
					}else{
						echo' display:none ';
					}	
					echo'" >new</span>
				</div></a>
				<form method="POST" action="search.php">
					<input type="hidden" name="query" value="">
					<input class="headerSideButtons headerSideFindFriendsButton" type="submit" name="search" value="Find More Friends">
				</form>
				<a href="about.php"><div class="headerSideButtons headerSideAboutButton">About Site</div></a>
				<a onclick="toggleFeedbackForm()"><div class="headerSideButtons headerSideFeedbackButton">User Feedback</div></a>';
				$checkFriendRequests=mysqli_query($conn,"SELECT * FROM connections WHERE userB='$username' AND connectionStatus='1'");
				if(mysqli_num_rows($checkFriendRequests)>0){
					echo'<a href="profile.php"><div class="headerSideButtons">Friend Requests ('.mysqli_num_rows($checkFriendRequests).')</div></a>';
				}
				echo'<form method="POST" action="logout.php"><input style="display:flex;width:100%;color:white;font-size:medium;border:none" class="headerSideButtons headerSideLogoutButton" type="submit" value="Logout" name="logout"></form>
			</div>
		</div>';
	}else{
		echo'<div id="headerDiv">
			<div id="headerFirst">
				<a title="v1.5" href="index.php"><div id="headerSiteName">Social Site</div></a>
			</div>
			<div id="headerMiddle"></div>
			<div id="headerLast">
				<a href="index.php" class="headerButtons">Login</a>
				<a href="index.php?#signupForm" class="coolButton">SignUp</a>
			</div>
		</div>';
	}

	/*Edit Post Div*/
	echo'<div id="editPostDiv"></div>';

	/*Delete Post Div*/
	echo'<div id="deletePostDiv"></div>';

	/*Post Privacy Div*/
	echo'<div id="privacyPostDiv"></div>';

	/*Delete Comment Div*/
	echo'<div id="deleteCommentDiv"></div>';

	/*Edit Comment Div*/
	echo'<div id="editCommentDiv"></div>';	

	/*Show Friend Requests Div in Mobile*/
	echo'<div id="showFriendRequestsDiv"></div>';	

	/*Feedback Form*/
	if(isset($_SESSION['id'])){
		echo'<div id="feedbackFormDiv">
			<div class="editPostDivOuter">
				<div class="editPostInnerDiv">
					<div class="headingName">Feedback Form</div><hr><br><br>
					<div id="feedbackContent">
						<div>Hey there '.getFriendsFullName($username,$conn).'! </div><br>
						<div>Thanks for reaching out..</div>
						<div>Please write out your concerns in the below box.. It would be valuable for us..</div><br><br>
						<input type="hidden" id="feedbackUserId" value="'.$userId.'">
						<input type="hidden" id="feedbackUserFullName" value="'.getFriendsFullName($username,$conn).'">
						<textarea id="feedbackInput" placeholder="Write any suggestion/concern/bug report/feedback" class="feedbackInput"></textarea><br><br>
						<button class="miniButtons coolButton" type="button" onclick="sendFeedback()">Send</button>
						<button class="miniButtons cancelButton" type="button" onclick="toggleFeedbackForm()">Cancel</button>
					</div>
				</div>
			</div>
		</div>';
	}

	/*Feedback Form*/
	if(!isset($_SESSION['id'])){
		echo'<div id="forgotPasswordDiv">
			<div class="editPostDivOuter">
				<div class="editPostInnerDiv">
					<div class="headingName">Password Recovery Form</div><hr><br><br>
					<div id="forgotPasswordContent">
						<div>Forgot your password? No need to worry.. <br>We understand the trouble to remember those passwords</div><br>
						<div>Please Enter the E-mail ID you have signed up with..</div><br>
						<input type="text" id="emailIdToBeRecovered" placeholder="Registered Email ID"><br><br>
						<button class="miniButtons coolButton" type="button" onclick="forgotPasswordSendEmail()">Confirm</button>
						<button class="miniButtons cancelButton" type="button" onclick="forgotPassword()">Cancel</button>
					</div>
				</div>
			</div>
		</div>';
	}

	function getFriendsFullName($friendUsername,$conn){
		$getFriendDetails=mysqli_query($conn,"SELECT * FROM users WHERE username='$friendUsername'");
		$combinedName='Unknown';
		if(mysqli_num_rows($getFriendDetails)>0){
			while($rowFriendDetails=mysqli_fetch_assoc($getFriendDetails)){
				$friendFirstName=$rowFriendDetails['firstName'];
				$friendlastName=$rowFriendDetails['lastName'];
				$combinedName=$friendFirstName.' '.$friendlastName;
			}
		}
		return ucwords($combinedName);
	}

	function profileDetails($conn,$profileUsername,$profileUserId,$profileEmail,$profileFirstName,$profileLastName,$profilePhone,$profilePassword){
		return '
	
		<div class="profileDetail">Username: <span class="actualDetail">'.$profileUsername.'</span>
		<form method="POST" style="display:inline-flex" >
			<input type="hidden" name="userId" value="'.$profileUserId.'">
			<input type="hidden" name="editReason" value="username">
			<input type="hidden" name="editReasonValue" value="'.$profileUsername.'">
			<input class="miniButtons" name="editProfile" type="submit" value="edit">
		</form></div>
		<div class="profileDetail">Full Name: <span class="actualDetail">'.getFriendsFullName($profileUsername,$conn).'</span>
		<form method="POST" style="display:inline-flex" >
			<input type="hidden" name="userId" value="'.$profileUserId.'">
			<input type="hidden" name="editReason" value="name">
			<input type="hidden" name="editReasonValue" value="'.$profileFirstName.' '.$profileLastName.'">
			<input class="miniButtons" name="editProfile" type="submit" value="edit">
		</form></div>
		<div class="profileDetail">E-mail: <span class="actualDetail">'.strtolower($profileEmail).'</span>
		<form method="POST" style="display:inline-flex" >
			<input type="hidden" name="userId" value="'.$profileUserId.'">
			<input type="hidden" name="editReason" value="email">
			<input type="hidden" name="editReasonValue" value="'.$profileEmail.'">
			<input class="miniButtons" name="editProfile" type="submit" value="edit">
		</form></div>
		<div class="profileDetail">Phone: <span class="actualDetail">'.$profilePhone.'</span>
		<form method="POST" style="display:inline-flex" >
			<input type="hidden" name="userId" value="'.$profileUserId.'">
			<input type="hidden" name="editReason" value="phone">
			<input type="hidden" name="editReasonValue" value="'.$profilePhone.'">
			<input class="miniButtons" name="editProfile" type="submit" value="edit">
		</form></div>
		<div class="profileDetail">Password: <span class="actualDetail">********</span>
		<form method="POST" style="display:inline-flex" >
			<input type="hidden" name="userId" value="'.$profileUserId.'">
			<input type="hidden" name="editReason" value="password">
			<input type="hidden" name="editReasonValue" value="'.$profilePassword.'">
			<input class="miniButtons" name="editProfile" type="submit" value="edit">
		</form></div>
		';
	}

	function getProfilePictureLocation($conn,$username){
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

	function getProfilePicture($conn,$username){
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

	/*Get ProfilePic ID for further use*/
	function getProfilePicId($conn,$username){
		$getProfilePicId=mysqli_query($conn,"SELECT * FROM posts WHERE username='$username' AND type='profilePicture' ORDER BY id DESC LIMIT 1");
		if(mysqli_num_rows($getProfilePicId)>0){
			while($rowProfilePicId=mysqli_fetch_assoc($getProfilePicId)){
				$profilePicId=$rowProfilePicId['id'];
			}
		}else{$profilePicId='';}
		return $profilePicId;
	}

	function friendsList($conn,$currentUsername){
		$friendsArray=array();
		$checkFriends=mysqli_query($conn,"SELECT * FROM connections WHERE (userB='$currentUsername' OR userA='$currentUsername') AND connectionStatus='2' ORDER BY id ASC");
		echo'<div id="friendsListDiv" class="sideDiv">';
		if(mysqli_num_rows($checkFriends)>0){
			echo'<div>Friends: </div><hr>';
			array_push($friendsArray, $currentUsername);
			while($rowFriends=mysqli_fetch_assoc($checkFriends)){
				echo'<form method="GET" action="userprofile.php" style="display:flex;" target="_blank">';
				echo'<button class="friendLinkDiv" type="submit">';
				if($rowFriends['userA']==$currentUsername){
					echo '<img class="profilePic" style="margin:0 10px" src="'.getProfilePictureLocation($conn,$rowFriends['userB']).'">'.getFriendsFullName($rowFriends['userB'],$conn);
				}
				elseif($rowFriends['userB']==$currentUsername){
					echo '<img class="profilePic" style="margin:0 10px" src="'.getProfilePictureLocation($conn,$rowFriends['userA']).'">'.getFriendsFullName($rowFriends['userA'],$conn);
				}
				echo'</button>';
				if($rowFriends['userA']==$currentUsername){
					echo '<input type="hidden" name="userName" value="'.$rowFriends['userB'].'">';
					array_push($friendsArray, $rowFriends['userB']);
				}
				elseif($rowFriends['userB']==$currentUsername){
					echo '<input type="hidden" name="userName" value="'.$rowFriends['userA'].'">';
					array_push($friendsArray, $rowFriends['userA']);
				}
				echo'</form>';
			}
			echo'<div id="showFriendsAfterConfirm"></div>';
		}else{
			array_push($friendsArray, $currentUsername);
			echo'<div>Friends: </div><hr>';
			echo'<div id="showFriendsAfterConfirm"></div>';
			echo'<div>Social Life is all about friends..</div><br>
			<form method="POST" action="search.php">
				<input type="hidden" name="query" value="">
				<input class="coolButton" type="submit" name="search" value="Find Friends">
			</form>';
		}
		echo'</div>';
		$_SESSION['friendsArray']=$friendsArray;
	}

	function friendsListArray($conn,$currentUsername){
		$friendsArray=array();
		$checkFriends=mysqli_query($conn,"SELECT * FROM connections WHERE (userB='$currentUsername' OR userA='$currentUsername') AND connectionStatus='2'");
		if(mysqli_num_rows($checkFriends)>0){
			array_push($friendsArray, $currentUsername);
			while($rowFriends=mysqli_fetch_assoc($checkFriends)){
				if($rowFriends['userA']==$currentUsername){
					array_push($friendsArray, $rowFriends['userB']);
				}
				elseif($rowFriends['userB']==$currentUsername){
					array_push($friendsArray, $rowFriends['userA']);
				}
			}
		}else{
			array_push($friendsArray, $currentUsername);
		}
		$_SESSION['friendsArray']=$friendsArray;
	}

	function friendRequests($conn,$currentUsername){
		$checkConnectionRequests=mysqli_query($conn,"SELECT * FROM connections WHERE userB='$currentUsername' AND connectionStatus='1'");
		if(mysqli_num_rows($checkConnectionRequests)>0){
			echo'<div class="sideDiv" ><div class="headingName">Friend Requests: ('.mysqli_num_rows($checkConnectionRequests).')</div><hr>';
			while($rowConnectionsRequested=mysqli_fetch_assoc($checkConnectionRequests)){
				echo '<div class="requestedFriendDiv"><div class="requestedFriendDetails">
				<form method="GET" action="userprofile.php" style="display:flex;">
					<button class="friendLinkDiv" type="submit">
					<input type="hidden" name="userName" value="'.$rowConnectionsRequested['userA'].'">
					<img class="profilePic" style="margin-right:10px" src="'.getProfilePictureLocation($conn,$rowConnectionsRequested['userA']).'">'.getFriendsFullName($rowConnectionsRequested['userA'],$conn).'</button></form></div>';
				echo'<form style="display:inline-flex;justify-content:flex-end" method="POST">
					<button id="confirmConnection'.$currentUsername.$rowConnectionsRequested['userA'].'" class="headerButtons friendsButton confirmFriendButton" type="button" name="UpdateConnection" onclick="connectionToggle(\''.$currentUsername.'\',\''.$rowConnectionsRequested['userA'].'\',\''.$rowConnectionsRequested['id'].'\',2)">Confirm Friend</button>
					<div id="confirmedConnection'.$currentUsername.$rowConnectionsRequested['userA'].'" class="searchActionButton" style="display:none"><button class="coolButton friendsButton" type="button">Friends</button></div>
				</form></div>';
			}
			echo'</div>';
		}else{
			echo'<div class="sideDiv" id="friendsRequestedDiv">No Requests found..</div>';
		}
	}
	function connectionsRequested($conn,$currentUsername){
		$checkConnectionRequests=mysqli_query($conn,"SELECT * FROM connections WHERE userA='$currentUsername' AND connectionStatus='1'");
		if(mysqli_num_rows($checkConnectionRequests)>0){
			echo'<div class="sideDiv" ><div>Connections Requested: </div><hr>';
			while($rowConnectionsRequested=mysqli_fetch_assoc($checkConnectionRequests)){
				echo'<form method="GET" action="userprofile.php" style="display:flex">
				<button class="friendLinkDiv" type="submit">
				<img class="profilePic" style="margin:0 10px" src="'.getProfilePictureLocation($conn,$rowConnectionsRequested['userB']).'">'.getFriendsFullName($rowConnectionsRequested['userB'],$conn);
				echo'</button>
				<input type="hidden" name="userName" value="'.$rowConnectionsRequested['userB'].'">
				</form>';
			}
			echo'</div>';
		}
	}
	
	function userPosts($conn,$currentUsername,$userId){
		/*Photos or Profile Pictures*/
		$username = getUserNameFromId($conn, $_SESSION['id']);
		if($username==$currentUsername){
			$userPosts=mysqli_query($conn,"SELECT * FROM posts WHERE username='$currentUsername' ORDER BY id DESC");
		}else{
			$userPosts=mysqli_query($conn,"SELECT * FROM posts WHERE username='$currentUsername' AND privacy !='private' ORDER BY id DESC");
		}
		if(mysqli_num_rows($userPosts)>0){
			echo'<div class="profilePosts">';
			while($rowPost=mysqli_fetch_assoc($userPosts)){
				/*Search for Profile Picture*/
				echo'<span id="newPostAdded"></span>';/*For AJAX*/
				echo'<div id="postDivOf'.$rowPost['id'].'" class="smallPostsContainer postDiv">';
					echo '<div class="postHeading">
						<div style="display:flex">';
							getProfilePicture($conn,$currentUsername);
							echo '<div class="postProfileDetails">
								<div><form class="accountLinkForm" method="GET" action="userprofile.php">
								<input type="hidden" name="userName" value="'.$rowPost['username'].'">
								<button type="submit" class="postProfileName">'.getFriendsFullName($rowPost['username'],$conn).
								'</button></form>';
							if($rowPost['type']=='post'){echo' has added a new post';}
							elseif($rowPost['type']=='profilePicture'){echo' has changed Profile Picture';}
							echo'</div><div title="Posted on '.date('d-M-Y H:i a, D',strtotime($rowPost['dateTimeUploaded'])).'" class="postDate">'.date('d-M H:i',strtotime($rowPost['dateTimeUploaded']));
									echo'<span id = "postPrivacyShow'.$rowPost['id'].'">';/*For after editing update*/
									if($rowPost['privacy']=='public'){
										echo '<span title="This Post is visible to anyone" class = "postPrivacyStyle">&nbsp;&nbsp;&#127758;</span>';
									}else if($rowPost['privacy']=='private'){
										echo '<span title="This Post is visible to only you" class = "postPrivacyStyle">&nbsp;&nbsp;&#128274;</span>';
									}else if($rowPost['privacy']=='friends'){
										echo '<span title="This Post is visible to only your friends" class = "postPrivacyStyle">&nbsp;&nbsp;&#128101;</span>';
									}
									echo'</span>';
								echo'</div></div>
						</div>
						<div class="postMenuDiv">';
							if(isset($_SESSION['id'])){$username=getUserNameFromId($conn,$_SESSION['id']);
								if($currentUsername==$username){
									echo'<div id="menuIconFor'.$rowPost['id'].'" class="hamburgerMenuIcon" onclick="showMenuOptions('.$rowPost['id'].')">
										<div class="dotA"></div>
										<div class="dotB"></div>
										<div class="dotC"></div>
									</div>
									<div id="menuOptionsFor'.$rowPost['id'].'" class="menuOptions" style="display:none">
										<div id="'.$rowPost['id'].'EditButton" class="likeUserDetails" onclick="showPostEditDiv(\''.$rowPost['id'].'\',\''.$rowPost['caption'].'\')">edit</div>
										<div id="'.$rowPost['id'].'DeleteButton" class="likeUserDetails" onclick="showPostDeleteDiv(\''.$rowPost['id'].'\')">delete</div>
										<div id="'.$rowPost['id'].'PrivacyButton" class="likeUserDetails" onclick="showPostPrivacyDiv(\''.$rowPost['id'].'\')">privacy</div>
									</div>';
								}else{
									echo'<div></div>';//For Maintaining Flex Position
								}
							}
						echo'</div>
					</div>';
					if($rowPost['caption']!=''){
						echo'<div id="postCaption'.$rowPost['id'].'" class="PostCaption">'.$rowPost['caption'].'</div>';
					}
					$encryptedPostId=(149118912*$rowPost['id'])+149118912;
					echo'<form method="GET" action="posts.php"><input type="hidden" name="postId" value="'.$encryptedPostId.'"><button type="submit" class="postImageDiv"><img class="smallPosts" src="'.$rowPost['photo'].'"></button></form>';
					echo'<div class="postActionsDetails">';
						$likesCount=getLikesCount($conn,$rowPost['id']);
						$commentsCount=getCommentsCount($conn,$rowPost['id']);
						if($likesCount>0){echo'<span id="postLikesCount'.$rowPost['id'].'" class="postLikesDetails" onclick="showLikesDetails(\''.$rowPost['id'].'\')">'.getLikesCount($conn,$rowPost['id']).' Likes </span>';
							echo'<div>'.getLikesDetails($conn,$rowPost['id']).'</div>';}
						else{echo'<span id="postLikesCount'.$rowPost['id'].'" class="postLikesDetails" onclick="showLikesDetails(\''.$rowPost['id'].'\')"></span>';
							echo'<div>'.getLikesDetails($conn,$rowPost['id']).'</div>';}
						if($commentsCount>0){echo'<span class="postCommentsDetails" id="postCommentsCount'.$rowPost['id'].'"';?> onclick="
							if(document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display=='none'){
								document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display='block';
							}else if(document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display=='block'){
								document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display='none';
							}
							"  <?php echo' >'.getCommentsCount($conn,$rowPost['id']).' Comments</span>';}
										else{echo'<span class="postCommentsDetails" id="postCommentsCount'.$rowPost['id'].'"';?> onclick="
							if(document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display=='none'){
								document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display='block';
							}else if(document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display=='block'){
								document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display='none';
							}
							"  <?php echo'></span>';}
					echo'</div>
					<div class="postActions">';
						if(isset($_SESSION['id'])){
							$sessionUserId=$_SESSION['id'];
							likeActionButton($conn,$rowPost['id'],$sessionUserId,getUserNameFromId($conn,$_SESSION['id']));
							$showComments=1;
							commentActionButton($conn,$rowPost['id'],$sessionUserId,getUserNameFromId($conn,$_SESSION['id']),$showComments);
						}else{
							echo'<a class="coolButton" href="index.php">Login for More</a>';
							$encryptedPostId=(149118912*$rowPost['id'])+149118912;
							echo'<input type="hidden" id="link'.$encryptedPostId.'" value="https://socialsite14911.000webhostapp.com/posts.php?postId='.$encryptedPostId.'">
							<div class="shareButtonForm"><button class="shareButton" onclick="copy('.'link'.$encryptedPostId.')">Share</button>
							<div id="messagePopuplink'.$encryptedPostId.'" class="messagePopup" style="display:none">Link Copied!</div></div>';
						}
					echo'</div>
				</div>';
			}
			echo'</div>';
		}else{
			echo'<div id="newPostAdded" class="profilePosts" style="padding:20px 0">No Posts Added yet..</div>';	
		}
	}

	/*Get Likes count of posts*/
	function getLikesCount($conn,$postId){
		$checkLikes=mysqli_query($conn,"SELECT * FROM likes WHERE postId='$postId'");
		return mysqli_num_rows($checkLikes);
	}
	/*Get Comments count of posts*/
	function getCommentsCount($conn,$postId){
		$checkComments=mysqli_query($conn,"SELECT * FROM comments WHERE postId='$postId'");
		return mysqli_num_rows($checkComments);
	}

	/*Get Likes Details*/
	function getLikesDetails($conn,$postId){
		$checkLikes=mysqli_query($conn,"SELECT * FROM likes WHERE postId='$postId' ORDER BY id ASC");
		if(mysqli_num_rows($checkLikes)>0){
			echo'<div id="'.$postId.'LikeDetail" class="likeDetails" style="position:absolute;display:none">
			<div id="'.$postId.'newLikeDetail">';
			while($rowLikesDetail=mysqli_fetch_assoc($checkLikes)){
				echo'<form target="_blank" class="accountLinkForm" method="GET" action="userprofile.php">
					<input type="hidden" name="userName" value="'.getUserNameFromId($conn,$rowLikesDetail['userId']).'">
					<button type="submit" class="likeUserDetails">'.$rowLikesDetail['userFullName'].'</button>
				</form><br>';
			}
			echo'</div>';
			echo'</div>';
		}else{
			echo'<div id="'.$postId.'LikeDetail" class="likeDetails" style="position:absolute;display:none">
			<div id="'.$postId.'newLikeDetail">';
			echo'</div>';
			echo'</div>';
		}
	}

	/*Get Username from UserId*/
	function getUserNameFromId($conn,$userId){
		$checkUserFromUserId=mysqli_query($conn,"SELECT * FROM users WHERE id='$userId' LIMIT 1");
		$username='';
		if(mysqli_num_rows($checkUserFromUserId)>0){
			while($rowUserFromUserId=mysqli_fetch_assoc($checkUserFromUserId)){
				$username=$rowUserFromUserId['username'];
			}
		}
		return $username;
	}
	/*Get UserId from UserName*/
	function getUserIdFromUsername($conn,$username){
		$checkUserFromUsername=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' LIMIT 1");
		$userId='';
		if(mysqli_num_rows($checkUserFromUsername)>0){
			while($rowUserFromUsername=mysqli_fetch_assoc($checkUserFromUsername)){
				$userId=$rowUserFromUsername['id'];
			}
		}
		return $userId;
	}
	
	/*Like and Liked Button*/
	function likeActionButton($conn,$postId,$userId,$username){
		$checkLikeStatus=mysqli_query($conn,"SELECT * FROM likes WHERE postId='$postId' AND userId='$userId'");
		if(mysqli_num_rows($checkLikeStatus)<1){
			echo'<form method="POST" class="likeButtonForm">
				<input type="hidden" name="postId" value="'.$postId.'">
				<input id="userFullName'.$postId.'" type="hidden" name="userFullName" value="'.getFriendsFullName($username,$conn).'">
				<input id="userId'.$postId.'" type="hidden" name="userId" value="'.$userId.'">
				<div type="button" class="likeButton" id="likeDiv'.$postId.'" onclick="likeSubmit(\''.$postId.'\',1)" name="likeSubmit">Like</div>
				<div type="button" class="likedButton" id="unLikeDiv'.$postId.'" onclick="likeSubmit(\''.$postId.'\',0)" name="unlikeSubmit" style="display:none">Liked!</div>
			</form>';
		}else{
			echo'<form method="POST" class="likedButtonForm">
			<input type="hidden" name="postId" value="'.$postId.'">
			<input id="userFullName'.$postId.'" type="hidden" name="userFullName" value="'.getFriendsFullName($username,$conn).'">
			<input id="userId'.$postId.'" type="hidden" name="userId" value="'.$userId.'">
			<div type="button" class="likeButton" id="likeDiv'.$postId.'" onclick="likeSubmit(\''.$postId.'\',1)" name="likeSubmit" style="display:none">Like</div>
			<div type="button" class="likedButton" id="unLikeDiv'.$postId.'" onclick="likeSubmit(\''.$postId.'\',0)" name="unlikeSubmit">Liked!</div>
		</form>';
		}
	}

	/*Comments*/
	function commentActionButton($conn,$postId,$userId,$username,$showComments){
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
		<div id="post'.$postId.'commentsDiv" class="commentsOuterDiv" style="display:none"><hr>';
			if($showComments==1){
				$commentsCount=getCommentsCount($conn,$postId);
				if($commentsCount>0){
					echo'<div class="userCommentsDiv">';
						$postComments=mysqli_query($conn,"SELECT * FROM comments WHERE postId='$postId'");
						while($rowComment=mysqli_fetch_assoc($postComments)){
							echo'
							<div id="commentDivOf'.$rowComment['id'].'" class="commentDiv" title="Comment added on '.date('d-M-y h:ia',strtotime($rowComment['commentDateTime'])).'">
							<strong>'.$rowComment['userFullName'].'</strong>
							<span class="commentSpace" id="postComment'.$rowComment['id'].'">'.$rowComment['comment'].'</span>';
							/*Delete Comment*/
							if($rowComment['userId']==$userId){
								echo'<div id="menuIconForCommentOf'.$rowComment['id'].'" class="hamburgerMenuIcon hamburgerCommentsMenuIcon" onclick="showMenuOptionsOfComment('.$rowComment['id'].')" style="display:inline-flex">
									<div class="dotA"></div>
									<div class="dotB"></div>
									<div class="dotC"></div>
								</div>
								<div id="menuOptionsForCommentOf'.$rowComment['id'].'" class="menuOptionsOfComments" style="display:none">
									<div title="Edit Comment?" id="'.$rowComment['id'].'commentEditButton" class="likeUserDetails" onclick="showCommentEditDiv(\''.$rowComment['id'].'\')">edit</div>
									<div title="Delete the Comment?" id="'.$rowComment['id'].'commentDeleteButton" class="likeUserDetails" onclick="showCommentDeleteDiv(\''.$rowComment['id'].'\')">delete</div>
								</div>';
							}
							echo'</div>
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
				<button id="commentSubmitOf'.$userId.$postId.'" class="miniButtons" style="margin:0px;display:inline-flex" type="button" name="submitComment" onclick="addComment(\''.$postId.'\',\''.$userId.'\',\''.getFriendsFullName($username,$conn).'\')">
					Submit
				</button>
			</form>
		</div>';
	}
	
/*Profile Connection Details*/
/*Check User Connection*/
function getProfileConnectionDetails($conn,$sessionUser,$currentUsername){
	if(isset($_SESSION['id'])){
		echo'<div class="userProfileConnectionDiv">';
			$checkConnection=mysqli_query($conn,"SELECT * FROM connections WHERE (userA='$sessionUser' OR userB='$sessionUser') AND (userA='$currentUsername' OR userB='$currentUsername') AND (connectionStatus='1' OR connectionStatus='2') ORDER BY id DESC LIMIT 1");
			if(mysqli_num_rows($checkConnection)>0){
				while($rowConnectionStatus=mysqli_fetch_assoc($checkConnection)){
					if($currentUsername==$sessionUser){
						echo'';
					}elseif($rowConnectionStatus['connectionStatus']=='2'){
						echo'<div class="searchActionButton"><button class="coolButton friendsButton" type="disabled">Friends</button></div>';
						echo'<a href="messenger.php"><button class="headerButtons" type="button">Message</button></a>';
					}elseif($rowConnectionStatus['connectionStatus']=='1'){
						if($rowConnectionStatus['userA']==$sessionUser){
							echo'<div class="searchActionButton"><button class="coolButton pendingFriendButton" type="disabled">Connection Request Sent</button></div>';
						}else{
							echo'<form class="searchActionButton" method="POST">
								<button title="'.getFriendsFullName($currentUsername,$conn).' has sent you a friend request" id="confirmConnection'.$sessionUser.$currentUsername.'" class="headerButtons friendsButton confirmFriendButton" type="button" name="UpdateConnection" onclick="connectionToggle(\''.$sessionUser.'\',\''.$currentUsername.'\',\''.$rowConnectionStatus['id'].'\',2)">Confirm Friend</button>
								<div id="confirmedConnection'.$sessionUser.$currentUsername.'" class="searchActionButton" style="display:none">
									<button class="coolButton friendsButton" type="button">Friends</button>
									<a href="messenger.php"><button class="headerButtons" type="button">Message</button></a>
								</div>
							</form>';
						}
					}
				}
			}else{
				if($currentUsername==$sessionUser){
						echo'<div class="searchActionButton">(Self)</div>';
				}else{/*As strings cannot pass through PHP to JS directly, we use \' charecter before and after string */
					$connectionId='test';
					echo'<div class="searchActionButton"><form style="display:inline-flex" method="POST">
						<div type="button" class="coolButton" id="addConnection'.$sessionUser.$currentUsername.'" name="addConnection" onclick="connectionToggle(\''.$sessionUser.'\',\''.$currentUsername.'\',\''.$connectionId.'\',1)">Add Friend</div>
						<div class="searchActionButton" id="yetConfirmConnection'.$sessionUser.$currentUsername.'" style="display:none"><button class="coolButton pendingFriendButton" type="disabled">Connection Request Sent</button></div>
					</form></div>';
				}
			}
		echo'</div>';
	}
}
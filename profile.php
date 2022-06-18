<?php 

include 'header.php';
if (empty($_SESSION['id'])) {	//To logout when no session
	header('Location: index.php'); exit();
}


$userId=$_SESSION['id'];
$userDetails=mysqli_query($conn,"SELECT * FROM users WHERE id='$userId'");

if(mysqli_num_rows($userDetails)>0){
	echo'<div id="content" style="display:block">';	
		while($rowUsers=mysqli_fetch_assoc($userDetails)){
			$currentUserId=$rowUsers['id'];
			$currentUsername=$rowUsers['username'];
			$currentFirstName=$rowUsers['firstName'];
			$currentLastName=$rowUsers['lastName'];
			$currentPhone=$rowUsers['phone'];
			$currentEmail=$rowUsers['email'];
			$currentPassword=$rowUsers['password'];
			echo'
			<div id="profilePicModal" class="outsideModal" onclick="hideImage()">
				<div id="imageLoader" class="loaderButton"></div>
			    <img id="profilePicImage" class="imageModal" src="'.getProfilePictureLocation($conn,$currentUsername).'">
			    <div class="profilePicActionDetails">';
			    	$ProfilePicId=getProfilePicId($conn,$username);
					$likesCount=getLikesCount($conn,$ProfilePicId);
					$commentsCount=getCommentsCount($conn,$ProfilePicId);
					if($likesCount>0){echo'<span>'.getLikesCount($conn,$ProfilePicId).' Likes </span>';}
					if($commentsCount>0){echo'<span>'.getCommentsCount($conn,$ProfilePicId).' Comments</span>';}
				echo'</div>
			</div>
			<div id="profileInfo">
				<div id="profilePagePicture">
					<img id="profilePicture" class="profilePicBig" src="'.getProfilePictureLocation($conn,$currentUsername).'" ';
					?> onclick="showImage('profilePic')" ><?php
					echo'<script>
						function showImage(image){
							setTimeout(function(){document.getElementById(image+"Image").style.opacity="1";
								document.getElementById(image+"Image").style.display="block";
								document.getElementById("imageLoader").style.display="none";
							},1000);
							document.getElementById(image+"Modal").style.display="block";
							document.getElementById(image+"Image").style.opacity="0";
							document.getElementById(image+"Image").style.display="block";
							document.getElementById(image+"Image").style.display="none";
							document.getElementById("imageLoader").style.display="block";
						}
					  	function hideImage(){
						    var modals=document.getElementsByClassName("outsideModal");
						    for(var i=0;i<modals.length;i++){
						      modals[i].style.display="none";
						    }
					  	}
					</script>
					<br><input class="headerButtons" type="button" value="Change Profile Picture" onclick="openProfilePicUploader()">
					<form method="POST" enctype="multipart/form-data">	
						<span id="profilePicUploader" style="display:none">
							<textarea id="postCaptionEntry'.$username.'" class="postCaptionEntry" rows="1" cols="30" placeholder="Write Something or caption" name="caption"></textarea><br>
							<input id="postUploadFile" type="file" name="fileName" style="display:none">
							<select id="postPrivacy'.$username.'" class="miniButtons postPrivacyButton" style="padding:5px;margin:5px">
								<option value="public">&#127758;</option><option value="private">&#128274;</option><option value="friends">&#128101;</option>
							</select>
							<label class="miniButtons addPictureButton" style="padding:7px" for="postUploadFile">Select Picture</label>';
							echo'<script>
								document.getElementById("postUploadFile").onchange=
								function(){
									var path=this.value;';?> path=path.replace(/^.*\\/,"");<?php echo' 
									document.getElementById("getUploadedFileName").innerHTML=path;
									document.getElementById("getUploadedFileName").title=path;
								}
							</script>
							<div id="getUploadedFileName" style="width:200px;display:inline-flex"></div>
							
							<div id="profilePictureSubmitOf'.$username.'" class="coolMiniButton postUploadButton" style= "padding:7px;" type="button" name="profilePicUpload" onclick="addPost(\''.$username.'\',\''."profilePicture".'\')">Upload</div>
							<button class="coolMiniButton captionDoneButton" type="button">Done</button>
						</span>
					</form>
					<div id="smallScreenProfileName">
						<h2>'.getFriendsFullName($username,$conn).'</h2>
					</div>
				</div>

				<div id="profileDetails">
					<div id="bigScreenProfileName">
						<h2>'.getFriendsFullName($username,$conn).'</h2>
					</div>
					'.profileDetails($conn,$rowUsers['username'],$rowUsers['id'],$rowUsers['email'],$rowUsers['firstName'],$rowUsers['lastName'],$rowUsers['phone'],$rowUsers['password']).'
				</div>
			</div>';
		}

		if(isset($_POST['editProfile'])){
			echo'<div id="profileEditOuter">
				<div id="profileEditInner">';
					$editType=$_POST['editReason'];
					$userId=$_POST['userId'];
					$editReasonValue=$_POST['editReasonValue'];
			
					echo'<div class="headingName">Edit '.$editType.'</div><hr><br>
					Your Current '.$editType.' is: <strong>'.$editReasonValue.'</strong><br>
					<form method="POST">
						<span>Enter your new '.$editType.': </span><br>';
						if($editType!='name'){echo'<input type="text" name="newValue">';}
						else{
							echo'<br>First Name: <input type="text" name="firstName"><br>';
							echo'Last Name: <input type="text" name="lastName"><br>';
						}
						echo'<input type="hidden" name="userId" value="'.$userId.'"><br><br>
						<input type="hidden" name="editType" value="'.$editType.'">
						<input class="miniButtons" type="submit" name="confirmEdit" value="Confirm">
						<a href="profile.php" class="miniButtons cancelButton" type="button">Cancel</a>
					</form>';
				echo'</div>
			</div>';
		}

		if(isset($_POST['confirmEdit'])){
			$editType=$_POST['editType'];
			$userId=$_POST['userId'];
			$newValue=mysqli_real_escape_string($conn,$_POST['newValue']);
			if(($editType!='password')&&($editType!='name')){
				$checkUsers=mysqli_query($conn,"SELECT * FROM users WHERE $editType='$newValue'");
				if(mysqli_num_rows($checkUsers)<1){
					mysqli_query($conn,"UPDATE users SET $editType='$newValue' WHERE id='$userId'");
					/*As usernames are stored in other database, they also need to be changed*/
					if($editType=='username'){
						mysqli_query($conn,"UPDATE posts SET username='$newValue' WHERE username='$username'");
						mysqli_query($conn,"UPDATE connections SET userA='$newValue' WHERE userA='$username'");
						mysqli_query($conn,"UPDATE connections SET userB='$newValue' WHERE userB='$username'");
						/*As before page loads, old username is stored in $username*/
					}
					echo'<script>window.location.href="profile.php?UpdateSuccess"</script>';
					exit();
				}else{
					echo '<span class="errorMessage">'.$editType.' is taken.. Try a new one..</span>';
				}	
			}elseif($editType=='password'){
				mysqli_query($conn,"UPDATE users SET $editType='$newValue' WHERE id='$userId'");
				echo'<script>window.location.href="profile.php?UpdateSuccess"</script>';
				exit();
			}elseif($editType=='name'){
				$firstName=mysqli_real_escape_string($conn,$_POST['firstName']);
				$lastName=mysqli_real_escape_string($conn,$_POST['lastName']);
				mysqli_query($conn,"UPDATE users SET firstName='$firstName', lastName='$lastName' WHERE id='$userId'");
				echo'<script>window.location.href="profile.php?UpdateSuccess'.$firstName.$lastName.'"</script>';
				exit();
			}
		}

		$checkFriendsCount=mysqli_query($conn,"SELECT * FROM connections WHERE (userB='$currentUsername' OR userA='$currentUsername') AND connectionStatus='2'");
		$checkConnectionRequestsCount=mysqli_query($conn,"SELECT * FROM connections WHERE userB='$currentUsername' AND connectionStatus='1'");
		echo'<div id="userProfileButtonsDiv">
			<div id="userProfileDetailsButtonDiv">
				<button id="userProfileDetailsButton"';?> onclick="showThisProfileDiv('Details');" <?php echo'>Details</button>
			</div>
			<div id="userProfilePostsButtonDiv"><button id="userProfilePostsButton"';?> onclick="showThisProfileDiv('Posts');" <?php echo'>Posts</button>
			</div>
			<div id="userProfileFriendsButtonDiv"><button id="userProfileFriendsButton" ';?>onclick="showThisProfileDiv('Friends');" <?php echo'>Friends ';
				if(mysqli_num_rows($checkFriendsCount)>0){echo '('.mysqli_num_rows($checkFriendsCount).')';}
				echo'</button>
			</div>
			<div id="userProfileFriendRequestsButtonDiv"><button id="userProfileFriendRequestsButton" ';?>onclick="showThisProfileDiv('FriendRequests');" <?php echo'>Friend Requests ';
				if(mysqli_num_rows($checkConnectionRequestsCount)>0){echo '('.mysqli_num_rows($checkConnectionRequestsCount).')';}
				echo'</button>
			</div>
		</div>';

		echo'<div id="newPostsAfterFriends" style="display:none"';?> onclick="window.location.href='index.php'" <?php echo' class="coolButton">New Posts</div>';
		echo'<div id="userProfileDetailsDiv" style="display:none">
			<div class="postDiv" style="padding:30px 10px;min-height:500px">
				<div class="headingName">User Details:</div><hr>'.
				profileDetails($conn,$currentUsername,$currentUserId,$currentEmail,$currentFirstName,$currentLastName,$currentPhone,$currentPassword).
			'</div>
		</div>';

		echo'<div id="userProfileFriendsDiv" style="display:none">';
			friendsList($conn,$currentUsername);
			echo'<div class="sideDiv">
				<form method="POST" action="search.php">
					<input type="hidden" name="query" value="">
					<input class="coolButton" type="submit" name="search" value="Find More Friends">
				</form>
			</div>';
		echo'</div>';


		echo'<div id="userProfileFriendRequestsDiv" style="display:none;">';
			friendRequests($conn,$currentUsername);
			connectionsRequested($conn,$currentUsername);
		echo'</div>';

		echo'<div id="userProfilePostsDiv">';
			userPosts($conn,$currentUsername,$userId);
		echo'</div>';

	echo'<script>document.title="'.ucwords($currentFirstName).' '.ucwords($currentLastName).'"</script>';		
	echo'</div>';
}

include 'footer.php';
?>
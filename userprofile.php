<?php

include 'header.php';

if(isset($_GET['userName'])){
	$sessionUser=$username;
	$username=$_GET['userName'];
	echo'<div id="content" style="display:block">';		
		
	$userDetails=mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
	if(mysqli_num_rows($userDetails)>0){
		while($rowUsers=mysqli_fetch_assoc($userDetails)){
			$currentUserId=$rowUsers['id'];
			$currentUsername=$rowUsers['username'];
			$currentFirstName=$rowUsers['firstName'];
			$currentLastName=$rowUsers['lastName'];
			$currentPhone=$rowUsers['phone'];
			$currentEmail=$rowUsers['email'];
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
					<img class="profilePicBig" src="'.getProfilePictureLocation($conn,$currentUsername).'" ';
					?> onclick="showImage('profilePic')" ><?php
					echo'<script>
						function showImage(image){
						    setTimeout(function(){
						    	document.getElementById(image+"Image").style.opacity="1";
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
				  </script>';
					?><?php
					echo'<div id="smallScreenProfileName">
						<h2>'.getFriendsFullName($username,$conn).'</h2>';
						echo'<div id="smallScreenConnectionDetails">';
							getProfileConnectionDetails($conn,$sessionUser,$currentUsername);
						echo'</div>';
					echo'</div>
				</div>
				<div id="profileDetails">
					<div id="bigScreenProfileName">
						<h2>'.getFriendsFullName($username,$conn).'</h2>
					</div>
					
					<div class="profileDetail">
						Username: <span class="actualDetail">'.$rowUsers['username'].'</span>
					</div>
					<div class="profileDetail">
						Full Name: <span class="actualDetail">'.getFriendsFullName($currentUsername,$conn).'</span>
					</div>';
					if(isset($_SESSION['id'])){
						echo'<div class="profileDetail">
							E-mail: <span class="actualDetail">'.strtolower($rowUsers['email']).'</span>
						</div>
						<div class="profileDetail">
							Phone: <span class="actualDetail">'.$rowUsers['phone'].'</span>
						</div>';
					}else{
						echo'<div class="profileDetail">
							E-mail: <span class="actualDetail">************</span>
						</div>
						<div class="profileDetail">
							Phone: <span class="actualDetail">**********</span>
						</div>
						<div class="profileDetail">
							<a class="headerButtons" href="index.php">Login to Connect</a>
						</div>';
					}

					echo'<div id="bigScreenConnectionDetails">';
						getProfileConnectionDetails($conn,$sessionUser,$currentUsername);
					echo'</div>';

				echo'</div>
			</div>';
		/*Posts*/
		echo'<div id="userProfileButtonsDiv">
			<div id="userProfileDetailsButtonDiv"><button id="userProfileDetailsButton" ';?>onclick="
				document.getElementById('userProfilePostsDiv').style.display='none';
				document.getElementById('userProfileFriendsDiv').style.display='none';
				document.getElementById('userProfileDetailsDiv').style.display='block';
				document.getElementById('userProfilePostsButton').style.color='black';
				document.getElementById('userProfileFriendsButton').style.color='black'
				document.getElementById('userProfileDetailsButton').style.color='green'
				document.getElementById('userProfilePostsButtonDiv').style.borderBottom='none';
				document.getElementById('userProfileFriendsButtonDiv').style.borderBottom='none';
				document.getElementById('userProfileDetailsButtonDiv').style.borderBottom='5px solid green';
				" <?php echo'>Details</button></div>
			<div id="userProfilePostsButtonDiv"><button id="userProfilePostsButton" ';?>onclick="
				document.getElementById('userProfilePostsDiv').style.display='block';
				document.getElementById('userProfileFriendsDiv').style.display='none';
				document.getElementById('userProfileDetailsDiv').style.display='none';
				document.getElementById('userProfilePostsButton').style.color='green';
				document.getElementById('userProfileFriendsButton').style.color='black'
				document.getElementById('userProfileDetailsButton').style.color='black'
				document.getElementById('userProfilePostsButtonDiv').style.borderBottom='5px solid green';
				document.getElementById('userProfileFriendsButtonDiv').style.borderBottom='none';
				document.getElementById('userProfileDetailsButtonDiv').style.borderBottom='none';
				" <?php echo'>Posts</button></div>	
			<div id="userProfileFriendsButtonDiv"><button id="userProfileFriendsButton" ';?>onclick="
				document.getElementById('userProfilePostsDiv').style.display='none';
				document.getElementById('userProfileFriendsDiv').style.display='block';
				document.getElementById('userProfileDetailsDiv').style.display='none';
				document.getElementById('userProfilePostsButton').style.color='black';
				document.getElementById('userProfileFriendsButton').style.color='green'
				document.getElementById('userProfileDetailsButton').style.color='black'
				document.getElementById('userProfilePostsButtonDiv').style.borderBottom='none';
				document.getElementById('userProfileFriendsButtonDiv').style.borderBottom='5px solid green';
				document.getElementById('userProfileDetailsButtonDiv').style.borderBottom='none';
				" <?php echo'>Friends</button></div>
		</div>';
		
		echo'<div id="userProfileDetailsDiv" style="display:none">
		<div class="postDiv" style="padding:30px 10px;min-height:500px">';
			echo'<div class="profileDetail">Username: <span class="actualDetail">'.$currentUsername.'</span>
				<div class="profileDetail">Full Name: <span class="actualDetail">'.getFriendsFullName($currentUsername,$conn).'</span>';
				if(isset($_SESSION['id'])){
					echo'<div class="profileDetail">E-mail: <span class="actualDetail">'.strtolower($currentEmail).'</span>
						<div class="profileDetail">Phone: <span class="actualDetail">'.$currentPhone.'</span>';
				}
		echo'</div></div>';
		
		echo'<div id="userProfileFriendsDiv" style="display:none">';
			friendsList($conn,$currentUsername);
		echo'</div>';
		
		echo'<div id="userProfilePostsDiv">';
			userPosts($conn,$currentUsername,$currentUserId);
		echo'</div>

	</div>';

		echo'<script>document.title="'.ucwords($currentFirstName).' '.ucwords($currentLastName).'"</script>';		
		}
	}else{
		echo'<div style="display:flex;margin-left:auto;margin-right:auto;justify-content:center;align-content:center;align-items:center;flex-direction:column;width:50%;background-color:gainsboro;box-shadow:0 0 2px 1px grey;margin-top:20px">
			<h3>Seems Like Page is broken..Go Back to HomePage</h3>
			<span class="loaderButton"></span>
			<a href="index.php" class="headerButtons">Home</a>
		</div>';
	}
	echo'</div>';
}

/*if(!isset($_SESSION['id'])){echo'<a class="headerButtons" href="index.php">Home Page</a>';}*/

include 'footer.php';

?>
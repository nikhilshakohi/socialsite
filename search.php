<?php 

include 'header.php';
if (empty($_SESSION['id'])) {	//To logout when no session
	header('Location: index.php'); exit();
}

$userId=$_SESSION['id'];
$userDetails=mysqli_query($conn,"SELECT * FROM users WHERE id='$userId'");
if(mysqli_num_rows($userDetails)>0){
	while($rowUserDetails=mysqli_fetch_assoc($userDetails)){
		$currentUserId=$rowUserDetails['id'];
		$currentUsername=$rowUserDetails['username'];
		$currentFirstName=$rowUserDetails['firstName'];
		$currentLastName=$rowUserDetails['lastName'];
		$currentPhone=$rowUserDetails['phone'];
		$currentEmail=$rowUserDetails['email'];
	}
}
if(isset($_POST['search'])){
	echo'<div id="pageLoader" class="loaderButton loaderButtonBig"></div>'; //As it is called on page load
	echo'<div id="content">
	<div id="contentLeft"></div>
	<div class="searchDiv">';
	$query=mysqli_real_escape_string($conn,$_POST['query']);
	$checkUsersProfile=mysqli_query($conn,"SELECT * FROM users WHERE username LIKE '%$query%' OR email LIKE '%$query%' OR phone LIKE '%$query%' OR firstName LIKE '%$query%' OR lastName LIKE '%$query%'");
	if(mysqli_num_rows($checkUsersProfile)>0){
		echo mysqli_num_rows($checkUsersProfile).' results have been found for '.$query.'<br><hr>';
		while($rowUserProfile=mysqli_fetch_assoc($checkUsersProfile)){
			$searchedUser=$rowUserProfile['username'];
			echo'<div class="searchProfileDiv"><form method="GET" action="userprofile.php">
			<button class="friendLinkDiv searchLinkDiv" type="submit">';
			/*Check for ProfilePic*/
			echo '<img class="profilePic" style="margin-right: 10px" src="'.getProfilePictureLocation($conn,$searchedUser).'">'.getFriendsFullName($searchedUser,$conn);
			echo'</button>';
			echo '<input type="hidden" name="userName" value="'.$searchedUser.'">
			</form>';
			$checkConnection=mysqli_query($conn,"SELECT * FROM connections WHERE (userA='$currentUsername' OR userB='$currentUsername') AND (userA='$searchedUser' OR userB='$searchedUser') AND (connectionStatus='1' OR connectionStatus='2') ORDER BY id DESC LIMIT 1");
			if(mysqli_num_rows($checkConnection)>0){
				while($rowConnectionStatus=mysqli_fetch_assoc($checkConnection)){
					if($searchedUser==$currentUsername){
						echo'<div class="searchActionButton" style="font-size:small">(Self)</div>';
					}elseif($rowConnectionStatus['connectionStatus']=='2'){
						echo'<div class="searchActionButton"><button class="coolButton friendsButton" type="disabled">Friends</button></div>';
					}elseif($rowConnectionStatus['connectionStatus']=='1'){
						if($rowConnectionStatus['userA']==$currentUsername){
							echo'<div class="searchActionButton"><button class="coolButton pendingFriendButton" type="disabled">Connection Request Sent</button></div>';
						}else{
							echo'<form class="searchActionButton" method="POST">
								<button id="confirmConnection'.$currentUsername.$rowUserProfile['username'].'" class="headerButtons friendsButton confirmFriendButton" type="button" name="UpdateConnection" onclick="connectionToggle(\''.$currentUsername.'\',\''.$rowUserProfile['username'].'\',\''.$rowConnectionStatus['id'].'\',2)">Confirm Friend</button>
								<div id="confirmedConnection'.$currentUsername.$rowUserProfile['username'].'" class="searchActionButton" style="display:none"><button class="coolButton friendsButton" type="button">Friends</button></div>
							</form>';
						}
					}
				}
			}else{
				if($searchedUser==$currentUsername){
						echo'<div class="searchActionButton">(Self)</div>';
				}else{/*As strings cannot pass through PHP to JS directly, we use \' charecter before and after string */
					$connectionId='test';
					echo'<div class="searchActionButton"><form style="display:inline-flex" method="POST">
						<div type="button" class="coolButton" id="addConnection'.$currentUsername.$rowUserProfile['username'].'" name="addConnection" onclick="connectionToggle(\''.$currentUsername.'\',\''.$rowUserProfile['username'].'\',\''.$connectionId.'\',1)">Add Friend</div>
						<div class="searchActionButton" id="yetConfirmConnection'.$currentUsername.$rowUserProfile['username'].'" style="display:none"><button class="coolButton pendingFriendButton" type="disabled">Connection Request Sent</button></div>
					</form></div>';
				}
			}
		echo'</div>';
		}
	}else{
		echo'<div>No results found for '.$query;
		echo'</div>';
	}
}
//Add Connection 
/*if(isset($_POST['addConnection'])){
	$userA=$_POST['userA'];
	$userB=$_POST['userB'];
	$searchQuery=$_POST['searchQuery'];
	$connectionStatus=$_POST['connectionStatus'];
	$checkConnectionReloadStatus=mysqli_query($conn,"SELECT * FROM connections WHERE connectionStatus='1' AND userA='$userA' AND userB='$userB'");
	if(mysqli_num_rows($checkConnectionReloadStatus)>0){
		echo'<div style="margin-top:100px">Connection Request for '.$userB.' already Sent..<br><br><br>
		<a class="headerButtons" href="index.php">Home Page</a>
		</div>';
	}else{
		$addConnection=mysqli_query($conn,"INSERT INTO connections (userA, userB, connectionStatus) VALUES ('$userA', '$userB', '$connectionStatus')");
		echo'<div style="margin-top:100px">Connection Request for '.$userB.' is Sent..<br><br><br>
		<a class="headerButtons" href="index.php">Home Page</a>
		</div>';
	}
	exit();
}*/
echo'</div>
<div id="contentRight"></div>
</div>';


include 'footer.php';

?>
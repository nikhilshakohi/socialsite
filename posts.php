<?php 

include 'header.php';

if(isset($_GET['postId'])){
	echo'<div id="content">';
	$encryptedPostId=mysqli_real_escape_string($conn,$_GET['postId']);
	$postId=($encryptedPostId-149118912)/149118912;
	if(isset($_SESSION['id'])){
		$friendsArray=$_SESSION['friendsArray'];
		$checkpostFriendName = mysqli_query($conn, "SELECT * FROM posts WHERE id = '$postId'");
		if(mysqli_num_rows($checkpostFriendName)>0){
			while($rowcheckPostFriendName = mysqli_fetch_assoc($checkpostFriendName)){
				$postFriendUserName = $rowcheckPostFriendName['username'];
			}
		}
		if(in_array($postFriendUserName, $friendsArray)){
			$checkPosts=mysqli_query($conn,"SELECT * FROM posts WHERE id='$postId' AND (privacy != 'private' AND (privacy = 'friends' OR privacy = 'public'))");
		}else{
			$checkPosts=mysqli_query($conn,"SELECT * FROM posts WHERE id='$postId' AND (privacy != 'private' AND privacy != 'friends')");
		}
	}else{
		$checkPosts=mysqli_query($conn,"SELECT * FROM posts WHERE id='$postId' AND (privacy != 'private' AND privacy != 'friends')");
	}
	if(mysqli_num_rows($checkPosts)>0){
		while($rowPost=mysqli_fetch_assoc($checkPosts)){
			echo'
			<div class="individualPostDiv">
				<div class="imageDiv">
					<img src="'.$rowPost['photo'].'" class="individualImageDiv">
				</div>
				<div id="captionDiv">';
					$postUsername=$rowPost['username'];
					echo'<div id="postDivOf'.$rowPost['id'].'" class="captionDiv">';
						echo '<div class="postHeading">
							<div style="display:flex">';getProfilePicture($conn,$postUsername);
								echo '<div class="postProfileDetails">
									'.getFriendsFullName($postUsername,$conn);
									if($rowPost['type']=='profilePicture'){echo' has changed Profile Picture';}
									echo'<div class="postDate">'.date('d-m H:i',strtotime($rowPost['dateTimeUploaded'])).'</div>
								</div>
							</div>
							<div class="postMenuDiv">';
								if($rowPost['username']==$username){
									echo'<div id="menuIconFor'.$rowPost['id'].'" class="hamburgerMenuIcon" onclick="showMenuOptions('.$rowPost['id'].')">
										<div class="dotA"></div>
										<div class="dotB"></div>
										<div class="dotC"></div>
									</div>
									<div id="menuOptionsFor'.$rowPost['id'].'" class="menuOptions" style="display:none">
										<button id="'.$rowPost['id'].'EditButton" class="likeUserDetails" onclick="showPostEditDiv(\''.$rowPost['id'].'\',\''.$rowPost['caption'].'\')">edit</button><br>
										<button id="'.$rowPost['id'].'DeleteButton" class="likeUserDetails" onclick="showPostDeleteDiv(\''.$rowPost['id'].'\')">delete</button>
										<div id="'.$rowPost['id'].'PrivacyButton" class="likeUserDetails" onclick="showPostPrivacyDiv(\''.$rowPost['id'].'\')">privacy</div>
									</div>';
								}else{
									echo'<div></div>';//For Maintaining Flex Position
								}
							echo'</div>
						</div>';
						echo'<div id="postCaption'.$rowPost['id'].'" class="PostCaption">'.$rowPost['caption'].'</div>';
						echo'<div class="postActionsDetails">';
							$likesCount=getLikesCount($conn,$rowPost['id']);
							$commentsCount=getCommentsCount($conn,$rowPost['id']);
							if($likesCount>0){echo'<span id="postLikesCount'.$rowPost['id'].'" class="postLikesDetails" onclick="showLikesDetails(\''.$rowPost['id'].'\')">'.getLikesCount($conn,$rowPost['id']).' Likes </span>';
							echo'<div>'.getLikesDetails($conn,$rowPost['id']).'</div>';
							}else{
								echo'<span id="postLikesCount'.$rowPost['id'].'" class="postLikesDetails" onclick="showLikesDetails(\''.$rowPost['id'].'\')"></span>';
								echo'<div>'.getLikesDetails($conn,$rowPost['id']).'</div>';
							}
							if($commentsCount>0){echo'<span id="postCommentsCount'.$rowPost['id'].'" class="postCommentsDetails"';?> onclick="
								if(document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display=='none'){
									document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display='block';
								}else if(document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display=='block'){
									document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display='none';
								}
								"  <?php echo'>'.getCommentsCount($conn,$rowPost['id']).' Comments</span>';}
												else{
													echo'<span id="postCommentsCount'.$rowPost['id'].'" class="postCommentsDetails"';?> onclick="
								if(document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display=='none'){
									document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display='block';
								}else if(document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display=='block'){
									document.getElementById('post<?php echo $rowPost['id'];?>commentsDiv').style.display='none';
								}
								"  <?php echo'></span>';
							}
						echo'</div>
						<div class="postActions">';
							if(isset($_SESSION['id'])){
								likeActionButton($conn,$rowPost['id'],$userId,$username);
								$showComments=1;
								commentActionButton($conn,$rowPost['id'],$userId,$username,$showComments);
							}else{
								echo'<a class="coolButton" href="index.php">Login for More</a>';
								$encryptedPostId=(149118912*$rowPost['id'])+149118912;
								echo'<input type="hidden" id="link'.$encryptedPostId.'" value="https://socialsite14911.000webhostapp.com/posts.php?postId='.$encryptedPostId.'">
								<div class="shareButtonForm"><button class="shareButton" onclick="copy('.'link'.$encryptedPostId.')">Share</button>
								<div id="messagePopuplink'.$encryptedPostId.'" class="messagePopup" style="display:none">Link Copied!</div></div>';
							}?>
							<script type="text/javascript">
								document.getElementById('post<?php echo $postId;?>commentsDiv').style.display='block';
							</script>
						<?php echo'</div>
					</div>';
				echo'</div>
			</div>
			';
		}
	}else{
		$privatePost=0;
		$checkPrivacy=mysqli_query($conn,"SELECT * FROM posts WHERE id='$postId' AND (privacy = 'private' OR privacy = 'friends')");
		if(mysqli_num_rows($checkPrivacy)>0){$privatePost=1;}
		echo'<div style="display:flex;margin-left:auto;margin-right:auto;justify-content:center;align-content:center;align-items:center;flex-direction:column;width:50%;background-color:gainsboro;box-shadow:0 0 2px 1px grey;margin-top:20px">
			<h3>Seems Like Page is broken..</h3>';
			if($privatePost==1){
				echo '<h3> or Post is Private..</h3><br>';
				if(!isset($_SESSION['id'])){echo'Login to view post..';}
			}
			echo'
			<span class="loaderButton"></span>
			<a href="index.php" class="headerButtons">';
				if(!isset($_SESSION['id'])){echo'Login';}else{echo'Home';}
			echo'</a><br>
		</div>';
	}
	echo'</div>';
}


include 'footer.php';

?>
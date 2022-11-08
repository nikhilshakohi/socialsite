<?php

if(isset($_SESSION['id'])){
	friendsListArray($conn,$username);
	$friendsArray=$_SESSION['friendsArray'];
	echo'<div id="pageLoader" class="loaderButton loaderButtonBig"></div>';
	echo'<div id="content" style="display:none">';
	
		echo'<div id="contentLeft">';
		echo'<span id="friendRequestsListDivHomePage">';
			friendRequests($conn,$username);
			echo'<script>document.getElementById("friendsRequestedDiv").style.display="none"</script>';
			echo'</span>';
		echo'</div>';



		echo'<div id="contentCenter">';
		//Add posts or message
		echo'<form id="addPostForm" method="POST" enctype="multipart/form-data">
			<a href="profile.php"><img id="profilePictureAtUploadButton" class="profilePic" src="'.getProfilePictureLocation($conn,$username).'"></a>
			<textarea id="postCaptionEntry'.$username.'" class="postCaptionEntry" placeholder="Write Something or caption" name="caption"></textarea><br>
			<input id="postUploadFile" type="file" name="fileName" style="display:none">
			<label class="miniButtons addPictureButton" for="postUploadFile">Add<br>Picture</label>
			<select id="postPrivacy'.$username.'" class="miniButtons postPrivacyButton" style="padding:5px;margin:5px">
				<option value="public">&#127758;</option><option value="private">&#128274;</option><option value="friends">&#128101;</option>
			</select>
			<script>document.getElementById("postUploadFile").onchange=function(){
				var path=this.value;';?> path=path.replace(/^.*\\/,""); <?php echo' 
				document.getElementById("getUploadedFileName").innerHTML=path;
				document.getElementById("getUploadedFileName").title=path;
				}</script>
			<div id="getUploadedFileName"></div>
			<input type="hidden" name="type" value="post">
			<button id="profilePictureSubmitOf'.$username.'" class="coolMiniButton postUploadButton" type="button" name="profilePicUpload" onclick="addPost(\''.$username.'\',\''."post".'\')">Upload</button>
			<button class="coolMiniButton captionDoneButton" type="button">Done</button>

		</form>';
		if(isset($_POST['uploadPost'])){
			if(!file_exists('posts/'.$username.'/posts')){
				mkdir('posts/'.$username.'/posts',0777,true);
			}
			$path='posts/'.$username.'/posts/'.basename($_FILES['fileName']['name']);
			if(move_uploaded_file($_FILES['fileName']['tmp_name'], $path)){
				if(!empty($_POST['caption'])){$caption=mysqli_real_escape_string($conn,$_POST['caption']);}
				else{$caption='';}
				$type=$_POST['type'];
				date_default_timezone_set('Asia/Kolkata');
				$dateUploaded=date('Y-m-d H:i:s');
				mysqli_query($conn,"INSERT INTO posts (username, photo, type, caption, dateTimeUploaded) VALUES ('$username', '$path', '$type', '$caption', '$dateUploaded')");
				header('Location:profile.php?ImageUploaded');
				exit();
			}else{
				echo'<span clas="errorMessage">File is too large to upload..</span>';
			}
		}
		$allPosts=mysqli_query($conn,"SELECT * FROM posts WHERE (username='$username') OR (username!='$username' AND privacy != 'private') ORDER BY id DESC;");
		$userPosts=mysqli_query($conn,"SELECT * FROM posts WHERE username='$username'");
		$checkCount=0;
		if(mysqli_num_rows($allPosts)>0){
			echo'<div id="newPostsAfterFriends" style="display:none"';?> onclick="window.location.href='index.php'" <?php echo' class="coolButton">New Posts</div>';
			while($rowPost=mysqli_fetch_assoc($allPosts)){

				if(in_array($rowPost['username'], $friendsArray)){

					/*Search for Profile Picture*/
					$postUsername=$rowPost['username'];
					echo'<span id="newPostAdded"></span>';/*For AJAX posts*/
					echo'<div id="postDivOf'.$rowPost['id'].'" class="postDiv">';
						echo '<div class="postHeading">
							<div style="display:flex">';getProfilePicture($conn,$postUsername);
								echo '<div class="postProfileDetails">
									<div><form class="accountLinkForm" method="GET" action="userprofile.php">
										<input type="hidden" name="userName" value="'.$postUsername.'">
										<button type="submit" class="postProfileName">'.getFriendsFullName($postUsername,$conn);
									echo'</button></form>';
									if($rowPost['type']=='profilePicture'){echo' has changed Profile Picture';}
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
									echo'</div>
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
										<div id="'.$rowPost['id'].'EditButton" class="likeUserDetails" onclick="showPostEditDiv(\''.$rowPost['id'].'\')">edit</div>
										<div id="'.$rowPost['id'].'DeleteButton" class="likeUserDetails" onclick="showPostDeleteDiv(\''.$rowPost['id'].'\')">delete</div>
										<div id="'.$rowPost['id'].'PrivacyButton" class="likeUserDetails" onclick="showPostPrivacyDiv(\''.$rowPost['id'].'\')">privacy</div>
									</div>';
								}else{
									echo'<div></div>';//For Maintaining Flex Position
								}
							echo'</div>
						</div>';
						echo'<div id="postCaption'.$rowPost['id'].'" class="PostCaption">'.$rowPost['caption'].'</div>';
						$encryptedPostId=(149118912*$rowPost['id'])+149118912;
						echo'<form method="GET" action="posts.php"><input type="hidden" name="postId" value="'.$encryptedPostId.'"><button type="submit" class="postImageDiv"><img loading="lazy" class="postImage" src="'.$rowPost['photo'].'"></button></form>';
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
								"  <?php echo'>'.getCommentsCount($conn,$rowPost['id']).' Comments</span>';
							}else{
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
							likeActionButton($conn,$rowPost['id'],$userId,$username);
							$showComments=1;
							commentActionButton($conn,$rowPost['id'],$userId,$username,$showComments);
						echo'</div>
					</div>';

				}else if(($checkCount==0)&&(mysqli_num_rows($userPosts)<1)){
					echo'<div id="newPostAdded" class="postDiv">No Posts added yet..</div>';
					$checkCount=1;
				}
			}
		}else{
			echo'<div id="newPostAdded" class="postDiv">No Posts Added yet..</div>';	
		}
		echo'</div>';

		echo'<div id="contentRight">';
			echo'<span id="friendListDivHomePage">';
			friendsList($conn,$username);
			echo'</span>';
		echo'</div>

	</div>';
}

?>
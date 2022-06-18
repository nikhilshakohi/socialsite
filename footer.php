		<footer id="footer">
			<div id="footerDiv" style="display: flex;justify-content: center;align-content: center;align-items: center;width: 80%;margin:auto;">
				<div style="width: 25%;">
					<h1>Social Site</h1>
				</div>
				<div style="width: 37.5%;">
					<b style="text-decoration: underline;">Pages</b><br>
					<a class="coolButtonFooter" href="index.php">Home</a><br>
					<a class="coolButtonFooter" href="profile.php">Profile</a><br>
					<a class="coolButtonFooter" href="messenger.php">Messenger</a><br>
				</div>
				<div style="width: 37.5%;">
					<b style="text-decoration: underline;">About</b><br>
					<a class="coolButtonFooter" href="about.php">About Social Site</a><br>
					<a class="coolButtonFooter" href="#" onclick="toggleFeedbackForm()">Feedback</a><br>
					<a class="coolButtonFooter" target="_blank" href="https://github.com/nikhilshakohi/socialsite">Source Code</a><br>
				</div>
			</div>
	</footer>
</body>
<script>

	function copy(postId){
		var link=document.getElementById(postId.id);	
		if(postId.value!=''){
			setTimeout(function(){document.getElementById("messagePopup"+postId.id).style.display="none";},3000)
			document.getElementById("messagePopup"+postId.id).style.display="block";
			document.getElementById("messagePopup"+postId.id).innerHTML='Link Copied to Clipboard!';/*<b>'+link.value+'</b>*/
		}else{
			alert("No Link Found..!");
		}	
		document.getElementById(postId.id).type="text";
		link.select();document.execCommand("copy");document.getElementById(postId.id).type="hidden";
	}

	function showContent(){
		document.getElementById("pageLoader").style.display="none";
		document.getElementById("content").style.display="flex";
	}

	function showMenuOptions(postId){
		if(document.getElementById('menuOptionsFor'+postId).style.display=='block'){
			document.getElementById('menuOptionsFor'+postId).style.display='none';
		}else{
			document.getElementById('menuOptionsFor'+postId).style.display='block';
		}
	}
	function showMenuOptionsOfComment(commentId){
		if(document.getElementById('menuOptionsForCommentOf'+commentId).style.display=='inline-flex'){
			document.getElementById('menuOptionsForCommentOf'+commentId).style.display='none';
		}else{
			document.getElementById('menuOptionsForCommentOf'+commentId).style.display='inline-flex';
		}
	}

	function showPostDeleteDiv(postId){
		var postId = postId;
		var showPostDelete = 'DummyText';
		document.getElementById(postId+'DeleteButton').innerHTML="Loading..";
		var data = 'showPostDelete='+showPostDelete+'&postId='+postId;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		document.getElementById("deletePostDiv").style.display="block";
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					document.getElementById("deletePostDiv").innerHTML=this.responseText;
					document.getElementById(postId+'DeleteButton').innerHTML="delete";
				}
			}
		}
	}
	function confirmDeletePost(postId){
		var postId = postId;
		var confirmedDeletePost = 'DummyText';
		var data = 'confirmedDeletePost='+confirmedDeletePost+'&postId='+postId;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					document.getElementById('postDivOf'+postId).style.padding='0';
					document.getElementById('postDivOf'+postId).innerHTML='deleted';
					setTimeout(function(){document.getElementById('postDivOf'+postId).innerHTML='';},1500);
					document.getElementById("deletePostDiv").style.display="none";
				}
			}
		}	
	}

	/*Change Privacy*/
	function showPostPrivacyDiv(postId){
		var postId = postId;
		var showPostPrivacy = 'DummyText';
		document.getElementById(postId+'PrivacyButton').innerHTML="Loading..";
		var data = 'showPostPrivacy='+showPostPrivacy+'&postId='+postId;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		document.getElementById("privacyPostDiv").style.display="block";
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					document.getElementById("privacyPostDiv").innerHTML=this.responseText;
					document.getElementById(postId+'PrivacyButton').innerHTML="privacy";
				}
			}
		}
	}
	function confirmUpdatePrivacyPost(postId){
		var postId = postId;
		UpdatedPrivacyStatus = document.getElementById("UpdatedPrivacyStatus"+postId).value;
		var confirmUpdatePrivacyPost = 'DummyText';
		var data = 'confirmUpdatePrivacyPost='+confirmUpdatePrivacyPost+'&postId='+postId+"&UpdatedPrivacyStatus="+UpdatedPrivacyStatus;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					var output = this.responseText;
					document.getElementById('confirmUpdatePrivacyPost'+postId).innerHTML='<div class = "loaderButton"></div>';
					setTimeout(function(){
						document.getElementById('confirmUpdatePrivacyPost'+postId).innerHTML=output;
					},1000);
					setTimeout(function(){
						document.getElementById("privacyPostDiv").style.display="none";
					},1500);

					if(UpdatedPrivacyStatus=='public'){
						document.getElementById("postPrivacyShow"+postId).innerHTML='<span title="This Post is visible to anyone" class = "postPrivacyStyle">&nbsp;&nbsp;&#127758;</span>';
					}else if(UpdatedPrivacyStatus=='private'){
						document.getElementById("postPrivacyShow"+postId).innerHTML='<span title="This Post is visible to only you" class = "postPrivacyStyle">&nbsp;&nbsp;&#128274;</span>';
					}else if(UpdatedPrivacyStatus=='friends'){
						document.getElementById("postPrivacyShow"+postId).innerHTML='<span title="This Post is visible to only your friends" class = "postPrivacyStyle">&nbsp;&nbsp;&#128101;</span>';
					}

				}
			}
		}	
	}

	/*Delete Comment*/
	function showCommentDeleteDiv(commentId){
		var commentId = commentId;
		var showCommentDelete = 'DummyText';
		document.getElementById(commentId+'commentDeleteButton').innerHTML="Loading..";
		var data = 'showCommentDelete='+showCommentDelete+'&commentId='+commentId;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		document.getElementById("deleteCommentDiv").style.display="block";
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					document.getElementById("deleteCommentDiv").innerHTML=this.responseText;
					document.getElementById(commentId+'commentDeleteButton').innerHTML="delete";
				}
			}
		}
	}
	function confirmDeleteComment(commentId){
		var commentId = commentId;
		var confirmedDeleteComment = 'DummyText';
		document.getElementById("confirmDeleteComment"+commentId).innerHTML="deleting...";
		var data = 'confirmedDeleteComment='+confirmedDeleteComment+'&commentId='+commentId;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					eachResponse=this.responseText.split('-period-');
					document.getElementById("postCommentsCount"+eachResponse[0]).innerHTML=eachResponse[1];
		      	  	document.getElementById('commentDivOf'+commentId).innerHTML='deleted';
					setTimeout(function(){document.getElementById('commentDivOf'+commentId).innerHTML='';},1500);
					document.getElementById("deleteCommentDiv").style.display="none";
				}
			}
		}	
	}

	function showThisProfileDiv(type){
		document.getElementById('userProfileDetailsDiv').style.display='none';
		document.getElementById('userProfilePostsDiv').style.display='none';
		document.getElementById('userProfileFriendsDiv').style.display='none';
		document.getElementById('userProfileFriendRequestsDiv').style.display='none';
		document.getElementById('userProfileDetailsButton').style.color='black';
		document.getElementById('userProfilePostsButton').style.color='black';
		document.getElementById('userProfileFriendsButton').style.color='black';
		document.getElementById('userProfileFriendRequestsButton').style.color='black';
		document.getElementById('userProfileDetailsButtonDiv').style.borderBottom='none';
		document.getElementById('userProfilePostsButtonDiv').style.borderBottom='none';
		document.getElementById('userProfileFriendsButtonDiv').style.borderBottom='none';
		document.getElementById('userProfileFriendRequestsButtonDiv').style.borderBottom='none';

		document.getElementById('userProfile'+type+'Div').style.display='block';
		document.getElementById('userProfile'+type+'Button').style.color='green';
		document.getElementById('userProfile'+type+'ButtonDiv').style.borderBottom='5px solid green';
	}

	function showLikesDetails(postId){
		if(document.getElementById(postId+'LikeDetail').style.display=="none"){
			document.getElementById('postLikesCount'+postId).style.backgroundColor="rgba(180,180,180,0.7)";
			document.getElementById(postId+'LikeDetail').style.display="block";
		}else if(document.getElementById(postId+'LikeDetail').style.display=="block"){
			document.getElementById(postId+'LikeDetail').style.display="none";
			document.getElementById('postLikesCount'+postId).style.backgroundColor="rgba(230, 230, 230, 1.0)";
		}
	}

	function showPostEditDiv(postId){
		var postId = postId;
		var caption = document.getElementById("postCaption"+postId).innerHTML;
		var showPostCaption = 'DummyText';
		document.getElementById(postId+'EditButton').innerHTML="Loading..";
		var data = 'showPostCaption='+showPostCaption+'&postId='+postId+'&caption='+caption;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		document.getElementById("editPostDiv").style.display="block";
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					document.getElementById("editPostDiv").innerHTML=this.responseText;
					document.getElementById("editedCaption"+postId).innerHTML=caption;
					document.getElementById(postId+'EditButton').innerHTML="edit";
				}
			}
		}
	}

	function editedPostCaption(postId){
		var postId = postId;
		var caption = document.getElementById("editedCaption"+postId).value;
		var editPost = 'DummyText';
		var data = 'editPost='+editPost+'&postId='+postId+'&caption='+caption;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					var output=this.responseText;
					document.getElementById("menuOptionsFor"+postId).style.display="none";
					document.getElementById("editPostDiv").style.display="none";
					document.getElementById('postCaption'+postId).innerHTML="<div style='display:inline-flex' class='loaderButton'></div>";
					setTimeout(function(){document.getElementById('postCaption'+postId).innerHTML=output;},500);
				}
			}
		}	
	}

	/*Edit Comment*/
	function showCommentEditDiv(commentId,comment){
		var commentId = commentId;
		var comment = document.getElementById("postComment"+commentId).innerHTML;
		var showComment = 'DummyText';
		document.getElementById(commentId+'commentEditButton').innerHTML="Loading..";
		var data = 'showComment='+showComment+'&commentId='+commentId+'&comment='+comment;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		document.getElementById("editCommentDiv").style.display="block";
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					document.getElementById("editCommentDiv").innerHTML=this.responseText;
					document.getElementById("editedComment"+commentId).innerHTML=comment;
					document.getElementById(commentId+'commentEditButton').innerHTML="edit";
				}
			}
		}
	}

	function editedComment(commentId){
		var commentId = commentId;
		var comment = document.getElementById("editedComment"+commentId).value;
		document.getElementById("editedCommentConfirmButton"+commentId).innerHTML="<div class='loaderButton'></div>"
		var editComment = 'DummyText';
		var data = 'editComment='+editComment+'&commentId='+commentId+'&comment='+comment;
		if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
		else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					var output=this.responseText;
					document.getElementById("menuOptionsForCommentOf"+commentId).style.display="none";
					document.getElementById("editCommentDiv").style.display="none";
					document.getElementById('postComment'+commentId).innerHTML="<div style='display:inline-flex' class='loaderButton'></div>";
					setTimeout(function(){document.getElementById('postComment'+commentId).innerHTML=output;},500);
				}
			}
		}	
	}

	function closeEditDiv(){
		document.getElementById("editPostDiv").style.display="none";
	}
	function closeEditCommentDiv(){
		document.getElementById("editCommentDiv").style.display="none";
	}
	function closeDeleteDiv(){
		document.getElementById("deletePostDiv").style.display="none";
	}
	function closePrivacyDiv(){
		document.getElementById("privacyPostDiv").style.display="none";
	}
	function closeDeleteCommentDiv(){
		document.getElementById("deleteCommentDiv").style.display="none";
	}

	function likeSubmit(postId,likeStatus){
		var likeStatus=likeStatus;
		var postId = postId;
		if(likeStatus==1){
			document.getElementById("likeDiv"+postId).innerHTML="<div class='loaderButton' style='display:inline-flex'></div>";
		}else{
			document.getElementById("unLikeDiv"+postId).innerHTML="<div class='loaderButton' style='display:inline-flex'></div>";
		}
		var userId = document.getElementById("userId"+postId).value;
		var userFullName = document.getElementById("userFullName"+postId).value;
		//Declare XML Request
		if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
		else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		var likeSubmit="";var unLikeSubmit="";//Declaring variables for POST Submit
		if(likeStatus==1){
		  	var data = "likeSubmit="+likeSubmit+"&postId="+postId+"&userId="+userId+"&userFullName="+userFullName;	
		}else{
		  	var data = "unLikeSubmit="+unLikeSubmit+"&postId="+postId+"&userId="+userId+"&userFullName="+userFullName;
		}
		xhr.open("POST", "conditions.php", true); 
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4) {
		      if (xhr.status == 200) {
		      	if(likeStatus==1){
		      		var eachResponse=this.responseText.split('-period-');
				  	document.getElementById('likeDiv'+postId).style.display="none";
		      		document.getElementById('unLikeDiv'+postId).style.display="block";
		      		document.getElementById('unLikeDiv'+postId).classList.add("likedAnimation");
		      		document.getElementById("postLikesCount"+postId).innerHTML=eachResponse[0];
		      		document.getElementById(postId+"newLikeDetail").innerHTML=eachResponse[1];
		      		document.getElementById("likeDiv"+postId).innerHTML="Like";
				  }else{
				  	var eachResponse=this.responseText.split('-period-');
				  	document.getElementById('likeDiv'+postId).style.display="block";
		      		document.getElementById('unLikeDiv'+postId).style.display="none";
		      		document.getElementById("postLikesCount"+postId).innerHTML=eachResponse[0];
		      		document.getElementById(postId+"newLikeDetail").innerHTML=eachResponse[1];
		      		document.getElementById("unLikeDiv"+postId).innerHTML="Liked!";
				  }
		      } else {
		          alert("There was a problem with the request.");
		      }
		    }
		}
	}

	function connectionToggle(userA,userB,connectionId,connectionStatus){
		var connectionStatus=connectionStatus;
		var userA = userA;
		var userB = userB;
		var connectionId = connectionId;
		if(connectionStatus==1){
			document.getElementById("addConnection"+userA+userB).innerHTML="<div class='loaderButton'></div>";
		}else{
			document.getElementById("confirmConnection"+userA+userB).innerHTML="<div class='loaderButton'></div>";
		}
		//Declare XML Request
		if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
		else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		var addConnection="";var updateConnection="";//Declaring variables for POST Submit
		if(connectionStatus==1){
		  	var data = "addConnection="+addConnection+"&userA="+userA+"&userB="+userB+"&connectionStatus="+connectionStatus;	
		}else{
		  	var data = "updateConnection="+updateConnection+"&connectionId="+connectionId+"&connectionStatus="+connectionStatus+"&userA="+userA+"&userB="+userB;
		}
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4) {
		      if (xhr.status == 200) {
		      	if(connectionStatus==1){
		      		document.getElementById("addConnection"+userA+userB).style.display="none";
				  	document.getElementById("yetConfirmConnection"+userA+userB).style.display="block";
				  }else{
				  	document.getElementById("confirmConnection"+userA+userB).style.display="none";
				  	document.getElementById("confirmedConnection"+userA+userB).style.display="block";
				  	document.getElementById("showFriendsAfterConfirm").innerHTML+=this.responseText;/*friends appended to friendsList*/
				  	document.getElementById("newPostsAfterFriends").style.display="block";
				  }
		      } else {
		          alert("There was a problem with the request.");
		      }
		    }
		}
	}

	/*Add Comments*/
	function addComment(postId,userId,userFullName){
		var postId=postId;
		var userId = userId;
		var userFullName = userFullName;
		document.getElementById('commentSubmitOf'+userId+postId).innerHTML="<span class='loaderButton'></span>";
		//Declare XML Request
		if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
		else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		var userComment=document.getElementById('postOf'+userId+postId).value;
		if(userComment!=''){
			var addComment="DummyText";//Declaring variables for POST Submit
			var data = "addComment="+addComment+"&postId="+postId+"&userId="+userId+"&userFullName="+userFullName+"&userComment="+userComment;	
			xhr.open("POST", "conditions.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(data);
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4) {
			      if (xhr.status == 200) {
			      	eachResponse=this.responseText.split('-period-');
			      	document.getElementById("postCommentsCount"+postId).innerHTML=eachResponse[0];
		      	  	document.getElementById('newCommentOf'+userId+postId).innerHTML+=eachResponse[1];/*new comment appended to comments*/
		      	  	document.getElementById('postOf'+userId+postId).value='';
		      	  	document.getElementById('commentSubmitOf'+userId+postId).innerHTML="Submit";
			      } else {
			          alert("There was a problem with the request.");
			      }
			    }
			}
		}else{
			document.getElementById('commentSubmitOf'+userId+postId).innerHTML="Submit";
		}
	}

	/*Add Profile Picture*/
	function addPost(username,type){
		var username=username;
		var type = type;
		var caption = document.getElementById('postCaptionEntry'+username).value;
		var postPrivacy = document.getElementById('postPrivacy'+username).value;
		document.getElementById('profilePictureSubmitOf'+username).innerHTML="<span class='loaderButton'></span>";
		var myFile = document.getElementById('postUploadFile');
	    /*If User selects only to write and no Image Uploaded*/
	    if(myFile.value==''){
	    	//Declare XML Request
			if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
			else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
			var uploadPostOnlyCaption="DummyText";//Declaring variables for POST Submit
			var data = "uploadPostOnlyCaption="+uploadPostOnlyCaption+"&username="+username+"&caption="+caption+"&type="+type+"&postPrivacy="+postPrivacy;	
			xhr.open("POST", "conditions.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(data);
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4) {
			      if (xhr.status == 200) {
				    if(document.getElementById('newPostAdded').innerHTML!="No Posts Added yet.."){	
			      		document.getElementById('newPostAdded').innerHTML=this.responseText+document.getElementById('newPostAdded').innerHTML;
			      	}else{
			      		document.getElementById('newPostAdded').innerHTML=this.responseText;
			      	}
			      	document.getElementById('postCaptionEntry'+username).value='';
			      	document.getElementById('getUploadedFileName').innerHTML='';
			      	document.getElementById('profilePictureSubmitOf'+username).innerHTML='Upload';
			      } else {
			          alert("There was a problem with the request.");
			      }
			    }
			}
	    }else{
	    	var files = myFile.files;
		    var formData = new FormData();
		    var file = files[0]; 
		    formData.append('postUploadFile', file, file.name);
		    //Declare XML Request
			if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
			else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
			var uploadPost="DummyText";//Declaring variables for POST Submit
			var data = "uploadPost="+uploadPost+"&username="+username+"&caption="+caption+"&type="+type+"&postPrivacy="+postPrivacy;	
			xhr.open("POST", "conditions.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(data);
			/*For File*/
			if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhrn = new XMLHttpRequest();} 
			else if (window.ActiveXObject) { /*IE 8 and older*/ var xhrn = new ActiveXObject("Microsoft.XMLHTTP");}
			xhrn.open("POST", "conditions.php", true);
			xhrn.send(formData);
			xhrn.onreadystatechange = function(){
				if(xhrn.readyState == 4) {
			      if (xhrn.status == 200) {
				    eachResponse=this.responseText.split('-period-');
			      	if(eachResponse[0]!='UploadError'){
				      	if(type=='profilePicture'){
				      		document.getElementById('profilePicture').src=eachResponse[0];
				      		document.getElementById('profilePicImage').src=eachResponse[0];
				      		document.getElementById('headerUserProfilePicture').src=eachResponse[0];
				      	}
				      	if(document.getElementById('newPostAdded').innerHTML!="No Posts Added yet.."){	
				      		document.getElementById('newPostAdded').innerHTML=eachResponse[1]+document.getElementById('newPostAdded').innerHTML;
				      	}else{
				      		document.getElementById('newPostAdded').innerHTML=eachResponse[1];
				      	}
				      	document.getElementById('postCaptionEntry'+username).value='';
				      	document.getElementById('getUploadedFileName').innerHTML='';
				      	document.getElementById('profilePictureSubmitOf'+username).innerHTML='Upload';
			      	}else{
				      	document.getElementById('postCaptionEntry'+username).value='';
				      	document.getElementById('getUploadedFileName').innerHTML='';
				      	document.getElementById('profilePictureSubmitOf'+username).innerHTML='File too Big';
			      		setTimeout(function(){document.getElementById('profilePictureSubmitOf'+username).innerHTML='Upload';},2000);
			      	}
			      } else {
			          alert("There was a problem with the request.");
			      }
			    }
			}
	    }
	}

	/*Check Messages*/
	function checkMessages(currentUsername,friendUsername){
		var currentUsername=currentUsername;
		var friendUsername=friendUsername;
		document.getElementById("contentRightPart").style.display="block";
		document.getElementById("contentLeftPart").classList.add("hideInSmall");
		document.getElementById(friendUsername+'Messageloader').innerHTML="<div class='loaderButton'></div>";
		document.getElementById("contentRightPart").innerHTML="<div class='loaderButton'></div>";
		//Declare XML Request
		if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
		else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		var checkMessages="DummyText";//Declaring variables for POST Submit
		var data = "checkMessages="+checkMessages+"&currentUsername="+currentUsername+"&friendUsername="+friendUsername;	
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4) {
		      if (xhr.status == 200) {
		      	var friendButtons=document.getElementsByClassName('friendLinkDiv');
		      	for(var i=0;i<friendButtons.length;i++){
		      		friendButtons[i].style.backgroundColor="";
		      		friendButtons[i].style.color="black";
		      	}
		      	document.getElementById("selectFriendButton"+friendUsername).style.backgroundColor="rgba(70,70,150,0.7)";
		      	document.getElementById("selectFriendButton"+friendUsername).style.color="white";
		      	/*New Message Tag*/
		      	document.getElementById(friendUsername+"newMsgTag"+currentUsername).style.display="none";
		      	eachResponse=this.responseText.split("-period-");
		      	document.getElementById("contentRightPart").innerHTML=eachResponse[0];
		      	document.getElementById("newMessageCount").innerHTML=eachResponse[1];
	      	  	document.getElementById(friendUsername+'Messageloader').innerHTML="";
	      	  	var currentUsernameId=document.getElementById(currentUsername+"getCurrentUserId"+friendUsername).innerHTML;
	      	  	var friendUsernameId=document.getElementById(currentUsername+"getFriendUserId"+friendUsername).innerHTML;
	      	  	document.getElementById(currentUsernameId+"chatInput"+friendUsernameId).focus();
				setInterval(checkMessageContinously,2000,currentUsernameId,friendUsernameId);
		      } else {
		          alert("There was a problem with the request.");
		      }
		    }
		}
	}

	function checkMessageContinously(currentUserId,friendUserId){
		var currentUserId=currentUserId;
		var friendUserId=friendUserId;
		//Declare XML Request
		if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
		else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		var checkMessagesContinuosly="DummyText";//Declaring variables for POST Submit
		var data = "checkMessagesContinuosly="+checkMessagesContinuosly+"&currentUserId="+currentUserId+"&friendUserId="+friendUserId;	
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4) {
		      if (xhr.status == 200) {
		      	document.getElementById("messageMiddlePart"+friendUserId).innerHTML=this.responseText;
		      } else {
		          alert("There was a problem with the request.");
		      }
		    }
		}
	}

	/*Show Friends in Messages*/
	function showMessagesDiv(){
		document.getElementById("contentRightPart").style.display="none";
		document.getElementById("contentLeftPart").classList.remove("hideInSmall");
	}

	/*Send Message*/
	function sendMessage(currentUserId,friendUserId){
		var currentUserId=currentUserId;
		var friendUserId=friendUserId;
		var message=document.getElementById(currentUserId+'chatInput'+friendUserId).value;
		document.getElementById(currentUserId+'chatInputSend'+friendUserId).innerHTML="<div class='loaderButton'></div>";
		//Declare XML Request
		if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
		else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		var sendMessage="DummyText";//Declaring variables for POST Submit
		var data = "sendMessage="+sendMessage+"&currentUserId="+currentUserId+"&friendUserId="+friendUserId+"&message="+message;	
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4) {
		      if (xhr.status == 200) {
		      	document.getElementById("messageMiddlePart"+friendUserId).innerHTML=this.responseText;
		      	document.getElementById(currentUserId+'chatInput'+friendUserId).value='';
	      	  	document.getElementById(currentUserId+'chatInputSend'+friendUserId).innerHTML="Send";
	      	  	document.getElementById(currentUserId+'chatInput'+friendUserId).focus();
		      } else {
		          alert("There was a problem with the request.");
		      }
		    }
		}
	}

	function toggleFeedbackForm(){
		if(document.getElementById("feedbackFormDiv").style.display=="block"){
			document.getElementById("feedbackFormDiv").style.display="none";
		}else{
			document.getElementById("feedbackFormDiv").style.display="block";	
		}
	}

	function sendFeedback(){
		var userFeedback=document.getElementById("feedbackInput").value;
		var userId=document.getElementById("feedbackUserId").value;
		var userFullName=document.getElementById("feedbackUserFullName").value;
		//Declare XML Request
		if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
		else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		var sendFeedback="DummyText";//Declaring variables for POST Submit
		var data = "sendFeedback="+sendFeedback+"&userId="+userId+"&userFullName="+userFullName+"&userFeedback="+userFeedback;	
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4) {
		      if (xhr.status == 200) {
		      	var output=this.responseText;
		      	setTimeout(function(){document.getElementById("feedbackContent").innerHTML=output;},2000);
		      	document.getElementById("feedbackContent").innerHTML="<br><br><div class='loaderButton'></div>";
		      } else {
		          alert("There was a problem with the request.");
		      }
		    }
		}
	}

	function forgotPassword(){
		if(document.getElementById("forgotPasswordDiv").style.display=="block"){
			document.getElementById("forgotPasswordDiv").style.display="none";
		}else{
			document.getElementById("forgotPasswordDiv").style.display="block";	
		}
	}

	function forgotPasswordSendEmail(){
		var emailId=document.getElementById("emailIdToBeRecovered").value;
		var tryAgain=document.getElementById("forgotPasswordContent").innerHTML;
		//Declare XML Request
		if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
		else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		var forgotPasswordSendEmail="DummyText";//Declaring variables for POST Submit
		var data = "forgotPasswordSendEmail="+forgotPasswordSendEmail+"&emailId="+emailId;	
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4) {
		      if (xhr.status == 200) {
		      	var eachResponse=this.responseText.split("-period-");
		      	setTimeout(function(){
		      		if(eachResponse[1]=='errorMessage'){
		      			document.getElementById("forgotPasswordContent").innerHTML=tryAgain+eachResponse[0];
		      		}else{
		      			document.getElementById("forgotPasswordContent").innerHTML=eachResponse[0];
		      		}
		      	},2000);
		      	document.getElementById("forgotPasswordContent").innerHTML="<br><br><div class='loaderButton'></div>";
		      } else {
		          alert("There was a problem with the request.");
		      }
		    }
		}
	}

	function signupUser(){
		var username = document.getElementById("signupUsername").value;
		var mobile = document.getElementById("signupMobile").value;
		var email = document.getElementById("signupEmail").value;
		var firstName = document.getElementById("signupFirstName").value;
		var lastName = document.getElementById("signupLastName").value;
		var password = document.getElementById("signupPassword").value;
		document.getElementById("signupSubmit").innerHTML = "<div class='loaderButton'></div>";

		/*Remove Previous Validation error messges*/
		document.getElementById("signupUsername").style.border ="none";
		document.getElementById("signupMobile").style.border ="none";
		document.getElementById("signupPassword").style.border ="none";
		document.getElementById("signupFirstName").style.border ="none";
		document.getElementById("signupLastName").style.border ="none";
		document.getElementById("signupEmail").style.border ="none";
		document.getElementById("loginStatusMessage").innerHTML = "";

		/*Validations*/
		if(username == '' || password == '' || firstName == '' || email == ''){ /*Empty Fields*/
			setTimeout(function(){document.getElementById("loginStatusMessage").innerHTML = "";},5000);
			document.getElementById("loginStatusMessage").innerHTML = "<span class='errorMessage'>Please Fill in all the fields</span>";
			document.getElementById("signupUsername").style.border = "1px solid orange";
			document.getElementById("signupPassword").style.border = "1px solid orange";
			document.getElementById("signupEmail").style.border = "1px solid orange";
			document.getElementById("signupFirstName").style.border = "1px solid orange";
		}else if(!/^[a-zA-Z0-9 ]+$/.test(username)){ /*Validate expressions*/
			setTimeout(function(){document.getElementById("loginStatusMessage").innerHTML = "";},5000);
			document.getElementById("loginStatusMessage").innerHTML = "<span class='errorMessage'>Invalid charecters in Username</span>";
			document.getElementById("signupUsername").style.border = "1px solid red";
		}else if(!/^[a-zA-Z0-9!@#$%^&*]{4,15}$/.test(password)){ /*Validate expressions*/
			setTimeout(function(){document.getElementById("loginStatusMessage").innerHTML = "";},5000);
			document.getElementById("loginStatusMessage").innerHTML = "<span class='errorMessage'>Invalid charecters in Password / Minimum 4 charecters are required</span>";
			document.getElementById("signupPassword").style.border = "1px solid red";
		}else if(!/^[a-zA-Z0-9.-_]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email)){ /*Validate expressions*/
			setTimeout(function(){document.getElementById("loginStatusMessage").innerHTML = "";},5000);
			document.getElementById("loginStatusMessage").innerHTML = "<span class='errorMessage'>Email not valid</span>";
			document.getElementById("signupEmail").style.border = "1px solid red";
		}else if(!/^[a-zA-Z ]*$/.test(firstName)){ /*Validate expressions*/
			setTimeout(function(){document.getElementById("loginStatusMessage").innerHTML = "";},5000);
			document.getElementById("loginStatusMessage").innerHTML = "<span class='errorMessage'>Invalid charecters in First Name</span>";
			document.getElementById("signupFirstName").style.border = "1px solid red";
		}else if(!/^[a-zA-Z ]*$/.test(lastName)){ /*Validate expressions*/
			setTimeout(function(){document.getElementById("loginStatusMessage").innerHTML = "";},5000);
			document.getElementById("loginStatusMessage").innerHTML = "<span class='errorMessage'>Invalid charecters in Last Name</span>";
			document.getElementById("signupLastName").style.border = "1px solid red";
		}else if(!/^[0-9 ]*$/.test(mobile)){ /*Validate expressions*/
			setTimeout(function(){document.getElementById("loginStatusMessage").innerHTML = "";},5000);
			document.getElementById("loginStatusMessage").innerHTML = "<span class='errorMessage'>Invalid charecters in Mobile (only numbers)</span>";
			document.getElementById("signupMobile").style.border = "1px solid red";
		}else{ /*If all above conditions are valid, User is signed up!*/
			/*AJAX Functionality*/
			/*Declare variables*/
			var signupCheck = "RandomInput";
			var data = "signupCheck="+signupCheck+"&username="+username+"&password="+password+"&firstName="+firstName+"&lastName="+lastName+"&email="+email+"&mobile="+mobile;
			/*Declare XML*/
			if(window.XMLHttpRequest){var xhr = new XMLHttpRequest();}
			else if(window.ActiveXObject){var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
			/*AJAX Methods*/
			xhr.open("POST","conditions.php",true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(data);
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4){
					if(xhr.status == 200){
						var eachResponse=this.responseText.split("-period-");
				      	if(eachResponse[1]=='success'){
				      		document.getElementById("loginStatusMessage").innerHTML=eachResponse[0];
				      		setTimeout(function(){window.location.href = "index.php"},2000);
				      	}else{
				      		document.getElementById("loginStatusMessage").innerHTML=eachResponse[0];
				      		setTimeout(function(){document.getElementById("loginStatusMessage").innerHTML='';},5000);
				      	}
					}
				}
			}
		}
		document.getElementById("signupSubmit").innerHTML = "SIGNUP";
	}

	//Login
	function loginUser(){
		var username=document.getElementById("loginUsername").value;
		var password=document.getElementById("loginPassword").value;
		document.getElementById("loginSubmit").innerHTML="<div class='loaderButton'></div>";
		//Declare XML Request
		if (window.XMLHttpRequest) { /*Mozilla, Safari*/ var xhr = new XMLHttpRequest();} 
		else if (window.ActiveXObject) { /*IE 8 and older*/ var xhr = new ActiveXObject("Microsoft.XMLHTTP");}
		var loginCheck="DummyText";//Declaring variables for POST Submit
		var data = "loginCheck="+loginCheck+"&username="+username+"&password="+password;
		xhr.open("POST", "conditions.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4) {
		      if (xhr.status == 200) {
		      	var eachResponse=this.responseText.split("-period-");
				if(eachResponse[1]=='success'){
					window.location.href="index.php?LoginSuccess";
				}else{
					document.getElementById("loginStatusMessage").innerHTML=eachResponse[0];
				}
				document.getElementById("loginSubmit").innerHTML="LOGIN";
		      } else {
		          alert("There was a problem with the request.");
		      }
		    }
		}
	}

	//Profile Pic Upload
	function openProfilePicUploader(){
		if(document.getElementById('profilePicUploader').style.display=='block'){
			document.getElementById('profilePicUploader').style.display='none';
		}else{
			document.getElementById('profilePicUploader').style.display='block';
		}; 
	}

</script>
</html>
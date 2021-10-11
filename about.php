<?php 

include 'header.php';

echo'<div id="content">
	<div id="contentLeft"></div>
	<div id="contentCenter">
		<h3>Hello there!</h3>
		<div class="postDiv" style="text-align:left;padding:20px 30px">
			<h4>About Site:</h4>
			<li style="list-style-type:circle">Social site is a social website developed using </li>
			<div style="display:flex;">
				<div style="width:20%"></div>
				<div style="width:30%">
					<li>HTML</li>
					<li>CSS</li>
					<li>Vanilla JS</li>
				</div>
				<div style="width:30%">
					<li>AJAX</li>
					<li>PHP</li>
					<li>SQL</li>
				</div>
				<div style="width:20%"></div>
			</div><br>
			<li style="list-style-type:circle">Source code is available at <a style="color:blue" href="https://github.com/nikhilshakohi/socialsite">Social Site.</a></li>
			<li style="list-style-type:circle">AJAX functionality is used at likes, unlikes, adding of comments, editing comments, deleting of comments, adding of posts,deleting of posts</li>
			<li style="list-style-type:circle">Messaging is a full refresh-less feature with AJAX.</li>
			<h4>Functionalities:</h4>
				<div style="padding:0 20px">
					<li>User can signup, login with minimum of username, email, password</li>
					<li>User can find friends who have created account and can send friend requests</li>
					<li>User can accept friend requests</li>
					<li>User can message their friends in real-time</li>
					<li>User can add pictures with captions in their feed without page refresh</li>
					<li>User can add profile pictures and add captions without page refresh</li>
					<li>User can edit personal details</li>
					<li>User can like, comment on posts without page refresh</li>
					<li>User can share posts</li>
					<li>User can search for friends</li>
					<li>User can add, edit or delete posts and comments</li>
					<li>User can send feedback which would be taken care on regular basis</li>
					<li>User can find the source code in the github link provided</li>
					<li>Device friendly Interface</li>
				</div>
			<h4>Future Sights / Try-ons:</h4>
				<div style="padding:0 20px">
					<li>Real-time posts update</li>
					<li>Search through posts</li>
					<li>User Stories for a day</li>
					<li>Multiple pictures upload</li>
					<li>User Videos upload</li>
				</div>
			<h4>About the developer:</h4>
			<li style="list-style-type:circle">Developed by <a style="color:blue" href="https://github.com/nikhilshakohi">Nikhil Shakohi.</a></li>
			<br><br><br>
		</div>
	</div>
	<div id="contentRight"></div>
</div>';

include 'footer.php';

?>
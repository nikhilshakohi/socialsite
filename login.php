<?php
	//Check if user is not logged in using !session
	if(!isset($_SESSION['id'])){
		echo'<h2>Social Site</h2>
			<div id="pageLoader" class="loaderButton loaderButtonBig"></div>
			<div id="formsContainer">
				<form id="loginForm">
					<h3>Login</h3><hr><br>
					<input id="loginUsername" class="formInput" type="text" name="username" placeholder="Username or E-mail" autofocus required><br>
					<input id="loginPassword" class="formInput" type="password" name="password" placeholder="Password" required><br>
					<input class="checkboxItem" id="toggleLoginPassword" type="checkbox" ';?> onclick="if(document.getElementById('loginPassword').type==='password'){
						document.getElementById('loginPassword').type='text';
						}else{
							document.getElementById('loginPassword').type='password';
							}" <?php echo'><label class="checkboxItem" for="toggleLoginPassword">Show Password</label><br><br><br>
					<button id="loginSubmit" class="formSubmit" type="button" name="login" onclick="loginUser()">LOGIN</button>
					<input class="forgotPassword" type="button" value="Forgot Password?" onclick="forgotPassword()"><br>';
					/*echo'<input class="formReset" type="reset" name="reset" value="RESET"><br>';*/
						
					echo'<button type="button" class="formChangeButton signupButton"';?> onclick="
						document.getElementById('loginForm').style.transform='translateX(-120%)';
						document.getElementById('signupForm').style.transform='translateX(0)';
						" <?php echo'>SIGNUP</button>
				</form>
				<form id="signupForm">
					<h3>Signup</h3><hr>
					<input id="signupUsername" class="formInput" type="text" name="username" placeholder="Username" required>
					<input id="signupMobile" class="formInput" type="tel" name="mobile" placeholder="Mobile" inputmode="numeric"><br>';/*title="Minimum 7 digits" pattern="[0-9]{7,}" can be used if mobile input is required*/
					echo'<input id="signupEmail" class="formInput" type="email" name="email" placeholder="E-mail" pattern="[a-z0-9._%-+]+@[a-z0-9-.]+\.[a-z]{2,}$" required><br>
					<input id="signupFirstName" class="formInput" type="text" name="firstName" placeholder="First Name">
					<input id="signupLastName" class="formInput" type="text" name="lastName" placeholder="Last Name"><br>
					<input id="signupPassword" class="formInput" type="password" name="password" placeholder="Password" title="Minimum 4 charecters" pattern=".{4,}" required><br>
					<input class="checkboxItem" id="toggleSignupPassword" type="checkbox" ';?> onclick="if(document.getElementById('signupPassword').type==='password'){
						document.getElementById('signupPassword').type='text';
						}else{
							document.getElementById('signupPassword').type='password';
							}" <?php echo'><label class="checkboxItem" for="toggleSignupPassword">Show Password</label><br>
					<button id="signupSubmit" class="formSubmit" type="button" onclick="signupUser()" name="Signup">SIGNUP</button>
					<input class="formReset" type="reset" name="reset" value="RESET"><br>
					<a href="index.php" type="button" id="anotherLoginButton" class="formChangeButton signupButton" style="display:none;">LOGIN PAGE</a>
					<script>
						function showLogin(){
							if(document.getElementById("signupForm").style.transform=="translateX(120%)"){
								document.getElementById("loginButton").style.display="none";
								document.getElementById("anotherLoginButton").style.display="block";
							}
							document.getElementById("signupForm").style.transform="translateX(120%)";
							document.getElementById("loginForm").style.transform="translateX(0)";
						}
					</script>
					<button id="loginButton" type="button" class="formChangeButton loginButton" onclick="showLogin()">LOGIN</button>
				</form>
			</div>
			<div id="loginStatusMessage"></div>';
	}

	echo '<script type = "text/javascript">
		var loginEnter = document.getElementById("loginForm");
		if(loginEnter){
			loginEnter.addEventListener("keyup", function(event){
				if(event.keyCode == 13){event.preventDefault();loginUser();}
			})
		}
		var signupEnter = document.getElementById("signupForm");
		if(signupEnter){
			signupEnter.addEventListener("keyup", function(event){
				if(event.keyCode == 13){event.preventDefault();signupUser();}
			})
		}

	</script>'

?>
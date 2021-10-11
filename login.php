<?php
	//Check if user is not logged in using !session
	if(!isset($_SESSION['id'])){
		echo'<h2>Social Site</h2>
			<div id="formsContainer">
				<form id="loginForm" method="POST">
					<h3>Login</h3><hr><br>
					<input class="formInput" type="text" name="username" placeholder="Username or E-mail" required><br>
					<input id="loginPassword" class="formInput" type="password" name="password" placeholder="Password" required><br>
					<input class="checkboxItem" id="toggleLoginPassword" type="checkbox" ';?> onclick="if(document.getElementById('loginPassword').type==='password'){
						document.getElementById('loginPassword').type='text';
						}else{
							document.getElementById('loginPassword').type='password';
							}" <?php echo'><label class="checkboxItem" for="toggleLoginPassword">Show Password</label><br><br><br>
					<input class="formSubmit" type="submit" name="login" value="LOGIN">
					<input class="formReset" type="reset" name="reset" value="RESET"><br>';
					if(isset($_POST['login'])){
						$username=mysqli_real_escape_string($conn,$_POST['username']);
						$password=mysqli_real_escape_string($conn,$_POST['password']);
						$checkUser=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' OR email='$username'");
						if(mysqli_num_rows($checkUser)>0){
							while($rowUser=mysqli_fetch_assoc($checkUser)){
								if($password==$rowUser['password']){
									$_SESSION['id']=$rowUser['id'];
									echo'<script>window.location.href="index.php?LoginSuccess"</script>';
									exit();
								}else{
									echo'<span class="errorMessage">Incorrect username or password</span><br>';
								}
							}
						}else{
							echo'<span class="infoMessage">No Users Found.. <a class="miniButtons" href="#signupForm">Sign Up</a> to Create an Account</span><br>';
						}
					}	
					echo'<button type="button" class="formChangeButton signupButton"';?> onclick="
						document.getElementById('loginForm').style.transform='translateX(-120%)';
						document.getElementById('signupForm').style.transform='translateX(0)';
						" <?php echo'>SIGNUP</button>
				</form>
				<form id="signupForm" method="POST">
					<h3>Signup</h3><hr>
					<input class="formInput" type="text" name="username" placeholder="Username" required>
					<input class="formInput" type="tel" name="mobile" placeholder="Mobile" inputmode="numeric"><br>';/*title="Minimum 7 digits" pattern="[0-9]{7,}" can be used if mobile input is required*/
					echo'<input class="formInput" type="email" name="email" placeholder="E-mail" pattern="[a-z0-9._%-+]+@[a-z0-9-.]+\.[a-z]{2,}$" required><br>
					<input class="formInput" type="text" name="firstName" placeholder="First Name">
					<input class="formInput" type="text" name="lastName" placeholder="Last Name"><br>
					<input class="formInput" id="signUpPassword" type="password" name="password" placeholder="Password" title="Minimum 4 charecters" pattern=".{4,}" required><br>
					<input class="checkboxItem" id="toggleSignupPassword" type="checkbox" ';?> onclick="if(document.getElementById('signUpPassword').type==='password'){
						document.getElementById('signUpPassword').type='text';
						}else{
							document.getElementById('signUpPassword').type='password';
							}" <?php echo'><label class="checkboxItem" for="toggleSignupPassword">Show Password</label><br>
					<input class="formSubmit" type="submit" name="Signup" value="SIGNUP">
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
			</div>';
		if(isset($_POST['Signup'])){
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
				echo'<span class="successMessage">User Added.. <a href="index.php" class="coolButton">Login</a> to continue</span>';
				exit();
			}else{
				echo'<span class="errorMessage">Username/E-mail already registered</span>';
			}
			
		}
	}

?>
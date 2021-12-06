<?php
include 'db.php';
	//Check if user is not logged in using !session
	if(!isset($_SESSION['id'])){
		echo'<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<link rel="stylesheet" type="text/css" href="style.css">
			<meta name="viewport" content="width=device-width, intial-scale=1.0">
			<title>Social Site</title>
			<style>
				#headerDiv{
					position: fixed;
					display: flex;align-content: center;align-items: center;
					top: 0;left: 0;right: 0;
					height: 60px;
					background: linear-gradient(to left, silver, grey);z-index: 2;
					box-shadow: 0 0 10px 0 rgba(50, 20, 150, 1.0);
				}
				#headerFirst,#headerMiddle,#headerLast{
					display: flex;align-content: center;align-items: center;
					width: 33%;height: 60px;
				}
				#headerMiddle{justify-content: center;}
				#headerLast{justify-content: flex-end;padding-right:10px}
				#headerSiteName{
					margin: 5px 10px;
					font-size: x-large;
				}
				.headerButtons, .coolButton{
					cursor: pointer;
					padding: 10px 20px;margin: 10px;
					border-radius: 5px;border: none;color: white;
					background: linear-gradient(to left, rgba(120,120,120,1), rgba(90,90,90,1));
					font-size: medium;
				}
				.headerButtons:hover, .coolButton:hover{
					background: linear-gradient(to left, rgba(100,100,100,1), rgba(20,20,20,1));	
				}
				.coolButton{background: linear-gradient(to right, rgba(50,50,100,1), rgba(50,50,250,1));margin: 0px;}
				.coolButton:hover{background: linear-gradient(to right, rgba(150,0,180,1), rgba(100,150,250,1));}
				#formsContainer{
					position: relative;
					box-shadow: 0 0 2px 2px rgba(180, 180, 180, 1.0);
					background-color: rgba(200, 200, 200, 0.8);
					max-width: 450px;
					margin: 80px auto 10px auto;
					padding: 10px;
					border-radius: 10px;
					overflow: hidden;
				}
				.formSubmit{
					padding: 10px;margin: 7px;
					transform: scale(1.2);/*Used this instead of font size as chrome is not changing font size unless clicked anywhere once*/
					width: 250px;max-width: 90%;
					border: none;
					border-radius: 10px;
					cursor: pointer;
					color: white;
					background: linear-gradient(to right, rgba(50,50,100,1), rgba(50,50,250,1));
				
				}
				.formSubmit:hover{
					background: linear-gradient(to right, rgba(150,0,180,1), rgba(100,150,250,1));
				}

			</style>
		</head>
		<body onload="showContent()">';
			echo'<div id="headerDiv">
				<div id="headerFirst">
					<a href="index.php"><div id="headerSiteName">Social Site</div></a>
				</div>
				<div id="headerMiddle"></div>
				<div id="headerLast">
					<a href="index.php" class="headerButtons">Login</a>
					<a href="index.php?#signupForm" class="coolButton">SignUp</a>
				</div>
			</div>';
			function showError(){
				echo'<div style="display:flex;margin-left:auto;margin-right:auto;justify-content:center;align-content:center;align-items:center;flex-direction:column;width:50%;background-color:gainsboro;box-shadow:0 0 2px 1px grey;margin-top:20px">
					<h3>Seems Like Page is broken..Go Back to HomePage</h3>
					<span class="loaderButton"></span>
					<a href="index.php" class="headerButtons">Home</a>
				</div>';
			}
			echo'<div id="formsContainer">';
					if(($_GET['emailId'])&&($_GET['key'])) {
						$emailId=$_GET['emailId'];
						$key=$_GET['key'];

						  $checkEmail=mysqli_query($conn,"SELECT * FROM users WHERE email='$emailId' ORDER BY id DESC LIMIT 1");
						  if(mysqli_num_rows($checkEmail)>0){
						  	while($rowUser=mysqli_fetch_assoc($checkEmail)){
						  		$userPassword=$rowUser['password'];
						  		$userEmailId=$rowUser['email'];
						  		$checkEncItem = $userEmailId.$userPassword;
								$encMethod="AES-128-CTR";
								$encKey="149118912";
								$encOptions=0;
								$encIv="socialSytEncyrpt";
								$decryption = openssl_decrypt($key, $encMethod ,$encKey , $encOptions, $encIv);
						  		if($decryption==$checkEncItem){
					  				echo'<form method="POST">
							  			Enter New Password: <input id="NewPassword" type="password" name="newPassword" placeholder="New Password"><br><br>
							  			<input type="hidden" name="emailId" value="'.$emailId.'">
							  			<input class="checkboxItem" id="toggleNewPassword" type="checkbox" ';?> onclick="
								  		if(document.getElementById('NewPassword').type==='password'){
											document.getElementById('NewPassword').type='text';
										}else{
										document.getElementById('NewPassword').type='password';
										}" <?php echo'><label class="checkboxItem" for="toggleNewPassword">Show Password</label><br><br><br>
									<input class="formSubmit" type="submit" name="resetPassword" value="Submit">
						  			</form>';
						  		}
						  	}
						  }else{
						  		showError();
						  	}

					}else{
						showError();
					}
			echo'</div>
		</body>
		</html>';
	}
	if($_POST['resetPassword']){
		$emailId=$_POST['emailId'];
		$password=mysqli_real_escape_string($conn,$_POST['NewPassword']);
		mysqli_query($conn,"UPDATE users SET password='$password' WHERE email='$emailId'");
		echo'Password Changed Successfully';
		exit();
	}

?>
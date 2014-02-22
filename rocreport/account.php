<?php 
include("loader.php");
//write is user logged in
$user = new User($_COOKIE['rocrep_loggedin_userid'], $_COOKIE['rocrep_loggedin_usermail'], "");
$user_status = $user->isLoggedIn();
if ($user_status != 1) { 
//print_r($_POST);
//exit;
if ( isset($_POST['submit']) && isset($_POST['action']) ) {
	$errorr = "";
	$errors = "";
	$successr = "";
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	if ($_POST['action'] == "login" && $_POST['submit'] == "Sign In") {
		if(!filter_var($_POST['emails'], FILTER_VALIDATE_EMAIL)) {
				$errors = "Invalid E-mail. Enter again.";	
		} else {		
			if ( isset($_POST['emails']) && !empty($_POST['emails']) && isset($_POST['passwords']) && !empty($_POST['passwords']) ) {
				$user = new User("", $_POST['emails'], "");	
				$u = $user->login($_POST['passwords']);
				if ($u == -2) {
					//echo "ddd";
					$errors = "User does not exist";
				} else if ($u == -1) {
					$errors = "Incorrect Password";
				} else {
					$errors = json_encode($u);
				}
			} else {
				$errors = "Please Enter Username And Password";
			}
		}
		if (isset($_POST['ismobile']) && $_POST['ismobile'] == "yes") {
			echo $errors;
			exit;
		} else {
			header("Location:/rocreport");
			exit;
		}
		//exit;
	} else if ($_POST['action'] == "register" && $_POST['submit'] == "Register") {
		if ( isset($_POST['emailr']) && !empty($_POST['emailr']) && isset($_POST['passwordr']) && !empty($_POST['passwordr']) && isset($_POST['namer']) && !empty($_POST['namer']) ) {
			if(!filter_var($_POST['emailr'], FILTER_VALIDATE_EMAIL)) {
				$errorr = "Invalid E-mail. Enter again.";	
			} else if (strlen($_POST['passwordr']) < 6) { 
				$errorr = "Password Length - atleast 6 characters";	
			} else {
				$user = new User("", $_POST['emailr'], $_POST['namer']);		
				$user = $user->register($_POST['passwordr']);
				if ($user['userid'] == -2) {
					$errorr = "User already exists";	
				} else if ($user['userid'] == -1) {
					$errorr = "Failed to create new user, try again";	
				} else {
					$errorr = 1;
				}
			}
		} else {
			$errorr = "Please Enter Username, Password and Name";
		}
		if (isset($_POST['ismobile']) && $_POST['ismobile'] == "yes") {
			echo $errorr;
			exit;
		}			
	}
}	
?>
	<?php include("header.php"); ?>
	<link href="css/signin.css" rel="stylesheet">
		<?php /*<div style="padding: 20px; height: 700px;">
			<div style="float: left; width: 335px; border-right: 1px solid #008EBA; height: 300px;">
				<h2>Sign-In</h2>
				<?php 
					if ($errors != "") {
						echo "<span class='redError'>$errors</span><br /><br />";
					}
				?>
				<table>
					<form action="account" method="POST">
						<tr><td>E-mail</td><td><input value="<?=isset($_POST['emails'])?$_POST['emails']:""?>" name="emails" id="emails" placeholder="Enter your e-mail address" /></td></tr>
						<tr><td>Password</td><td><input value="<?=isset($_POST['passwords'])?$_POST['passwords']:""?>" type="password" name="passwords" id="passwords" placeholder="Enter your password" /></td></tr>
						<tr><td></td><td><input type="submit" name="submit" id="submit" value="Sign In" /></td></tr>
						<input type="hidden" name="action" id="action" value="login" />
					</form>
				</table>
			</div>		
			<div style="float: right; width: 320px;">
				<h2>Register</h2>
				<?php 
					if ($errorr != "") {
						echo "<span class='redError'>$errorr</span><br /><br />";
					}
					if ($errorr == 1) {
						echo "<span class='greenFine'>User Registered, please sign in now.</span><br /><br />";
					}
				?>				
					<table>
						<form action="account" method="POST">
							<tr><td>Name</td><td><input value="<?=isset($_POST['namer'])?$_POST['namer']:""?>" name="namer" id="namer" placeholder="Enter your Name" /></td></tr>
							<tr><td>E-mail</td><td><input value="<?=isset($_POST['emailr'])?$_POST['emailr']:""?>" name="emailr" id="emailr" placeholder="Enter your e-mail address" /></td></tr>
							<tr><td>Password</td><td><input value="<?=isset($_POST['passwordr'])?$_POST['passwordr']:""?>" type="password" name="passwordr" id="passwordr" placeholder="Enter your password" /></td></tr>
							<tr><td></td><td><input type="submit" name="submit" id="submit" value="Register" /></td></tr>
							<input type="hidden" name="action" id="action" value="register" />
						</form>				
					</table>
			</div>
			<div style="clear: both;"></div>
		</div>	*/ ?>
		<div class="container">

		    <div class="list-group" >
		        <a class="list-group-item active" href="#">
		            <h2 class="list-group-item-heading">RocReport</h2>
		        </a>
		    </div>


		    <form class="form-signin" role="form"  action="account" method="POST">
		        <h3 class="form-signin-heading">Log In Please</h3>
				<?php 
					if ($errors != "") {
						echo "<span class='redError'>$errors</span><br /><br />";
					}
				?>		        
		        <input value="<?=isset($_POST['emails'])?$_POST['emails']:""?>" name="emails" id="emails" type="email" class="form-control" placeholder="Email address" required autofocus>
		        <input value="<?=isset($_POST['passwords'])?$_POST['passwords']:""?>" type="password" name="passwords" id="passwords" type="password" class="form-control" placeholder="Password" required>
		        <!--<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>-->
		        <input type="submit" name="submit" id="submit" value="Sign In" />
				<input type="hidden" name="action" id="action" value="login" />
		    </form>

		    <br />OR<br />

		    <form class="form-signin" role="form"  action="account" method="POST">
		        <h3 class="form-signin-heading">Register Please</h3>
				<?php 
					if ($errorr != "") {
						echo "<span class='redError'>$errorr</span><br /><br />";
					}
					if ($errorr == 1) {
						echo "<span class='greenFine'>User Registered, please sign in now.</span><br /><br />";
					}
				?>		        
				<input value="<?=isset($_POST['namer'])?$_POST['namer']:""?>" name="namer" id="namer" type="email" class="form-control" placeholder="Username" required autofocus>
		        <input value="<?=isset($_POST['emailr'])?$_POST['emailr']:""?>" name="emailr" id="emailr" type="email" class="form-control" placeholder="Email address" required >
		        <input value="<?=isset($_POST['passwordr'])?$_POST['passwordr']:""?>" type="password" name="passwordr" id="passwordr" type="password" class="form-control" placeholder="Password" required>
		        <!--<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>-->
		        <input type="submit" name="submit" id="submit" value="Register" />
				<input type="hidden" name="action" id="action" value="register" />
		    </form>		    

		</div> <!-- /container -->		
	<?php include("footer.php"); ?>
<?php } else {

		if (isset($_GET["action"]) && $_GET["action"] == "logout") {
			$user->logout();
		}
		header("Location:/rocreport");

} ?>
<?php 
include("loader.php");

//mobile api
//print_r($_POST);
if (isset($_POST["ismobile"]) && $_POST["ismobile"] == "yes") {
	$update = new Update();
	echo $updateId = $update->createNewMobile();
	exit;
}

//write is user logged in
$user = new User($_COOKIE['rocrep_loggedin_userid'], $_COOKIE['rocrep_loggedin_usermail'],"");
$user_status = $user->isLoggedIn();
if ($user_status == 1) { 

	//Handle form submit
	//clean post
	if (isset($_POST)) {
    	//sanitise post
    	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    	//escape post		
    	foreach ($_POST as $k=>$v) {
    		$_POST[$k] = $db->escape_string($v);
    	}		
	}


	//create new update
	if (isset($_POST["action"]) && $_POST["action"] == "submitupdate") { 
		$update = new Update();
		echo $updateId = $update->createNew();
		//print_r($_POST);		
	}	
?>
	<?php include("header.php"); ?>	
			<?php if ((isset($_POST["action"]) && $_POST["action"] == "newupdate") 
					|| ($updateId <= 0 && isset($_POST["action"]) && $_POST["action"] == "submitupdate")
				) { 

			?>
			<?php 
				if ((isset($_POST["action"]) && $_POST["action"] == "newupdate") 
					|| ($updateId <= 0 && isset($_POST["action"]) && $_POST["action"] == "submitupdate")
				) {
					//add template
					if ($updateId != "notyet") {
						$error = "yes";
					}
					include("addupdate_template.php");
				}
				?>
		<?php } else { ?>
			<div style="padding: 20px; ">
				<form action="addupdate" method="post">
					<input type="hidden" name="action" id="action" value="newupdate" />
					<input class="startNewupdate" type="submit" value="Click here to start &rarr;" />
				</form>
				<br />
			
			</div>


		<?php } ?>
	<?php $noPlugin = 1; include("footer.php"); ?>
<?php } else {
	header("Location:/rocreport");
} ?>
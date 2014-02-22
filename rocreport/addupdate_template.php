<?php include("header.php"); ?>		
    <link href="/rocreport/css/signin.css" rel="stylesheet">		
	<!--<div style="padding: 20px; ">				
	<?php
		if (isset($error) && $error == "yes") {
			echo "<span class='redError'>There was an error, please check all the fields!</span><br /><br />";
		}
	?>
	</div>-->
		<div class="container">

		    <div class="list-group" >
		        <a class="list-group-item active" href="#">
		            <h2 class="list-group-item-heading">RocReport</h2>
		        </a>
		    </div>
		<!--<form enctype="multipart/form-data" action="addupdate" method="post" onsubmit="return checkForm();">
			<input placeholder="Nature of complaint" value="<?=isset($_POST["rocrep_update_nat"])?$_POST["rocrep_update_nat"]:""?>" id="rocrep_update_nat" name="rocrep_update_nat" type="text" />
			<br />
			<input placeholder="Title" value="<?=isset($_POST["rocrep_update_name"])?$_POST["rocrep_update_name"]:""?>" id="rocrep_update_name" name="rocrep_update_name" type="text" />
			<br />
			<input placeholder="Image" type="file" value="<?=isset($_POST["rocrep_update_img"])?$_POST["rocrep_update_img"]:""?>" id="rocrep_update_img" name="rocrep_update_img" type="text" />						
			<br />
			<input placeholder="location" value="<?=isset($_POST["rocrep_update_location"])?$_POST["rocrep_update_location"]:""?>" id="rocrep_update_location" name="rocrep_update_location" type="text" />						
			<input value="<?=isset($_POST["rocrep_update_latlong"])?$_POST["rocrep_update_latlong"]:"11"?>" id="rocrep_update_latlong" name="rocrep_update_latlong" type="hidden" />						
			<br />
			<textarea id="rocrep_update_more" name="rocrep_update_more" type="hidden"><?=isset($_POST["rocrep_update_more"])?$_POST["rocrep_update_more"]:""?></textarea>
			<br />
			<input type="hidden" name="action" value="submitupdate" />
			<input type="submit" name="submit" value="Submit" />
		</form>-->
		    <form enctype="multipart/form-data"  class="form-signin" role="form"  action="addupdate" method="post">
		        <h3 class="form-signin-heading">Add a new update</h3>
		        <input type="text" class="form-control" placeholder="Nature of complaint" value="<?=isset($_POST["rocrep_update_nat"])?$_POST["rocrep_update_nat"]:""?>" id="rocrep_update_nat" name="rocrep_update_nat" required autofocus>
		        <br />
		        <input type="text" class="form-control" placeholder="Title" value="<?=isset($_POST["rocrep_update_name"])?$_POST["rocrep_update_name"]:""?>" id="rocrep_update_name" name="rocrep_update_name" required autofocus>
		        <br />
		        <input type="file" class="form-control" placeholder="Image" type="file" value="<?=isset($_POST["rocrep_update_img"])?$_POST["rocrep_update_img"]:""?>" id="rocrep_update_img" name="rocrep_update_img" required autofocus>
		        <br />
		        <input type="text" class="form-control" placeholder="Location"  value="<?=isset($_POST["rocrep_update_location"])?$_POST["rocrep_update_location"]:""?>" id="rocrep_update_location" name="rocrep_update_location" required autofocus>
		        <br />
				<input value="<?=isset($_POST["rocrep_update_latlong"])?$_POST["rocrep_update_latlong"]:"11"?>" id="rocrep_update_latlong" name="rocrep_update_latlong" type="hidden" />						
				<br />
				<textarea required autofocus id="rocrep_update_more" name="rocrep_update_more" type="hidden"><?=isset($_POST["rocrep_update_more"])?$_POST["rocrep_update_more"]:""?></textarea>
				<br />
				<input type="hidden" name="action" value="submitupdate" />
				<input type="submit" name="submit" value="Submit" />
		    </form>
	    </div>
	</div>
<?php include("footer.php"); ?>	</div>
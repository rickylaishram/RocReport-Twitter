<?php 
include("loader.php");

$update = new Update();

//print_r($_POST);

if (isset($_POST["ismobile"]) && $_POST["ismobile"] == "yes") {
	$updates = $update->listupdates();
	echo json_encode($updates);	
	exit;
} else if (isset($_POST["report_id"]) && !empty($_POST["report_id"])) {
	$updates = $update->listupdates($_POST["report_id"]);
	//print_r($updates);
	?>
        <img alt="200x200" class="img-thumbnail" src="<?=$updates[0]['picture']?>">
        <button type="button" class="btn btn-large btn-primary disabled voted" disabled="disabled"><?=$updates[0]['votes']?> VOTES</button>
        <table class="table table-hover table-bordered" id="table-items">
            <tbody>
            <tr> <td class="warning list-group" style="width:130px">Title:</td> <td><?=$updates[0]['title']?></td></tr>
            <tr> <td class="warning list-group">Category:</td> <td> <?=$updates[0]['cat']?></td></tr>
            <tr> <td class="warning list-group">Details:</td> <td><?=$updates[0]['details']?></td></tr>
            <tr> <td class="warning list-group">Created:</td> <td> <?=$updates[0]['created']?></td></tr>
            <tr> <td class="warning list-group">Address Detail:</td> <td><?=$updates[0]['loc_name']?></td></tr>
       </tbody></table>
	<?php	
	exit;
}
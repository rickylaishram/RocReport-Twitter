<?php 
include("loader.php");

$update = new Update();

if (isset($_POST["ismobile"]) && $_POST["ismobile"] == "yes") {
	echo $update->upvotemobile($_POST["id"]);	
	exit;
}
<?php 
include("loader.php");
//write is user logged in
$user = new User($_COOKIE['rocrep_loggedin_userid'], $_COOKIE['rocrep_loggedin_usermail'],"");
$user_status = $user->isLoggedIn();
//if ($user_status == 1) { 
//if (1 == 1) { 
?>
<?php include("header.php"); ?>
<?php
	$update = new Update();
	$updates = $update->listupdates();
	foreach ($updates as $k=>$v) {

	}
?>
    <div id="details-item-wrapper">
    </div>

    <div id="details-item">
        <div id="item">
            <img alt="200x200" class="img-thumbnail" src="http://i.imgur.com/PeWhTiyl.jpg">
            <button type="button" class="btn btn-large btn-primary disabled voted" disabled="disabled">10 VOTES</button>
            <table class="table table-hover table-bordered" id="table-items">
                <tbody>
                <tr> <td class="warning list-group" style="width:130px">Title:</td> <td>I cannot eat snow</td></tr>
                <tr> <td class="warning list-group">Category:</td> <td> Snow Problem</td></tr>
                <tr> <td class="warning list-group">Details:</td> <td>Here i can put all the details about the situation that is happening regarding to the snow, i think it's a good idea to put more details to see how this look like</td></tr>
                <tr> <td class="warning list-group">Created:</td> <td> January 13 2014</td></tr>
                <tr> <td class="warning list-group">Address Detail:</td> <td>Once you get the Golisano, keep walking down until you find a blue wall</td></tr>
           </tbody></table>
        </div>
    </div>

    <div class="col-sm-3" style="padding:0px;">
    	<div id="logo"></div>
        <div id="reports_location" class="list-group">
            <a class="list-group-item active" style="cursor: pointer;">
                <h4 class="list-group-item-heading">Showing All</h4>
                <p class="list-group-item-text">Showing all the Reports. (RIT)</p>
                <span id="all" style="display: none">zoom_out</span>
            </a>        
        <?php
			foreach ($updates as $k=>$v) {
				//print_r($v);
		?>
            <a class="list-group-item" style="cursor: pointer;">
                <h4 class="list-group-item-heading"><?=$v["cat"]?></h4>
                <p class="list-group-item-text"><?=$v["title"]?></p>
                <span id="<?=$v['id']?>" style="display: none"><?=str_replace(";",",",$v["loc_coord"])?></span>
            </a>
		<?php
			}  
		?>      
        </div>
    </div><!-- /.col-sm-4 -->


    <div class="col-sm-9" style="padding:0px;">
        <div id="map-canvas"></div>
            <a href="#" class="list-group-item">
                <p class="list-group-item-text">Displaying places where reports are being sent. <img src="img/android-logo.jpg" /></p>
            </a>
        </div>
    </div><!-- /.col-sm-4 -->

<?php include("footer.php"); ?>
<?php
class  User{
    /* Member variables */
    private $emailid;
    private $userid;
    private $name;
    /*  Constructor */  
    public function __construct($u, $e, $n) {
    	$this->emailid = $e;
    	$this->userid = $u;
    	if ($n != "") {
    		$this->name = $n;
    	}
    }        
    /* Member functions */
    public function register( $password ){
    	global $db;
    	$email = $db->escape_string($this->emailid);
    	$name = $db->escape_string($this->name);
    	$statement = $db->prepare("SELECT email FROM users WHERE email = ?");
		$statement->bind_param('s', $email);
		if ($statement->execute()) {
			$statement->store_result();
			if ( $statement->num_rows >= 1) {
				//error - user already exists
				$userid = -2;
			} else {
		    	$statement_insert = $db->prepare("INSERT INTO users (email, passwordin, name, joinedon, ipjoined) VALUES (?,?,?,?,?)");
				$statement_insert->bind_param("sssis", $email, $password, $name, $joinedon, $ipjoined);
				//generate salt
		        $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
		        //generate password
				$password = crypt($db->escape_string($password), $salt);
				$joinedon = time();
				$ipjoined = $_SERVER["REMOTE_ADDR"];
				if ($statement_insert->execute()) {
					$userid = $db->insert_id;
				} else {
					$userid = -1;
				}
				$statement_insert->free_result();
			}
		}
		$statement->free_result();
       	return array("email"=>$this->emailid, "userid"=>$userid);
    }

    public function login($password){
    	global $db;
    	$email = $db->escape_string($this->emailid);  
    	$statement = $db->prepare("SELECT id, email, passwordin, name FROM users WHERE email = ?");
		$statement->bind_param('s', $email);
		if ($statement->execute()) {
			$statement->store_result();
			if ( $statement->num_rows >= 1) {
				$statement->bind_result($idStored, $emailStored, $passwordStored, $nameStored);
				while ( $statement->fetch() ) {
				    if (crypt($password, $passwordStored) == $passwordStored) {
				    	$return = $idStored;
				    	$now = time();
				    	//update last login ip & time
				    	$statement_update = $db->prepare("UPDATE users SET loggedon = ?, iplogged = ? WHERE id = ? AND email = ?");
						$statement_update->bind_param("isis", $now, $_SERVER["REMOTE_ADDR"], $idStored, $emailStored);	
						$statement_update->execute();		
						$statement_update->free_result();		    	
				    	//set cookies
						setcookie('rocrep_loggedin_userid',$idStored,time() + (86400 * 7));
						setcookie('rocrep_loggedin_usermail',$emailStored,time() + (86400 * 7));
						setcookie('rocrep_loggedin_username',$nameStored,time() + (86400 * 7));
						setcookie('rocrep_loggedin_userifier',$now,time() + (86400 * 7));
				    } else {
				    	//Incorrect login
				    	$return = -1;
				    }
				}				
			} else {
				//error - user does not exist
				$return = -2;
			}
		}
		$statement->free_result();
       	return $return;
    }

    public function isLoggedIn(){
    	if (isset($_COOKIE["rocrep_loggedin_userid"]) && $_COOKIE["rocrep_loggedin_userid"] != "" &&
    		isset($_COOKIE["rocrep_loggedin_usermail"]) && $_COOKIE["rocrep_loggedin_usermail"] != "" &&
    		isset($_COOKIE["rocrep_loggedin_username"]) && $_COOKIE["rocrep_loggedin_username"] != "" &&
    		isset($_COOKIE["rocrep_loggedin_userifier"]) && $_COOKIE["rocrep_loggedin_userifier"] != "") {
	       	global $db;
	    	$email = $db->escape_string($_COOKIE["rocrep_loggedin_usermail"]); 
			$id = $db->escape_string($_COOKIE["rocrep_loggedin_userid"]); 
			$userifier =  $db->escape_string($_COOKIE["rocrep_loggedin_userifier"]); 
	    	$statement = $db->prepare("SELECT * FROM users WHERE email = ? AND id = ? AND loggedon = ?");
	    	//checking loggedin field so that only one session is maintained on the server
			$statement->bind_param('sii', $email, $id, $userifier);
			if ($statement->execute()) {
				$statement->store_result();
				if ( $statement->num_rows == 1) {
					$return = 1;
				} else {
					//error - user login does not exist
					$this->logout();
					$return = -1;
				}
			} else {
				//some error in db query process
				$this->logout();
				$return = -2;
			}
			$statement->free_result();
		} else {
			//all cookies are not set
			$this->logout();
			$return = 0;
		}  
		return $return;     

    }

    public function logout(){
		unset($_COOKIE['rocrep_loggedin_userid']);
		unset($_COOKIE['rocrep_loggedin_usermail']);
		setcookie('rocrep_loggedin_usermail', '', time() - 3600);
		setcookie('rocrep_loggedin_userid', '', time() - 3600);  
		unset($_COOKIE['rocrep_loggedin_username']);
		unset($_COOKIE['rocrep_loggedin_userifier']);
		setcookie('rocrep_loggedin_username', '', time() - 3600);
		setcookie('rocrep_loggedin_userifier', '', time() - 3600);	
		unset($_COOKIE['rocrep_acquirefans_updateid']);
		setcookie('rocrep_acquirefans_updateid', '', time() - 3600);			     
    }

}

class  Update{
    /* Member variables */
    private $ownerid;
    private $updateid;
    private $created;

    /* Member functions */
    public function createNew(){
    	global $db;
    	$return = 0;
    	$owner = 0;
    	//sanitise post
    	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    	//escape post
    	foreach ($_POST as $k=>$v) {
    		if ($v == "" || empty($v)) {
    			$return = -1;
    			break;
    		} else {
    			$_POST[$k] = $db->escape_string($v);
    		}
    	}
    	if (!isset($_FILES["rocrep_update_img"]["name"])) {
    		//blank file
    		$return = -13;
    	}
    	//print_r($_POST);
    	if ($return <0 ) return $return;

    	//All good, proceed  
    	$statement = $db->prepare("SELECT email,id FROM users WHERE loggedon = ? AND id = ? AND email = ?");
		$statement->bind_param('iis', $_COOKIE["rocrep_loggedin_userifier"], $_COOKIE["rocrep_loggedin_userid"], $_COOKIE["rocrep_loggedin_usermail"]);
		if ($statement->execute()) {
			$statement->store_result();
			if ( $statement->num_rows == 1) {
				$statement->bind_result($email,$id);
				while ( $statement->fetch() ) {
					$owner = $id;
					echo $ownermail = $email;
				}
			} else {
				$owner = -1;
			}
		} else {
			$owner = -2;			
		}

		if ($owner > 0) {

			$allowedExts = array("jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["rocrep_update_img"]["name"]);
			$extension = end($temp);
			if ((($_FILES["rocrep_update_img"]["type"] == "image/jpeg")
			|| ($_FILES["rocrep_update_img"]["type"] == "image/jpg")
			|| ($_FILES["rocrep_update_img"]["type"] == "image/pjpeg")
			|| ($_FILES["rocrep_update_img"]["type"] == "image/x-png")
			|| ($_FILES["rocrep_update_img"]["type"] == "image/png"))
			&& ($_FILES["rocrep_update_img"]["size"] < 600000)
			&& in_array($extension, $allowedExts)) {
				if ($_FILES["rocrep_update_img"]["error"] > 0) {
			    	$return = -11;
			    } else {

				    $newFilename = $owner.time().$owner.".".$extension;
					$upload_dir = "/var/www/rocreport/uploads/";
					if (file_exists($upload_dir) && is_writable($upload_dir)) {
					    if (move_uploaded_file($_FILES["rocrep_update_img"]["tmp_name"], $upload_dir . $newFilename)) {
					    } else {
					    }
					} else {
					}

					$client_id = "768f8152aab7d6c";
					$image = file_get_contents("uploads/".$newFilename);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
					curl_setopt($ch, CURLOPT_POST, TRUE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
					curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($image)));

					$reply = curl_exec($ch);
					curl_close($ch);

					$reply = json_decode($reply);
					$picture = $reply->data->link;

			    }
			} else {
				$return = -12;
			}

			if ($return < -10) {
				return $return;
				exit;
			}

			$statement_insert = $db->prepare("INSERT INTO report (cat,title,details,picture,created,loc_coord,loc_name,email) VALUES (?,?,?,?,?,?,?,?)");
			//var_dump($statement_insert);
			//echo $db->error;
			//exit;
			$status = 1;

			$statement_insert->bind_param("ssssisss", $_POST["rocrep_update_nat"], 
															$_POST["rocrep_update_name"], 
															$_POST["rocrep_update_more"], 
															$picture,
															time(), 
															$_POST["rocrep_update_latlong"], 
															$_POST["rocrep_update_location"], 
															$ownermail );
			//var_dump($statement_insert);
			if ($statement_insert->execute()) {
				$this->updateid = $db->insert_id;
			} else {
				$this->updateid = -3;
			}
			$statement_insert->free_result();
			$return = $this->updateid;
		} else {
			$return = -2;
		}
       	return $return;
    }

    public function createNewMobile(){
    	global $db;
    	$return = 0;
    	$owner = 0;
    	//sanitise post
    	//print_r($_POST);
    	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    	//escape post
    	foreach ($_POST as $k=>$v) {
    		if ($v == "" || empty($v)) {
    			$return = -1;
    			break;
    		} else {
    			$_POST[$k] = $db->escape_string($v);
    		}
    	}

    	if ($return <0 ) return $return;

    	//All good, proceed  

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

		if ($u > 0) {
			$owner = $u;
			$ownermail = $_POST['emails'];
		} else {
			$owner = -2;			
		}

		if ($owner > 0) {

			$statement_insert = $db->prepare("INSERT INTO report (cat,title,details,picture,created,loc_coord,loc_name,email) VALUES (?,?,?,?,?,?,?,?)");
			//var_dump($statement_insert);
			//echo $db->error;
			//exit;
			$status = 1;
			$picture = $_POST["rocrep_update_pic"];
			$statement_insert->bind_param("ssssisss", $_POST["rocrep_update_nat"], 
															$_POST["rocrep_update_name"], 
															$_POST["rocrep_update_more"], 
															$picture,
															time(), 
															$_POST["rocrep_update_latlong"], 
															$_POST["rocrep_update_location"], 
															$ownermail );
			//var_dump($statement_insert);
			if ($statement_insert->execute()) {
				$this->updateid = $db->insert_id;
			} else {
				$this->updateid = -3;
			}
			$statement_insert->free_result();
			$return = $this->updateid;
		} else {
			$return = -2;
		}
       	return $return;
    }

    public function listupdates($id = 0) {
       	global $db;
       	//echo $_COOKIE["rocrep_loggedin_userid"];
       	$counter = 0;
       	$results = array();
       	if ($id == 0) {
    		$statement = $db->prepare("SELECT id,cat,title,details,picture,created,loc_coord,loc_name,email,votes,re_status FROM report WHERE re_status = 1 ORDER BY votes DESC, id DESC");
       	} else {
       		//echo "aaaa";
    		$statement = $db->prepare("SELECT id,cat,title,details,picture,created,loc_coord,loc_name,email,votes,re_status FROM report WHERE re_status = 1 AND id = ?");
    		$statement->bind_param("i",$id);
       	}
		if ($statement->execute()) {
			//echo $db->error." Err3 ";
			$statement->bind_result($id, $cat, $title, $details, $picture, $created, $loc_coord, $loc_name, $email,$votes,$status);
			//echo $db->error." Err4 ";
			while ( $statement->fetch() ) {
				$results[$counter]["id"] = $id;
				$results[$counter]["cat"] = $cat;
				$results[$counter]["title"] = $title;
				$results[$counter]["details"] = $details;
				$results[$counter]["picture"] = $picture;
				$results[$counter]["loc_coord"] = $loc_coord;
				$results[$counter]["loc_name"] = $loc_name;
				$results[$counter]["email"] = $email;	
				$results[$counter]["created"] = gmdate("m / d / Y", $created);
				$results[$counter]["votes"] = $votes;
				$results[$counter]["status"] = $status;
				$counter++;																		
			}
		} else {
			//echo $db->error." Err5 ";
		} 
		return $results; 
    }

    function upvote($id) {
    	global $db;
    	$return = 0;
    	$owner = 0;
    	//sanitise post
    	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    	//escape post
    	foreach ($_POST as $k=>$v) {
    		if ($v == "" || empty($v)) {
    			$return = -1;
    			break;
    		} else {
    			$_POST[$k] = $db->escape_string($v);
    		}
    	}

    	//print_r($_POST);
    	if ($return <0 ) return $return;

    	//All good, proceed  
    	$statement = $db->prepare("SELECT email,id FROM users WHERE loggedon = ? AND id = ? AND email = ?");
		$statement->bind_param('iis', $_COOKIE["rocrep_loggedin_userifier"], $_COOKIE["rocrep_loggedin_userid"], $_COOKIE["rocrep_loggedin_usermail"]);
		if ($statement->execute()) {
			$statement->store_result();
			if ( $statement->num_rows == 1) {
				$statement->bind_result($email,$id);
				while ( $statement->fetch() ) {
					$owner = $id;
					$ownermail = $email;
				}
			} else {
				$owner = -1;
			}
		} else {
			$owner = -2;			
		}

		if ($owner > 0) {

			if ($return < -10) {
				return $return;
				exit;
			}

			$ownerstring = "[".$owner."]";
			$filterstring = "%[".$owner."]%";

	    	$statement_update = $db->prepare("UPDATE report SET votes = votes + 2, votedby = CONCAT(votedby,?) WHERE email = ? AND id = ? AND votedby NOT LIKE ?");
			$statement_update->bind_param("ssis", $ownerstring, $ownermail, $id, $filterstring);
			if ($statement_update->execute()) {
				$statement_update->store_result();
				$this->updateid = $statement_update->affected_rows;
			} else {
				$this->updateid = -3;
			}
			$return = $this->updateid;				
			$statement_update->free_result();
		} else {
			$return = -2;
		}
       	return $return;
    }

    function upvotemobile($id) {
    	global $db;
    	$return = 0;
    	$owner = 0;
    	//sanitise post
    	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    	//escape post
    	foreach ($_POST as $k=>$v) {
    		if ($v == "" || empty($v)) {
    			$return = -1;
    			break;
    		} else {
    			$_POST[$k] = $db->escape_string($v);
    		}
    	}

    	if ($return <0 ) return $return;

    	//All good, proceed  

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

		if ($u > 0) {
			$owner = $u;
			$ownermail = $_POST['emails'];
		} else {
			$owner = -2;			
		}

		if ($owner > 0) {
			$ownerstring = "[".$owner."]";
			$filterstring = "%[".$owner."]%"; 
	    	$statement_update = $db->prepare("UPDATE report SET votes = votes + 2, votedby = CONCAT(votedby,?) WHERE email = ? AND id = ? AND votedby NOT LIKE ?");
			$statement_update->bind_param("ssis", $ownerstring, $ownermail, $id, $filterstring);	
			if ($statement_update->execute()) {
				$statement_update->store_result();
				$this->updateid = $statement_update->affected_rows;
			} else {
				$this->updateid = -3;
			}
			$return = $this->updateid;				
			$statement_update->free_result();

		} else {
			$return = -2;
		}
       	return $return;    	
    }    

    function postreportupdatebycivic() {
    	global $db;
    	$return = 0;
    	$owner = 0;
    	//sanitise post
    	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    	//escape post
    	foreach ($_POST as $k=>$v) {
    		if ($v == "" || empty($v)) {
    			$return = -1;
    			break;
    		} else {
    			$_POST[$k] = $db->escape_string($v);
    		}
    	}

    	//print_r($_POST);
    	$report_id = $_POST["report_id"];
    	$report = $_POST["report"];

    	//All good, proceed  
    	$statement = $db->prepare("SELECT email,id FROM users WHERE loggedon = ? AND id = ? AND email = ? AND level = 10");
		$statement->bind_param('iis', $_COOKIE["rocrep_loggedin_userifier"], $_COOKIE["rocrep_loggedin_userid"], $_COOKIE["rocrep_loggedin_usermail"]);
		if ($statement->execute()) {
			$statement->store_result();
			if ( $statement->num_rows == 1) {
				$statement->bind_result($email,$id);
				while ( $statement->fetch() ) {
					$civic = $id;
					$civicmail = $email;
				}
			} else {
				$civic = -1;
			}
		} else {
			$civic = -2;			
		}

		if ($civic > 0) {

			if ($return < -10) {
				return $return;
				exit;
			}

			$ownerstring = "[".$owner."]";
			$filterstring = "%[".$owner."]%";

	    	$statement_update = $db->prepare("UPDATE report SET status = ? WHERE id = ?");
			$statement_update->bind_param("ii", $status, $report_id);
			if ($statement_update->execute()) {
				$statement_update->store_result();
				$this->updateid = $statement_update->affected_rows;
			} else {
				$this->updateid = -3;
			}
			$return = $this->updateid;				
			$statement_update->free_result();

			$statement_insert = $db->prepare("INSERT INTO report_update (id_report,details,created) VALUES (?,?,?)");
			//var_dump($statement_insert);
			//echo $db->error;
			//exit;
			$status = 1;
			$picture = $_POST["rocrep_update_pic"];
			$statement_insert->bind_param("ssssisss", $_POST["rocrep_update_civ_id"], 
															$_POST["rocrep_update_civ_details"], 
															time());
			//var_dump($statement_insert);
			if ($statement_insert->execute()) {
				$this->updateid = $db->insert_id;
			} else {
				$this->updateid = -3;
			}
			$statement_insert->free_result();

			$return += $this->updateid;

		} else {
			$return = -2;
		}
       	return $return;    	
    }

}
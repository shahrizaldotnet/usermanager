<?php
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/
//error_reporting(E_ALL);
//ini_set('display_errors',1);
if (!defined('included')){
die('You cannot access this file directly!');
}
class myDatabase
{
  public $db;
  
  public function __construct($database)
  {
      $this->db = $database;
	  
  }

  public function getUserInfo($user,$data)
  {
	$info_ = "";
	if($stmt = $this->db->prepare("SELECT $data FROM members WHERE username =? OR email=? LIMIT 1")) 
	{
		
		$stmt->bind_param('ss',$user,$user);
		$stmt->execute();
		$stmt->bind_result($info);
		$stmt->store_result();
		$stmt->fetch(); 
		$stmt->close(); 
		$info_ = $info;
	}
	else
	{
		$_SESSION['error'] = 'No database found.';
	}
	return $info_;
  }
public function admin_login_required() {
	if($this->admin_logged_in()) {
		return true;
	} else {
		$_SESSION['error'] = 'You do not have sufficient permissions to access the page.';
		header('Location: '.DIR.'login.php');
		exit();
	}
}
public function admin_logged_in() {
	if($this->is_admin() && $this->logged_in()) {
		return true;
	} else {
		return false;
	}
}
public function is_admin() {

	if($stmt = $this->db->prepare("SELECT isadmin FROM members WHERE username =? LIMIT 1")) 
	{
		$stmt->bind_param("s",$_SESSION['username']);
		$stmt->execute();
		$stmt->bind_result($is_admin);
		$stmt->store_result();
		while($stmt->fetch()) {
			if($is_admin == 1)
			{	
				$stmt->close();
				return true;
			} else {
				$stmt->close();
				return false;
			}
		}
	} else {
		$stmt->close();
		return false;
	}
}
public function edit_user($ID, $name, $username, $email, $pass, $status)
{
	$active = 0;
	if($status == "enable") { //enable
		$active = 1;
	} elseif($status == "disable") { //disable
		$active = 0;
	} else { //banned
		$active = 2;
	}
	
	//strip all tags from variable    
    $username = ((strtolower($username)));
    $email = wp_strip_all_tags(($email));
	
	$name = preg_replace('/\s+/', '', $name);
	$username = preg_replace('/\s+/', '', $username);
	
	if(strlen($pass) < 4 && $pass != '')
	{
		$_SESSION['error'] = 'Password too short! minimum 4 alphanumeric';
		return;
		exit();
	}
	if(valid_name($username)===false || valid_name($name)===false)
	{
		$_SESSION['error'] = 'Only alphanumeric characters are allowed, minimum 3 characters';
		return;
		exit();
	}
	if( $name == '' || $username == '' || $email == '')
	{
		$_SESSION['error'] = 'Please enter your info below';
		return;
		exit();
	}
	$sql = "SELECT username, email FROM members WHERE memberID =? LIMIT 1";
	$con = $this->db;
	if($stmt = $con->prepare($sql)) 
	{
		$stmt->bind_param("i",$ID);
		$stmt->execute();
		$stmt->bind_result($current_username,$current_email);
		/* Store the result (to get properties) */
		$stmt->store_result();
		$stmt->fetch();
		
		if($this->username_exists($username) == true &&  $current_username != $username ) 
		{
			$_SESSION['error'] = 'Username exists';
			$stmt->close();
			return;
			exit();
		}
		if($this->email_valid($email) == false)
		{
			$_SESSION['error'] = 'Email address is invalid';
			$stmt->close();
			return;
			exit();
		}
		if($this->email_exists($email) == true && $current_email != $email ) 
		{
			$_SESSION['error'] = 'Email Address is already registered';
			$stmt->close();
			return;
			exit();
		}
		if( $pass == '' ) {
			$query = "UPDATE members SET name=?, username=?, email=?, active=? WHERE memberID=?";
		} else {
			$query = "UPDATE members SET name=?, username=?, email=?, password=?, active=?, salt=? WHERE memberID=?";
		}
		if($statement = $this->db->prepare($query)) {
			if( $pass == '' ) {
				$statement->bind_param('sssii', $name, $username, $email, $active, $ID);
			} else {
				$salt = bin2hex(openssl_random_pseudo_bytes(22));
			    // Save the hash but no need to save the salt
			    $hash = crypt($pass, "$2a$12$".$salt);
				
				$statement->bind_param('ssssisi', $name, $username, $email, $hash, $active, $salt, $ID);
			}
			if($statement->execute())
			{
				$_SESSION['success'] = 'Profile changed';
			} else {
				$_SESSION['error'] = 'Profile fail to change';
			}
		} else {
			$_SESSION['error'] = 'Profile fail to change';
		}
	} else {
		$_SESSION['error'] = 'Profile fail to change';
	}
	$stmt->close();
	$statement->close();
}
public function add_users($user, $username, $email, $pass, $status)
{
		
		$active = 0;
		if($status == "enable") { //enable
			$active = 1;
		} elseif($status == "disable") { //disable
			$active = 0;
		} else { //banned
			$active = 2;
		}
		
		if($this->register($user, $username, $email, $pass) == true)
		{
			$mysqli = $this->db;
			$sql = "SELECT * FROM members WHERE username = '$username'";
			$stmt = $mysqli->query($sql);
			 if ($stmt->num_rows == 1) {
				$mysqli->query("UPDATE members SET active = '$active' WHERE username = '$username'");
			 }
		}
}
public function register($user, $username, $email, $pass){

	   //strip all tags from variable   
	   $email = wp_strip_all_tags(($email));
		   
		$user = preg_replace('/\s+/', '', $user);
		$username = preg_replace('/\s+/', '', $username);
		$username = strtolower($username);
		$email = preg_replace('/\s+/', '', $email);
		$name_added = false;
		
		if(strlen($pass) < 4)
		{
			$_SESSION['error'] = 'Password too short! minimum 4 alphanumeric';
			return;
			exit();
		}
		if(valid_name($username)===false || valid_name($user)===false)
		{
			$_SESSION['error'] = 'Only alphanumeric characters are allowed, minimum 3 characters';
			return;
			exit();
		}
		if($this->email_valid($email) == false)
		{
			$_SESSION['error'] = 'Email address is invalid';
			return;
			exit();
		}
		if($this->email_exists($email) == true)
		{
			$_SESSION['error'] = 'Email Address is already registered';
			return;
			exit();
		}
		if($user == '' || $username == '' || $email == '' || $pass == '')
		{
			$_SESSION['error'] = 'Sorry no info input!';
			$name_added = false;
		}
		else
		{
			if($this->username_exists($username) == false) 
			{
				$sql = "INSERT INTO members (name,username,email,password,salt) VALUES (?,?,?,?,?)";
				$con = $this->db;
				if($stmt = $con->prepare($sql)) 
				{
					$salt = bin2hex(openssl_random_pseudo_bytes(22));
					// Save the hash but no need to save the salt
					$hash = crypt($pass, "$2a$12$".$salt);
					
					
					$stmt->bind_param("sssss",$user,$username,$email,$hash,$salt);
					if ($stmt->execute()) { 
					   $_SESSION['success'] = 'Info is added successfully. You can login using your info.';
					   $name_added = true;
					} else {
						$_SESSION['error'] = 'Info cannot be added.';
						$name_added = false;
					}
				}else {
			   
				  // define an error message
				  $_SESSION['error'] = 'Sorry, no database found';
				  $name_added = false;
				 }
				 $stmt->free_result();
				 $stmt->close();
				 //$con->close();
			}
			else
			{
				$_SESSION['error'] = 'Username is not available.';
				$name_added = false;
			}
		 }
		 return $name_added;
		
}  
//log user in ---------------------------------------------------
public function login($user, $pass){

   //strip all tags from variable    
   $user = wp_strip_all_tags(($user));
 
   // check if the user id and password combination exist in database
	$ip = getip();
    if( is_locked_out($ip,$this->db) == true )
	{
		return;
		exit();
	}
		$sql = "SELECT username, password, email, lastlogin, ipadd, salt, active FROM members WHERE username =? LIMIT 1";
		$con = $this->db;
		if($stmt = $con->prepare($sql)) 
		{
		
				$stmt->bind_param("s",$user);
				$stmt->execute();
				$stmt->bind_result($username,$password,$email,$lastlogin,$ipadd,$salt,$status);
				/* Store the result (to get properties) */
				$stmt->store_result();
				// if there's username found
				if($stmt->num_rows!==0){
				/* Fetch the value */
					while ($stmt->fetch()) {
						
						if( $status == '2' ) { // if banned
							$_SESSION['error'] = 'You are banned!.';
							$_SESSION['authorized'] = false;
							$stmt->free_result();
							$stmt->close();
							return;
							exit();	
						}
						
						if( crypt($pass,$password) == $password )
						{
							// set the session
							$user_browser = $_SERVER['HTTP_USER_AGENT'];
							$_SESSION['authorized'] = true;
							// to auto logout
							$_SESSION['last_activity'] = time(); 
							$_SESSION['username'] = $username;
							
							$_SESSION['lastlogin'] =  date("d-m-Y H:i:s", strtotime($lastlogin));
							$_SESSION['ipaddress'] = $ipadd;
							$_SESSION['success'] = 'You are now login';
							$_SESSION['login_string'] = hash('sha512',$salt.$user_browser.PEPPER);
							logged_ip($con,$ip,$username);
							reset_ip($ip,$con);
							// direct to admin
							header('Location: '.DIRADMIN);
							$stmt->free_result();
							$stmt->close();
							exit();							
									
						}
						else
						{
							// fail password
							fail_loggin($con,$ip,$username);
							// define an error message
							$_SESSION['error'] = 'Sorry, wrong username or password.';
							$_SESSION['authorized'] = false;
						}
				   }
				}
				else
				{
					// fail username
					fail_loggin($con,$ip,$username);
					$_SESSION['error'] = 'Sorry, wrong username or password.';
					$_SESSION['authorized'] = false;
				}
	   } else {
	   
		  // define an error message
		  $_SESSION['success'] = 'Sorry, no database found';
		  $_SESSION['authorized'] = false;
	     }
		 $stmt->free_result();
		 $stmt->close();
		 //$con->close();
}
public function email_valid($email) {
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
		return false;
	}
	return true;
}
public function email_exists($email){

	$sql = "SELECT email FROM members WHERE email =? LIMIT 1";
	$con = $this->db;
	if($stmt = $con->prepare($sql)) 
	{
		$stmt->bind_param("s",$email);
		$stmt->execute();
		$stmt->bind_result($email_check);
		/* Store the result (to get properties) */
		$stmt->store_result();
		$stmt->fetch();

		if ($stmt->num_rows == 1){
			//$_SESSION['error'] = 'The email address is already registered';
			$stmt->free_result();
			$stmt->close();
			return true;
		} else {
			$stmt->free_result();
			$stmt->close();
			return false;
		}
	} else {
   
	  // define an error message
	  $_SESSION['error'] = 'Sorry, no database found';
	  $stmt->free_result();
	  $stmt->close();
	 
	  return true;
	 }
}
public function username_exists($username){

		$username = preg_replace('/\s+/', '', $username);
		if(valid_name($username)===false)
		{
			$_SESSION['error'] = 'Only alphanumeric characters are allowed, minimum 3 characters';
			return;
			exit();
		}
		$sql = "SELECT username FROM members WHERE username =? LIMIT 1";
		$con = $this->db;
		if($stmt = $con->prepare($sql)) 
		{
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$stmt->bind_result($username_check);
			/* Store the result (to get properties) */
			$stmt->store_result();
			$stmt->fetch();

            if ( strtolower($username_check) == strtolower($username) ){
			$stmt->free_result();
		    $stmt->close();
		   
            return true;
           
            }
			else
			{
			  $stmt->free_result();
			  $stmt->close();
			  
			  return false;
			}
	    } else {
	   
		  // define an error message
		  $_SESSION['error'] = 'Sorry, no database found';
		  $stmt->free_result();
		  $stmt->close();
		 
		  return true;
	     }
}
public function revalidatetoken($username) {
	if(isset($_SESSION['authorized'],$_SESSION['login_string'])) {
		// set the session
		$user_browser = $_SERVER['HTTP_USER_AGENT'];
		$salt = $this->getUserInfo($_SESSION['username'],"salt");
		$_SESSION['login_string'] = hash('sha512',$salt.$user_browser.PEPPER);
	}
}
// Authentication
public function logged_in() {
	if(isset($_SESSION['authorized'],$_SESSION['login_string'])) {
	
		$username =  $_SESSION['username'];
		$login_string = $_SESSION['login_string'];
		// Get the user-agent string of the user.
		$user_browser = $_SERVER['HTTP_USER_AGENT'];
		if($stmt = $this->db->prepare("SELECT salt, active FROM members WHERE username =? LIMIT 1")) 
		{
			$stmt->bind_param("s",$username);
			$stmt->execute();
			$stmt->bind_result($salt,$status);
			$stmt->store_result();
			$stmt->fetch();
			if ($stmt->num_rows == 1){
				if( $status == '2' ) { // if banned
					$_SESSION['error'] = 'You are banned!.';
					$_SESSION['authorized'] = false;
				}
				$login_check = hash('sha512', $salt.$user_browser.PEPPER);
				if( $login_check == $login_string && $_SESSION['authorized'] == true )
				{
					$_SESSION['login_string'] = hash('sha512',$salt.$user_browser.PEPPER);
					$stmt->free_result();
					$stmt->close();
					return true;
				} else {
					$stmt->free_result();
					$stmt->close();
					return false;
				}
				
			} else {
				$stmt->free_result();
				$stmt->close();
				return false;
			}
		} else {
			$stmt->free_result();
			$stmt->close();
			return false;
		}
	
	} else {
		return false;
	}
}
public function login_required() {
	if($this->logged_in()) {	
		return true;
	} else {
		header('Location:'.DIR.'login.php');
		exit();
	}	
}

public function logout() {

	// Unset all of the session variables.
	$_SESSION = array();

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	} 
	 
	// remove all session variables
	session_unset();
	// destroy the session
	session_destroy(); 
	header('Location: '.DIR.'login.php');
    exit();
}
// change profile
public function changeprofile($id,$name,$username,$email) {

	//strip all tags from variable   
    $email = wp_strip_all_tags(($email));
	
	$name = preg_replace('/\s+/', '', $name);
	$username = preg_replace('/\s+/', '', $username);
	$username = strtolower($username);

	if( $name == '' || $username == '' || $email == '')
	{
		$_SESSION['error'] = 'Please enter your info below';
		return;
		exit();
	}
	
	if(valid_name($username)===false || valid_name($name)===false)
	{
		$_SESSION['error'] = 'Only alphanumeric characters are allowed, minimum 3 characters';
		return;
		exit();
	}

	if($this->email_valid($email) == false)
	{
		$_SESSION['error'] = 'Email address is invalid';
		return;
		exit();
	}
	if($this->email_exists($email) == true && $email != $this->getUserInfo($_SESSION['username'],"email")) 
	{
		$_SESSION['error'] = 'Email Address is already registered';
		return;
		exit();
	}
	
	if($this->username_exists($username) == true && $username != $_SESSION['username']) 
	{
		$_SESSION['error'] = 'Username exists';
		return;
		exit();
	}
	$query = "UPDATE members SET name=?, username=?, email=? WHERE (username=? AND memberID=?)";
	if($statement = $this->db->prepare($query)) {
		$statement->bind_param('ssssi', $name, $username, $email, $_SESSION['username'], $id);
		if($statement->execute())
		{
			$_SESSION['success'] = 'Profile changed';
			$_SESSION['username'] = $username;
			$this->revalidatetoken($username);

		} else {
			$_SESSION['error'] = 'Profile fail to change';
		}
	} else {
		$_SESSION['error'] = 'Profile fail to change';
	}
}
// change password
public function changepassword($pass1,$pass2,$ID) {
	
	if( $pass1 == '' || $pass2 == '' )
	{
		$_SESSION['error'] = 'Please enter both password below';
		return;
		exit();
	}
	if($pass1 != $pass2)
	{
		$_SESSION['error'] = 'Both password are different';
		return;
		exit();
	}
	if(strlen($pass1) < 4)
	{
		$_SESSION['error'] = 'Password too short! minimum 4 alphanumeric';
		return;
		exit();
	}
	$sql = "SELECT username FROM members WHERE memberID =? LIMIT 1";
	$con = $this->db;
	if($stmt = $con->prepare($sql)) 
	{
		$stmt->bind_param("i",$ID);
		$stmt->execute();
		$stmt->bind_result($username);
		/* Store the result (to get properties) */
		$stmt->store_result();
		// if there's username found
		if($stmt->num_rows!==0){
			while ($stmt->fetch()) {
			    $salt = bin2hex(openssl_random_pseudo_bytes(22));
			    // Save the hash but no need to save the salt
			    $hash = crypt($pass1, "$2a$12$".$salt);
			   
				$query = "UPDATE members SET password = ?, salt = ? WHERE username = '$username'";
				$statement = $con->prepare($query);
				$statement->bind_param('ss', $hash,$salt);
				if ($statement->execute()) { 
				  $_SESSION['success'] = 'Please relogin with your new password';
				  $_SESSION['authorized'] = false;
				} else {
					$_SESSION['error'] = 'Password is not changed!';
				}
				$statement->free_result();
				$statement->close();
			}
		}
		$stmt->free_result();
		$stmt->close();
	}
	
}
public function check_if_time_is_expired() {
	// 1 min = 60 sec
    if(time() - $_SESSION['last_activity'] > TIMER) { // 10 minutes but you could use 480 for 8 minutes
        // Do redirect or take other action here
		$this->logout();
		exit;
    } else {
		$_SESSION['last_activity'] = time();
	}
}

}
function is_locked_out($ip,$db) {
	$sql = "SELECT IPADD, DATE, attempts, FAIL FROM login WHERE IPADD = INET_ATON('$ip')";
    $result = $db->query($sql);
	if($result->num_rows == 1) {
		$row = $result->fetch_array();
		$datetime1 = new DateTime($row['DATE']);
		$datetime2 = new DateTime(date("Y-m-d H:i:s"));
		$interval = $datetime1->diff($datetime2);
		$failtimes = $row['FAIL'];
		
		if( $failtimes > 0 && $interval->format('%i') >= lockedforhowlong($failtimes) )
		{
			reset_ip($ip,$db);
			
			$db->query("UPDATE login SET FAIL = '$failtimes' + 1 WHERE IPADD = INET_ATON('$ip')");
			return false;
		}
		if( $row['attempts'] >= 3 )
		{
			
			if($failtimes == 0) { $failtimes = 1; $db->query("UPDATE login SET FAIL = '$failtimes' WHERE IPADD = INET_ATON('$ip')"); }
			$time = (lockedforhowlong($failtimes)) - $interval->format('%i');
			
			$_SESSION['error'] = 'You have been locked for: ' .$time. ' mins';
			return true;
		}
    }
	return false;
}
function lockedforhowlong($failedattempts) {
    $lockeddownmins = 5;
	$lockedtimemins = $failedattempts * $lockeddownmins;

	return $lockedtimemins;
}
function logged_ip($db,$ip,$username) {
	$query = "UPDATE members SET ipadd=INET_ATON('$ip'), lastlogin=NOW() WHERE username=?";
	$statement = $db->prepare($query);

	
	//bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
	$statement->bind_param('s',$username);
	$results = $statement->execute();
	
	if($results){
		//$_SESSION['success'] = 'Success! record updated';
	} else {
		$_SESSION['error'] = 'Error : ('.$db->errno.') '.$db->error;
	}
}
function fail_loggin($db,$ip,$username)
{
	$sql = "SELECT IPADD, DATE, attempts, FAIL FROM login WHERE IPADD = INET_ATON('$ip')";
    $result = $db->query($sql);
	if($result->num_rows == 1) {
		$row = $result->fetch_array();
		$attempt = $row['attempts'];
		$db->query("UPDATE login SET attempts = '$attempt' + 1 WHERE IPADD = INET_ATON('$ip')");
	} else {
				
		$DATE = date('Y-m-d H:i:s');
		$db->query("INSERT INTO login (IPADD,DATE,attempts) VALUES (INET_ATON('$ip'),'$DATE','1')");
	}
	
	$_SESSION['authorized'] = false;
}
function reset_ip($ip,$db) {
	$sql = "SELECT * FROM login WHERE IPADD = INET_ATON('$ip')";
	$stmt = $db->query($sql);
	 if ($stmt->num_rows == 1) {
		$db->query("UPDATE login SET attempts = '0',FAIL = '0' WHERE IPADD = INET_ATON('$ip')");
	 }
}
//// to check ip address
function getip() {
       foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe

                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}

function wp_strip_all_tags( $string, $remove_breaks = false ){
    $string = preg_replace( '@<(script|style)[^>]*?>.*?@si', '', $string );
    $string = strip_tags($string);

    if ( $remove_breaks )
        $string = preg_replace('/[rnt ]+/', ' ', $string);

    $string = str_replace('"', "", $string);
    $string = str_replace("'", "", $string);
    return trim($string);
}

function valid_name($string) {
	if(preg_match('/^[a-zA-Z0-9]{3,}$/', $string)) { // for english chars + numbers only
		// valid username, alphanumeric & longer than or equals 3 chars
		return true;
	}
	return false;
}
// Render error messages
function messages() {
    $message = '';

	if( isset($_SESSION['error']) &&  $_SESSION['error'] != '')
	{
		//if($_SESSION['error'] != '') {
			$message = '<div class="alert alert-danger" role="alert">'.$_SESSION['error'].'</div>';
			$_SESSION['error'] = '';
		//}
	}
	if( isset($_SESSION['success']) && $_SESSION['success'] != '')
	{
		//if($_SESSION['success'] != '') {
			$message = '<div class="alert alert-success" role="alert">'.$_SESSION['success'].'</div>';
			$_SESSION['success'] = '';
		//}
	}
	
    echo "$message";
}

function errors($error){
	if (!empty($error))
	{
			$i = 0;
			while ($i < count($error)){
			$showError.= '<div class="alert alert-danger" role="alert">'.$error[$i].'</div>';
			$i ++;}
			echo $showError;
	}// close if empty errors
} // close function
function randomSalt($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~`!@#$%^&*()_+=-[]{}:;/.,<>?';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
} 

?>
<?php
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/
ob_start();
session_start();
// Leaves old session intact
session_regenerate_id();

// Deletes old session
session_regenerate_id(true);


// db properties
define('DBHOST','DBHOST');
define('DBUSER','DBUSER');
define('DBPASS','DBPASS');
define('DBNAME','DBNAME');


$con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME) or die("Some error occurred during connection " . mysqli_error($con));

// define site path with backslash
define('DIR','http://mysite.com/user/');

// define admin site path with backslash
define('DIRADMIN','http://mysite.com/admin/');

// define site title for top of the browser
define('SITETITLE','User Manager');

// can register 1 else 0
define('can_register', 1);

// define time to logout (sec) 
define('TIMER', '600');

// define pepper for checking logging purposes
// you can use your own randomize value below
define('PEPPER','lKGl]i]YD0W4816/XJ[23Et7e)U?G-Cgd.2HIH[hTv&@w<%d*og!#Ov&cJ_]N,:R');

// use captcha 1 else 0
define('using_captcha', 1);

//define include checker
define('included', 1);

require('functions.php');

$user = new myDatabase($con);
?>
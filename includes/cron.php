<?php
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/

require('../includes/config.php'); 
//MySqli Delete Query\
$mysqli = $user->db;
// delete record that is 1 day old
//$results = $mysqli->query("DELETE FROM tokens WHERE date < (NOW() - INTERVAL 1 DAY)");

// delete record that is 1 hour old
$results = $mysqli->query("DELETE FROM tokens WHERE date < (NOW() - INTERVAL 1 HOUR)");

if($results){
    print 'Success! deleted one day old records';
}else{
    print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
}

?>
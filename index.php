<?php
require('includes/config.php'); 

//make sure user is logged in, function will redirect use if not logged in
$user->login_required();
$user->check_if_time_is_expired();


echo 'you are logged-in!!';
?>
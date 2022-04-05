<?php 

require '../controller/helper.php';
require '../controller/User.php';
require '../model/Database.php';
require '../model/Model.php';

$user = new User();
$user->checkSession();

if(isset($_GET['user'])) {
	$user->userLoginDevMode(htmlentities($_GET['user']));
}

?>
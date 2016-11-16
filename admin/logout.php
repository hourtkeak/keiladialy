<?php
require_once 'class.user.php';
$user_logout = new USER();
$user_logout->sec_session_start();

if(!$user_logout->is_logged_in())
{
// Unset all session values 
$_SESSION = array();

// get session parameters 
$params = session_get_cookie_params();

// Delete the actual cookie. 
setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

// Destroy session 
session_destroy();
header("Location: index.php");
exit();
}

if($user_logout->is_logged_in()!="")
{
	$user_logout->logout();	
	$user_logout->redirect('index.php');
}
?>
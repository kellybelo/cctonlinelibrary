<?php

require 'connection.php';

//function to check login input against database
function checkAuth($userId, $password, $DBH){
	
	$q = $DBH->prepare("select * from users_table where user_id = :userId and password = :password LIMIT 1");
    $q->bindValue(':userId', $userId);
    $q->bindValue(':password', $password);
    $q->execute();
    $check = $q->fetch(PDO::FETCH_ASSOC);
	
	if (empty($check)){
		return null;
	}else{
		return $check;
	}
}

if($_POST){
	
	$userId   = $_POST['userId'];
    $password = $_POST['password'];

	
	$user = checkAuth($userId, $password, $DBH);
	if($user){
		session_start();
		$_SESSION['userId'] = $userId;
	    echo '<script> window.location="myaccount.php"; </script>';
	}else{
		 echo '<script> window.location="index.php"; </script>';
	}
}
?>


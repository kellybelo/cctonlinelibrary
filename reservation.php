<?php

require 'connection.php';
require("MailSender.php");

$sender = new Sender();

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
	$isbn     = $_POST['isbn'];
	
	$user = checkAuth($userId, $password, $DBH);
	if($user){
		session_start();
		$_SESSION['userId'] = $userId;
				
		//select first avaliable volume of the book
		$sql = "select id from volumes where isbn=? and reserved=0 LIMIT 1;";
		$q = $DBH->prepare($sql);
		$q->bindParam(1, $isbn, PDO::PARAM_STR);
		$q->execute();
		$row = $q->fetch(PDO::FETCH_ASSOC);
		
		//check if there is a volume
		if (!empty($row)){
			
			$volume_id = $row['id']; //take the id of the volume
			
			//this could be done as procedure or transection to prevent race condition(put this in documentation).
			$sql = "UPDATE volumes SET reserved = 1 WHERE id=?;";
			$sth = $DBH->prepare($sql);
			
			$sth->bindParam(1, $volume_id, PDO::PARAM_INT);
			$sth->execute();					
				
			$sql = "INSERT INTO reservation(`user_id`, `volume_id`, `rental_date`, `due_date`) VALUES(?, ?, now(), DATE_ADD(now(), INTERVAL 7 DAY));";
			$sth = $DBH->prepare($sql);
		
			$sth->bindParam(1, $userId, PDO::PARAM_INT);
			$sth->bindParam(2, $volume_id, PDO::PARAM_INT);
			$sth->execute();
			
			//this funtion is getting the last PK inserted into the reservation table.
			$last_id = $DBH->lastInsertId();
			
			$q = $DBH->prepare("select r.id as r_id, volume_id, due_date, title_name
			from reservation as r, titles as t, volumes as v 
			where v.id=r.volume_id
			and v.isbn=t.isbn
			and r.id = ?
			");
			
			$q->bindParam(1, $last_id, PDO::PARAM_INT);
			$q->execute();
			$r = $q->fetch(PDO::FETCH_ASSOC);
			
			echo $sender->reserve($user['fname'] . " " . $user['lname'], $user['email'], $r['r_id'], $r['volume_id'], $r['title_name'],
			$r['due_date']);
				
		}else{
			echo "You've been added to a waiting list!";
		}		
	}else{
		echo 'login not accepted, get out of here';}		
}
	
?>

<script>
window.location="myaccount.php";
</script>
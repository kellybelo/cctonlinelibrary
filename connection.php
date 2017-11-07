<?php
	// making the connection to the database
	try {
		$host = '127.0.0.1';
		$dbname = 'library';
		$user = 'root';
		$pass = '';
		# MySQL with PDO_MYSQL
		$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        } catch(PDOException $e) { 
		echo 'Error';
		}  
	
?>
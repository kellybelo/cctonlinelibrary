<?php
require 'connection.php';

if($_POST){
	
	$reservation_id  = $_POST['id'];
	
	//set return date in reservation table
	$sql = "UPDATE reservation SET return_date = now() WHERE id=?;";
	$sth = $DBH->prepare($sql);
			
	$sth->bindParam(1, $reservation_id, PDO::PARAM_INT);
	$sth->execute();	
	
	//get volume_id of book that has been returned
	$sql = "SELECT volume_id from reservation WHERE id=?;";
	$q = $DBH->prepare($sql);
	$q->bindParam(1, $reservation_id, PDO::PARAM_INT);
	$q->execute();
	
	$row = $q->fetch(PDO::FETCH_ASSOC);
	$volume_id = $row['volume_id'];
	
	//set the volume to be avaliable
	$sql = "UPDATE volumes SET reserved = 0 WHERE id=?;";
	$sth = $DBH->prepare($sql);
	$sth->bindParam(1, $volume_id, PDO::PARAM_INT);
	$sth->execute();
	
}	
?>



<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
		<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	</head>
	
	<body>
		<div data-role="page" id="page">
			<div data-role="header">
				<h1>CCT Library Online Admin</h1>
			</div>
			<div data-role="navbar" data-grid="c" data-theme="a">
	   		 <ul>
				<li><a href="/index.php" data-ajax="false">Home</a></li>
	   		 </ul>
			</div><!-- /navbar -->	 		
			<div data-role="content">
				<form data-ajax="false" action="admin.php" method="post">
					<p>Update Database<p>
					Reservation Number<input type="text" name="id"/>
					<input type="submit" value="Return book"/>
				</form>	
				
			</div>
			
			<div data-role="footer" data-theme="b">
			<h4> @copy CCT Library Online team </h4>
			</div >
			
			
		</div>
				
	
	</body>
</html>





<?php
/*this page is not finished
I'm testing if i can show the user information on the scree
and looking for some jquery style.
*/
require 'connection.php';
session_start();
	if(isset($_SESSION['userId'])) {
			$userId = $_SESSION['userId'];

			$q = $DBH->prepare("select r.id as r_id, volume_id, rental_date, due_date, return_date, title_name, v.isbn as v_isbn, fname, lname, now()
			from reservation as r, titles as t, volumes as v, users_table as u
			where v.id=r.volume_id
			and v.isbn=t.isbn
			and r.user_id=u.user_id
			and r.user_id=? ORDER BY r_id DESC;");
			// running the SQL
			$q->bindParam(1, $userId, PDO::PARAM_STR);
			$q->execute();
			// pulling the data into a variable
			$rows = $q->fetchall(PDO::FETCH_ASSOC);
			$fname = $rows[0]['fname'];
			$lname = $rows[0]['lname'];
	}
	else
	{
		echo 'error userId';
	}
?>
<!DOCTYPE HTML>


<html>
	<head>	
        <meta="UFT-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
		<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> 
	</head>
<body>
	<div data-role="page" id="page">		
		<div data-role="header" data-position="fixed" style="background-color:white;">
			<div class="ui-grid-d">
			
				<div  class="ui-block-a" style="max-width:100%; width:auto;  margin:0 auto;"> 
				<img src="./img/log4.jpg">
				</div>			
				<div  class="ui-block-b" style="max-width:100%; width:auto;  margin:0 auto;"> 
				<h1>Online Library</h1>
				</div>
				<div class="ui-block-c"style="float:right;  max-width:80%; width:auto;  margin:0 auto;">
				<a href="https://cctsms2016.appspot.com/"><img src="./img/pic.png"></a>
				</div>
				<div class="ui-block-d"style="float:right; max-width:80%; width:auto;  margin:0 auto;"">
				<a href="https://cctsms2016.appspot.com/"><img src="./img/cct.jpg"></a>
				</div>
				
				
			</div>
						
				
			<div data-role="navbar" data-grid="c">
	   		<ul>
				<li><a href="index.php" data-ajax="false">Home</a></li>
				<li><a href="video.php" data-ajax="false">Video</a></li>
				<li><a href="http://eds.a.ebscohost.com/eds/search/basic?sid=c9ae83ef-fd1f-4a0e-bc9c-5211f3a72608@sessionmgr4009&vid=2&tid=3000EP&sdb=edspub">Publications</a></li>
				<li><a href="logout.php" data-ajax="false">Logout</a></li>
			</ul>
			</div><!-- /navbar -->
		</div>
		
		
		<div data-role="content">
		
		<?php	
		echo "<h3 style='text-align: center;'>Userame: " . $fname . " " . $lname . "</h3>";
		
		foreach($rows as $r){
			echo "<div style='background-color:#e1f1f5; text-align: center; max-width:800px; width:auto;  margin:0 auto;
				border-radius: 10px; box-shadow: 5px 5px 5px #cccccc;'>";
				
				if ($r['return_date'] == null and $r['now()'] > $r['due_date']){
				echo '<img style="float:right;" src="./img/over.jpg">';
				
			}
			echo	"<p> <ul style='text-align:justify'><b>Reservation number: " . $r['r_id'] . "</b></p>
				 <p> Book Title: " . $r['title_name'] . "</p>
				<p> ISBN: " . $r['v_isbn'] . "</p>
				<p> Reservation date: " . $r['rental_date'] . "</p>
				<p> Due data: " . $r['due_date'] . "</p>
				<p> Returned date: " . $r['return_date'] . "</P> </ul>";
				
			
			echo "</div>";								
	    }
		?>
		
			
		</div>
		
		<div data-role="footer" data-position="fixed"  data-theme="b">
		<h4> @copy CCT Library Online team </h4>
		
		</div >		
		
	</div>				
	
	</body>
<html>
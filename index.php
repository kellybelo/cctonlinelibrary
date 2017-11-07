<?php
require 'connection.php';

session_start();

$q = $DBH->prepare("select * from titles");
// running the SQL
$q->execute();
// pulling the data into a variable
$rows = $q->fetchall(PDO::FETCH_ASSOC);

?>

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
				<li><a href="index.php" data-ajax="false" class="ui-btn-active">Home</a></li>
				<?php 
					if(isset($_SESSION['userId'])) {
						echo '<li><a href="myaccount.php" data-ajax="false">MyAccount</a></li>';
					}else{
						echo '<li><a href="#popupLogin" data-rel="popup" data-position-to="window" class="ui-btn ui-btn-inline ui-btn-a" data-transition="pop">MyAccount</a></li>';
					}
				?>
				<li><a href="video.php" data-ajax="false">Video</a></li>
				<li><a href="http://eds.a.ebscohost.com/eds/search/basic?sid=c9ae83ef-fd1f-4a0e-bc9c-5211f3a72608@sessionmgr4009&vid=2&tid=3000EP&sdb=edspub">Publications</a></li>
			</ul>
			</div><!-- /navbar -->
			<div data-role="popup" id="popupLogin" data-theme="a" class="ui-corner-all">
				<form style="background-color:#e1f1f5;" data-ajax="false" action="login.php" method="post">
		            <h3>Please sign in</h3>
		
		           	UserID <input type="text" name="userId"/>
					Password <input type="password" name="password"/>
		            <button type="submit" >Sign in</button>
				</form>			
			</div>
		</div>
		
   		<div data-role="main" class="ui-content" style="max-width:800px; width:auto;  hight:500px; margin:0 auto;">			
			<form class="ui-filterable">			
    			<input id="inset-autocomplete-input" data-type="search" placeholder="Search book titles or book types...">
			</form>
			<ul data-role="listview" data-inset="true" data-filter="true" data-filter-reveal="true" data-input="#inset-autocomplete-input">
				<?php
					
					foreach($rows as $r){
					echo "<li><a data-ajax='false', href='book.php?id=" . $r['isbn'] ."'>" . $r['title_name'] . " - " . $r['category'] . "</a></li>";							
					}
				?>
			</ul>	
		</div >	
		</div>
		<div data-role="footer" data-position="fixed"  data-theme="b">
		<h4> @copy CCT Library Online team </h4>
		
		</div >
	</div>
	
</body>

</html>

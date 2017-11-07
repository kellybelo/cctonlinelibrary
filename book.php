<?php
	// making the connection to the database
	require 'connection.php';
	
		
	//getting isbn from the url.
	 if(isset($_GET['id'])) {
		$isbn = $_GET['id'];
	
	
		// selecting the row from the database
		$q = $DBH->prepare("SELECT title_name, category, au_fname, au_lname
		FROM authors as a, titles as t, title_authors as r
		WHERE t.isbn=r.isbn
		AND r.au_id=a.au_id
		AND t.isbn = :id;");	
		
		$q->bindValue(':id', $isbn, PDO::PARAM_STR);
		// running the SQL
		$q->execute();
		// pulling the data into a variable
		$check = $q->fetch(PDO::FETCH_ASSOC);
		// taking each individual piece
		$title_name = $check['title_name'];
		$au_fname = $check['au_fname'];
		$au_lname = $check['au_lname'];
		$category = $check['category'];
		
		// counting the total of volumes and passing to the variable $volumes.
		$q = $DBH->prepare("SELECT COUNT(isbn) as count_vol from volumes where isbn= :id;");
		$q->bindValue(':id', $isbn, PDO::PARAM_STR);
		// running the SQL
		$q->execute();
		// pulling the data into a variable
		$check = $q->fetch(PDO::FETCH_ASSOC);
		$volumes = $check['count_vol'];
		
		if($volumes){
			
			$q = $DBH->prepare("SELECT COUNT(isbn) as copies from volumes where reserved=0 and isbn= :id;");
			$q->bindValue(':id', $isbn, PDO::PARAM_STR);
			// running the SQL
			$q->execute();
			// pulling the data into a variable
			$check = $q->fetch(PDO::FETCH_ASSOC);
			$copies = $check['copies'];
				
			//checking number of isbn copies
			if($copies){
				$msg = 'Book available';
			}else{
				$msg = 'Waiting list';
			}
			
		}else{
			$msg = 'e-Book';
		}
	 }
	
?>

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta="UFT-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>  
  
	<!-- Google Books Embedded Viewer API Example -->
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    
    <script type="text/javascript" src="https://www.google.com/books/jsapi.js"></script>
	<script type="text/javascript">
		google.books.load();
		
		function initialize() {
			var viewer = new google.books.DefaultViewer(document.getElementById('viewerBook'));
			viewer.load(document.getElementById('isbn').value); //'144933072X'
		}
		google.books.setOnLoadCallback(initialize); 
		function showDiv(){
			$("#loginDiv").show();
		}
	</script>
  </head>  
  <body >
  <div data-role="page" id="page">		
		<div data-role="header" style="background-color:white;">
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
		</div>	
		
		
		<div data-role="main" class="ui-content" style="max-width:800px; width:auto;  margin:0 auto;">
			
			<div>
				<div id="viewerBook" style="height:600px" ></div>		
			</div>			
			<div style="background-color:white; shadow:grey;">
				<input type='hidden' id='isbn' value='ISBN:<?php echo $isbn?>'></input>
				<p><b>Title: </b><?php echo $title_name?></p>
				<p><b>Author: </b><?php echo $au_fname?> <?=$au_lname?></p>
				<p><b>Category: </b><?php echo $category?></p>					
				<p><b>ISBN: </b><?php echo $isbn?></p>
				<p><?php echo $msg?></p>
				<?php
				if($msg=='Book available' or 'waiting list ' ){
					echo '<button onclick="showDiv();">Reserve this book</button>';
				}
				if($msg=='e-Book'){
					echo '<button onclick="">Open e-book</button>';
				}
				?>
			</div>			
			<div id="loginDiv" style="width:px; display:none">						
				<form data-ajax="false" action="reservation.php" method="post">
					<input type='hidden' name="isbn" value="<?=$isbn?>"></input>
					UserID <input type="text" name="userId"/>
					Password <input type="password" name="password"/>
					<input type="submit" value="confirm"/>
				</form>						
			</div>
		</div>
			
		<div data-role="footer" data-position="fixed" data-theme="b">
		<h4> @copy CCT Library Online team </h4>		
		</div >	
	</div>			
 </body>
</html>
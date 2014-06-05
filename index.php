<?php
session_start();
if(isset($_POST['Username']))
{
	$_SESSION['Username'] = $_POST['Username'];
	unset($_POST['Username']);
}
if(isset($_POST['Password']))
{
	$_SESSION['Password'] = $_POST['Password'];
	unset($_POST['Password']);
}
echo 	'<html><head><title>UCPi: User Control Panel INI</title><link href="css/style.css" rel="stylesheet">
		<link href="starter-template.css" rel="stylesheet"></head>
		<body>
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
         <span class="sr-only">Toggle navigation</span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">User Control Panel</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
		';
		   if(isset($_SESSION['Password']) && isset($_SESSION['Username']))
		   {
			   echo '<li><a STYLE=text-decoration:none href="index.php?page=0" target="_self">My Profile</a></li>
			   <li><a STYLE=text-decoration:none href="index.php?page=1" target="_self">Account Settings</a></li>
			   <li><a STYLE=text-decoration:none href="index.php?page=2" target="_self">Signature</a></li>
			   <li><a STYLE=text-decoration:none href="index.php?page=3" target="_self">Players Stats</a></li>
			   <li><a STYLE=text-decoration:none href="index.php?page=4" target="_self">Logout</a></li>';
		   }
		   else
		   {
			   echo'<li><a STYLE=text-decoration:none href="index.php" target="_self">Login</a></li>';   
		   }
		   echo '</ul>
				</div><!--/.nav-collapse -->
				</div>
				</div>';
			echo '<div class="container">
					
					<div class="starter-template">';	
			include('ucp.php'); 
			echo '</div></div></div></html>';
?>
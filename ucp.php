<?php
if(!isset($_SESSION['Username'])&&!isset($_SESSION['Password'])&&!isset($_POST['Username']) && !isset($_POST['Password']))
{
	echo '<form name="form1" method="post" action="index.php">
		<strong><span style="color:black">UCPi Login</span></strong><br>
		Username: <input name="Username" type="text" id="Username"><br>
		Password: <input name="Password" type="password" id="Password"><br>
		<input type="submit" name="Submit" value="Login">
		</form>';
}
else
{
	require_once 'Config/Lite.php';
	
	$FTP_HOST = "HOST";
	$FTP_USER = "USER";
	$FTP_PASS = "PASS";

	// set up basic connection
	$cHandle = ftp_connect($FTP_HOST) or die("Server can't connect to ftp");

	// login with username and password
	$login_result = ftp_login($cHandle, $FTP_USER, $FTP_PASS) or die("Server can not login to ftp!");
	
	$user['Name'] = 'ftp://'.$FTP_USER.':'.$FTP_PASS.'@YOURSERVERIPHERE/samp03/users/'.$_SESSION['Username'].'.ini';
	
	$file = new Config_Lite($user['Name']);//Get INI file
	
	//Check If account exists
	if (!file_exists($user['Name'])) {
    unset ($_SESSION['Password'],$_SESSION['Username']);
	echo 'Invalid Account name!
	<meta HTTP-EQUIV="REFRESH" content="3; url=index.php">';
	} else
	{
	//GetData
	$user['Password'] = $file->get(null, 'Password');
	$user["Money"] = $file->get(null, 'Money');
	$user["Score"] = $file->get(null, 'Score');
	$user["Admin"] = $file->get(null, 'Admin');
	$user["VIP"] = $file->get(null, 'VIP');
	$user["Skin"] = $file->get(null, 'Skin');
	$user["ID"] = $file->get(null, 'ID');
	$user["RegOn"] = $file->get(null, 'RegDate');
	$password1 = $_SESSION['Password'];
	
	//Hashing Pass..
	$hashed = hash('whirlpool', $password1);
	//Checking Pass
	if($user['Password'] != $hashed)
	{
	unset ($_SESSION['Password'],$_SESSION['Username']);
	echo 'Invalid Password, Please try again!
	<meta HTTP-EQUIV="REFRESH" content="3; url=index.php">';
	} else
	{
			if(!isset($_GET['page']))
			{
				$_GET['page'] = 0;
			}
			$page = $_GET['page'];
			if($page == 0)
			{
				echo '<table align="center" width="80%"><TR VALIGN=TOP><td><span style="color:black">
				</td>
				<td>';
				echo '<br><img align="left" SRC="images/skins/'.$user['Skin'].'.jpg">';
				echo '<h1 style="color:red" align="center">Your Stats:</h1>';
				echo "<br><span style='color:black'><h2 align='center'>Money: ".$user['Money']."</span>";
				echo "<br><span style='color:black'>Score: ".$user['Score']."</span>";
				echo "<br><span style='color:black'>AdminLevel: ".$user['Admin']."</span>";
				echo "<br><span style='color:black'>VIPLevel: ".$user['VIP']."</span>";
				echo "<br><span style='color:black'>Skin: ".$user['Skin']."</span>";
				echo "<br><span style='color:black'>Registered On: ".$user['RegOn']."</span>";
				echo "</h2>";
				echo'</td></tr></table>';
			} else
			if($page == 1)
			{
				if(!isset($_GET['ac']))
				{
					$_GET['ac'] = 0;
				}
				$acc = $_GET['ac'];
				if($acc == 0)
				{
					echo '<h1><a href="index.php?page=1&ac=1">Change Password</a></h1>';
				} else
				if($acc == 1)
				{
					if(!isset($_POST['password']))
					{
						echo '<center>
						New Password: <form action="index.php?page=1&ac=1" method="post">
						<input type="text" name="password" />
						<input type="submit" value="Change" />
						</form>
						<center>';
					}
					else
					{	
						//Hashing Pass..
						$hashed = hash('whirlpool', $_POST['password']);
						$file->set(null, 'Password', $hashed);//Set password
						$file->save();//save file
						echo "Your password have been changed successfully";
						unset ($_SESSION['Password'],$_SESSION['Username']);
						echo '<br>Please Login Again, with new password!
						<meta HTTP-EQUIV="REFRESH" content="3; url=index.php">';
						
					}	
					}
			}
			else
			if($page == 2)
			{
				if(!isset($_POST['name']))
				{
					echo '<center><br>Type the username below to generate:<br>
					<form action="index.php?page=2" method="post">
					<input type="text" name="name" />
					<input type="submit" value="Show" />
					</form>
					<center>';
				}
				else
				{
					$user['Name'] = 'ftp://'.$FTP_USER.':'.$FTP_PASS.'@YOURSERVERIPHERE/samp03/users/'.$_POST['name'].'.ini';
					
					$file = new Config_Lite($user['Name']);//Get INI file
					if (!file_exists($user['Name'])) {
					echo 'No such user exists.
						<meta HTTP-EQUIV="REFRESH" content="3; url=index.php?page=2">';
					} else
					{
					//GetData Of user.
					$user["Money"] = $file->get(null, 'Money');
					$user["Score"] = $file->get(null, 'Score');
					$user["Admin"] = $file->get(null, 'Admin');
					$user["VIP"] = $file->get(null, 'VIP');
					$user["Skin"] = $file->get(null, 'Skin');
					$user["ID"] = $file->get(null, 'ID');
					$user["RegOn"] = $file->get(null, 'RegDate');
					
					$add = "images/generate.jpg";
					$tsrc = "sig\/".$_POST['name'].".jpg";
					$im = ImageCreateFromJPEG($add);
					$green = imagecolorallocate($im,41,175,72);
					$pink = imagecolorallocate($im,222,0,225);
					$font2 = 'arial.ttf';
					$font = 'arialbd.ttf';
					imagettftext($im, 20, 0 ,80 ,25, $green, $font,$_POST['name']);
					imagettftext($im, 10, 0 ,80 ,60, $pink, $font,"Cash ".$user['Money']);
					imagettftext($im, 10, 0 ,80 ,80, $pink, $font,"Score ".$user['Score']);
					imagettftext($im, 10, 0 ,80 ,100, $pink, $font,"SkinID  ".$user['Skin']);
					
					imagettftext($im, 10, 0 ,170 ,60, $pink, $font,"AdminLevel ".$user['Admin']);
					imagettftext($im, 10, 0 ,170 ,80, $pink, $font,"VIPLevel ".$user['VIP']);
					imagettftext($im, 10, 0 ,170 ,100, $pink, $font,"Registered On  ".$user['RegOn']);
					ImageJpeg($im,$tsrc);
					echo "<h1 style='color:red' align='center'>".$_POST['name']."'s statics has been generated:</h1>";
					echo '<center><img src="sig/'.$_POST['name'].'.jpg"></img></center>';
					}
					}
				}	
			else
			if($page == 3)
			{
				if(!isset($_POST['name']))
				{
					echo '<center><br>Type the username below:<br>
					<form action="index.php?page=3" method="post">
					<input type="text" name="name" />
					<input type="submit" value="Show" />
					</form>
					<center>';
				}
				else
				{
					$user['Name'] = 'ftp://'.$FTP_USER.':'.$FTP_PASS.'@YOURSERVERIPHERE/samp03/users/'.$_POST['name'].'.ini';
					
					$file = new Config_Lite($user['Name']);//Get INI file
					if (!file_exists($user['Name'])) {
					echo 'No such user exists.
						<meta HTTP-EQUIV="REFRESH" content="3; url=index.php?page=3">';
					} else
					{
					//GetData Of user.
					$user["Money"] = $file->get(null, 'Money');
					$user["Score"] = $file->get(null, 'Score');
					$user["Admin"] = $file->get(null, 'Admin');
					$user["VIP"] = $file->get(null, 'VIP');
					$user["Skin"] = $file->get(null, 'Skin');
					$user["ID"] = $file->get(null, 'ID');
					$user["RegOn"] = $file->get(null, 'RegDate');
					
					echo '<table align="center" width="80%"><TR VALIGN=TOP><td><span style="color:black">
					</td>
					<td>';
					echo "<h1 style='color:red' align='center'>".$_POST['name']." stats:</h1>";
					echo "<span style='color:black'><h2 align='center'>Money: ".$user['Money']."</span>";
					echo "<br><span style='color:black'>Score: ".$user['Score']."</span>";
					echo "<br><span style='color:black'>AdminLevel: ".$user['Admin']."</span>";
					echo "<br><span style='color:black'>VIPLevel: ".$user['VIP']."</span>";
					echo "<br><span style='color:black'>Skin: ".$user['Skin']."</span>";
					echo "<br><span style='color:black'>Registered On: ".$user['RegOn']."</span>";
					echo "</h2>";
					echo'</td></tr></table>';
					}
					}
				}
					
			else
			if($page == 4)
			{
				unset ($_SESSION['Password'],$_SESSION['Username']);
				echo '<span style="color:red">Logging you out...</span><meta HTTP-EQUIV="REFRESH" content="2; url=index.php">';
			}
			else
			{
				$_GET['page'] = 0;
				echo 'Invalid page, redirecting you to profile.
				<meta HTTP-EQUIV="REFRESH" content="2; url=index.php">';
			}
	}
	}
	}
?>

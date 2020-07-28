<?php
session_start();
#include('db.php');
 global $con;
 
$nameErr = $emailErr = $phoneErr= $passwrodErr= $usernameErr= $name = $email = $phone = $username = $passwrod = "";
 if(isset($_POST['btn_submit']))
 {
 		$username=$_POST['username'];
 		$password=$_POST['password'];	
 		 
      #  if($username=='admin' && $password=='admin'){
		 
        	header('Location:afterlogin.php');
        
       #}
		#$else{

        	#header('Location:index.php?msg=error');
       # }
 }
  
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Forensics Analysis</title>

<link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>
	<table  class="wraper" border="0">
	<?php include('menu.php'); ?>
		<tr>
			<td  height="505" colspan="2" valign="top" align="center"> 
				<h1>Login</h1>
				<form action="" method="post">
				<table class="table_login" width="40%" border="0" style="margin-left: auto;margin-right:auto;" >
  					<tr>
						<td text-align="right">Username</td>
						<td><input type="text"   size="30" name="username" required="required" id="username" value="admin"></td>
					</tr>
					<tr>
						<td text-align="left">Password</td>
						<td><input type="password"   size="30" name="password" required="required" value="admin" id="password"></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><button name="btn_submit" type="submit" style="width: 25%;height: 31px;margin-right: 50px;">Login</button> </td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
		<tr class="tr_row">
			<td height="20" colspan="2" bgcolor="#9F6479" align="center"><span class="style11">Copyright &copy; 2019 College of Engineering, Pune</span></td>
		</tr>
	</table>
</body>
</html>

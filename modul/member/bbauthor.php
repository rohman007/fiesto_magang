<?php
 
 include ("../../kelola/urasi.php");
 include ("../../kelola/fungsi.php");
 include ("bbfungsi.php");
 include ("bburasi.php");
 
 $username = $_POST['user'];
 $password = $_POST['pass'];
 $password = fiestopass($password);
 
 $flag=0;
 
 //$sql = "Select COUNT(username) from users;";
 $sql = "SELECT userid, level, fullname FROM users WHERE username='$username' AND password='$password'"; 
 $result = mysql_query($sql);
 /*if (!$result) {
	die('Invalid query: ' . mysql_error());
 }*/

 if(mysql_num_rows($result)==1)
 {
	$flag=1;
 }
 
 if($flag==1) echo  "|||true";
 else echo "|||false";
 
?>
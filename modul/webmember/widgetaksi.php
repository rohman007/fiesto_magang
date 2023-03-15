<?php
if ($jenis=='usermenu') 
{	if (!isset($_SESSION['member_uid'])) 
	{	
		$widget .= '
		<form action="'.$urlfunc->makePretty("?p=webmember&action=login").'" id="loginform" name="loginform" method="POST">
		<p>'._USERNAME.'<br />
		<input type="text" name="username" size="15" class=\"form-control\ "/></p>
		<p>'._PASSWORD.'<br />
		<input type="password" name="password" size="15" /></p>
		<input type="hidden" name="check_login" value="1">
		<p><input type="submit" name="submit" value="'._LOGIN.'" class=\"form-control\></p>
		</form>';
		$widget .= "<div class=\"registerlink\">";
		if($is_public_register) {
			$widget .= "<a href=\"".$urlfunc->makePretty("?p=webmember&action=register")."\">"._REGISTER."</a> ";
		}
		$widget .= "<a href=\"".$urlfunc->makePretty("?p=webmember&action=forgotpassword")."\">"._DOYOUFORGET."</a>";
		$widget .= "</div>";
	} else {	
		$widget .= "<ul class=\"member_menu\">\r\n";
		//$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=webmember")."\">"._MEMBERHOME."</a></li>\r\n";
		$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=order&action=history")."\">"._CEKSTATUSORDER."</a></li>\r\n";
		$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=webmember&action=editaccount")."\">"._EDITACCOUNT."</a></li>\r\n";
		$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=webmember&action=logout")."\">"._LOGOUT."</a></li>\r\n";
		$widget .= "</ul>\r\n";
	}
}
?>

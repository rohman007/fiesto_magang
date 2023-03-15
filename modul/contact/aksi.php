<?php
$action = $_GET['action'];
$sql = "SELECT judulfrontend FROM module WHERE nama='contact'";
$result = $mysql->query($sql);
list($title) = $mysql->fetch_row($result);
$content .= "<div id=\"contactall\">\r\n";

if ($action=='') {
	$sql = "SELECT value FROM contact WHERE name='notebefore'";
	$result = $mysql->query($sql);
	list($note) = $mysql->fetch_row($result);
	if ($note!='') {
		$content .= "<div id=\"notebefore\">\r\n";
		$content .= "$note\r\n";
		$content .= "</div>\r\n";
	}
	
	$sql = "SELECT value FROM contact WHERE name='noteafter'";
	$result = $mysql->query($sql);
	list($note) = $mysql->fetch_row($result);
	$admincontent .= "<h2>"._NOTEAFTER."</h2>";
	$admincontent .= "<div><textarea name=\"noteafter\" class=\"usetiny\">$note</textarea></div>";
	$sql = "SELECT value, required, tipeform FROM contact WHERE published='1' AND grup='0' ORDER BY indeks";
	$result = $mysql->query($sql);
	$content .= "<div id=\"contactform\">\r\n";
	$content .= "<form class=\"form-horizontal\" method=\"POST\" action=\"".$urlfunc->makePretty("?p=contact&action=send")."\">\r\n";
	//$content .= "<input type=\"hidden\" name=\"action\" value=\"kirim\" />\r\n";
	//if (!$ismobile) $content .= "<table border=\"0\">\r\n";
	if (!$ismobile) $content .= "<fieldset>\r\n";
	$i=0;
	while (list($value,$isrequired,$tipeform) = $mysql->fetch_row($result)) {
		$rowclass = (fmod($i,2)==0) ? "selang" : "seling" ;
		$required = ($isrequired) ? '*' : '' ;
		$tipefield = explode(';',$tipeform);
		if ($ismobile) {
			switch ($tipefield[0]) {
				case 'text':
					$content .= "<p class=\"$rowclass\">$value $required :<br /><input type=\"text\" name=\"field$i\" size=\"$tipefield[1]\" max_length=\"$tipefield[2]\" /></p>";
					break;
				case 'textarea':
					$content .= "<p class=\"$rowclass\">$value $required :<br /><textarea name=\"field$i\" cols=\"$tipefield[1]\" rows=\"$tipefield[2]\" class=\"$tipefield[3]\"></textarea></p>";
					break;
				case 'submit':
					$content .= "<p class=\"$rowclass\"><input type=\"submit\" name=\"submit\" value=\"$value\" /></p>";
					break;
			}
		} else {
			switch ($tipefield[0]) {
				case 'text':
					//$content .= "<div class=\"$rowclass form-group\"><td align=\"right\" valign=\"top\">$value $required :</td><td><input type=\"text\" name=\"field$i\" size=\"$tipefield[1]\" max_length=\"$tipefield[2]\" /></td></div>\r\n";
					$content .= "<div class=\"$rowclass form-group\"><span class=\"col-sm-2 text-right\">$value $required :</span><div class=\"col-sm-10 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"field$i\" size=\"$tipefield[1]\" max_length=\"$tipefield[2]\" /></div></div>\r\n";
					break;
				case 'textarea':
					$content .= "<div class=\"$rowclass form-group\"><span class=\"col-sm-2 text-right\">$value $required :</span><div class=\"col-sm-10 col-md-8\"><textarea class=\"form-control\" name=\"field$i\" cols=\"$tipefield[1]\" rows=\"$tipefield[2]\" class=\"$tipefield[3]\"></textarea></div></div>\r\n";
					break;
				case 'submit':
					// Google reCAPTCHA
					$content .= "<div class=\"$rowclass form-group\"><div class=\"col-md-10 col-md-offset-2 col-sm-offset-2\">";
					$content .= '<div class="g-recaptcha" data-sitekey="'.$siteKey.'"></div>';
					$content .= "</div></div>";
					$content .= "<div class=\"$rowclass form-group\"><div class=\"col-md-10 col-md-offset-2 col-sm-offset-2\"><input class=\"btn btn-default more\" type=\"submit\" name=\"submit\" value=\"$value\" />";
					break;
			}
		}
		$i++;
	}
	$sql = "SELECT	value FROM contact WHERE grup='1' AND indeks='1'";
	$result = $mysql->query($sql);
	list($value) = $mysql->fetch_row($result);
	if ($ismobile) {
		$content .= "<p>$value</p>\r\n";
	} else {
		$content .= '<p>'.$value.'</p></div>';
		$content .= "</fieldset>\r\n";
	}
	$content .= '<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl='.$lang.'"></script>';
	$content .= "</form>\r\n";
	$content .= "</div>\r\n";
	
	$sql = "SELECT value FROM contact WHERE name='noteafter'";
	$result = $mysql->query($sql);
	list($note) = $mysql->fetch_row($result);
	if ($note!='') {
		$content .= "<div id=\"noteafter\">\r\n";
		$content .= "$note\r\n";
		$content .= "</div>\r\n";
	}
}
if ($action=='send') {
	$logtext = "\r\n".date("Y-m-d H:i:s")."\t".$_SERVER['REMOTE_ADDR'];

	$sql = "SELECT name, value FROM contact WHERE grup='1' ORDER BY indeks";
	$result = $mysql->query($sql);
	while (list($name,$value) = $mysql->fetch_row($result)) {
		$$name=$value;
	}

	$nama = parse_url($_SERVER['HTTP_REFERER']);
	$isdomainok = FALSE;
	foreach ($okaydomains as $okaydomain) {
		if ($nama['host'] == $okaydomain || $nama['host'] == "www.$okaydomain" || $serverlocation == "off") $isdomainok = TRUE;
	}
		
	$wsv = time();
	$wib = $wsv + $serverhouroffset * 3600;
	$isiemail = date("Y-m-d H:i:s",$wib)." (WIB)\r\n".date("Y-m-d H:i:s",$wsv)." (server time)\r\n".$_SERVER['REMOTE_ADDR']."\r\n\r\n";

	
	$sql = "SELECT name, value, required, tipeform FROM contact WHERE published='1' AND grup='0' ORDER BY indeks";
	$result = $mysql->query($sql);
	for ($i=0;$i<$mysql->num_rows($result)-1;$i++) {
		$field[$i]=$_POST["field$i"];
	}
	$i=0;
	$findat = 0;
	$findhttp = 0;
	$pesan = $msgok;
	if (!($isdomainok)) {
		fiestolog($logtext."\t001\tWrong referer\t".$_SERVER['HTTP_REFERER'],'logattack.txt');
		$pesan = $msghack." (Error 001)";
	}
	
	while (list($name,$value,$required,$tipeform) = $mysql->fetch_row($result)) {
		if ($i<$mysql->num_rows($result)-1) {
			// Validasi: Trim field.
			$isifield=trim($field[$i]);

			// Validasi: Required fields.
			if ($required && $isifield=='') $pesan = $msgreq;

			// Filter 2: Cari kata-kata yang berkaitan dengan header e-mail.

			if (preg_match("/(cc)\s*(:)/",strtolower($isifield)) && $pesan==$msgok) {
				fiestolog($logtext."\t002\tcc exist\t$isifield",'logattack.txt');
				$pesan = $msghack." (Error 002)";
			}
			if (preg_match("/(boundary)\s*(=)/",strtolower($isifield)) && $pesan==$msgok) {
				fiestolog($logtext."\t003\tBoundary exist\t$isifield",'logattack.txt');
				$pesan = $msghack." (Error 003)";
			}
			if (strpos(strtolower($isifield),"content-type") !== FALSE && $pesan==$msgok) {
				fiestolog($logtext."\t004\tContent-Type exist\t$isifield",'logattack.txt');
				$pesan = $msghack." (Error 004)";
			}
			if (strpos(strtolower($isifield),"content-transfer-encoding") !== FALSE && $pesan==$msgok) {
				fiestolog($logtext."\t005\tContent-Transfer-Encoding exist\t$isifield",'logattack.txt');
				$pesan = $msghack." (Error 005)";
			}
			if (strpos(strtolower($isifield),"mime-version") !== FALSE && $pesan==$msgok) {
				fiestolog($logtext."\t006\tMIME-Version exist\t$isifield",'logattack.txt');
				$pesan = $msghack." (Error 006)";
			}

			if(isset($taboos) && $pesan==$msgok) {
				foreach ($taboos as $taboo) {
					if (preg_match("/\b$taboo\b/",strtolower($isifield))) {
						fiestolog($logtext."\t007\tTaboo words detected\t$isifield",'logattack.txt');
						$pesan = $msghack." (Error 007)";
					}
				}
			}
					
			if ($pesan==$msgok) {
				// Filter 3 (persiapan): ambil nilai field ke array
				$dobel[$i] = substr($isifield,0,5);

				// Filter 3 (persiapan): hitung jumlah field yang mengandung @
				if (strpos($isifield,"@") !== FALSE) $findat++;

				// Filter 3 (persiapan): hitung jumlah field yang mengandung http://
				if (strpos($isifield,"http://") !== FALSE) $findhttp++;

				//Pengamanan: Potong string sebatas yang diizinkan
				$tipefield = explode(';',$tipeform);
				switch ($tipefield[0]) {
					case 'text':
						$batasmax = $tipefield[2];
						break;
					case 'textarea':
						$batasmax = 0;
						break;
					case 'submit':
						$batasmax = 5;
						break;
				}
				if ($batasmax>0) $isifield = substr($isifield,0,$batasmax);

				//Pengamanan: Hilangkan HTML dan PHP tag
				$isifield = strip_tags($isifield);

				//Pengamanan: Ubah ";" menjadi ","
				$isifield = str_replace(";",",",$isifield);
			}
			
			//Pengamanan: Jika field email, validate. Otherwise, ubah "@" menjadi "[at]".
			if ($name=='email' && $pesan==$msgok) {
				$email = $isifield;
				if (preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/",$isifield) || !preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/",$isifield)) $pesan = $msgmail;
			} else {
				$isifield = str_replace("@","[at]",$isifield);
			}
			//Formatting: Field komentar
			if ($name=='comment' && $pesan==$msgok) {
				$isiemail .= "\r\n$isifield\r\n\r\n";
			} else {
				$isiemail .= "$value: $isifield\r\n";
			}
			
			if ($name=='sendername' && $pesan==$msgok) $sendername=$isifield;
			$i++;
		}
	}
	
	// Filter 3: Hitung threshold
	$threshold=0;
	for ($i=0;$i<$mysql->num_rows($result)-1;$i++) {
		for ($j=$i+1;$j<$mysql->num_rows($result)-1;$j++) {
			if ($dobel[$i]==$dobel[$j] && $dobel[$i]!='') $threshold++;
		}
	}
	if ($threshold>=3 && $pesan==$msgok) {
		fiestolog($logtext."\t008\tThreshold field ganda terlampaui\t$threshold",'logattack.txt');
		$pesan = $msghack." (Error 008)";
	}

	if ($findat>=3 && $pesan==$msgok) {
		fiestolog($logtext."\t009\tThreshold [at] terlampaui\t$findat",'logattack.txt');
		$pesan = $msghack." (Error 009)";
	}

	if ($findhttp>=3 && $pesan==$msgok) {
		fiestolog($logtext."\t010\tThreshold http terlampaui\t$findhttp",'logattack.txt');
		$pesan = $msghack." (Error 010)";
	}
	
	/**
	* Google reCaptcha
	* Aly 2018-05-04
	*/
	if ($_POST['g-recaptcha-response'] == '' && $pesan==$msgok) $pesan = "Something went wrong (Error required Google reCaptcha)";
	if (isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] != '') {
		$recaptcha = new \ReCaptcha\ReCaptcha($secret);
		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		if (!$resp->isSuccess()) {
			$pesan = "<p>The following error was returned: ";
			foreach ($resp->getErrorCodes() as $code) {
				fiestolog($logtext."\t010\tThe following error was returned:\t$code",'logattack.txt');
				$pesan .= '<kbd>' . $code . '</kbd> ';
			}
		}
	}
	
	// Send to mail
	if ($pesan==$msgok) {
		fiestophpmailer($umbalemail, _SUBJECT, $isiemail, $smtpuser, $sendername, $email);
	}
	$content .= "<div id=\"formstatus\">\n";
	$content .= "<p>$pesan</p>";
	$content .= "<p><a href=\"javascript:history.go(-1)\">$msgback</a></p>\n";
	$content .= "</div>\n";	
	
}
$content .= "</div>\r\n";	//tutup contactall
?>

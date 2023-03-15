<?php
$userid = $_SESSION['member_uid'];
$action = fiestolaundry($_GET['action'],20);
$pid = fiestolaundry($_GET['pid'],20);

$postuser= fiestolaundry($_POST['txtUser'],50);
$postpass1= trim($_POST['txtPass1']);
$postpass2= trim($_POST['txtPass2']);
$postnama= fiestolaundry($_POST['txtNama'],50);
$postperusahaan= fiestolaundry($_POST['txtPerusahaan'],50);
$postalamat1= fiestolaundry($_POST['txtAlamat1'],50);
$postalamat2= fiestolaundry($_POST['txtAlamat2'],50);
$postkota= fiestolaundry($_POST['txtKota'],30);
$postkodepos= fiestolaundry($_POST['txtKodepos'],10);
$posttelepon= fiestolaundry($_POST['txtTelepon'],20);
$postponsel1= fiestolaundry($_POST['txtPonsel1'],20);
$postponsel2= fiestolaundry($_POST['txtPonsel2'],20);
$postfax= fiestolaundry($_POST['txtFax'],20);
$postemail= fiestolaundry($_POST['txtEmail'],50);

$is_forced_login = false;

$postuserlogin = fiestolaundry($_POST['username'],50);

$sql = "SELECT judulfrontend FROM module WHERE nama='webmember'";
$result = $mysql->query($sql);
list($title) = $mysql->fetch_row($result);

if ($action=="") 
{
	if($is_public_register)
	{	
		if (!isset($_SESSION['member_uid'])) 
		{
			header("Location:".$urlfunc->makePretty("?p=webmember&action=login"));
		} 
		else 
		{
			// header("Location:".$cfg_app_url);
			// $content .= "<ul class=\"member_menu\">\r\n";
			// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=webmember")."\">"._MEMBERHOME."</a></li>\r\n";
			// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=webmember&action=editaccount")."\">"._EDITACCOUNT."</a></li>\r\n";
			// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=order&action=history")."\">"._CEKSTATUSORDER."</a></li>\r\n";
			// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=webmember&action=logout")."\">"._LOGOUT."</a></li>\r\n";
			// $content .= "</ul>\r\n";
			
			$content .= "<div class=\"panel panel-default\">";
			// $content .= "<div class=\"panel-heading\">Panel heading without title</div>";
			$content .= "<div class=\"panel-body\"><a href=\"".$urlfunc->makePretty("?p=webmember")."\">"._MEMBERHOME."</a></div>";
			$content .= "<div class=\"panel-body\"><a href=\"".$urlfunc->makePretty("?p=webmember&action=editaccount")."\">"._EDITACCOUNT."</a></div>";
			$content .= "<div class=\"panel-body\"><a href=\"".$urlfunc->makePretty("?p=order&action=history")."\">"._CEKSTATUSORDER."</a></div>";
			$content .= "<div class=\"panel-body\"><a href=\"".$urlfunc->makePretty("?p=webmember&action=logout")."\">"._LOGOUT."</a></div>";
			$content .= "</div>";
			
			$content .= "<div class=\"panel panel-default\">";
			$content .= "<div class=\"panel-heading\">"._POTITLE."</div>";
			$content .= "<div class=\"panel-body\"><a href=\"".$urlfunc->makePretty("?p=po&action=list")."\">"._POLIST."</a></div>";
			// $content .= "<div class=\"panel-body\"><a href=\"".$urlfunc->makePretty("?p=po&action=confirm")."\">"._POCONFIRMATION."</a></div>";
			$content .= "<div class=\"panel-body\"><a href=\"".$urlfunc->makePretty("?p=po&action=history")."\">"._POHISTORY."</a></div>";
			$content .= "<div class=\"panel-body\"><a href=\"".$urlfunc->makePretty("?p=wishlist")."\">"._POWISHLIST."</a></div>";
			$content .= "</div>";			
			
			// $content .= "<h3>"._POTITLE."</h3>";
			// $content .= "<ul class=\"member_po\">";
			
			// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=po&action=list")."\">"._POLIST."</a></li>\r\n";
			// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=po&action=confirm")."\">"._POCONFIRMATION."</a></li>\r\n";
			// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=po&action=history")."\">"._POHISTORY."</a></li>\r\n";
			// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=po&action=whitelist")."\">"._POWHITELIST."</a></li>\r\n";
			// $content .= "</ul>";
		}
	}
	else
	{
		$content.=_CONTACTADMINTOREGISTER;
	}
}

if ($action=="checkout") 
{
	
	if($is_public_register)
	{	
		if (!isset($_SESSION['member_uid'])) 
		{
			$tambahan = ($pid != '') ? "&pid=".$pid : "";
			$content .= '
			<div id="formlogin" class="col-xs-12 col-sm-6 col-lg-6">
				<form class="box" action="'.$urlfunc->makePretty("?p=webmember&action=login".$tambahan).'" id="loginform" name="loginform" method="POST">
					<h3 class="page-subheading">'. _CHOSENLOGIN .'</h3>
					<div class="form_content clearfix">
						<div class="form-group">
							<label for="username">'._EMAIL.'</label>
							<input type="text" value="'.$postuserlogin.'" name="username" class="is_required validate account_input form-control"/>
						</div>
						<div class="form-group">
							<label for="password">'. _PASSWORD .'</label>
							<input type="password" value="" name="password" class="is_required validate account_input form-control"/>
						</div>
						<p class="lost_password form-group">
							<a id="webmember_forgot" href="'.$urlfunc->makePretty("?p=webmember&action=forgotpassword").'">'._DOYOUFORGET.'</a>
						</p>
						<table border="0">
							<tr>
								<td>
									<p class="submit">		
										<input type="hidden" name="check_login" value="1"/>
										<button class="more button btn btn-default button-medium" name="submit_login" type="submit">
											<span>
												<i class="fa fa-lock left"></i>
												'._LOGIN.'
											</span>
										</button>
									</p>
								</td>
							</tr>
						</table>
					</div>
				</form>
			</div>';
		
			$content .= '
			<div class="col-xs-12 col-sm-6 col-lg-6">
				<form class="box">
					<h3 class="page-subheading">'._SHOPPINGASGUESTORMEMBER.'</h3>
					<div class="form_content clearfix">
						<div class="form-group">';
					if($harusmember==2)
					{
					$label=ucfirst(strtolower(_SHOPPINGWITHOUTREGISTER));	
					$content .= "<p>".$label."</p>";
					$content .= '
								<a title="'._CONTINUECHECKOUT.'" class="more button-exclusive btn btn-default" href="'.$urlfunc->makePretty("?p=cart&action=checkout&do=checkout").'">
									'._CONTINUECHECKOUT.'
								</a>';
					}
					if($is_public_register)
					{
						$tambahan = ($pid != '') ? "&pid=".$pid : "";
						$content .= "<p>"._BECOMEAMEMBER."</p>";
						$content .= '
						<a title="'._REGISTER.'" class="more button-exclusive btn btn-default" href="'.$urlfunc->makePretty("?p=webmember&action=register".$tambahan).'">	
							'._REGISTER.'
						</a>';
					}
			$content .= '</div>
					</div>
				</form>
			</div>';
		} 
		else 
		{
			$content .= "<ul class=\"member_menu\">\r\n";
			$content .= "<li><a href=\"".$urlfunc->makePretty("?p=webmember")."\">"._MEMBERHOME."</a></li>\r\n";
			$content .= "<li><a href=\"".$urlfunc->makePretty("?p=webmember&action=editaccount")."\">"._EDITACCOUNT."</a></li>\r\n";
			$content .= "<li><a href=\"".$urlfunc->makePretty("?p=order&action=history")."\">"._CEKSTATUSORDER."</a></li>\r\n";
			$content .= "<li><a href=\"".$urlfunc->makePretty("?p=topup_pulsa&action=history")."\">"._CEKSTATUSORDERPULSA."</a></li>\r\n";
			$content .= "<li><a href=\"".$urlfunc->makePretty("?p=webmember&action=logout")."\">"._LOGOUT."</a></li>\r\n";
			$content .= "</ul>\r\n";
		}
	}
	else
	{
		$content.=_CONTACTADMINTOREGISTER;
	}
}

if($action=="doregister" and $is_public_register)
{	$kondisi = true;
	// Start Cek Required
	$postuser = $postemail;
	if($postuser=='')
	{	$errormessages .= "<li>"._USERNAME." "._ISREQUIRED."</li>\r\n";
		$kondisi = false;
	}
	
	if($postpass1=='')
	{	$errormessages .= "<li>"._PASSWORD." "._ISREQUIRED."</li>\r\n";
		$kondisi = false;
	}
	
	if($postpass2=='')
	{	$errormessages .= "<li>"._RETYPEPASS." "._ISREQUIRED."</li>\r\n";
		$kondisi = false;
	}
	
	if($postnama=='')
	{	$errormessages .= "<li>"._NAME." "._ISREQUIRED."</li>\r\n";
		$kondisi = false;
	}
	
	if($postalamat1=='')
	{	$errormessages .= "<li>"._ADDRESS." "._ISREQUIRED."</li>\r\n";
		$kondisi = false;
	}
	
	if($postkota=='')
	{	$errormessages .= "<li>"._CITY." "._ISREQUIRED."</li>\r\n";
		$kondisi = false;
	}
	
	if($posttelepon=='')
	{	$errormessages .= "<li>"._TELP1." "._ISREQUIRED."</li>\r\n";
		$kondisi = false;
	}
	
	if($postemail=='')
	{	$errormessages .= "<li>"._EMAIL." "._ISREQUIRED."</li>\r\n";
		$kondisi = false;
	}	
	
	if(empty($_SESSION['6_letters_code'] ) || empty($_POST['6_letters_code']) || strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0) {
		if (empty($_POST['6_letters_code'])) {
			$errormessages .= "<li>"._CAPTCHAREQ."</li>\r\n";
			$kondisi = false;
		} else {
			$errormessages .= "<li>"._CAPTCHANOTMATCH."</li>\r\n";
			$kondisi = false;
		}
	}
	// End Cek Required
	
	if($kondisi==true)
	{	if(!validatemail($postemail))
		{	$errormessages .= "<li>"._INVALIDEMAIL."</li>\r\n";
			$kondisi = false;
		}
		
		//cek apakah username 3-12 karakter
		/*if (strlen($postuser)<3 || strlen($postuser)>12) 
		{	$errormessages .= "<li>"._INVALIDUSERNAME."</li>\r\n";
			$kondisi = false;
		}

		if (!preg_match("/^[A-Za-z0-9][A-Za-z0-9]*(_[A-Za-z0-9]+)*$/",$postuser))
		{	$errormessages .= "<li>"._INVALIDUSERNAME."</li>\r\n";
			$kondisi = false;
		}*/
		
		if ($postpass1!=$postpass2)
		{	$errormessages .= "<li>"._PASSWORDANDRETYPENOTSAME."</li>\r\n";
			$kondisi = false;
		} 
		
		if (strlen($postpass1)<6)
		{	$errormessages .= "<li>"._INVALIDPASSWORD."</li>\r\n";
			$kondisi = false;
		}

		$pos = strpos($postpass1, $postuser);
		if ($pos !== false) 
		{	$errormessages .= "<li>"._USERINPASSWORD."</li>\r\n";
			$kondisi = false;
		}
				
		//cek apakah password termasuk kata-kata terlarang
		foreach ($prohibitedpasses as $prohibitedpass) 
		{	if (strtolower($postpass1)==$prohibitedpass)
			{	$errormessages .= "<li>"._PROHIBITEDPASS."</li>\r\n";
				$kondisi = false;
			}
		}
		//cek apakah username sudah terpakai
		$sql = "SELECT user_id FROM $tabelwebmember WHERE username='$postuser'"; 
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result)>0) 
		{	$errormessages .= "<li>"._USERNAME." "._ALREADYEXIST."</li>\r\n";
			$kondisi = false;
		}
		
		//cek apakah email sudah terpakai
		$sql = "SELECT user_id FROM $tabelwebmember WHERE user_email='$postemail'"; 
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result)>0) 
		{	$errormessages .= "<li>"._EMAIL." "._ALREADYEXIST."</li>\r\n";
			$kondisi = false;
		}
	}	
	if($kondisi)
	{	

		if ($pakaifbpixel) {
			$content .= <<<HTML
			<script>
			  fbq('track', 'CompleteRegistration');
			</script>
HTML;
		}
		$cookedpass=fiestopass($postpass1);
		$sql="INSERT INTO $tabelwebmember SET username='$postuser', 
			user_password='$cookedpass', fullname='$postnama', level='1',
			company='$postperusahaan', address1='$postalamat1', activity='0', 
			address2='$postalamat2', city='$postkota', zip='$postkodepos',
			telephone='$posttelepon', cellphone1='$postponsel1', 
			cellphone2='$postponsel2', fax='$postfax', user_email='$postemail',
			user_regdate=NOW()";
		$result = $mysql->query($sql);
		if($result)
		{	$uid = $mysql->insert_id();
			//mail kode aktivasi
			$activationcode  = substr($cookedpass,16,16).$uid.substr($cookedpass,0,16);
			$activationcode  = md5(sha1($activationcode));
			$url_activate_user=$urlfunc->makePretty("?p=webmember&action=activate&code=$activationcode");
			list($admin_email)=$mysql->fetch_row($mysql->query("SELECT value FROM contact WHERE name='umbalemail'"));

			include("$cfg_app_path/modul/$namamodul/lang/$lang/mailactvcode.php");
			$option = "From: $admin_email";
			
			$sql="UPDATE $tabelwebmember SET activationcode='".$activationcode."' 
				WHERE user_id='$uid' ";
			
			$result = $mysql->query($sql);
			if ($is_public_activation==2 or $is_public_activation==3)
			{
				$sql = "UPDATE $tabelwebmember SET activity='1',activedate=NOW() WHERE user_id='$uid' AND activationcode='$activationcode'"; 
				$result = $mysql->query($sql);
													
				if($result and $is_public_activation==2)
				{
					$content .= "<h1>"._SUCCESS."</h1>\r\n";
					$content  = "<p>"._ACTIVATED."</p>\r\n";
				}
				if($result and $is_public_activation==3)
				{
					//force login
					$action="login";
					$postusername = $postuser;
					$postpassword = $postpass1;
					$is_forced_login = true;
					// if($pid == 'pulsa')
						// $_SESSION['URL_BEFORE_LOGIN'] = "?p=topup_pulsa&action=topup&pid=new";
					// else if($pid == 'gadget')
						// $_SESSION['URL_BEFORE_LOGIN'] = "?p=cart&action=checkout";
				}													
			}
			else
			{
				if ($is_public_register and $is_public_activation==1)
				{	
					// echo "$postemail,$subject,$message,$admin_email,$smtpuser,$smtpuser";
					fiestophpmailer($postemail,$subject,$message,$smtpuser,$postnama,$postemail);
					$content .= "<h1>"._SUCCESS."</h1>\r\n";
					$content  = "<p>"._CEKEMAILFORACTIVATION."</p>\r\n";
					$content .= "<p><a href=\"".$url_activate_user."\">"._INSERTACTIVATIONCODE."</a></p>\r\n";
				}
				else if($is_public_register and !$is_public_activation)
				{	
					// fiestophpmailer($admin_email, $subject_admin, $message_admin,$postemail);
					fiestophpmailer($admin_email, $subject_admin, $message_admin,$smtpuser,$postemail);
					$content .= "<h1>"._SUCCESS."</h1>\r\n";
					$content  = "<p>"._WAITACTIVATION."</p>\r\n";
					
				}
			}			
		}
		else
		{	$content .= "<h1>"._ERROR."</h1>\r\n";
			$content  = "<p>result=".$result."</p>\r\n";
			$content  = "<p>"._DBERROR."</p>\r\n";
			$content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\r\n";
		}
	}
	else
	{
        $title = "Error";
        $content .= "<ul class=\"error\">\r\n";
        $content .= $errormessages;
        $content .= "</ul>\r\n";
		$content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\n";
	}
}
elseif($action=="doregister" and !$is_public_register)
{	$content .= "<h1>"._ERROR."</h1>\r\n";
	$content  = "<p>"._NORIGHT."</p>\r\n";
	$content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\r\n";
}

if($action=="register" and $is_public_register)
{	if (isset($_SESSION['member_uid'])) 
	{	$content .= "<h1>"._ERROR."</h1>\r\n";
		$content  = "<p>"._ALREADYLOGIN."</p>\r\n";
		$content .= "<p><a class=\"link_kembali\" href=\"".$urlfunc->makePretty("?p=webmember")."\"><i class=\"fa fa-angle-left fa-3\"></i> "._GOTOMAIN."</a></p>\r\r\n";
	}
	else
	{	
		$content .= "<div class=\"form-checkout\">";
		if($pid == "pulsa")
		{
			$content .= "
			<div class=\"form-data-topup\">
				<h3>"._DATATRANSAKSITOPUP."</h3>
				<table class=\"table-striped\">
					<tr>
						<td>"._PROVIDER." </td>
						<td> : ".get_nama_provider($_SESSION['topup']['provider'])."</td>
					</tr>
					<tr>
						<td>"._NOMINAL." </td>
						<td> : ".fuang(get_nominal_produk($_SESSION['topup']['produk']))."</td>
					</tr>
					<tr>
						<td>"._NOMORTUJUAN." </td>
						<td> : ".$_SESSION['topup']['nomor_tujuan']."</td>
					</tr>
					<tr>
						<td>"._TOTAL." </td>
						<td> : ".fuang($_SESSION['topup']['total'])."</td>
					</tr>
				</table>
			</div>";
		}
		$content .= "
		<div class=\"form-register\">
			<form id=\"signupform\" class=\"form-group row\" method=\"POST\" action=\"".$urlfunc->makePretty("?p=webmember&action=doregister&pid=$pid")."\">
				<div class=\"col-sm-6\">
					<div class=\"form-group\">
						<span class=\"text-right req\">"._EMAIL." <span class=\"reqsign\">*</span></span>
						<input class=\"form-control\" name=\"txtEmail\" type=\"text\" size=\"40\" maxlength=\"50\" required=\"required\">
						<div class=\"notice\">"._CORRECTEMAIL."</div>
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._NAME." <span class=\"reqsign\">*</span></span>
						<input class=\"form-control\" name=\"txtNama\" type=\"text\" size=\"40\" maxlength=\"40\" required=\"required\">
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._PASSWORD." <span class=\"reqsign\">*</span></span>
						<input class=\"form-control\" name=\"txtPass1\" id=\"txtPass1\" type=\"password\" size=\"20\" maxlength=\"40\" required=\"required\">
						<div class=\"notice\">"._CORRECTPASS."</div>
						<span class=\"reqsign\"></span>
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._RETYPEPASS." <span class=\"reqsign\">*</span></span>
						<input class=\"form-control\" name=\"txtPass2\" id=\"txtPass2\" type=\"password\" size=\"20\" maxlength=\"40\" required=\"required\">
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._ADDRESS." <span class=\"reqsign\">*</span></span>
						<input class=\"form-control\" name=\"txtAlamat1\" type=\"text\" size=\"40\" maxlength=\"50\" required=\"required\">
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._CITY." <span class=\"reqsign\">*</span></span>
						<input class=\"form-control\" name=\"txtKota\" type=\"text\" size=\"30\" maxlength=\"30\" required=\"required\">
					</div>
					<div class=\"form-group\" style=\"display:none\">
						<input class=\"form-control\" name=\"txtAlamat2\" type=\"text\" size=\"40\" maxlength=\"50\">
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._ZIP."<span class=\"reqsign\"></span></span>
						<input class=\"form-control\" name=\"txtKodepos\" type=\"text\" size=\"10\" maxlength=\"10\">
					</div>
				</div>
				<div class=\"col-sm-6\">
					
					<div class=\"form-group\">
						<span class=\"text-right req\">"._COMPANY." <span class=\"reqsign\"></span></span>
						<input class=\"form-control\" name=\"txtPerusahaan\" type=\"text\" size=\"40\" maxlength=\"50\">
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._TELP1." <span class=\"reqsign\">*</span></span>
						<input class=\"form-control\" name=\"txtTelepon\" type=\"text\" size=\"20\" maxlength=\"20\" required=\"required\">
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._MOBILE1."<span class=\"reqsign\"></span></span>
						<input class=\"form-control\" name=\"txtPonsel1\" type=\"text\" size=\"20\" maxlength=\"20\">
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._MOBILE2."<span class=\"reqsign\">:</span></span>
						<input class=\"form-control\" name=\"txtPonsel2\" type=\"text\" size=\"20\" maxlength=\"20\">
					</div>
					<div class=\"form-group\">
						<span class=\"text-right req\">"._FAX."<span class=\"reqsign\"></span></span>
						<input class=\"form-control\" name=\"txtFax\" type=\"text\" size=\"20\" maxlength=\"20\">
					</div>
					<div class=\"form-group\">
						<p>
							<img src=\"$cfg_app_url/captcha.php?rand=".rand()."\" id=\"captchaimg\" ><br>
							<label for=\"message\">"._ENTERCODEABOVE."</label><br>
							<input id=\"6_letters_code\" name=\"6_letters_code\" type=\"text\" required><br>
							<small>"._CAPTCHACANTREADTHEIMAGE." <a href=\"javascript: refreshCaptcha();\">"._CAPTCHAHERE."</a> "._CAPTCHAREFRESH."</small>
						</p>
					</div>	
				</div>
				<div class=\"col-sm-6\">
					<div class=\"form-group\">
						<input class=\"btn btn-default more\" type=\"submit\" name=\"submit\" value=\""._SUBMIT."\">
						<p>"._REQNOTE."</p>
					</div>
				</div>
			</form>
		</div>";
		$content .= "
		</div>";
	}
}
elseif($action=="register" and !$is_public_register)
{	$content .= "<h1>"._ERROR."</h1>\r\n";
	$content  = "<p>"._NORIGHT."</p>\r\n";
	$content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\r\n";
}

if($action=="activate" and $is_public_activation==1)
{	$activationcode = fiestolaundry($_REQUEST['code'],40);
	if($activationcode=="")
	{	$content  .= "<h2>"._ACTIVATIONTITLE."</h2>\n";
		$content .= "<p>"._ACTIVATIONCONTENT."</p>\n";
		$content .= "<form action=\"".$urlfunc->makePretty("?p=webmember&action=activate")."\" method=\"POST\">\n";
		$content .= "<input type=\"text\" name=\"code\" size=\"40\" maxlength=\"40\" />\n";
		$content .= "<input type=\"submit\" name=\"submit\" value=\"Submit\" />\n";
		$content .= "</form>";
	}
	else
	{	$sql = "SELECT user_id FROM $tabelwebmember 
			WHERE activationcode='$activationcode' AND activity='0'
			AND bandate='0000-00-00 00:00:00' "; 
		
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result)>0) 
		{	list($uid) = $mysql->fetch_row($result);
			$sql = "UPDATE $tabelwebmember SET activity='1',activedate=NOW() 
				WHERE user_id='$uid' AND activationcode='$activationcode'"; 
			$result = $mysql->query($sql);
			if($result)
			{	$content .= "<h1>"._SUCCESS."</h1>\r\n";
				$content  = "<p>"._ACTIVATED."</p>\r\n";
				$content .= "<p><a class=\"link_kembali\" href=\"$cfg_app_url\"><i class=\"fa fa-angle-left fa-3\"></i> "._GOTOMAIN."</a></p>\r\n";
			}
			else
			{	$content .= "<h1>"._ERROR."</h1>\r\n";
				$content  = "<p>"._DBERROR."</p>\r\n";
				$content .= "<p><a class=\"link_kembali\" href=\"".$urlfunc->makePretty("?p=webmember&action=activate")."\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\r\n";
			}
		} 
		else 
		{	$content .= "<h1>"._ERROR."</h1>\r\n";
			$content  = "<p>"._NOCODE."</p>\r\n";
			$content .= "<p><a class=\"link_kembali\" href=\"".$urlfunc->makePretty("?p=webmember&action=activate")."\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\r\n";
		}
	}
}
elseif($action=="activate" and !$is_public_activation)
{	$content .= "<h1>"._ERROR."</h1>\r\n";
	$content  = "<p>"._NORIGHT."</p>\r\n";
	$content .= "<p><a class=\"link_kembali\" href=\"$cfg_app_url\"><i class=\"fa fa-angle-left fa-3\"></i> "._GOTOMAIN."</a></p>\r\n";
}
	
if($action=="login")
{	
	//apabila belum login
	if (!isset($_SESSION['member_uid'])) 
	{

		// if($pid == 'pulsa')
			// $_SESSION['URL_BEFORE_LOGIN'] = "?p=topup_pulsa&action=topup&pid=new";
		// else if($pid == 'gadget')
			// $_SESSION['URL_BEFORE_LOGIN'] = "?p=cart&action=checkout";
		$error_notif = "";
		$is_valid = false;
		if($_POST['check_login'] || $is_forced_login)
		{
			if(!$is_forced_login)
			{
				$postusername= fiestolaundry($_POST['username'],50);
				$postpassword= fiestolaundry($_POST['password'],100);
			}
			
			if (!$postusername || !$postpassword) 
			{	
				$error_notif = _USERPASSEMPTY;
			}
			else
			{	$postpassword = fiestopass($postpassword);
				$sql = "SELECT * FROM $tabelwebmember 
					WHERE username='$postusername' AND user_password='$postpassword' 
					AND activity='1'"; 
				$result = $mysql->query($sql);
				if ($mysql->num_rows($result)>0) {	
					$dataUser = $mysql->fetch_array($result);
					$_SESSION['member_uname'] = $dataUser["username"];
					$_SESSION['member_uid'] = $dataUser["user_id"];
					
					$sql = "UPDATE $tabelwebmember SET lastlogin=NOW() 
						WHERE user_id='".$dataUser["user_id"]."'";
					$result = $mysql->query($sql);
			
					// if (isset($_SESSION['cart']) && count($_SESSION['cart'] > 0)) {
						// header("Location: " . $urlfunc->makePretty("?p=cart&action=checkout"));
					// } else {
						// header("Location:".$cfg_app_url);
					// }
					
					if(isset($_SESSION['URL_BEFORE_LOGIN'])) {
						$temp_url_before_login = $_SESSION['URL_BEFORE_LOGIN'];
						header("Location: ".$temp_url_before_login);
					} else {
						header("Location:".$cfg_app_url);
					}
					
				} else {	
					$error_notif = _WRONGUSERPASSORNOTACTIVE;
				}
			} 	
		}

		//login form fayette
		$content .= '<div class="row"><div id="formlogin" class="col-xs-12 col-sm-6 col-lg-6">
						<form class="box" action="'.$urlfunc->makePretty("?p=webmember&action=login").'" 
						id="loginform" name="loginform" method="POST">
							<h3 class="page-subheading">'. _CHOSENLOGIN .'</h3>
							<div class="form_content clearfix">
								<div class="form-group">
									<label for="username">'._USERNAME.'</label>
									<input type="text" value="'.$postuserlogin.'" name="username" 
									class="is_required validate account_input form-control"/>
								</div>
								<div class="form-group">
									<label for="password">'. _PASSWORD .'</label>
									<input type="password" value="" name="password"
									class="is_required validate account_input form-control"/>
								</div>
								<p class="lost_password form-group">
									<a id="webmember_forgot" href="'.$urlfunc->makePretty("?p=webmember&action=forgotpassword").'">'
									._DOYOUFORGET.
									'</a>';
					/*if($is_public_register)
					{
						$content .= "&nbsp;&nbsp;|&nbsp;&nbsp;<a id='webmember_register' class='button' href=\"".$urlfunc->makePretty("?p=webmember&action=register")."\">"
									._REGISTER.
									"</a>";
					}*/
						$content .= '</p>
								<table border="0">
								<tr>
								<td>
								<p class="submit">		
									<input type="hidden" name="check_login" value="1"/>
									<button class="more button btn btn-default button-medium" name="submit_login" type="submit">
										<span>
											<i class="fa fa-lock left"></i>
											'._LOGIN.'
										</span>
									</button>
								</td>';
					//apabila proses login gagal maka munculkan notifikasi
					if(!$is_valid)
					{
						$content .=
								'<td>&nbsp;&nbsp;</td>
								<td valign="top"><font color="red">'.$error_notif.'</font></td>';
					}
						$content .=	
								'</tr>
								</table>
								</p>
							</div>
						</form>
					</div>';
		$content .= '
			<div class="col-xs-12 col-sm-6 col-lg-6">
				<form class="box">
					<h3 class="page-subheading">'._BEAMEMBERTOCONTINUE.'</h3>
					<div class="form_content clearfix">
						<div class="form-group">';
					if($harusmember==2) {
						$label=ucfirst(strtolower(_SHOPPINGWITHOUTREGISTER));	
						$content .= "<p>".$label."</p>";
						$content .= '
									<a title="'._CONTINUECHECKOUT.'" class="more button-exclusive btn btn-default" href="'.$urlfunc->makePretty("?p=cart&action=checkout&do=checkout").'">
										'._CONTINUECHECKOUT.'
									</a>';
					}
					if($is_public_register)
					{
						$tambahan = ($pid != '') ? "&pid=".$pid : "";
						$content .= "<p>"._BECOMEAMEMBER."</p>";
						$content .= '
						<a title="'._REGISTER.'" class="more button-exclusive btn btn-default" href="'.$urlfunc->makePretty("?p=webmember&action=register".$tambahan).'">	
							'._REGISTER.'
						</a>';
					}
			$content .= '</div>
					</div>
				</form>
			</div></div>';
	}
	//apabila sudah login, redirect ke halaman webmember
	else
	{
		header("Location:".$cfg_app_url);
	}
}	
		
if($action=="editaccount")
{	if (!isset($_SESSION['member_uid'])) 
	{	$content .= "<span class=\"error\">";
		$content .= _NORIGHT."<br/>";
		$content .= "</span>";
	}
	else
	{	$sql = "
			SELECT fullname, company, address1, address2, city, zip, telephone, 
				cellphone1, cellphone2, fax, user_email 
			FROM $tabelwebmember WHERE user_id='$userid'";
		$result = $mysql->query($sql);

		list($fullname,$company,$address1,$address2,$city,$zip,$telephone,$cellphone1,
			$cellphone2,$fax,$email) = $mysql->fetch_row($result);
			$content .= "<h2>"._EDITACCOUNT."</h2>";
			$content .= "<script type=\"text/javascript\" src=\"$cfg_app_url/js/global.js\"></script>";
			$content .= "<div class=\"menu-edit-password\"><a class=\"btn btn-default more active\" href=\"javascript:showtab(0)\">"._EDITNONPASSWORD."</a>&nbsp;&nbsp;<a class=\"btn btn-default more\" href=\"javascript:showtab(1)\">"._EDITPASSWORD."</a></div>\r\n";
			$content .= "<div id=\"tab0\" style=\"display:block\">\r\n";
			$content .= '
				<form class="form-horizontal" name="editprofile" id="editprofile" action="'.$urlfunc->makePretty("?p=webmember&action=editprofile").'" method="POST">
					<fieldset>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._NAME.' <span class="reqsign">*:</span></span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtNama" type="text" size="40" maxlength="50" value="'.$fullname.'" /></div>
						</div>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._COMPANY.':</span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtPerusahaan" type="text" size="40" maxlength="50" value="'.$company.'" /></div>
						</div>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._ADDRESS.' <span class="reqsign">*:</span></span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtAlamat1" type="text" size="40" maxlength="50" value="'.$address1.'" /></div>
						</div>
						<div class="form-group">
							<div class="col-sm-10 col-md-8 col-md-offset-3 col-sm-offset-2"><input class="form-control" name="txtAlamat2" type="text" size="40" maxlength="50" value="'.$address2.'" /></div>
						</div>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._CITY.' <span class="reqsign">*:</span></span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtKota" type="text" size="30" maxlength="30" value="'.$city.'" /></div>
						</div>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._ZIP.':</span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtKodepos" type="text" size="10" maxlength="10" value="'.$zip.'" /></div>
						</div>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._TELP1.' <span class="reqsign">*:</span></span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtTelepon" type="text" size="20" maxlength="20" value="'.$telephone.'" /></div>
						</div>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._MOBILE1.':</span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtPonsel1" type="text" size="20" maxlength="20" value="'.$cellphone1.'" /></div>
						</div>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._MOBILE2.':</span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtPonsel2" type="text" size="20" maxlength="20" value="'.$cellphone2.'" /></div>
						</div>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._FAX.':</span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtFax" type="text" size="20" maxlength="20" value="'.$fax.'" /></div>
						</div>
						<div class="form-group">
							<span class="col-sm-2 col-md-3 text-right req">'._EMAIL.' <span class="reqsign">*:</span></span>
							<div class="col-sm-10 col-md-8"><input class="form-control" name="txtEmail" type="text" size="40" maxlength="50" value="'.$email.'" /></div>
						</div>
						<div class="form-group">
							<div class="col-md-10 col-md-offset-3 col-sm-offset-2"><input class="btn btn-default more" type="submit" name="submit" value="'._SAVE.'"><p>'._REQNOTE.'</p></div>
						</div>
					</fieldset>
				</form>';
			$content .= "</div>\r\n";

			$content .= "<div id=\"tab1\" style=\"display:none\">\r\n";
			$content .= '
				<form method="POST" action="'.$urlfunc->makePretty("?p=webmember&action=editpassword").'" name="editpassword" id="editpassword">
				    <table border="0" cellspacing="1" cellpadding="3">
				      <tr>
				        <td>'._ENTEROLDPASSWORD.'</td>
					<td>:</td>
				        <td><input class="form-control" type="password" name="old_pass" size="30" value=""></td>
				      </tr>
				      <tr>
				        <td>'._ENTERNEWPASSWORD1.'</td>
					<td>:</td>
				        <td><input class="form-control" type="password" name="new_pass1" size="30" value=""></td>
				      </tr>
				      <tr>
				        <td>'._ENTERNEWPASSWORD2.'</td>
					<td>:</td>
				        <td><input class="form-control" type="password" name="new_pass2" size="30" value=""></td>
				      </tr>
				      <tr>
				        <td>&nbsp;</td>
				        <td>&nbsp;</td>
				        <td><input type="submit" value="'._SAVE.'" class="btn btn-default more"></td>
				      </tr>
				    </table>
				</form>
			    ';
			$content .= "</div>\r\n";
	}
}	

if($action=="editpassword")
{	$sql = "SELECT user_id, user_password, username 	
			FROM $tabelwebmember WHERE user_id='$userid'";
	$result = $mysql->query($sql);
	list($cid,$cpassword,$cusername) = $mysql->fetch_row($result);
	$kondisi = true;
	
	$old_pass = trim($_POST['old_pass']);
	$new_pass1 = trim($_POST['new_pass1']);
	$new_pass2 = trim($_POST['new_pass2']);

	if ($old_pass == "" || $new_pass1 == "" || $new_pass2 == "") 
	{	$content .= "<span class=\"error\">";
		$content .= _REQUIREDFIELDS."<br/>";
		$content .= "</span>";
		$kondisi = false;
	}
	
	if (fiestopass($old_pass) != $cpassword)
	{	$content .= "<span class=\"error\">";
		$content .= _WRONGOLDPASSWORD."<br/>";
		$content .= "</span>";
		$kondisi = false;
	} 
	
	if ($new_pass1 != $new_pass2)
	{	$content .= "<span class=\"error\">";
		$content .= _WRONGNEWPASSWORD."<br/>";
		$content .= "</span>";
		$kondisi = false;
	} 
	
	//cek apakah password >=6
	if (strlen($new_pass1)<6) 
	{	$content .= "<span class=\"error\">";
		$content .= _INVALIDPASSWORD."<br/>";
		$content .= "</span>";
		$kondisi = false;
	} 
	
	//cek apakah password mengandung username
	$pos = strpos($new_pass1, $cusername);
	if ($pos !== false)
	{	$content .= "<span class=\"error\">";
		$content .= _USERINPASSWORD."<br/>";
		$content .= "</span>";
		$kondisi = false;
	} 
	 
	//cek apakah password termasuk kata-kata terlarang
	foreach ($prohibitedpasses as $prohibitedpass) 
	{	if (strtolower($new_pass1)==$prohibitedpass) 
		{	$content .= "<span class=\"error\">";
			$content .= _PROHIBITEDPASS."<br/>";
			$content .= "</span>";
			$kondisi = false;
		} 
	}

	if($kondisi)
	{	$new_pass1 = fiestopass($new_pass1);
		$sql = "UPDATE $tabelwebmember SET user_password='$new_pass1' 
			WHERE user_id='$userid'"; 
		$result = $mysql->query($sql);
		if ($result)
		{	$content .= "<h1>"._TITLEPASSCHANGED."</h1>\r\n";
			$content  = "<p>"._PASSCHANGED."</p>\r\n";
			$content .= "<p><a class=\"link_kembali\" href=\"".$urlfunc->makePretty("?p=webmember")."\"><i class=\"fa fa-angle-left fa-3\"></i> "._GOTOMAIN."</a></p>\r\n";
		} 
		else
		{	$content .= "<h1>"._ERROR."</h1>\r\n";
			$content  = "<p>"._DBERROR."</p>\r\n";
			$content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\r\n";
		}
	}
	else
	{	$content .= "<p><a href=\"javascript:history.go(-1)\">"._BACK."</a></p>\r\r\n";
	}
}

if($action=="editprofile")
{	$kondisi = true;
	if($postnama=='')
	{	$content .= "<span class=\"error\">";
		$content .= "<span class=\"fieldname\">"._NAME."</span> "._ISREQUIRED."<br/>";
		$content .= "</span>";
		$kondisi = false;
	}
	
	if($postalamat1=='')
	{	$content .= "<span class=\"error\">";
		$content .= "<span class=\"fieldname\">"._ADDRESS."</span> "._ISREQUIRED."<br/>";
		$content .= "</span>";
		$kondisi = false;
	}
	
	if($postkota=='')
	{	$content .= "<span class=\"error\">";
		$content .= "<span class=\"fieldname\">"._CITY."</span> "._ISREQUIRED."<br/>";
		$content .= "</span>";
		$kondisi = false;
	}
	
	if($posttelepon=='')
	{	$content .= "<span class=\"error\">";
		$content .= "<span class=\"fieldname\">"._TELP1."</span> "._ISREQUIRED."<br/>";
		$content .= "</span>";
		$kondisi = false;
	}
	
	if($postemail=='')
	{	$content .= "<span class=\"error\">";
		$content .= "<span class=\"fieldname\">"._EMAIL."</span> "._ISREQUIRED."<br/>";
		$content .= "</span>";
		$kondisi = false;
	}	
	// End Cek Required
	
	if($kondisi==true)
	{	if(!validatemail($postemail))
		{	$content .= "<span class=\"error\">";
			$content .= _INVALIDEMAIL."<br/>";
			$content .= "</span>";
			$kondisi = false;
		}
		
		
		//cek apakah email sudah terpakai
		$sql = "SELECT user_id FROM $tabelwebmember 
			WHERE user_email='$postemail' AND user_id!='$userid' "; 
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result)>0) 
		{	$content .= "<span class=\"error\">";
			$content .= "<span class=\"fieldname\">"._EMAIL."</span> "._ALREADYEXIST."<br/>";
			$content .= "</span>";
			$kondisi = false;
		}
	}
	
	if($kondisi==true)
	{	$sql = "UPDATE $tabelwebmember SET fullname='$postnama',
			company='$postperusahaan', address1='$postalamat1', address2='$postalamat2',
			city='$postkota', zip='$postkodepos', telephone='$posttelepon', 
			cellphone1='$postponsel1', cellphone2='$postponsel2', fax='$postfax', 
			user_email='$postemail' WHERE user_id='$userid'";
		
		$result = $mysql->query($sql);
		if($result)
		{	//$content .= "<h1>"._TITLEACCTCHANGED."</h1>\r\n";
			$content .= "<p>"._ACCTCHANGED."</p>\r\n";
			$content .= "<p><a class=\"link_kembali\" href=\"".$urlfunc->makePretty("?p=webmember")."\"><i class=\"fa fa-angle-left fa-3\"></i> "._GOTOMAIN."</a></p>\r\n";
		}
		else
		{	//$content .= "<h1>"._ERROR."</h1>\r\n";
			$content  = "<p>"._DBERROR."</p>\r\n";
			$content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\r\n";
		}
	}
	else
	{	$content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\r\n";
	}
}

if($action=="logout")
{	unset($_SESSION['member_uname']);
	unset($_SESSION['member_uid']);
	unset($_SESSION['cart']);
	header("Location:$cfg_app_url");
}

if  ($action=='forgotpassword') {
	$content .= "<p>"._FORGOTPASSCONTENT."</p>";
	$content .= '
		<form action="'.$urlfunc->makePretty("?p=webmember&action=sendpassword").'" name="forgotpass" id="forgotpass" method="POST">
		    <table border="0" cellspacing="1" cellpadding="3">
		      <tr>
		        <td align="right">'._EMAIL.':</td>
		        <td><input class="form-control" type="text" name="txtEmail" size="50" value=""></td>
		      </tr>
		      <tr>
		        <td align="right">&nbsp;</td>
		        <td><input class="more" type="submit" value="'._SUBMIT.'"></td>
		      </tr>
		    </table>
		</form>';
}
if ($action=='sendpassword') {
	$mailpass=$_POST['txtEmail'];
	if (validatemail($mailpass)) {
		$sql="SELECT username, user_email FROM $tabelwebmember WHERE user_email='$mailpass'";
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result) > 0) {
			list($username, $user_email) = $mysql->fetch_row($result);
			$newpass = rand(0,9).time().rand(0,9);
			$message  = _WEHAVERESET."\r\n";
			$message .= _USERNAME.": $username\r\n";
			$message .= _PASSWORD.": $newpass\r\n\r\n";
			$message .= _YOUCANCHANGE;
			$newpass = fiestopass($newpass);
			$sql = "UPDATE $tabelwebmember SET user_password='$newpass' WHERE username='$username'"; 
			$mysql->query($sql);
			if (fiestophpmailer($user_email, _FORGOTPASSSUBJ, $message, $smtpuser, $smtpsendername)) {
				$content = "<p>"._MAILSENT."</p><p><a class=\"link_kembali\" href=\"$cfg_app_url\"><i class=\"fa fa-angle-left fa-3\"></i> "._GOTOMAIN."</a></p>\r\n";
			} else {
				$content = "<p>"._CANTSENDMAIL."</p><p><a class=\"link_kembali\" href=\"javascript:history.back()\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\n";
			}
		} else {
			$content = "<p>"._CANTFINDMAIL."</p><p><a class=\"link_kembali\" href=\"javascript:history.back()\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\n";
		}
	} else {
		$content = "<p>"._INVALIDEMAIL."</p><p><a class=\"link_kembali\" href=\"javascript:history.back()\"><i class=\"fa fa-angle-left fa-3\"></i> "._BACK."</a></p>\r\n";
	}
}

?>

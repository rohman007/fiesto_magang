<?php
// session_start();

// $action=fiestolaundry($_REQUEST['action'],20);
// $pid=fiestolaundry($_GET['pid'],11);
$screen = fiestolaundry($_GET['screen'], 11);

$sql = "SELECT judulfrontend FROM module WHERE nama='testimonial' ";
$result = $mysql->query($sql);
list($judulfrontend) = $mysql->fetch_row($result);
	
$title = $judulfrontend;

if ($action=='' || $action == 'view') {
	// hitung dulu untuk menentukan berapa halaman...
	$sql = "SELECT id FROM testimonial WHERE status='1'";	
	$result = $mysql->query($sql);
	$total_records = $mysql->num_rows($result);
	$pages = ceil($total_records/$max_page_list);

	if ($screen=='') $screen = 0;
	$start = $screen * $max_page_list;

	// ... baru kemudian diambil lagi, kali ini dengan limit perhalaman
	$sql = "SELECT id, nama, komentar FROM testimonial WHERE status='1' ORDER BY tanggal DESC LIMIT $start, $max_page_list";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) > 0) {
		$titleurl = array();
		$titleurl['cat_id']='testimonial';
		$photo = $mysql->query($sql);
		if ($pages > 1) $content .= aksipagination($namamodul, $screen, "action=view");
		$content .= "<ul class=\"testimonial\">\r\n";
		while (list($id, $nama, $comment) = $mysql->fetch_row($photo)) {
			$content .= "<li>";
			$content .= "<div class=\"testiname\">$nama</div>";
			$content .= "<div class=\"testicomment\">".nl2br($comment)."</div>";
			$content .= "</li>\r\n";
		}
		$content .= "</ul>\r\n";
	} else {
		$content .= '<p>'._NOTESTI.'</p>';
	}
	$content .= "<p><a href=\"".$urlfunc->makePretty("?p=testimonial&action=add")."\">"._ADDTESTI."</a></p>\r\n";
}
	
if ($action=='add') {
	$content .= "
	<script>
	function setCaretPosition(ctrl, pos)
	{	if(ctrl.setSelectionRange)
		{	ctrl.focus();
			ctrl.setSelectionRange(pos,pos);
		}
		else if (ctrl.createTextRange) 
		{	var range = ctrl.createTextRange();
			range.collapse(true);
			range.moveEnd('character', pos);
			range.moveStart('character', pos);
			range.select();
		}
	}
	
	function limitText(limitField, limitNum) 
	{	if (limitField.value.length > limitNum) 
		{	limitField.value = limitField.value.substring(0, limitNum);
			setCaretPosition(limitField, limitNum);
		} 
		var messageText = '"._CHARLEFT.": <sisa>';
		sisa = limitNum - limitField.value.length;
		messageText = messageText.replace('<sisa>',sisa); 
		document.getElementById('length_left').innerHTML = messageText;
		
	}
	function refreshCaptcha()
	{
		var img = document.images['captchaimg'];
		img.src = img.src.substring(0,img.src.lastIndexOf('?'))+'?rand='+Math.random()*1000;
	}	
	</script>";
	$content .= "<div id=\"formnamakomentar\">\r\n";
	$content .= "<form method=\"POST\" action=\"".$urlfunc->makePretty("?p=testimonial&action=tambahdata")."\" enctype=\"multipart/form-data\">\r\n";
	$content .= "<input type=\"hidden\" name=\"action\" value=\"tambahdata\" />\r\n";
	$content .= "<p>"._NAME."<br /><input type=\"text\" name=\"cname\" size=\"30\"></p>\r\n";
	$content .= "<p>"._YOURHISTORY."<br /><textarea name=\"comment\" rows=\"10\" cols=\"50\" onKeyUp=\"limitText(this,1000)\"></textarea><br /><span id=\"length_left\">"._CHARLEFT.": 1000</span></p>\r\n";
	$content .= "<p>
					<img src=\"$cfg_app_url/captcha.php?rand=".rand()."\" id=\"captchaimg\" ><br>
					<label for=\"message\">"._ENTERCODEABOVE."</label><br>
					<input id=\"6_letters_code\" name=\"6_letters_code\" type=\"text\"><br>
					<small>Can't read the image? click <a href=\"javascript: refreshCaptcha();\">here</a> to refresh</small>
					</p>";
	$content .= "<p><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></p>\r\n";
	$content .= "</form>\r\n";
	$content .= "</div>\r\n";
	$content .= "<p><a href=\"".$urlfunc->makePretty("?p=testimonial")."\">"._BACK."</a></p>\r\n";
}

if ($action=='tambahdata')
{	

	$cname=fiestolaundry($_POST['cname'],30);
	$komentar=fiestolaundry($_POST['comment'],500);
	
	$error = array();
	$kondisi = true;
	if($cname=='')
	{	
		$error[] = _NAMEREQ;
		$kondisi = false;
	}
	if($komentar=='')
	{	
		$error[] = _TESTIMONIALREQ;
		$kondisi = false;
	}
	/* if ($_POST['6_letters_code'] == '') {
		$error[] = _CAPTCHAREQ;
		$kondisi = false;
	}
	if(empty($_SESSION['6_letters_code'] ) || strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0) {
		$error[] = _CAPTCHANOTMATCH;
		$kondisi = false;
	} */
	
	if(empty($_SESSION['6_letters_code'] ) || empty($_POST['6_letters_code']) || strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0) {
		if (empty($_POST['6_letters_code'])) {
			$error[] = _CAPTCHAREQ;
			$kondisi = false;
		} else {
			$error[] = _CAPTCHANOTMATCH;
			$kondisi = false;
		}
	}
	
	if (!empty($error)) {
		$content .= '<ul class="error">';
		foreach($error as $msg) {
			$content .= '<li>'.$msg.'</li>';
		}
		$content .= '</ul>';
		$content .= "<p><a href=\"javascript:history.back()\">"._BACK."</a></p>\r\n";
	}
	
	$komentar = nl2br($komentar); 
	if($kondisi)
	{
		$sql =	"INSERT INTO testimonial (nama, komentar, status, tanggal) VALUES ('$cname', '$komentar', '0', NOW())";
		if ($mysql->query($sql)) {
			$content .= "<h3>"._DBSUCCESS."</h3><p>"._TESTIMONIALTHANKS."</p><p><a href=\"".$urlfunc->makePretty("?p=testimonial")."\">"._BACK."</a></p>";
		} else {
			$content .= "<h2>"._DBERROR."</h2><p><a href=\"".$urlfunc->makePretty("?p=testimonial")."\">"._BACK."</a></p>";
		}
	}
}


?>
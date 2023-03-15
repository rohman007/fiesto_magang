<?php
session_start();

$action=fiestolaundry($_REQUEST['action'],20);
$pid=fiestolaundry($_GET['pid'],11);

$sql = "SELECT judulfrontend FROM module WHERE nama='testimonial' ";
$result = mysql_query($sql);
list($judulfrontend) = mysql_fetch_row($result);
	
$title = $judulfrontend;


if ($action=='') {
	// hitung dulu untuk menentukan berapa halaman...
	$sql = "SELECT id FROM testimonial WHERE status='1'";	
	$result = mysql_query($sql);
	$total_records = mysql_num_rows($result);
	$pages = ceil($total_records/$max_page_list);

	if ($screen=='') $screen = 0;
	$start = $screen * $max_page_list;

	// ... baru kemudian diambil lagi, kali ini dengan limit perhalaman
	$sql = "SELECT id, nama, komentar FROM testimonial WHERE status='1' ORDER BY tanggal DESC LIMIT $start, $max_page_list";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) > 0) {
		$result = mysql_query($sql);
		$content .= "<ul class=\"testimonial\">\r\n";
		while (list($id, $nama, $comment) = mysql_fetch_row($result)) {
			$content .= "<li>";
			$content .= "<div class=\"testiname\">$nama</div>";
			$content .= "<div class=\"testicomment\">".nl2br($comment)."</div>";
			$content .= "</li>\r\n";
		}
		$content .= "</ul>\r\n";
	}
	$content .= "<p><a href=\"?p=testimonial&action=add\">"._ADDTESTI."</a></p>\r\n";
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
	</script>";
	$content .= "<div id=\"formnamakomentar\">\r\n";
	$content .= "<form method=\"POST\" action=\"$thisfile\" enctype=\"multipart/form-data\">\r\n";
	$content .= "<input type=\"hidden\" name=\"action\" value=\"tambahdata\" />\r\n";
	$content .= "<p>Nama<br /><input type=\"text\" name=\"cname\"></p>\r\n";
	$content .= "<p>Testimonial<br /><textarea name=\"comment\" rows=\"10\" onKeyUp=\"limitText(this,1000)\"></textarea><br /><span id=\"length_left\">"._CHARLEFT.": 1000</span></p>\r\n";
	$content .= "<p><input type=\"submit\" name=\"submit\" value=\""._ADD."\"></p>\r\n";
	$content .= "</form>\r\n";
	$content .= "</div>\r\n";
	$content .= "<p><a href=\"?p=testimonial\">"._BACK."</a></p>\r\n";
}

if ($action=='tambahdata')
{	
	$cname=fiestolaundry($_POST['cname'],30);
	$komentar=fiestolaundry($_POST['comment'],500);
	
	$kondisi = true;
	if($cname=='' || $komentar=='') $content .= "<ul>\r\n";
	if($cname=='')
	{	$content .= "<li>"._NAMEREQ."</li>\r\n";
		$kondisi = false;
	}
	if($komentar=='')
	{	$content .= "<li>"._TESTIMONIALREQ."</li>\r\n";
		$kondisi = false;
	}
	if($cname=='' || $komentar=='') $content .= "</ul><p><a href=\"javascript:history.back()\">"._BACK."</a></p>\r\n";
	
	if($kondisi)
	{
		$sql =	"INSERT INTO testimonial (nama, komentar, status, tanggal) VALUES ('$cname', '$komentar', '0', NOW())";
		if (mysql_query($sql)) {
			$content .= "<h2>"._DBSUCCESS."</h2><p>"._TESTIMONIALTHANKS."</p><p><a href=\"?p=testimonial\">"._BACK."</a></p>";
		} else {
			$content .= "<h2>"._DBERROR."</h2><p><a href=\"?p=testimonial\">"._BACK."</a></p>";
		}
	}
}


?>
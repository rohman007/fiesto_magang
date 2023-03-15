<?php
// $keyword = fiestolaundry($_GET['keyword'],500);
// $screen = fiestolaundry($_GET['screen'],11);
$screen = $_SEG[2];
// $pid = fiestolaundry($_GET['pid'],11);
// $action = fiestolaundry($_REQUEST['action'],20);

$sql = "SELECT judulfrontend FROM module WHERE nama='testi'";
$result = mysql_query($sql);
list($judulmodul) = mysql_fetch_row($result);

$title = "$judulmodul";

/*
cara aneh: yang hot muncul di hal 1 (w/o pagination), selanjutnya klik "Selengkapnya" untuk melihat semua testi


if ($action=='') {
	$sql = "SELECT id, filename, nama, judul, summary FROM testi WHERE publish='1' ORDER BY judul, nama";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0) {
		$content .= "<ul id=\"testilist\">\r\n";
		while (list($id, $filename, $nama, $judul, $summary) = mysql_fetch_row($result)) {
			$content .= "<li><div class=\"judultesti\"><a href=\"?p=testi&action=showtesti&pid=$id\">$judul</a></div>\r\n";
			$content .= "<div id=\"namatesti\">$nama</div>\r\n";
			$content .= "<div id=\"summarytesti\">$summary</div></li>\r\n";
		}
		$content .= "</ul>\r\n";
		//$content .= "<p><a href=\"?p=testi&action=list\">"._TESTILIST."</a></p>\r\n";
	} else {
		$content .= _NOTESTI;
	}
}

if ($action=='list') {
	// hitung dulu untuk menentukan berapa halaman...
	$sql = "SELECT id FROM testi WHERE publish='1' AND ishot='1'";
	$result = mysql_query($sql);
	$total_records = mysql_num_rows($result);
	$pages = ceil($total_records/$max_page_list);

	if ($screen=='') $screen = 0;
	$start = $screen * $max_page_list;

	// ... baru kemudian diambil lagi, kali ini dengan limit perhalaman
	$sql = "SELECT id, filename, nama, judul 
		FROM testi WHERE publish='1' 
		ORDER BY $sort LIMIT $start, $max_page_list";

	$photo = mysql_query($sql);
	$total_images=mysql_num_rows($photo);

	if ($total_images=="0") {
		$content .= _NOTESTI;
	} else {
		$titleurl = array();
		$titleurl["cat_id"] = $catname;
			
		if ($pages>1) $catpage = aksipagination($namamodul,$screen,"", $titleurl);
		$content .= ($catpage!='') ? "<div class=\"catpage\">$catpage</div>\r\n" : "" ;
		$content .= "<br />" ;
		$content .= show_me_the_list();
	}
}
*/

//jun 8:02 08/01/2012 cara normal, jeleknya ga bisa kontrol urutan, jadi diurut by hot DESC, judul, nama

if ($action=='' || $action == 'list') {
	// hitung dulu untuk menentukan berapa halaman...
	$sql = "SELECT id, filename, nama, judul, summary FROM testi WHERE publish='1' ORDER BY ishot DESC, judul, nama " . (($action == '') ? " LIMIT 3" : "");
	$result = mysql_query($sql);
	$total_records = mysql_num_rows($result);
	$pages = ceil($total_records/$max_page_list);
	if ($screen=='') $screen = 0;
	$start = $screen * $max_page_list;
	
	// ... baru kemudian diambil lagi, kali ini dengan limit perhalaman
	if ($action == '') $limit = " LIMIT 3"; else $limit = " LIMIT $start, $max_page_list";
	$sql = "SELECT id, filename, nama, judul, summary, filename FROM testi WHERE publish='1' ORDER BY ishot DESC, judul, nama $limit";
	$result = mysql_query($sql);
	$total_images=mysql_num_rows($result);

	if (mysql_num_rows($result)>0) {
		// if ($pages>1) $catpage = aksipagination($namamodul,$screen,"");
		if ($pages>1) $catpage = aksipagination($namamodul, $screen, "action=list");	
		// $content .= "<ul id=\"testilist\">\r\n";
		$content .= "<div class=\"row testimonials\">";
		$content .= "<div class=\"col-lg-12 hide\">";
		if ($action == '') {
			$content .= "
				<a href=\"".$urlfunc->makePretty("?p=testi&action=list")."\" class=\"btn button-default more p_right\" title=\"see all testimonials\">
					<span>"._SEEALLTESTI."<i class=\"fa fa-angle-right fa-3\"></i></span>
				</a>
			";
		}
		$content .= ($catpage!='') ? "<div class=\"catpage\">$catpage</div>\r\n" : "" ;
		$content .= "</div>	<!-- /.col-lg-12 hide -->\r\n";
		while (list($id, $filename, $nama, $judul, $summary, $filename) = mysql_fetch_row($result)) {
			// $content .= "<li><div class=\"judultesti\"><a href=\"".$urlfunc->makePretty("?p=testi&action=view&pid=$id")."\">$judul</a></div>\r\n";
			// $content .= "<div id=\"namatesti\">$nama</div>\r\n";
			// $content .= "<div id=\"summarytesti\">$summary</div></li>\r\n";
			$titleurl = array();
			$titleurl['pid'] = $judul;
			// ".(($judul != '') ? "<div class=\"titletesti\">$judul</div>" : "")."
			
			$thumbnail = (file_exists("$cfg_thumb_path/$filename") && $filename != '') ? "$cfg_thumb_url/$filename" : "./images/gravatar.jpg";
			$content .= "
				<div class=\"col-sm-4\">
					<blockquote>
						
						<p class=\"clients-words\">$summary</p>
						<a class=\"btn more\" href=\"".$urlfunc->makePretty("?p=testi&action=view&pid=$id", $titleurl)."\">"._READMORE."</a>
						<span class=\"clients-name\">&mdash; $nama</span>
						<img src=\"$thumbnail\" class=\"img-circle img-thumbnail\">
					</blockquote>
				</div>			
			";
		}
		// $content .= ($catpage!='') ? "<div class=\"catpage\">$catpage</div>\r\n" : "" ;
		$content .= "</div>	<!-- /.row -->\r\n";
		// $content .= "</ul>\r\n";
		//$content .= "<p><a href=\"?p=testi&action=list\">"._TESTILIST."</a></p>\r\n";
	} else {
		$content .= _NOTESTI;
	}
	
}

if ($action=='view') {
	// hitung dulu untuk menentukan berapa halaman...
	$sql = "SELECT filename, nama, judul, keterangan FROM testi WHERE id='$pid' AND publish='1'";	
	$result = mysql_query($sql);
	if (mysql_num_rows($result)>0) {
		list($filename, $nama, $judul, $keterangan) = mysql_fetch_row($result);
	}
	$thumbnail = (file_exists("$cfg_thumb_path/$filename") && $filename != '') ? "$cfg_thumb_url/$filename" : "/images/gravatar.jpg";
	$content .= "<div id=\"fototesti\"><img src=\"$thumbnail\" alt=\"$nama\" title=\"$nama\"></div>\r\n";
	$content .= "<div id=\"namatesti\">$nama</div>\r\n";
	// $content .= "<div id=\"judultesti\">$judul</div>\r\n";
	$content .= "<div id=\"isitesti\">$keterangan</div>\r\n";
	$content .= "<p><a class=\"link_kembali\" href=\"".$urlfunc->makePretty("?p=testi")."\"><i class=\"fa fa-angle-left fa-3\"></i>"._BACK."</a></p>\r\n";
}

?>
<?php
$tahun=$_GET['tahun'];
$bulan=$_GET['bulan'];
// $action=$_GET['action'];
// $pid=$_GET['pid'];
$tempid = explode('_',$_SEG[2]);
$pid = $tempid[0];

$sql = "SELECT judulfrontend FROM module WHERE nama='event'";
$result = $mysql->query($sql);
list($title) = $mysql->fetch_row($result);

require_once 'aplikasi/schema.php';

if ($action=='') {
	$content .= "<div class=\"row\" >";
	$sql = "SELECT id, judul, deskripsi, tglmulai, tglselesai, filename, kota, lokasi FROM event WHERE tglselesai>=CURDATE() ORDER BY tglmulai ASC";
	$result = $mysql->query($sql);
	$content .= "<div class=\"col-sm-12\"><h2>"._FUTUREEVENT."</h2></div>";
	$content .= "<div class=\"col-sm-12 block-list-agenda\">";
	$content .= "<div class=\"row\">";
	if ($mysql->num_rows($result) > 0) {
		$result = $mysql->query($sql);
		// $content .= "<ul class=\"eventlist\">";
		while (list($id, $judul, $deskripsi, $tglmulai, $tglselesai, $filename, $kota, $lokasi) = $mysql->fetch_row($result)) {
			$titleurl = array();
			$titleurl["pid"] = $judul;
			// $content .= "<li>";
			$content .= "<div class=\"col-sm-4 page-thumbnail-agenda\">";
			$content .= "<div class=\"thumbnail\">";
			if($filename!="" && file_exists("$cfg_thumb_path/$filename")) {	
				// $content .= "<div class=\"gambarevent\">";
				// $content .= "<a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=event&action=view&pid=$id",$titleurl)."\"><img  itemprop=\"image\" src=\"$cfg_thumb_url/$filename\" alt=\"$title\" border=\"0\" /></a>\r\n";
				// $content .= "</div>\r\n";
				$content .= "<img alt=\"$judul\" style=\"width: 100%; display: block;\" src=\"$cfg_thumb_url/$filename\" data-holder-rendered=\"true\">";
			}
			
			$content .= "<div class=\"caption\">";
			$content .= "<div class=\"title-agenda-thumbnail\"><h3>$judul</h3></div>\r\n";
			
			$content .= "<div itemprop=\"location\">
									 <div class=\"lokasievent\" >$lokasi</div>\r\n";
			$content .= "<div class=\"kotaevent\" >$kota</div>\r\n</div>";
			
			$content .= "<div class=\"tglevent\">";
			$ttglmulai=$tglmulai;
			$ttglselesai=$tglselesai;
			$tglmulai = tglformat($tglmulai);
			$tglselesai = tglformat($tglselesai);
			$mulai = explode(" ",$tglmulai);
			$selesai = explode(" ",$tglselesai);
			if ($mulai[2] == $selesai[2]) {
				if ($mulai[1] == $selesai[1]) {
					if ($mulai[0] == $selesai[0]) {
						$content .= "<meta content='$ttglmulai'>$mulai[0] $mulai[1] $mulai[2]";
					} else {
						$content .= "<meta content='$ttglmulai'>$mulai[0]-<meta content='$ttglselesai'>$selesai[0] $mulai[1] $mulai[2]";
					}
				} else {
					$content .= "<meta icontent='$ttglmulai'>$mulai[0] $mulai[1] - <meta content='$ttglselesai'>$selesai[0] $selesai[1] $mulai[2]";
				}
			} else {
				$content .= "<meta content='$ttglmulai'>$mulai[0] $mulai[1] $mulai[2] - <meta content='$ttglselesai'>$selesai[0] $selesai[1] $selesai[2]";
			}
			$content .= "</div>";			
			// $content .= "</li>\r\n";
			$content .= "<a class=\"btn more\" href=\"".$urlfunc->makePretty("?p=event&action=view&pid=$id",$titleurl)."\">"._READMORE."</a>";
			$content .= "</div>	<!-- /.caption -->";
			$content .= "</div>	<!-- /.thumbnail -->";
			$content .= "</div>	<!-- /.col-sm-6 col-md-4 -->";
		}
		// $content .= "</ul>";
	} else {
		$content .= _NOEVENT;
	}
	$content .= "</div></div>";
	$sql = "SELECT id, judul, deskripsi, tglmulai, tglselesai, filename, kota, lokasi FROM event WHERE tglselesai<CURDATE() ORDER BY tglmulai DESC";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) > 0) {
		$content .= "<div class=\"col-sm-12\"><h2>"._ARCHIVE."</h2></div>";
		$content .= "<div class=\"col-sm-12\"><ul class=\"archive\">\r\n";
		while(list($id, $judul, $deskripsi, $tglmulai, $tglselesai, $filename, $kota, $lokasi) = $mysql->fetch_row($result)) {
			if (($timestamp = strtotime($tglmulai)) !== -1) {
				$i=getdate($timestamp);
				$angkabulan=$i[mon]-1;
				if ($namabulan[$angkabulan] != $bulanini) {
					$tahun = $i[year];
					$content .= "<li><a href=\"".$urlfunc->makePretty("?p=event&action=archive&tahun=$tahun&bulan=$i[mon]")."\">$namabulan[$angkabulan] $tahun</a>\r\n";
					$bulanini = $namabulan[$angkabulan];
				}
			}
		}
		$content .= "</ul></div>\r\n";
	}
	$content.="</div>";
}

/*
if ($action=='archive') {
	if ($tahun!='' && $bulan!='') {
		$angkabulan=$bulan-1;
		$content .= "<h2>"._ARCHIVE.": $namabulan[$angkabulan] $tahun</h2>\r\n";
		$content .= archive($tahun,$bulan,$cat_id);
	}
}
*/

if ($action=='view') {
	$content .= "<div  class=\"fullevent\">";
	$sql = "SELECT id, judul, deskripsi, tglmulai, tglselesai, filename, kota, lokasi FROM event WHERE id='$pid'";
	$result = $mysql->query($sql);
	$schema = new Schema();
	$content .= $schema->SingleEvent($sql);
	if ($mysql->num_rows($result) > 0) {
		list($id, $judul, $deskripsi, $tglmulai, $tglselesai, $filename, $kota, $lokasi) = $mysql->fetch_row($result);
		$content .= "<div class=\"title_agenda_detail title_detail\"><h4 itemprop=\"name\">$judul</h4></div>\r\n";
		$content .= "<div class=\"date_news\">";
		$content .= "<span class=\"glyphicon glyphicon-time\"></span>";
		$content .= "<span class=\"tglevent\">";
		$ttglmulai=$tglmulai;
		$ttglselesai=$tglselesai;
		$tglmulai = tglformat($tglmulai);
		$tglselesai = tglformat($tglselesai);
		
		$mulai = explode(" ",$tglmulai);
		$selesai = explode(" ",$tglselesai);
		if ($mulai[2] == $selesai[2]) {
			if ($mulai[1] == $selesai[1]) {
				if ($mulai[0] == $selesai[0]) {
					$content .= "<meta content='$ttglmulai'>$mulai[0] $mulai[1] $mulai[2]";
				} else {
					$content .= "<meta content='$ttglmulai'>$mulai[0]-$selesai[0] $mulai[1] $mulai[2]";
				}
			} else {
				$content .= "<meta content='$ttglmulai'>$mulai[0] $mulai[1] - <meta content='$ttglselesai'>$selesai[0] $selesai[1] $mulai[2]";
			}
		} else {
			$content .= "<meta content='$ttglmulai'>$mulai[0] $mulai[1] $mulai[2] - <meta content='$ttglselesai'>$selesai[0] $selesai[1] $selesai[2]";
		}
		$content .= "</span>\r\n";
		$content .= "<span class=\"location\">
								 <span class=\"aksilokasievent\">$lokasi</span>\r\n";
		$content .= "<span class=\"aksikotaevent\">$kota</span>
								 </span>\r\n";
		$content .= "</div>	<!-- /.date_news -->\r\n\r\n";
		
		if ($filename!="" && file_exists("$cfg_fullsizepics_path/$filename")) $content .= "<div><img src=\"$cfg_fullsizepics_url/$filename\" alt=\"$judul\" class=\"img-responsive\"></div><hr>\r\n";
		$content .= "<div class=\"isiagenda\">$deskripsi</div>\r\n";
		
		// $content .= generate_schema_single_event($sql);
	}
	$content .= "</div>";
}
?>

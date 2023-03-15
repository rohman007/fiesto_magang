<?php 

include '../../kelola/urasi.php';
include '../../kelola/fungsi.php';
include 'urasi.php';

$limit = (intval(fiestolaundry($_GET['limit'], 11)) != 0 ) ? fiestolaundry($_GET['limit'], 11) : 10;
$offset = (intval(fiestolaundry($_GET['offset'], 11)) != 0 ) ? fiestolaundry($_GET['offset'], 11) : 0;
$cat_id = (intval(fiestolaundry($_GET['cat_id'], 11)) != 0 ) ? fiestolaundry($_GET['cat_id'], 11) : 0;

$sql = "SELECT konstanta, terjemahan FROM translation";
$result = $mysql->query($sql);
while (list($konstanta, $terjemahan) = $mysql->fetch_row($result)) {
	define($konstanta,$terjemahan);
}

// $sql = "SELECT id, judulberita, summary, isiberita, tglmuat FROM newsdata WHERE 1 ORDER BY id DESC LIMIT $limit OFFSET $offset";
if ($cat_id != '') $where_condition = " AND cat_id='$cat_id'";
$sql = "SELECT id, cat_id, tglmuat, judulberita, summary, isiberita, tglberita, ishot, thumb FROM newsdata WHERE publish='1' $where_condition ORDER BY id DESC LIMIT $limit OFFSET $offset";

try {
	$result = $mysql->query($sql);
	while($rows = $mysql->fetch_assoc($result)) {
		$results[] = $rows;
	}
} catch (Exception $e) {
	echo $e->getMessage();
}

if (count($results) > 0) {
	
	$urlfunc = new PrettyURL();
	
	foreach ($results as $row) {
		
		$id = $row['id']; 
		$cat_id = $row['cat_id']; 
		$tglmuat = $row['tglmuat']; 
		$judulberita = $row['judulberita'];
		$summary = $row['summary']; 
		$isiberita = $row['isiberita'];
		$tglberita = $row['tglberita'];
		$ishot = $row['ishot'];
		$thumb = $row['thumb'];
		
		$titleurl = array();
		$titleurl["pid"] = $judulberita;
		$content .= "					
		<div class=\"col-sm-4\">
			<div class=\"panel-body news-colx\">";
				if ($thumb != '' && file_exists("$cfg_thumb_path/$thumb")) {
					$content .= "
					<div class=\"img-news\">
						<img class=\"img-responsive\" alt=\"$judulberita\" src=\"$cfg_thumb_url/$thumb\">
					</div>	<!-- /.img-news -->\r\n";
				}
				$content .= "
					<div class=\"news-content caption-news-thumb\">
						<h1 class=\"newstitle\" ><a href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\">$judulberita</a></h1>";
				if ($isdateshown) $content .= "<span class=\"newsdate\">".tglformat($tglberita)."</span><br/>";
				if ($issummary && $summary!='') $content .= "<div class=\"newsshortdesc\">$summary</div>";
				$content .= "<a href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\" class=\"btn btn-default more\">"._LEARNMORE."</a>
					</div>	<!-- /.news-content -->\r\n
				";
				$content .= "
			</div>	<!-- /.panel-body -->\r\n
		</div>	<!-- /.col-sm-6 col-md-6 -->";			
		
	}
	
	echo $content;
}
?>
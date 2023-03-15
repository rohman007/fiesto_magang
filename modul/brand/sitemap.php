<?php	
$changefrequency = "monthly";	
$prioritas = 1;	
$sql = "SELECT judulfrontend FROM module WHERE nama='brand' ";	
$result = $mysql->query($sql);	
list($judulfrontend) = $mysql->fetch_array($result);	

$sql = "SELECT id, nama FROM catalogmerek ORDER BY nama";	
$result = $mysql->query($sql);	
if ($mysql->num_rows($result)>0) {		
	$html_standalone .= "<h2>$judulfrontend</h2>\r\n";		$html_standalone .= "<ul class=\"sitemap\">\r\n";		
	while (list($idmerek, $merek) = $mysql->fetch_row($result)) {			
		$titleurl = array();
		$titleurl["merek"] = $merek;			
		$url = $urlfunc->makePretty("?p=catalog&action=brand&merek=$idmerek", $titleurl);			
		$html_standalone .= "<li><a href=\"$url\">$merek</a></li>\r\n";			
		$xml_standalone .= "<url>
			<loc>".xmlurl($url)."</loc>						
			<changefreq>$changefrequency</changefreq>						
			<priority>$prioritas</priority>					
			</url>\r\n";		
	}		
	$html_standalone .= "</ul>\r\n";	
}
?>
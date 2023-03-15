<?php
if($posisi=="A")
{	$nilailebar = $widgetawidth;
}
elseif($posisi=="B")
{	$nilailebar = $widgetbwidth;
}

if($jenis=='ordertracking'){
	$widget .= "<div id=\"catalog_history\" align=\"center\">\r\n";
	$widget .= "<form action=\"".$urlfunc->makePretty("?p=order&action=trackorder")."\" method=\"POST\">\r\n";
	$widget .= "<div><input type=\"text\" name=\"order_orderid\" placeholder=\""._ORDERID."\"id=\"order_orderid\" class=\"searchtext\" /></div>\r\n";
	$widget .= "<div><input type=\"submit\" value=\""._TRACKORDER."\" class=\"order_submit\" /></div>\r\n";
	$widget .= "</form>\r\n";
	$widget .= "</div>\r\n";
}
?>

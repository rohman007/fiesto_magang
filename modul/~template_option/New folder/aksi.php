<?php
$action = fiestolaundry($_REQUEST['action'],20);
$sql = "SELECT judulfrontend FROM module WHERE nama='video'";
$result = mysql_query($sql);
if(mysql_num_rows($result)>0)
{
	list($title) = mysql_fetch_row($result);
}
if($action == '')
{
	for($i = 1; $i <= 7; $i++) $temp[] = "template$i";
	$template = join(',',$temp);
	$sql = "SELECT judul,$template FROM template_option";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)>0)
	{
		$content .= "<table class=\"tvideo\">";
		$i = 0;
		while(list($judul,$template)=mysql_fetch_row($result))
		{
			$i++;
			$content .= ($i%3==1) ? '<tr>' : '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' ;	
/* 			$content .= "<td class=\"youtubevideo\" width=\"150px\" height=\"112px\">
								<iframe class=\"youtube-player\" type=\"text/html\" src=\"http://www.youtube.com/embed/$url\" width=\"150\" height=\"112\" frameborder=\"0\">
								</iframe>
							 </td>";	 */		
			$home .= "<td class=\"youtubevideo\" width=\"150px\" height=\"112px\">
							<div id=\"bbuploader$i\" style=\"display:none;\">
								<object width=\"530\" height=\"410\">
									<param name=\"movie\" value=\"http://www.youtube.com/v/$url?version=3&amp;hl=id_ID&amp;rel=0&autoplay=1\"></param>
									<param name=\"allowFullScreen\" value=\"true\"></param>
									<param name=\"allowscriptaccess\" value=\"always\"></param>
									<embed src=\"http://www.youtube.com/v/$url?version=3&amp;hl=id_ID&amp;rel=0&autoplay=1\" type=\"application/x-shockwave-flash\" width=\"530\" height=\"398\" allowscriptaccess=\"always\" allowfullscreen=\"true\"></embed>
								</object>
							</div>
							<a rel=\"sexylightbox\" href=\"#TB_inline?height=410&width=530&background=#FFF&inlineId=bbuploader$i\">
								<img src=\"http://i4.ytimg.com/vi/$url/default.jpg\" width=\"150\" height=\"112\" />
							</a>
					  </td>";							 
			$content .= ($i%3==0) ? '</tr>' : '' ;
			$content .= ($i%3==0)?'<tr><td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>' : '';		
		}
		$content .= "</table>";		
	}else{
		$content .= _NOYOUTUBE;
	}
}
?>
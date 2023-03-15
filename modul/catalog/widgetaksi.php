<?php
if($posisi=="A")
{	$nilailebar = $widgetawidth;
}
elseif($posisi=="B")
{	$nilailebar = $widgetbwidth;
}

if ($jenis=='featured') {
	$sql  = "SELECT d.id, d.cat_id, d.filename, d.title, c.nama FROM catalogdata d, catalogcat c 
				WHERE d.publish='1' AND d.ishot='$isi' AND d.cat_id=c.id";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		$widget.= "<div class=\"owl-carousel owl-theme slide-widget\">\r\n";
		while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_cat_name) = $mysql->fetch_row($result)) {
			$titleurl = array();
			$titleurl["pid"] = $photo_title;
			$titleurl["cat_id"] = $photo_cat_name;
			$lebar="";
			$file_img = $cfg_thumb_path."/".$photo_filename;
				if(file_exists($file_img))
				{	list($widthimg, $heightimg, $type, $attr) = getimagesize($file_img);
					if($widthimg > $nilailebar)
					{	$lebar = "width=\"$nilailebar\" ";
					}
				}
			$widget .= "<div class=\"item\">";
			$widget .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&cat_id=$photo_cat_id", $titleurl)."\">";
			if($photo_filename!='')
				$widget .= "<img class=\"img-responsive\" $lebar src=\"$cfg_thumb_url/$photo_filename\" />";
			else
				$widget .= "<img class=\"img-responsive\" $lebar src=\"$cfg_app_url/images/none.gif\" />";	
			$widget .= "</a>";
			$widget .= "</div>";
		}
		$widget.= "</div>\r\n";
	} else {
		$widget.= _NOPROD;
	}
	$catcontent .= <<<SCRIPT
	<script>
	$(document).ready(function() {
    $(".slide-widget").owlCarousel({
    navigation : false, // Show next and prev buttons
    slideSpeed : 300,
	autoPlay : true,
    paginationSpeed : 400,
	transitionStyle : "fade",
	pagination : false,
    singleItem:true
    });
    });
	</script>
SCRIPT;
	$widget .= $catcontent;
}

if ($jenis=='catlist') {
	$sql = "SELECT id, nama, parent FROM catalogcat ORDER BY urutan";
	$cats = new categories();
	$mycats = array();
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);

	$currlevel = 1;
	$widget .= "<ul id=\"catalogwidget\">\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$selisihlevel=$cats->cats[$i]['level']-$currlevel;
		$titleurl = array();
		$titleurl["cat_id"] = $cats->cats[$i]['nama'];
		
		if ($selisihlevel>0) {
			$widget .= "<ul>\r\n";
			$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$cats->cats[$i]['nama']."</a></li>\r\n";
		}
		if ($selisihlevel==0) {
			$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$cats->cats[$i]['nama']."</a></li>\r\n";
		}
		if ($selisihlevel<0) {
			for ($j=0; $j<-$selisihlevel; $j++) {
				$widget .= "</ul>\n";
			}
			$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$cats->cats[$i]['nama']."</a></li>\r\n";
		}
		$currlevel=$cats->cats[$i]['level'];
	}	
	$widget .= "</ul>\r\n";
}

if ($jenis=='productlist') {
	$sql = "SELECT id, nama, parent FROM catalogcat ORDER BY urutan ";
	$cats = new categories();
	$mycats = array();
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);

	$currlevel = 1;
	$widget .= "<ul id=\"catalogproductwidget\">\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$selisihlevel=$cats->cats[$i]['level']-$currlevel;
		$titleurl = array();
		$titleurl["cat_id"] = $cats->cats[$i]['nama'];
		
		if ($selisihlevel>0) {
			$widget .= "<ul>\r\n";
			$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$cats->cats[$i]['nama']."</a></li>\r\n";
		}
		if ($selisihlevel==0) {
			$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$cats->cats[$i]['nama']."</a></li>\r\n";
		}
		if ($selisihlevel<0) {
			for ($j=0; $j<-$selisihlevel; $j++) {
				$widget .= "</ul>\r\n";
			}
			$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$cats->cats[$i]['nama']."</a></li>\r\n";
		}
		
		$sqlproduk = "SELECT id, title FROM catalogdata ";
		$sqlproduk .= "WHERE cat_id='".$cats->cats[$i]['id']."' ";
		$sqlproduk .= "AND publish='1' ";
		$sqlproduk .= "ORDER BY title ASC ";
		
		$resultproduk = $mysql->query($sqlproduk);
		if($mysql->num_rows($resultproduk)>0)
		{	$widget .= "<ul>\r\n";
			while(list($photo_id,$nama) = $mysql->fetch_row($resultproduk)) 
			{	$titleurl = array();
				$titleurl["pid"] = $nama;
				$titleurl["cat_id"] = $cats->cats[$i]['nama'];
				$widget .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&cat_id=".$cats->cats[$i]['id'], $titleurl)."\">".$nama."</a></li>\r\n";
			}
			$widget .= "</ul>\r\n";
		}
		
		$currlevel=$cats->cats[$i]['level'];
	}	
	$widget .= "</ul>\n";
}
if($jenis=='search'){
	$widget .= "<div id=\"searchproduct\" align=\"center\">\r\n";
	$widget .= "<form action=\"".$urlfunc->makePretty("?p=catalog&action=search")."\" method=\"POST\">\r\n";
	$widget .= "<input type=\"text\" name=\"keyword\" id=\"keyword\" class=\"searchtext\" /> <input type=\"submit\" value=\""._SEARCH."\" class=\"searchubmit\" />\r\n";
	$widget .= "</form>\r\n";
	$widget .= "</div>\r\n";
}
if ($jenis == 'dropdown') {
	$mycats = array();
	$cats = new categories();
	$sql_catalog = "SELECT id, nama, parent FROM catalogcat ORDER BY urutan ";
	$result_catalog = $mysql->query($sql_catalog);
	while($row_catalog = $mysql->fetch_array($result_catalog)) 
	{	$titleurl = array();
		$titleurl["cat_id"] = $row_catalog['nama'];
		$url = "";
		
		$sqlchild = "SELECT id, nama, parent FROM catalogcat 
			WHERE parent='".$row_catalog['id']."' ";
		$resultchild = $mysql->query($sqlchild);
		if($mysql->num_rows($resultchild)==0)
		{	$url = "?p=catalog&action=images&cat_id=".$row_catalog['id'];
		}
	
		if(strlen($url)>0)
		{	$url = $urlfunc->makePretty($url, $titleurl);
		}
		
		if($row_catalog['parent']==0)
		{	$row_catalog['parent'] = $row['id'];
		}
		else
		{	$row_catalog['parent'] = $row['id']."-".$row_catalog['parent'];
		}
		$row_catalog['id'] = $row['id']."-".$row_catalog['id'];
		
		$mycats[] = array('id'=>$row_catalog['id'],
			'parent'=>$row_catalog['parent'], 'type'=>$row['type'], 
			'judul'=>$row_catalog['nama'], 'url'=>$url, 'level'=>0);
	}
	$cats->get_cats($mycats);
	
	$currlevel = 1;
	$rightAlign = 0;
	$catcontent = "<div id=\"menuleft\">\r\n";
	$catcontent .= "<div id=\"cssmenu\">\r\n";
	$catcontent .= "<ul>\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$rightAlign = 0;		
		if ($cats->cats[$i]['url'] == '') {
			if ($cats->cats[$i]['level'] == 1) {
				// $menuitem = "<li class=\"dropdown\"><a href=\"#\" class=\"dropdown-toggle $active\" data-toggle=\"dropdown\"><span class=\"down\">".$cats->cats[$i]['judul']."</span><b class=\"caret\"></b></a>";
				$menuitem = "<li class=\"has-sub open\"><a><span class=\"down\">".$cats->cats[$i]['judul']."</span></a>";
			} else {
				$menuitem = "<li class=\"has-sub\"><a class=\"fly\"><span>".$cats->cats[$i]['judul']."</span></a>";
			}
		} else {
			if ($cats->cats[$i]['level'] == 1) {
				$menuitem = "<li class=\"top\"><a href=\"".$cats->cats[$i]['url']."\" class=\"top_link $active\" ><span>".$cats->cats[$i]['judul']."</span></a></li>\r\n";
			} else {
				$menuitem = "<li><a class=\"$active\" href=\"".$cats->cats[$i]['url']."\"><span>".$cats->cats[$i]['judul']."</span></a></li>\r\n";
			}
		}

		$selisihlevel=$cats->cats[$i]['level']-$currlevel;
		if ($selisihlevel>0) {
			if ($cats->cats[$i]['level'] <= 2) 
				$catcontent .= "<ul class=\"has-sub\" style=\"display:block\">\r\n";
			else
				$catcontent .= "<ul>";
			$catcontent .= "$menuitem\r\n";
		}
		if ($selisihlevel==0) {
			$catcontent .= "$menuitem\r\n";
		}
		if ($selisihlevel<0) {
			for ($j=0; $j<-$selisihlevel; $j++) {
				$catcontent .= "</ul></li>\r\n";
				if ($cats->cats[$i]['tipe']=='0') $catcontent .= "</li>\r\n";
			}
			$catcontent .= "$menuitem\r\n";
		}
		$currlevel=$cats->cats[$i]['level'];
	}	

	$catcontent .= "</ul>\r\n";
	$catcontent .= "</div> <!-- /#cssmenu -->\r\n";
	$catcontent .= "</div> <!-- /#left -->\r\n";
	
	$catcontent .= <<<SCRIPT
	<script>
	( function( $ ) {
		$( document ).ready(function() {	
			$('#cssmenu li.has-sub>a').bind('click', function(){
				$(this).removeAttr('href');
				var element = $(this).parent('li');
				if (element.hasClass('open')) {
					element.removeClass('open');
					element.find('li').removeClass('open');
					element.find('ul').slideUp();
				}
				else {
					element.addClass('open');
					element.children('ul').slideDown();
					element.siblings('li').children('ul');
					element.siblings('li').removeClass('open');
					element.siblings('li').find('li').removeClass('open');
					element.siblings('li').find('ul');
				}
			});
		});
	} )( jQuery );
	</script>
SCRIPT;
	$widget .= $catcontent;
}
?>

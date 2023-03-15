<?php
$cat_id = fiestolaundry($_GET['cat_id'], 11);
$action = fiestolaundry($_GET['action'], 20);
$pid = fiestolaundry($_GET['pid'], 11);
$keyword = fiestolaundry(urldecode($_REQUEST['keyword']), 200);	//jun 15:24 10/01/2012 demi pretty dari widget input box harus pake POST sedangkan utk prevnext butuh GET
$screen = fiestolaundry($_GET['screen'], 11);
$merek = fiestolaundry($_REQUEST['merek'], 11);
//$catmenu = print_menu("catalog", $cat_id);
$matauang = fiestolaundry($_POST['matauang'], 5);
$hargabawah = fiestolaundry($_POST['hargabawah'], 10);
$hargaatas = fiestolaundry($_POST['hargaatas'], 10);
$sb = fiestolaundry($_GET['sb'],20);

require_once 'aplikasi/schema.php';

$sql = "SELECT judulfrontend FROM module WHERE nama='catalog'";
$result = $mysql->query($sql);
list($title) = $mysql->fetch_row($result);
if ($action == '') {
    $sql = "SELECT id, parent, nama, filename FROM catalogcat WHERE parent=0 ORDER BY urutan";
    $cats = new categories();
    $mycats = array();
    $result = $mysql->query($sql);
    while ($row = $mysql->fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'parent' => $row['parent'], 'nama' => $row['nama'], 'filename' => $row['filename'], 'level' => 0);
    }
    $cats->get_cats($mycats);

    $currlevel = 1;
    $catcontent .= "<ul class=\"list-product\" itemscope itemtype=\"Brand\" >\r\n";
	$titleurl = array();
    /* for ($i = 0; $i < count($cats->cats); $i++) {
		$titleurl['cat_id'] = $cats->cats[$i]['nama'];
		$url = "<li itemprop=\"name\"><a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'],$titleurl)."\">".$cats->cats[$i]['nama']."</a></li>";
        $selisihlevel = $cats->cats[$i]['level'] - $currlevel;
        if ($selisihlevel > 0) {
            $catcontent .= "<ul>\r\n";
            $catcontent .= "$url\r\n";
        }
        if ($selisihlevel == 0) {
            $catcontent .= "$url\r\n";
        }
        if ($selisihlevel < 0) {
            for ($j = 0; $j < -$selisihlevel; $j++) {
                $catcontent .= "</ul>\r\n";
            }
            $catcontent .= "$url\r\n";
        }
        $currlevel = $cats->cats[$i]['level'];
    } */
	
	for ($i = 0; $i < count($cats->cats); $i++) {
		$titleurl['cat_id'] = $cats->cats[$i]['nama'];
		$_id = $cats->cats[$i]['id'];
		$parent = $cats->cats[$i]['parent'];
		$catname = $cats->cats[$i]['nama'];
		$catfilename = $cats->cats[$i]['filename'];
		
		$catthumbnail = ($catfilename != '' && file_exists("$cfg_fullsizepics_path/$catfilename")) ? "<img src='$cfg_fullsizepics_url/".$catfilename."' alt='".$catname."'>" : "$catname";
		$url = "<li class=\"brand-$_id\"><a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'],$titleurl)."\">".$catthumbnail."</a></li>";
		$catcontent .= "$url\r\n";
		
	}
    $catcontent .= "</ul>\r\n";
}


if ($action == "images") 
{

	$titleurl = array();
    if (!empty($cat_id)) 
    {
        $sql = "SELECT nama, description, filename FROM catalogcat WHERE id='$cat_id'";
        $result = $mysql->query($sql);
        list($catname, $catdesc, $catfilename) = $mysql->fetch_row($result);
		$title = $catname;
		if($catfilename!='')
		{
			$catthumb = "<div class=\"image-category\"> <img src='$cfg_fullsizepics_url/".$catfilename."' alt='".$catname."'></div>";
		}
		//overriding meta tags by jun 5:54 17/01/2012
		$config_site_titletag = "$catname - $config_site_titletag";
		if ($catdesc != '') $config_site_metadescription = strip_tags($catdesc);

        $cats = new categories();
        $mycats = array();
        $sql = 'SELECT id, nama, parent, description FROM catalogcat';

        $result = $mysql->query($sql);
        while ($row = $mysql->fetch_array($result)) 
        {
            $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'description' => $row['description'], 'level' => 0);
        }
        $cats->get_cats($mycats);
        $catnav = "<a href=\"".$urlfunc->makePretty("?p=catalog")."\">$topcatnamenav</a>";
		$catnav_pixel = $topcatnamenav;
        for ($i = 0; $i < count($cats->cats); $i++) 
        {
            $cats->cat_map($cats->cats[$i]['id'], $mycats);
            if ($cats->cats[$i]['id'] == $cat_id) {
                //$catdesc = $cats->cats[$i]['description'];
                for ($a = 0; $a < count($cats->cat_map); $a++) {
                    $cat_parent_id = $cats->cat_map[$a]['id'];
                    $cat_parent_name = $cats->cat_map[$a]['nama'];
					$titleurl['cat_id']=$cat_parent_name;
                    $catnav .= "$separatorstyle<a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=$cat_parent_id",$titleurl)."\">$cat_parent_name</a>";//l=id
                    $catnav_pixel .= "$separatorstyle$cat_parent_name";//l=id
                }
                $catnav .= $separatorstyle . $cats->cats[$i]['nama'];
                $catnav_pixel .= $separatorstyle . $cats->cats[$i]['nama'];
            }
        }


        if ($mysql->num_rows($result) > 0) 
        {
            $sql = "SELECT nama, filename FROM catalogcat WHERE id='$cat_id'";
            $result = $mysql->query($sql);
			list($cat_name) = $mysql->fetch_row($result);
			
            $sql = "SELECT id, nama, filename FROM catalogcat WHERE parent='$cat_id'";
            $result = $mysql->query($sql);
            $totalsubcat = $mysql->num_rows($result);
            if ($totalsubcat > 0) 
            {
                //$catsubcat = "<div class=\"titleofsubcatlist\">"._SUBCAT."</div>\n";
                $catsubcat .= "<ul class=\"test\">\n";
                while (list($catsubcat_id, $catsubcat_name, $catsubcat_filename) = $mysql->fetch_row($result)) 
                {
					$titleurl['cat_id']=$catsubcat_name;
					$catfilename = $catsubcat_filename;
					$catthumbnail = ($catfilename != '' && file_exists("$cfg_fullsizepics_path/$catfilename")) ? "<img src='$cfg_fullsizepics_url/".$catfilename."' alt='".$catname."'><span>$catsubcat_name</spa>" : "$catsubcat_name";
                    $catsubcat .= "<li><a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=$catsubcat_id",$titleurl)."\">$catthumbnail</a></li>\n";//l=id
                }
                $catsubcat .= "</ul>\n";
            }
			// $titleurl['cat_id'] = $cats->cats[$i]['nama'];
			// $_id = $cats->cats[$i]['id'];
			// $parent = $cats->cats[$i]['parent'];
			// $catname = $cats->cats[$i]['nama'];
			// $catfilename = $cats->cats[$i]['filename'];
			
			// $catthumbnail = ($catfilename != '' && file_exists("$cfg_fullsizepics_path/$catfilename")) ? "<img src='$cfg_fullsizepics_url/".$catfilename."' alt='".$catname."'>" : "$catname";
			// $url = "<li class=\"brand-$_id\" itemprop=\"name\"><a itemprop=\"url\" href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'],$titleurl)."\">".$catthumbnail."</a></li>";
			// $catcontent .= "$url\r\n";

            // hitung dulu untuk menentukan berapa halaman...
            $sql = "SELECT id FROM catalogdata";
            if (!empty($cat_id)) 
            {
                $sql .= " WHERE cat_id='$cat_id' AND publish='1'";
            } 
            else 
            {
                $sql .= " WHERE publish='1'";
            }

            $result = $mysql->query($sql);
            $total_records = $mysql->num_rows($result);
            $pages = ceil($total_records / $cfg_per_page);

            $mysql->free_result($result);

            // get the image information
            if ($screen == '') 
            {
                $screen = 0;
            }

            $start = $screen * $cfg_per_page;

            // ... baru kemudian diambil lagi, kali ini dengan limit perhalaman
            $sql = "SELECT id, cat_id, filename, title, publish, ishot, keterangan, harganormal, diskon, matauang,
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon,isbest,ispromo,isnew,issold
			FROM catalogdata";
            if (!empty($cat_id)) 
            {
                $sql .= " WHERE cat_id='$cat_id' AND publish='1'";
            } 
            else 
            {
                $sql .= " WHERE publish='1'";
            }
			
			// New By aly
			$temp_sort_by = array_keys($sort_by);
			$sql .= ' ORDER BY ';
			$sb = str_replace('%20',' ', $sb);
			if (isset($sb) && $sb != '') {
				$sql .= " $sb ";
			} else {
				$sql .= " {$temp_sort_by[0]} ";
			}
			
            // $sql .= " ORDER BY " . $sort;
            $sql .= " LIMIT $start, $cfg_per_page";

            $photo = $mysql->query($sql);
            $total_images = $mysql->num_rows($photo);

            if ($total_images == "0") 
            {
                if ($totalsubcat == 0) 
                {
                    $cattitle .= _ERROR;
                    $catcontent .= _NOPROD;
                }
            } 
            else 
            {
				
				$titleurl = array();
				$titleurl['cat_id'] = $cat_name;
				$urlstatus = $urlfunc->makePretty("?p=catalog&action=images&cat_id=$cat_id&sb=", $titleurl);
				
				$sortbyorder = sort_by($urlstatus, $sb);
				
                if ($pages > 1) {
					$titleurl = array();
					$titleurl['cat_id']=$cat_name;
                    $catpage = aksipagination($namamodul, $screen, "action=images&cat_id=$cat_id&sb=$sb");	
				}
					
					
				// <!-- Facebook Pixel Code -->
				// $catcontent .= <<<SCRIPT
				// <script>
					// fbq('track', 'ViewContent', {
					  // content_name: '$catname',
					  // content_category: '$catnav_pixel',
					  // content_ids: ['$cat_id'],
					  // content_type: '$title',
					  // currency: 'IDR',
					  // referrer: document.referrer,
					  // userAgent: navigator.userAgent,
					  // language: navigator.language
					// });
				// </script>
// SCRIPT;
                if ($showtype == 1)
                    {$catcontent .= show_me_the_list();}
                if ($showtype == 0)
                    {$catcontent .= show_me_the_images();}
            }
        } 
        else 
        {
            $cattitle .= _ERROR;
            $catcontent .= _NOCAT;
        }
    } 
    else 
    {
        $cattitle .= _ERROR;
        $catcontent .= _NOCAT;
    }
}

if (substr($action, 0, 4) == 'view') {

    $sql = "SELECT cat_id FROM catalogdata WHERE id='$pid'";
    $result = $mysql->query($sql);
    list($cat_id) = $mysql->fetch_row($result);

    $cats = new categories();
    $mycats = array();
    $sql = 'SELECT id, nama, parent, description FROM catalogcat';
    $result = $mysql->query($sql);
    while ($row = $mysql->fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'description' => $row['description'], 'level' => 0);
    }
    $cats->get_cats($mycats);
	$titleurl = array();
    $catnav = "<a href=\"".$urlfunc->makePretty("?p=catalog")."\">$topcatnamenav</a>";
    for ($i = 0; $i < count($cats->cats); $i++) {
        $cats->cat_map($cats->cats[$i]['id'], $mycats);
        if ($cats->cats[$i]['id'] == $cat_id) {
            //$catdesc = $cats->cats[$i]['description'];
            for ($a = 0; $a < count($cats->cat_map); $a++) {
                $cat_parent_id = $cats->cat_map[$a]['id'];
                $cat_parent_name = $cats->cat_map[$a]['nama'];
				$titleurl['cat_id']=$cat_parent_name;
                $catnav .= "$separatorstyle<a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=$cat_parent_id",$titleurl)."\">$cat_parent_name</a>";
            }
			$titleurl['cat_id']=$cats->cats[$i]['nama'];
            $catnav .= $separatorstyle . "<a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'],$titleurl)."\">" . $cats->cats[$i]['nama'] . "</a>";
        }
    }

    $tambahan = '';
    $num = count($customfield);
    for ($i = 0; $i < $num; $i++) {
        $tambahan .= ", $customfield[$i]";
    }

    // if we're editing, get the image details
    /* if ($num > 0) {
      $sql = "SELECT id, cat_id, filename, date, title, publish, $tambahan FROM catalogdata WHERE id='$pid'";
      } else {
      $sql = "SELECT id, cat_id, filename, date, title, publish FROM catalogdata WHERE id='$pid'";
      } */
    $sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, keterangan,
					harganormal, diskon, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon $tambahan
		FROM catalogdata WHERE id='$pid'";

    $getpic = $mysql->query($sql);
    $hasil = $mysql->fetch_array($getpic);

    $catimg .= '<div id="productfull" style="text-align:center">';
    if ($hasil['filename'] != '')
        $catimg .= '<img src="' . $cfg_fullsizepics_url . '/' . $hasil['filename'] . '" ' . $imagesize . ' alt="' . $hasil['title'] . '" title="' . $hasil['title'] . '" />';
    else
        $catimg .= '<img itemprop="image" src="' . $cfg_app_url . '/images/none.gif" />';
    $catimg .= '</div>';

    $title = $hasil['title'];
	
	//overriding meta tags by jun 5:54 17/01/2012
	$config_site_titletag = $hasil['title']." - ".$config_site_titletag;
	if ($hasil['ketsingkat'] != '') $config_site_metadescription = strip_tags($hasil['ketsingkat']);
	
    $catcontent = "<div class=\"catalogtitle\"><h1>" . $hasil['title'] . "</h1></div>\r\n";
    if ($hasil['keterangan'] != '')
        $catcontent .= '<div id="keterangan">' . $hasil['keterangan'] . '</div>' . "\n";
		
	if($prod_attribut==1)
	{
	//ukuran	
	$catcontent .='<p>';
	$q=$mysql->query("SELECT size,stok from attribut WHERE id='$pid' order by auto");
	if($mysql->num_rows($q))
	{	$i=0;
		while($d=$mysql->fetch_array($q))
		{
		$cekstok="belumpilih";
		if($d['stok']<=0)
		{
		$cekstok="habis";
		}
		if($cekstok!='habis')
		{
		$catcontent .="<a class=\"pilihukuran $cekstok\" id=\"pilihukuran{$i}\" size=\"".$d['size']."\" stok=\"".$d['stok']."\" href=\"javascript:void(0);\" >".$d['size']."</a>";
		$i++;
		}
		}
	}
	$catcontent .='<p>';
	}
    $hasil['diskon'] = str_replace(" ", "", $hasil['diskon']);
    if ($hasil['harganormal'] > 0) {
        if ($hasil['diskon'] == '') {
            $kelasnormal = 'normalwodisc';
        } else {
            $kelasnormal = 'normalwdisc';
        }
				
        $catcontent .= '<p class="harga_catalog">' . _PRICE . ' 
				<span class="' . $kelasnormal . '">' . $hasil['matauang'] . ' ' . number_format($hasil['harganormal'], 0, ',', '.') . '</span>';
        if ($hasil['diskon'] != '') {
            if (substr_count($hasil['diskon'], "%") == 1) {
                $label_disc = $hasil['diskon'];
            } else {
                $label_disc = $hasil['matauang'] . ' ' . number_format($hasil['diskon'], 0, ',', '.');
            }
            $catcontent .= ' <span class="discprice">' . $hasil['matauang'] . ' ' . number_format($hasil['hargadiskon'], 0, ',', '.') . '</span>';
            $catcontent .= ' <span class="discvalue">(' . _YOUSAVE . ' ' . $label_disc . ')</span>';
        }
        $catcontent .= '</p>' . "\r\n";
		//START ADD TO CATALOG
		if ($pakaicart) {
			$catcontent .= "<div class=\"addtocart\">";
			$catcontent .= "<form action=\"".$urlfunc->makePretty("?p=cart&action=add")."\" method=\"POST\">";
			if($prod_attribut==1)
			{
			$catcontent .= "<input type=\"hidden\" name=\"pilihukuran\" id=\"pilihukuran\" value=\"\" />";
			}
			$catcontent .= "<input type=\"hidden\" name=\"pid\" value=\"$pid\" />";
			$catcontent .= "<input type=\"text\" name=\"qty\" id=\"qty\" size=\"2\" value=\"1\" style=\"text-align:right\" />";
			$catcontent .= "<input type=\"submit\" id=\"addtocartbutton\" value=\"" . _ADDTOCART . "\" />";
			$catcontent .= "</form>";
			$catcontent .= "</div>";
		}
		//END ADD TO CATALOG
    }

    //prevnext
    //SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, 

			
    $sql = "SELECT id, title, filename FROM catalogdata";
    if ($action == "viewsearch") {
		$keyword=strip_tags($keyword);
		$r_keyword=preg_split("/[\s,]+/",$keyword);
		$sql .= " WHERE (1=1";
		foreach ($r_keyword as $splitkeyword) {
			$sql .= " AND title LIKE '%$splitkeyword%'";
		}
		$sql .= ")";
		
		$sql .= " OR (1=1";
		foreach ($r_keyword as $splitkeyword) {
			$sql .= " AND ketsingkat LIKE '%$splitkeyword%'";
		}
		$sql .= ")";

		$sql .= " OR (1=1";
		foreach ($r_keyword as $splitkeyword) {
			$sql .= " AND keterangan LIKE '%$splitkeyword%'";
		}
		$sql .= ")";

		foreach ($searchedcf as $fieldygdisearch) {
			$sql .= " OR (1=1";
			foreach ($r_keyword as $splitkeyword) {
				$sql .= " AND $fieldygdisearch LIKE '%$splitkeyword%'";
			}
			$sql .= ")";
		}
	}
    if ($action == "viewimages") $sql .= " WHERE (cat_id='$cat_id')";
    if ($action == "viewbrand") $sql .= " WHERE (idmerek='$merek')";
	
    $sql .= " AND (publish='1') ORDER BY " . $sort;

    $result = $mysql->query($sql);
    $num = $mysql->num_rows($result);
    for ($i = 0; $i < $num; $i++) {
        if ($mysql->result($result, $i, "id") == $pid) {

            if ($i != 0) {
                $showprevid = $mysql->result($result, $i - 1, "id");
                $showprevname = $mysql->result($result, $i - 1, "filename");
                $showprevtitle = $mysql->result($result, $i - 1, "title");
            } else {
                $showprevid = -1;
            }

            if ($i != $num - 1) {
                $shownextid = $mysql->result($result, $i + 1, "id");
                $shownextname = $mysql->result($result, $i + 1, "filename");
                $shownexttitle = $mysql->result($result, $i + 1, "title");
            } else {
                $shownextid = -1;
            }

            $tempthumb = $cfg_thumb_url;
            if ($showprevid != -1) {
                if ((file_exists($cfg_thumb_path . "/" . $showprevname)) && ($showprevname != '')) {
                    $image_stats = getimagesize($cfg_thumb_path . "/" . $showprevname);
                    $new_w = $image_stats[0];
                    $new_h = $image_stats[1];
                    $if_thumb = "yes";
                } elseif ((!file_exists($cfg_thumb_path . "/" . $showprevname)) && (file_exists($cfg_fullsizepics_path . "/" . $showprevname)) && ($showprevname != '')) {
                    $image_stats = getimagesize($cfg_fullsizepics_path . "/" . $showprevname);
                    $imagewidth = $image_stats[0];
                    $imageheight = $image_stats[1];
                    $img_type = $image_stats[2];
                    if ($imagewidth > $imageheight) {
                        $new_w = $cfg_thumb_width;
                        $ratio = ($imagewidth / $cfg_thumb_width);
                        $new_h = round($imageheight / $ratio);
                    } else {
                        $new_h = $cfg_thumb_width;
                        $ratio = ($imageheight / $cfg_thumb_width);
                        $new_w = round($imagewidth / $ratio);
                    }
                    $cfg_thumb_url = $cfg_fullsizepics_url;
                    $if_thumb = "no";
                } else {
                    $showprevname = $noimage_filename;
                }
        $titleurl = array();
                if ($action == 'viewsearch') {
                    $actiondependent = "keyword=$keyword";
				}
                if ($action == 'viewimages') {
                    $actiondependent = "cat_id=$cat_id";
					$sql1 = "SELECT nama FROM catalogcat WHERE id='$cat_id'";
					$result1 = $mysql->query($sql1);
					list($catname)=$mysql->fetch_row($result1);
					$titleurl["cat_id"] = $catname;
				}
                if ($action == 'viewbrand') {
                    $actiondependent = "merek=$merek";
				}
				$titleurl["pid"] = $showprevtitle;
                $catprev .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=$action&pid=$showprevid&$actiondependent",$titleurl)."\">";
                if ($showprevname != '')
                    $catprev .= "<img src=\"$cfg_thumb_url/$showprevname\" border=\"0\" alt=\"$showprevtitle\" title=\"$showprevtitle\" width=\"$new_w\" height=\"$new_h\">";
                else
                    $catprev .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\">";
                $catprev .= "</a>";
                $catprev .= "<br><a href=\"".$urlfunc->makePretty("?p=catalog&action=$action&pid=$showprevid&$actiondependent",$titleurl)."\">&lt;&lt; " . _PREVIOUS . "</a>";
            } else {
                $catprev .= '&nbsp;';
            }
            $cfg_thumb_url = $tempthumb;


            $tempthumb = $cfg_thumb_url;
            if ($shownextid != -1) {
                if ((file_exists($cfg_thumb_path . "/" . $shownextname)) && ($shownextname != '')) {
                    $image_stats = getimagesize($cfg_thumb_path . "/" . $shownextname);
                    $new_w = $image_stats[0];
                    $new_h = $image_stats[1];
                    $if_thumb = "yes";
                } elseif ((!file_exists($cfg_thumb_path . "/" . $shownextname)) && (file_exists($cfg_fullsizepics_path . "/" . $shownextname)) && ($shownextname != '')) {
                    $image_stats = getimagesize($cfg_fullsizepics_path . "/" . $shownextname);
                    $imagewidth = $image_stats[0];
                    $imageheight = $image_stats[1];
                    $img_type = $image_stats[2];
                    if ($imagewidth > $imageheight) {
                        $new_w = $cfg_thumb_width;
                        $ratio = ($imagewidth / $cfg_thumb_width);
                        $new_h = round($imageheight / $ratio);
                    } else {
                        $new_h = $cfg_thumb_width;
                        $ratio = ($imageheight / $cfg_thumb_width);
                        $new_w = round($imagewidth / $ratio);
                    }
                    $cfg_thumb_url = $cfg_fullsizepics_url;
                    $if_thumb = "no";
                } else {
                    $shownextname = $noimage_filename;
                }
                if ($action == 'viewsearch') {
                    $actiondependent = "keyword=$keyword";
				}
                if ($action == 'viewimages') {
                    $actiondependent = "cat_id=$cat_id";
					$sql1 = "SELECT nama FROM catalogcat WHERE id='$cat_id'";
					$result1 = $mysql->query($sql1);
					list($catname)=$mysql->fetch_row($result1);
					$titleurl["cat_id"] = $catname;
					//$_SESSION['URL_BEFORE_LOGIN']=$urlfunc->makePretty("?p=catalog&action=viewimages&pid=".$_GET['pid']."&cat_id=".$_GET['cat_id']);
				}
                if ($action == 'viewbrand') {
                    $actiondependent = "merek=$merek";
				}
				$titleurl["pid"] = $shownexttitle;
                $catnext .= "<div align=\"center\"><a href=\"".$urlfunc->makePretty("?p=catalog&action=$action&pid=$shownextid&$actiondependent",$titleurl)."\">";
                if ($shownextname != '')
                    $catnext .= "<img src=\"$cfg_thumb_url/$shownextname\" border=\"0\" alt=\"$shownexttitle\" title=\"$shownexttitle\" width=\"$new_w\" height=\"$new_h\">";
                else
                    $catnext .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\">";
                $catnext .= "</a>";
                $catnext .= "<br><a href=\"".$urlfunc->makePretty("?p=catalog&action=$action&pid=$shownextid&$actiondependent",$titleurl)."\">" . _NEXT . " &gt;&gt;</a></div>";
            } else {
                $catnext .= '&nbsp;';
            }
            $cfg_thumb_url = $tempthumb;
        }
    }
}

if ($action == "search") {
    $title .= ": " . _SEARCHRESULT;

    if ($keyword != '') {
		$keyword=strip_tags($keyword);
		$r_keyword=preg_split("/[\s,]+/",$keyword);

		$admintitle = _SEARCHRESULTS;

		$sql  = "SELECT id FROM catalogdata WHERE (1=1";
		foreach ($r_keyword as $splitkeyword) {
			$sql .= " AND title LIKE '%$splitkeyword%'";
		}
		$sql .= ")";
		
		$sql .= " OR (1=1";
		foreach ($r_keyword as $splitkeyword) {
			$sql .= " AND ketsingkat LIKE '%$splitkeyword%'";
		}
		$sql .= ")";

		$sql .= " OR (1=1";
		foreach ($r_keyword as $splitkeyword) {
			$sql .= " AND keterangan LIKE '%$splitkeyword%'";
		}
		$sql .= ")";

		foreach ($searchedcf as $fieldygdisearch) {
			$sql .= " OR (1=1";
			foreach ($r_keyword as $splitkeyword) {
				$sql .= " AND $fieldygdisearch LIKE '%$splitkeyword%'";
			}
			$sql .= ")";
		}
        $result = $mysql->query($sql);
        $total_records = $mysql->num_rows($result);
        $pages = ceil($total_records / $cfg_per_page);

        if ($screen == '') {
            $screen = 0;
        }

        $start = $screen * $cfg_per_page;

        $sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, harganormal, diskon, matauang, 
						if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
						as nilaidiskon,
						harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
							diskon)) as hargadiskon
							 FROM catalogdata c WHERE (1=1";
							 
		foreach ($r_keyword as $splitkeyword) {
			$sql .= " AND title LIKE '%$splitkeyword%'";
		}
		$sql .= ")";
		
		$sql .= " OR (1=1";
		foreach ($r_keyword as $splitkeyword) {
			$sql .= " AND ketsingkat LIKE '%$splitkeyword%'";
		}
		$sql .= ")";

		$sql .= " OR (1=1";
		foreach ($r_keyword as $splitkeyword) {
			$sql .= " AND keterangan LIKE '%$splitkeyword%'";
		}
		$sql .= ")";

		foreach ($searchedcf as $fieldygdisearch) {
			$sql .= " OR (1=1";
			foreach ($r_keyword as $splitkeyword) {
				$sql .= " AND $fieldygdisearch LIKE '%$splitkeyword%'";
			}
			$sql .= ")";
		}							 
		$sql .= " AND publish='1' $filter ORDER BY $sort LIMIT $start, $cfg_per_page";
//die($sql);
        $photo = $mysql->query($sql);
        $total_images = $mysql->num_rows($photo);
        if ($total_images > 0) {
            $keyword = urlencode($keyword);
            if ($pages > 1) {
                $catpage = aksipagination($namamodul, $screen, "action=search&keyword=$keyword");
			}	
			
			if ($pakaifbpixel) {
				$catcontent .= <<<HTML
				<script>
				  fbq('track', 'Search');
				</script>

HTML;
			}
            $catcontent .= show_me_the_images();
        } else {
            $catcontent .= _NOSEARCHRESULT;
        }
    } else {
        $catcontent .= _NOSEARCHRESULT;
    }
}

if ($action == "brand") {
    $sql = "SELECT nama FROM catalogmerek WHERE id='$merek'";
    $result = $mysql->query($sql);
    list($title) = $mysql->fetch_row($result);

    $sql = "SELECT id FROM catalogdata WHERE idmerek='$merek' AND publish='1'";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $cfg_per_page);

    if ($screen == '')
        $screen = 0;

    $start = $screen * $cfg_per_page;

    $sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon
		 FROM catalogdata WHERE idmerek='$merek' AND publish='1' ORDER BY $sort LIMIT $start, $cfg_per_page";
    $photo = $mysql->query($sql);
    $total_images = $mysql->num_rows($photo);

    if ($total_images == "0") {
        $catcontent .= _NOPROD;
    } else {
        $keyword = urlencode($keyword);
		$sql = "SELECT nama FROM catalogmerek ORDER BY nama";
		$result = $mysql->query($sql);
		list($namamerek) = $mysql->fetch_row($result);
		if ($pages > 1) {
			$titleurl = array();
			$titleurl["merek"] = $namamerek;
            $catpage = aksipagination($namamodul, $screen, "action=brand&merek=$merek");
		}
        if ($showtype == 1)
            $catcontent .= show_me_the_list();
        if ($showtype == 0)
            $catcontent .= show_me_the_images();
    } //end total_images
}

if($action=="detail") {
	
	ob_start();
	
	// $sql = "SELECT cat_id FROM catalogdata WHERE id='$pid'";
    // $result = $mysql->query($sql);
    // list($cat_id) = $mysql->fetch_row($result);

    // $cats = new categories();
    // $mycats = array();
    // $sql = 'SELECT id, nama, parent, description FROM catalogcat';
    // $result = $mysql->query($sql);
    // while ($row = $mysql->fetch_array($result)) {
        // $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'description' => $row['description'], 'level' => 0);
    // }
    // $cats->get_cats($mycats);
	// $titleurl = array();
    // $catnav = "<a href=\"".$urlfunc->makePretty("?p=catalog")."\">$topcatnamenav</a>";
    // for ($i = 0; $i < count($cats->cats); $i++) {
        // $cats->cat_map($cats->cats[$i]['id'], $mycats);
        // if ($cats->cats[$i]['id'] == $cat_id) {
            // //$catdesc = $cats->cats[$i]['description'];
            // for ($a = 0; $a < count($cats->cat_map); $a++) {
                // $cat_parent_id = $cats->cat_map[$a]['id'];
                // $cat_parent_name = $cats->cat_map[$a]['nama'];
				// $titleurl['cat_id']=$cat_parent_name;
                // $catnav .= "$separatorstyle<a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=$cat_parent_id",$titleurl)."\">$cat_parent_name</a>";
            // }
			// $titleurl['cat_id']=$cats->cats[$i]['nama'];
            // $catnav .= $separatorstyle . "<a href=\"".$urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'],$titleurl)."\">" . $cats->cats[$i]['nama'] . "</a>";
        // }
    // }
	

	//=======================================
	$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, keterangan,
			harganormal, diskon, matauang, 
			if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
			as nilaidiskon,
			harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
				diskon)) as hargadiskon,thumb,isbest,ispromo,isnew,issold,idmerek, tokopedia, bukalapak, shopee
			FROM catalogdata WHERE id='$pid'";
	
    $getpic = $mysql->query($sql);
    $hasil = $mysql->fetch_array($getpic);

	$cisnew		= $hasil['isnew'] == 1 ? "<div class='new'></div>" : "";
	$cispromo	= $hasil['ispromo'] == 1 ? "<span class='reduction'><span>Promo</span></span>" : "";
	$cisobral	= $hasil['isbest'] == 1 ? "<span class='best'><span>Best</span></span>" : "";
	$cissold	= $hasil['issold'] == 1 ? "<div class='sold_out'><span></span></div>" : "";
	
	// Schema
	// $schema = new Schema();
	// $content .= $schema->ProductDetail($sql);
	// $content .= $schema->ProductBreadcrumbs($pid);
	//////////////////
	$title = $hasil['title'];
	$config_site_titletag = $hasil['title']." - ".$config_site_titletag;
	if($hasil['ketsingkat'] != '') $config_site_metadescription = strip_tags($hasil['ketsingkat']);
		
	$content .= ob_get_clean();

	//additional code here
					
	$image_temp = '';
	$content .= '<div class="row">';
	$content .= '<div class="pb-left-column col-xs-12 col-sm-6 col-md-6">';
	if($hasil['thumb']!='') {
		$f = explode(":",$hasil['thumb']);
		$class_shop = (count($f) > 1) ? "shop" : "shopx";
		$content .= '<div id="shop-single" class="'.$class_shop.' shop-single">';
						// <!--<div class="container">-->
		$content .= '<div class="row">';
		$content .= '<div class="col-md-12">';
		$content .= '<div class="shop-content-item" itemscope itemtype="http://schema.org/Product">';
		$content .= '<div class="icon-product">'.$cisnew.$cisobral.$cispromo.$cissold.'</div>	<!-- /.icon-product -->';
								// <!-- Slider -->
		$content .= '<div class="shop-slider-container">';
		$content .= '<ul class="gallery" id="shop-slider">';
									
		$x=0;
		$graph_image = file_exists("$cfg_fullsizepics_path/$f[0]") ? "$cfg_fullsizepics_url/$f[0]" : "";
		foreach($f as $i => $im)
		{	
			$image_temp .= $i == 0 ? "$cfg_fullsizepics_url/$im" : "";
			$x++;
			$content .= '<li class="zoom"><img alt="" src="'.$cfg_fullsizepics_url.'/'.$im.'" /></li>';
		}
		$content .= '</ul>';
		$content .= '<div class="bottom-border"></div>';
		$content .= '<div class="shop-slider-pager" id="shop-slider-pager">';
		if($x > 1) {	
			$index = 0;
			foreach($f as $i => $im) {
				$content .= '<a data-slide-index="'.$index.'" href="#"><img alt="" src="'.$cfg_fullsizepics_url.'/'.$im.'" /></a>';
				$index++;
			}
		}
		$content .= '</div>	<!-- /#shop-slider-pager -->';
		$content .= '</div> <!-- /.shop-slider-container -->';
		$content .= '</div> <!-- /.shop-content-item -->';
		$content .= '</div> <!-- /.col-md-12 -->';
		$content .= '</div>	<!-- /.row -->';
		$content .= '</div> <!-- /#shop-single -->';
	}

	$content .= '</div>	<!-- /.pb-left-column -->';
	
	$brand_name = get_brand_name($hasil['idmerek']);
	$cat_name = get_cat_name($hasil['cat_id']);
	$brand_id = $hasil['idmerek'];
	$titleurl = array();
	$titleurl['merek'] = $brand_name;
	$content .= '<div class="pb-right-column col-xs-12 col-sm-6 col-md-6">';

	$content .= ($brand_name != '') ? '<div class="brand-name"><a href="'.$urlfunc->makePretty("?p=catalog&action=brand&merek=$brand_id", $titleurl).'">'.$brand_name.'</a></div>' : '';
	
	
	/////////attribut tambahan
	$r_attribut=unserialize($hasil['attribut_tambahan']);
	$atm=$mysql->query("SELECT id,nama,type FROM catalog_atm order by id");
	if($atm and $mysql->num_rows($atm)>0) {
		$x=0;
		$content_attribut="";
		while($datm=$mysql->fetch_assoc($atm)) {
			if($r_attribut[$datm['id']]!='') {
				if($x%2==0)	{$content_attribut.="<div class='attribut_tambahan_left'>";}else{$content_attribut.= "<div class='attribut_tambahan_right'>";}
				$content_attribut.= "<div class='attribut_label'>".$datm['nama']."</div><div class='attribut_value'>".$r_attribut[$datm['id']]."</div>";
				$content_attribut.="</div>";
				$x++;
			}
		}
	
	}
	if($content_attribut!='') {
		$content .= "<div id='description_product2'>$content_attribut</div>";	
	}
	/////////end attribut tambahan
	
	$content .= '<div class="price">';
				
	$hasil['diskon'] = str_replace(" ", "", $hasil['diskon']);
	$simpan = $hasil['diskon'];
	
	$content_value_pixel = 0;
	if ($hasil['harganormal'] > 0) {
		
		if ($_SESSION['member_nominal'] > 0) {
			
			$content .= '<div class="price-old" id="tHarga">'.$hasil['matauang'].' <span class="tAmount">'.number_format($hasil['harganormal'], 0, ',', '.').'</span></div>	<!-- /#tHarga -->';
			
			if ($_SESSION['member_type'] == 1) {
				$nominal = $_SESSION['member_nominal'];
				$disc = $hasil['harganormal'] * $nominal/100;
				$label_disc = str_replace("%", "", $nominal) . " %";
				$harga_disc = $hasil['harganormal'] - $disc;
				
				$content_value_pixel = $harga_disc;
				$content .= '<div class="price-now" id="tHargaDiskon">'.$hasil['matauang'].' <span class="tAmount">'.number_format($harga_disc, 0, ',', '.').'</span></div>	<!-- /#tHargaDiskon -->';
				$content .= '<span class="reduction"><span>-'.$label_disc.'</span></span>';
			}
		} else {
			
			if ($hasil['diskon'] != '') {
				$content .= '<div class="price-old" id="tHarga">'.$hasil['matauang'].' <span class="tAmount">'.number_format($hasil['harganormal'], 0, ',', '.').'</span></div>	<!-- /#tHarga -->';
				$content .= '<div class="price-now" id="tHargaDiskon">'.$hasil['matauang'].' <span class="tHargaDiskon">'.number_format($hasil['hargadiskon'], 0, ',', '.').'</span></div>	<!-- /#tHargaDiskon -->';
				$harga = $hasil['hargadiskon'];
				if (substr_count($hasil['diskon'], "%") == 1) {
					$label_disc = $hasil['diskon'];
				} else {
					$label_disc = $hasil['matauang'].' '.number_format($hasil['diskon'], 0, ',', '.');
				}
				$content .= '<span class="reduction"><span>-'.$label_disc.'</span></span>';
			} else {
				$harga = $hasil['harganormal'];
				$content .= '<span class="price-now" id="tHarga">'.$hasil['matauang'].' <span class="tAmount">'.number_format($hasil['harganormal'], 0, ',', '.').'</span></span>	<!-- /#tHarga -->';
			}
			
			$content_value_pixel = $hasil['harganormal'];
		}
		if ($hasil['keterangan'] != '')
		{
			$content .= '<div id="description_product">'.$hasil['keterangan'].'</div>';
			$content .= '<hr>';
		}
		if ($pakaicart and !$hasil['issold']) {
			$content .= '<form class="form_beli" id="form_beli'.$hasil['id'].'" action="'.$urlfunc->makePretty("?p=cart&action=add").'" method="POST">';
			
			if ($pakaicatalogattribut) {
				
				$where_group = ($catalog_attribute_option == 0) ? " GROUP BY size" : "";
				$sql = "SELECT id, size, color, hexa, price FROM catalogattribut WHERE catalog_id='$pid' $where_group ORDER BY urutan";
				if ($result = $mysql->query($sql)) {
					
					$param_sizes = array();
					$param_colors = array();
					$param_catalogattribut = array();
					while($row = $mysql->fetch_assoc($result)) {
						$param_sizes[] = $row['size'];
						$param_colors[] = $row['color'];
						// if ($row['color'] != '' || $row['hexa'] != '') {
							// $param_colors[$row['size']] = array('value' => $row['color'], 'hex' => $row['hexa']);
						// }
						
						$param_catalogattribut[] = array('size' => $row['size'], 'color' => $row['color'], 'hexa' => $row['hexa'], 'price' => $row['price']);
					}
					
					
					if (count($param_catalogattribut) > 0) {
						// $content .= '<div id="catalog-size" class="product_attributes">';
						// $content .= '<label>Ukuran</label>';
						// $content .= '<div id="param-size-result"></div>';
						// $content .= '<div class="input-colom">';
						// $content .= '<select id="param-size" name="param-size" data-id="'.$pid.'">';
						// $content .= '<option value="">Pilih</option>';
						// foreach($param_sizes as $size) {
							// $content .= '<option value="'.$size.'">'.$size.'</option>';
						// }
						// $content .= '</select>';
						// $content .= '</div>';
						// $content .= '</div>';
						
						
						// $content .= '<div id="catalog-color" class="product_attributes">';
						// $content .= '<label>Warna</label>';
						// $content .= '<div id="param-color-result">'.$param_colors[$param_sizes[0]]['value'].'</div>';
						// $content .= '<div class="input-colom">';
						// $content .= '<select id="param-color" name="param-color">';
						// $content .= '<option value="">Pilih</option>';
						// // foreach($param_sizes as $size) {
							// // $content .= '<option value="'.$size.'">'.$size.'</option>';
						// // }
						// $content .= '</select>';
						// $content .= '</div>';
						// $content .= '</div>';
						
						if ($catalog_attribute_option == 1) {
							$content .= '<div id="catalog-size" class="product_attributes">';
							$content .= '<label>Ukuran</label>';
							$content .= '<div id="param-size-result"></div>';
							$content .= '<div class="input-colom">';
							$content .= '<select id="param-size" name="param-size" data-id="'.$pid.'">';
							$content .= '<option value="">Pilih</option>';
							foreach($param_sizes as $size) {
								$content .= '<option value="'.$size.'">'.$size.'</option>';
							}
							$content .= '</select>';
							$content .= '</div>';
							$content .= '</div>';
						}
						if ($catalog_attribute_option == 2) {
							$content .= '<div id="catalog-color" class="product_attributes">';
							$content .= '<label>Warna</label>';
							// $content .= '<div id="param-color-result">'.$param_colors[$param_sizes[0]]['value'].'</div>';
							$content .= '<div class="input-colom">';
							$content .= '<select id="param-color" name="param-color" data-id="'.$pid.'">';
							$content .= '<option value="||'.number_format($harga, 0, ',', '.').'|'.$harga.'">Pilih</option>';
							// foreach($param_colors as $color) {
								// $content .= '<option value="'.$color.'">'.$color.'</option>';
							// }
							$html .= "<option value=\"".$row['id']."|{$row['color']}|$amount|{$row['price']}|{$row['size']}\">".$row['color']." (+".number_format($row['price'], 0, ',', '.').")</option>";
							
							foreach($param_colors as $color) {
								$get_product_attr = get_product_attr($pid, $color);
								$product_attr = join('|', $get_product_attr);
								$content .= '<option value="'.$product_attr.'">'.$color.'</option>';
							}
							$content .= '</select>';
							$content .= '</div>';
							$content .= '</div>';
						}
						
						// $content .= '<div id="catalog-size" class="product_attributes">';
						// $content .= '<label>Ukuran</label>';
						// $content .= '<div id="param-size-result"></div>';
						// $content .= '<div class="input-colom">';
						// $content .= '<select id="param-size" name="param-size" data-id="'.$pid.'">';
						// $content .= '<option value="">Pilih</option>';
						// foreach($param_sizes as $size) {
							// $content .= '<option value="'.$size.'">'.$size.'</option>';
						// }
						// $content .= '</select>';
						// $content .= '</div>';
						// $content .= '</div>';
						
						
						
					}
					
				}
			}
						
			$content .= '<div class="product_attributes">';
			$content .= '<label>'._QUANTITY.'</label>';
					// <!--<input type="number" min="1" name="qty" class="text" id="quantity_wanted" value="1">-->';
			$content .= '<div class="input-colom"><a id="qtyminus" class="qty-trigger qty-trigger-detail" data-id="'.$pid.'">-</a>';
			if ($pakaistock) {
				$data_max =  'data-max="'.get_item_stock($pid).'"';
			}
			
			$content .= '<input type="hidden" name="qty" id="quantity_wanted_'.$pid.'" class="cart_quantity_input form-control grey" data-min="1" '.$data_max.' value="1">';
			$content .= '<span id="lblcart_quantity_input_detail_'.$pid.'" class="lblcart_quantity_input">1</span>';
			$content .= '<a id="qtyplus" class="qty-trigger qty-trigger-detail" data-id="'.$pid.'">+</a>';
			$content .= '<input type="hidden" id="product_id" name="pid" value="'.$pid.'" />';
			
			if ($pakaicatalogattribut || $pakaifbpixel) {
				$content .= '<input type="hidden" id="content_name" name="content_name" value="'.$title.'" />';
				$content .= '<input type="hidden" id="content_category" name="content_category" value="'.$cat_name.'" />';
				$content .= '<input type="hidden" id="content_ids" name="content_ids" value="'.$pid.'" />';
				$content .= '<input type="hidden" id="value" name="value" value="'.$content_value_pixel.'" />';
			}
			$content .= '</div>';
			$content .= '</div>';
			
			if ($hasil['is_notes'] != '') {
				$content .= '
					<div id="notes" class="product_attributes">
						<label>'._NOTES.'</label>
						<div><textarea name="is_notes" cols="60" rows="5"></textarea></div>
					</div>
				';
			}

			$content .= '<div class="cart-button button-group">';
			$content .= '<button id="addToCartButton" class="btn btn-cart" type="submit">';
			$content .= _ADDTOCART;
			$content .= '<i class="fa fa-shopping-cart"></i>';
			$content .= '</button>';
			$content .= '</div>	<!-- /.cart-button -->';
			$content .= '</form>	<!-- /#form_beli -->';
			
			if ($pakaifbpixel) {
				// <!-- Facebook Pixel Code -->
				$params['content_name'] = $title;
				$params['content_category'] = $cat_name;
				$params['content_ids'] = "['$pid']";
				$params['content_type'] = 'product';
				$params['value'] = $content_value_pixel;
				$params['currency'] = 'IDR';
				$params['referrer'] = 'document.referrer';
				$params['userAgent'] = 'navigator.userAgent';
				$params['language'] = 'navigator.language';
					
				$fb_param_pixels = json_encode($params);
				
				// <!-- Facebook Pixel Code -->
				$content .= <<<SCRIPT
				<script>
					fbq('track', 'ViewContent', $fb_param_pixels);			
					
				</script>
SCRIPT;
			}

		} else {
			$content .= '<div class="notavailable"></div>';
		}
	}
	// Social Media link
	if ($pakaimarketplaceurl) {
		if ($hasil['tokopedia'] != '') {
			$content .= '<h3>Also Available at</h3>';
		}
		$content .= '<ul class="social-media">';
		if ($hasil['tokopedia'] != '') {
			$content .= '<li class="social-media-item tokopedia"><a href="'.$hasil['tokopedia'].'" target="_blank"><img src="'.$cfg_app_url.'/images/toped.png" alt="Tokopedia"></a></li>';
		}
		if ($hasil['bukalapak'] != '') {
			$content .= '<li class="social-media-item bukalapak"><a href="'.$hasil['bukalapak'].'" target="_blank"><img src="'.$cfg_app_url.'/images/bukalapak.png" alt="Bukalapak"></a></li>';
		}
		if ($hasil['lazada'] != '') {
			$content .= '<li class="social-media-item lazada"><a href="'.$hasil['lazada'].'" target="_blank"><img src="'.$cfg_app_url.'/images/lazada.png" alt="Lazada"></a></li>';
		}
		if ($hasil['shopee'] != '') {
			$content .= '<li class="social-media-item shopee"><a href="'.$hasil['shopee'].'" target="_blank"><img src="'.$cfg_app_url.'/images/shopee.jpg" alt="Shopee"></a></li>';
		}
		$content .= '</ul>';
	}
	$content .= '</div>	<!-- /.price -->';
	$content .= '</div>	<!-- /.price -->';
	$content .= '</div>	<!-- /.price -->';
	
	
//end of additional code here

#region Schema Product
// $titleurl['pid'] = $hasil['title'];
// $titleurl['cat_id'] = get_cat_name($hasil['cat_id']);
// $harganormal = number_format($hasil['harganormal'], 0, ',', '.');

// $url = $urlfunc->makePretty("?p=catalog&action=detail&pid=$pid&cat_id=$cat_id", $titleurl);
// $schema = <<<SCHEMA
// <script type="application/ld+json">
// {
  // "@context": "http://schema.org",
  // "@type": "Product",
  // "image": "{$image_temp}",
  // "url": "{$url}",
  // "name": "{$hasil['keterangan']}",
  // "offers": {
    // "@type": "AggregateOffer",
    // "price": "{$harganormal}",
    // "priceCurrency": "{$hasil['matauang']}"
  // }
// }
// </script>
// SCHEMA;
// echo $schema;
#endregion Schema Product
	$content .="
	<script>
	</script>
	";
}
	
//$catmenu --> ada, tapi tidak ditampilkan agar tampilan lebih rapi
$content .= ( $catthumb != '' ) ? $catthumb : "";
$content .= ( $catnav != '') ? "<div class=\"catnav\">" . _CATEGORY . ": $catnav</div><br />\r\n" : "";
$content .= ( $catdesc != '') ? "<div class=\"catdesc\">$catdesc</div>\r\n" : "";
$content .= ( $catsubcat != '') ? "<div class=\"catsubcat\">$catsubcat</div>\r\n" : "";
$content .= ( $sortbyorder != '') ? "<div class=\"sortbyorder\">$sortbyorder</div>\r\n" : "";
$content .= ( $catpage != '') ? "<div class=\"catpage\">$catpage</div>\r\n" : "";
$content .= $catcontent;

?>

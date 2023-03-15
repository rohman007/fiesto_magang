<?php

function is_really_int(&$val) {
    $num = (int) $val;
    if ($val == $num) {
        $val = $num;
        return true;
    }
    return false;
}

function bbpush($id)
{
	// APP ID provided by RIM
	$appid = '803-1nOne8820M8cBc88t1';
	// Password provided by RIM
	$password = 'dmEY4wdn';
	// Application device port
	$appport = 29941;
//transaction_id 	autoid 	tanggal_buka_transaksi 	tanggal_tutup_transaksi 	customer_id 	receiver_id 	metode_pembayaran 	ongkir 	status 	catatan 	no_pengiriman 	latestupdate 	customer_id 	web_user_id 	nama_lengkap 	email 	alamat 	alamat2 	kota 	provinsi 	id_country 	zip 	telepon 	cellphone1 	cellphone2 
	try {
		$query=mysql_query("select * from sc_transaction t,sc_customer c,webmember w where 	c.customer_id=t.customer_id AND w.user_id=c.web_user_id AND t.transaction_id='$id'");
		$row=mysql_fetch_array($query);
		
		$message=$row['tanggal_buka_transaksi']."+".$row['fullname']."+".$row['status']."+".$row['transaction_id']."+";

		//Deliver before timestamp
		$deliverbefore = gmdate('Y-m-d\TH:i:s\Z', strtotime('+10 minutes'));

		// An array of address must be in PIN format or "push_all"
		// Format = WAPPUSH=PIN%3APORT/TYPE=USER@rim.com

		/* $pinquery=mysql_query("select * from webmember where not bbpin='******'");
		
		$i=0;
		while($rowpin = mysql_fetch_array($pinquery))
		{
			$addresstosendto[$i]=$rowpin['bbpin'];
		} */
		// ini okee
		//$addresstosendto[] = '262990c5';
		$addresses = '<address address-value="' . $row['bbpin'] . '" />';
		/* foreach ($addresstosendto as $value) {
			$addresses .= '<address address-value="' . $value . '" />';
		} */
		//atau pakai ini
		//$addresses = "<address address-value='$pin' />";
		
		// create a new cURL resource
		$err = false;
		$ch = curl_init();
		$messageid = microtime(true);

		$data = '--asdwewe. "\r\n" '.
		'Content-Type: application/xml; charset=UTF-8' . "\r\n\r\n" .
		'<?xml version="1.0"?>
		<!DOCTYPE pap PUBLIC "-//WAPFORUM//DTD PAP 2.1//EN" "http://www.openmobilealliance.org/tech/DTD/pap_2.1.dtd">
		<pap>
		<push-message push-id="' . $messageid . '" deliver-before-timestamp="' . $deliverbefore . '" source-reference="' . $appid . '">'
		. $addresses .
		'<quality-of-service delivery-method="unconfirmed"/>
		</push-message>
		</pap>' . "\r\n" .
		'--asdwewe' . "\r\n" .
		'Content-Type: text/plain' . "\r\n" .
                //'Content-Encoding: binary'. "\r\n".
		'Push-Message-ID: ' . $messageid . "\r\n\r\n" .
		stripslashes($message) . "\r\n" .
		'--asdwewe--' . "\r\n";

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, "https://cp803.pushapi.na.blackberry.com//mss/PD_pushRequest");
		curl_setopt($ch, CURLOPT_PORT , 443);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_CAINFO, getcwd()."\cacert.pem");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, "My BB Push Server\1.0");
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $appid . ':' . $password);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$__extra_Headers = array(
			"Content-Type: multipart/related; boundary=asdwewe; type=application/xml",
			"Accept: text/html, image/gif, image/jpeg, *; q=.2, */*; q=.2",
			"Connection: keep-alive",
			"X-Rim-Push-Dest-Port: ".$appport,
            "X-RIM-PUSH-ID: ".$messageid,
            "X-RIM-Push-Reliability-Mode: APPLICATION"
        );
		curl_setopt($ch, CURLOPT_HTTPHEADER, $__extra_Headers);


		// grab URL and pass it to the browser
		$xmldata = curl_exec($ch);
		/*if($xmldata === false){
			echo 'Error pada CURL : ' . curl_error($ch)."\n";
		}else{
			echo 'Operasi push berhasil'."\n";
		}*/


		// close cURL resource, and free up system resources
		curl_close($ch);

		//Start parsing response into XML data that we can read and output
		$p = xml_parser_create();
		xml_parse_into_struct($p, $xmldata, $vals);
		$errorcode = xml_get_error_code($p);
		if ($errorcode > 0) {
			//echo xml_error_string($errorcode)."\n";
			$err = true;
		}
		xml_parser_free($p);

		/*echo 'Our PUSH-ID: ' . $messageid . "<br \>\n";
		if (!$err && $vals[1]['tag'] == 'PUSH-RESPONSE') {
			echo 'PUSH-ID: ' . $vals[1]['attributes']['PUSH-ID'] . "<br \>\n";
			echo 'REPLY-TIME: ' . $vals[1]['attributes']['REPLY-TIME'] . "<br \>\n";
			echo 'Response CODE: ' . $vals[2]['attributes']['CODE'] . "<br \>\n";
			echo 'Response DESC: ' . $vals[2]['attributes']['DESC'] . "<br \> \n";
		} else {
			echo '<p>An error has occured</p>' . "\n";
			echo 'Error CODE: ' . $vals[1]['attributes']['CODE'] . "<br \>\n";
			echo 'Error DESC: ' . $vals[1]['attributes']['DESC'] . "<br \>\n";
		}*/


	} catch (Exception $e) {
		//var_dump($e->getMessage());
	}

	//exit();

}

function leastcatoption($cat_id="") {
    global $catmenu, $topcatnamecombo;
    
    $cats = new categories();
    $mycats = array();
    $sql = 'SELECT id, nama, parent FROM catalogcat';
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'level' => 0);
    }
    $cats->get_cats($mycats);

    $catmenu .= "<option value=\"\">----- "._CATEGORY." -----</option>\n";
    for ($i = 0; $i < count($cats->cats); $i++) {
		$sql1 = "SELECT id, nama FROM catalogcat WHERE parent='".$cats->cats[$i]['id']."'";
		$result1 = mysql_query($sql1);
		if (mysql_num_rows($result1)==0) {	//ujung (tidak punya parent)
			$cats->cat_map($cats->cats[$i]['id'], $mycats);
			$cat_name = $cats->cats[$i]['nama'];
			$catmenu .= '<option value="' . $cats->cats[$i]['id'] . '"';
			$catmenu .= ( $cats->cats[$i]['id'] == $cat_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
			for ($a = 0; $a < count($cats->cat_map); $a++) {
				$cat_parent_id = $cats->cat_map[$a]['id'];
				$cat_parent_name = $cats->cat_map[$a]['nama'];
				$catmenu .= " / $cat_parent_name";
			}
			$catmenu .= " / $cat_name</option>\r\n";
		}
    }

    $catmenu .= "</select>\r\n";
    return $catmenu;
}

function show_me_the_images($isadmin=FALSE) {
    global $action, $cfg_per_page, $cfg_max_cols, $urlfunc, $pakaicart;
    global $cfg_thumb_width, $sql, $cfg_thumb_path, $cfg_thumb_url, $imagefolder;
    global $screen, $pages, $photo, $cat_id, $keyword, $merek, $random, $cfg_app_url, $ismobile,$mainblockwidth;
	//romli : ambil variable dari template urasi
	global $class_product_row;
	global $class_product_item;
	
	global $catnav_pixel, $catname;
	/////////////
	$x = 1;
	ob_start();
	$jum_foto = mysql_num_rows($photo);
	
	// // Filter
	
	// $q = "SELECT nama FROM catalogfilterdata ORDER BY nama";
	// $res = mysql_query($q);
	// echo '<div id="filters" class="button-group">';
	// echo '<button class="button is-checked" data-filter="*">show all</button>';
	// while($row = mysql_fetch_assoc($res)) {
		// echo '<button class="button" data-filter=".'.strtolower($row['nama']).'">'.$row['nama'].'</button>';
	// }
	// echo '</div>';

	// // End of filter 
	
	$content_ids = '';
	$content_value = 0;
	$catcontent .= "<div class=\"row thumbnail-product grid\">";
	while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangansingkat, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc,$isbest,$ispromo,$isnew,$issold,$is_filter,$brand_id) = mysql_fetch_row($photo)) {
		
		// $sql = "SELECT nama FROM catalogfilterdata WHERE FIND_IN_SET(id, '$is_filter')";
		// $result = mysql_query($sql);
		
		// $gridname = '';
		// while(list($grid_name) = mysql_fetch_row($result)) {
			// $gridname .= ' '. strtolower($grid_name);
		// }
		
		// if ($x > 1) [
			// $content_ids .= "$photo_id, ";
		// ]
		$brand_name = get_brand_name($brand_id);
		
		$isdiskon=0;
		$lebar = $mainblockwidth / $cfg_max_cols;
		if ($diskon != '') {
			$isdiskon=1;
			if (substr_count($diskon, "%") == 1) {
				$label_disc = str_replace("%", "", $diskon) . " %";
			} else {
				$label_disc = $matauang . ' ' . number_format($diskon, 0, ',', '.');
			}
		}
		
		if ($_SESSION['member_nominal'] > 0) {
			$isdiskon=1;
			if ($_SESSION['member_type'] == 1) {
				$nominal = $_SESSION['member_nominal'];
				$disc = $harganormal * $nominal/100;
				$label_disc = str_replace("%", "", $nominal) . " %";
				$harga_disc = $harganormal - $disc;
			}
		}
		
		$cisdiskon=$isdiskon==1?"<span class='reduction'><span>-".$label_disc."</span></span>":"";
		$cispromo=$ispromo==1?"<span class='reduction'><span>Promo</span></span>":"";
		$cisnew=$isnew==1?"<div class='new'></div>":"";
		$cissold=$issold==1?"<div class='sold_out'><span></span></div>":"";
		$cisbest=$isbest==1?"<span class='best'><span>Best</span></span>":"";
		
        //Junaidi: Bugfix agar selalu ambil dari direktori thumb jika ada thumbnail
        $tempthumb = $cfg_thumb_url;
        if (file_exists($cfg_thumb_path . "/" . $photo_filename) && $photo_filename <> '') {
            $image_stats = getimagesize($cfg_thumb_path . "/" . $photo_filename);
            $new_w = $image_stats[0];
            $new_h = $image_stats[1];
            $if_thumb = "yes";
        } elseif ((!file_exists($cfg_thumb_path . "/" . $photo_filename)) && (file_exists($cfg_fullsizepics_path . "/" . $photo_filename))) {
            $image_stats = getimagesize($cfg_fullsizepics_path . "/" . $photo_filename);
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
        } elseif ((!file_exists($cfg_thumb_path . "/" . $photo_filename)) && (!file_exists($cfg_fullsizepics_url . "/" . $photo_filename))) {
            $photo_filename = $noimage_filename;
        } // end if file exists		

        $titleurl = array();
        $titleurl["pid"] = $photo_title;
        $titleurl["merek"] = $brand_name;

        if ($action == 'search')
            $actiondependent = "keyword=$keyword";
        if ($action == 'images') {
            $actiondependent = "cat_id=$cat_id";
            $sqlcatname = "SELECT nama FROM catalogcat WHERE id='$cat_id' ";
            $resultcatname = mysql_query($sqlcatname);
            list($catname) = mysql_fetch_row($resultcatname);
            $titleurl["cat_id"] = $catname;
            mysql_free_result($resultcatname);
        }
        if ($action == 'brand') {
            $actiondependent = "merek=$merek";
            $sqlmerekname = "SELECT nama FROM catalogmerek WHERE id='$merek' ";
            $resultmerekname = mysql_query($sqlmerekname);
            list($merekname) = mysql_fetch_row($resultmerekname);
            $titleurl["merek"] = $merekname;
            mysql_free_result($resultmerekname);
        }

		
        if ($isadmin) {
            $catcontent .= "<a href=\"?p=catalog&action=edit&pid=$photo_id&$actiondependent\">";
            if ($photo_filename != '') {
                $catcontent .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" height=\"$new_h\" width=\"$new_w\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\">";
            } else {
                $catcontent .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\">";
			}
            $catcontent .= "</a>\r\n";
            $catcontent .= "<div class=\"catpublishis$photo_published\" align=\"center\"><div class=\"cathotis$photo_ishot\">$photo_title</div></div>";
        } else {
			
			//additional code
			$catcontent .= '<div class="col-sm-3 element-item '.$gridname.'">';
			$catcontent .= '<div class="product-col">';
			$catcontent .= '<div class="image-product">';
			if ($photo_filename != '' && file_exists("$cfg_thumb_path/$photo_filename")) {
				$catcontent .= '<a href="' . $urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&$actiondependent", $titleurl) . '">';
				$catcontent .= '<img class="img-responsive" src="'.$cfg_thumb_url.'/'.$photo_filename.'" alt="'.$photo_title.'" title="'.$photo_title.'">';
				$catcontent .= '</a>';
			} else {
				$catcontent .= '<img class="img-responsive" src="'.$cfg_app_url.'"/images/none.gif" alt="None">';
			}
			
			$catcontent .= '</div>	<!-- /.image-product -->';
			
			$catcontent .= '<div class="caption">';
			$catcontent .= '<div class="title-product">';
			$catcontent .= '<h4><a href="'.$urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&$actiondependent", $titleurl).'">'.$photo_title.'</a></h4>';
			$catcontent .= '</div>';
			$catcontent .= (($brand_name != '') ? "<div class=\"brand\"><a href=\"".$urlfunc->makePretty("?p=catalog&action=brand&merek=$brand_id", $titleurl)."\">$brand_name</a></div>" : "");
			
			if ($harganormal > 0) {
				$catcontent .= '<div class="price">';
				if($cisdiskon) {
					$catcontent .= '<div class="price-old">'.$matauang. ' '.number_format($harganormal, 0, ',', '.').'</div>';
					$catcontent .= '<div class="price-now">'.$matauang. ' '.number_format($harga_disc, 0, ',', '.').'</div>'. $cisdiskon.' ';
				} else {
					$catcontent .= '<span class="price-now">'.$matauang. ' '.number_format($harganormal, 0, ',', '.').'</span>';
				}
				$catcontent .= '</div>	<!-- /.price -->';
				
				$content_value += $harga_disc;
				// if ($pakaicart && !$issold) {
					// $catcontent .= '<form class="form_beli" id="form_beli'.$hasil['id'].'" action="'.$urlfunc->makePretty("?p=cart&action=add").'" method="POST">';
					// $catcontent .= '<input type="hidden" name="pid" value="'.$photo_id.'" />';
					// $catcontent .= '<input type="hidden" name="qty" value="1" />';
					// $catcontent .= '<div class="cart-button button-group">';
					// $catcontent .= '<button class="btn btn-cart" type="submit">Beli';
					// $catcontent .= '<i class="fa fa-shopping-cart"></i>';
					// $catcontent .= '</button>';
					// $catcontent .= '</div>	<!-- /.cart-button -->';
					// $catcontent .= '</form>';
				// }
			}
			$catcontent .= '</div>	<!-- /.caption -->';
			$catcontent .= '<div class="icon-product">'.$cisnew.$cissold.$cisbest.'</div>';
							
			$catcontent .= '</div>	<!-- /.product-col -->';
			$catcontent .= '</div> <!-- /.element-item -->';
			//end of additional code

			
            //START ADD TO CATALOG
            /* if ($pakaicart and false) {
                $catcontent .= "<div  class=\"addtocart\">";
                $catcontent .= "<form action=\"".$urlfunc->makePretty("?p=cart&action=add")."\" method=\"POST\">";
                $catcontent .= "<input type=\"hidden\" name=\"pid\" value=\"$photo_id\" />";
//                $catcontent .= "<input type=\"text\" name=\"qty\" id=\"qty\" size=\"2\"/>";
                $catcontent .= "<div><input type=\"submit\" id=\"addtocartbutton\" value=\"" . _ADDTOCART . "\" /></div>";
                $catcontent .= "</form>";
                $catcontent .= "</div>";
            } */
            //END ADD TO CATALOG
        }
        //Junaidi: Bugfix agar selalu ambil dari direktori thumb jika ada thumbnail
        $cfg_thumb_url = $tempthumb;
        // if we're in admin mode, print out the admin links to edit, delete, etc, and whether
        // or not it's a published image
        if ($isadmin) {
            $catcontent .= "<p>[<a href=\"?p=catalog&pid=$photo_id&action=edit&cat_id=$photo_cat_id&keyword=$keyword&screen=$screen\">"._EDIT."</a>] [<a href=\"?p=catalog&pid=$photo_id&cat_id=$photo_cat_id&screen=$screen&action=delete\">"._DEL."</a>]</p>";
        }
        $x++;
    }
	$catcontent .= "</div>";
	
	// <!-- Facebook Pixel Code -->
	$params['content_name'] = $catname;
	$params['content_category'] = $catnav_pixel;
	// $params['content_ids'] = $content_ids;
	// $params['content_type'] = 'product_group';
	$params['value'] = $content_value;
	$params['currency'] = 'IDR';
	// $params['referrer'] = 'document.referrer';
	// $params['userAgent'] = 'navigator.userAgent';
	// $params['language'] = 'navigator.language';
		
	$pb_param_pixels = json_encode($params);
	$catcontent .= <<<HTML
	<script>
	  fbq('track', 'ViewContent', $pb_param_pixels);
	</script>
HTML;
	
	echo $catcontent;
    return ob_get_clean();
}

function show_me_the_list() {
    global $action, $cfg_per_page, $cfg_max_cols, $urlfunc, $pakaicart;
    global $cfg_thumb_width, $sql, $cfg_thumb_path, $cfg_thumb_url, $imagefolder;
    global $screen, $pages, $photo, $cat_id, $keyword, $merek, $random, $cfg_app_url;

   
    while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangan, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc) = mysql_fetch_row($photo)) {
        $titleurl = array();

        $titleurl["pid"] = $photo_title;
		
        $sqlcatname = "SELECT nama FROM catalogcat WHERE id='$photo_cat_id' ";
        $resultcatname = mysql_query($sqlcatname);
        list($catname) = mysql_fetch_row($resultcatname);
        $titleurl["cat_id"] = $catname;
        mysql_free_result($resultcatname);
		$catcontent .= "<div class=\"productsummary\" itemscope  itemtype=\"http://schema.org/Product\">";
        $catcontent .= "<div class=\"pstitle\" itemprop=\"name\"><h1><a itemprop=\"url\" href=\"" . $urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&cat_id=$photo_cat_id", $titleurl) . "\">$photo_title</a></h1></div>\r\n";

        $catcontent .= "<div class=\"psphoto\"><a href=\"" . $urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&cat_id=$photo_cat_id", $titleurl) . "\">";
        if ($photo_filename != '') {
            $catcontent .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\">";
        }else
			$catcontent .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\">";
        $catcontent .= "</a></div>\r\n";
		
		$catcontent .= "<div class=\"pssummary\">";
		$catcontent .= "<div class=\"pssummary-detail\" itemprop=\"description\">$keterangan</div>\r\n";
        $diskon = str_replace(" ", "", $diskon);
        if ($harganormal > 0) {
            if ($diskon == '') {
                $kelasnormal = 'normalwodisc';
            } else {
                $kelasnormal = 'normalwdisc';
            }
            $catcontent .= "<div class=\"harga\" itemprop=\"offers\" itemscope itemtype=\"http://schema.org/Offer\">" . _PRICE . ": <span class=\"$kelasnormal\">$matauang " . number_format($harganormal, 0, ',', '.') . "</span>";
            if ($diskon != '') {
                if (substr_count($diskon, "%") == 1) {
                    $label_disc = str_replace("%", "", $diskon) . "%";
                } else {
                    $label_disc = $matauang . ' ' . number_format($diskon, 0, ',', '.');
                }
                $catcontent .= " <span class=\"discprice\" itemprop=\"price\">$matauang " . number_format($harga_disc, 0, ',', '.') . "</span>";
                $catcontent .= " <span class=\"discvalue\">(" . _YOUSAVE . " " . $label_disc . ")</span>";
            }
            //add to catalog start
            //TO DO
            if ($pakaicart) {

                $catcontent .= "<div class=\"addtocart\">";
                $catcontent .= "<form action=\"".$urlfunc->makePretty("?p=cart&action=add")."\" method=\"POST\">";
                $catcontent .= "<input type=\"hidden\" name=\"pid\" value=\"$photo_id\" />";
//                $catcontent .= "<input type=\"text\" name=\"qty\" id=\"qty\" size=\"2\"/>";
                $catcontent .= "<input type=\"submit\" value=\"" . _ADDTOCART . "\" />";
                $catcontent .= "</form>";
                $catcontent .= "</div>";
                //add to catalog end
            }
            $catcontent .= "</div>\r\n";
        }
		$catcontent .= "</div>\r\n";
        $catcontent .= "<div class=\"psseparator\"></div>\r\n";
		$catcontent .= "</div>\r\n";
    }
   
    return $catcontent;
}
function catstructure($sql)
{
global $urlfunc,$cats;
$cats = new categories();
$mycats = array();
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
$mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'level' => 0);
}
$cats->get_cats($mycats);
ob_start();
echo "<ol class=\"sortable ui-sortable\"class=\"sortable ui-sortable\">";
 for ($i = 0; $i < count($cats->cats); $i++) 
 {
	if($cats->cats[$i]['parent']==0)
	{
		$cat_id=$cats->cats[$i]['id'];
		echo "<li id='list_$cat_id' data-catid='$cat_id'>";
		echo "<div class=\"kat_item ui-state-default ui-draggable\"><div class=\"kat_nama\">".$cats->cats[$i]['nama']."</div>";
           echo "<div class=\"kat_action\"><a href=\"?p=catalog&action=catedit&cat_id=".$cats->cats[$i]['id']."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=catalog&action=catdel&cat_id=" . $cats->cats[$i]['id'] . "\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a>
			</div></div>";
		child($cats->cats[$i]['id']);
		echo "</li>";
	}
 }
echo "</ol>";

return ob_get_clean();
}
function child($id)
{
global $urlfunc,$cats;
	echo "<ol>";
	for ($i = 0; $i < count($cats->cats); $i++) 
 	{
		if($cats->cats[$i]['parent']==$id)
		{
		$cat_id=$cats->cats[$i]['id'];
		echo "<li id='list_$cat_id' data-catid='$cat_id'>";
		echo "<div class=\"kat_item ui-state-default ui-draggable\"><div class=\"kat_nama\">".$cats->cats[$i]['nama'] ."</div> ";
           echo "<div class=\"kat_action\"><a href=\"?p=catalog&action=catedit&cat_id=".$cats->cats[$i]['id']."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=catalog&action=catdel&cat_id=" . $cats->cats[$i]['id'] . "\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a>
			</div></div>";
		child($cats->cats[$i]['id']);
		echo "</li>";
		}
		
	}
	echo "</ol>";

}
function catstructure1($sql) {
    global $urlfunc;
    $cats = new categories();

    $mycats = array();
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'level' => 0);
    }
    $cats->get_cats($mycats);

    $currlevel = 1;
    $catcontent .= "<ol class=\"sortable ui-sortable\">\n";
    for ($i = 0; $i < count($cats->cats); $i++) {

        $titleurl = array();
        $titleurl["cat_id"] = $cats->cats[$i]['nama'];
		$cat_id=$cats->cats[$i]['id'];
        $selisihlevel = $cats->cats[$i]['level'] - $currlevel;
        if ($selisihlevel > 0) {
            $catcontent .= "<ol>\n";
            $catcontent .= "<li id='list_$cat_id' data-catid='$cat_id'><div class=\"kat_item\"><div class=\"kat_nama\"><a href=\"?p=catalog&action=images&cat_id=".$cats->cats[$i]['id']."\">".$cats->cats[$i]['nama'] . "</a></div> ";
           // $catcontent .= "<a href=\"?p=catalog&action=images&cat_id=".$cats->cats[$i]['id']."\"><img alt=\"". _OPEN."\" border=\"0\" src=\"../images/open.gif\"></a> ";
            $catcontent .= "<div class=\"kat_action\"><a href=\"?p=catalog&action=catedit&cat_id=".$cats->cats[$i]['id']."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=catalog&action=catdel&cat_id=" . $cats->cats[$i]['id'] . "\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a>
			</div></div>
			</li>\n";
        }
        if ($selisihlevel == 0) {
            $catcontent .= "<li id='list_$cat_id' data-catid='$cat_id'><div class=\"kat_item\"><div class=\"kat_nama\"><a href=\"?p=catalog&action=images&cat_id=".$cats->cats[$i]['id']."\">".$cats->cats[$i]['nama'] . "</a></div> ";
            //$catcontent .= "<a href=\"?p=catalog&action=images&cat_id=".$cats->cats[$i]['id']."\"><img alt=\"". _OPEN."\" border=\"0\" src=\"../images/open.gif\"></a> ";
            $catcontent .= "<div class=\"kat_action\"><a href=\"?p=catalog&action=catedit&cat_id=".$cats->cats[$i]['id']."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=catalog&action=catdel&cat_id=" . $cats->cats[$i]['id'] . "\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></div></div></li>\n";
        }
        if ($selisihlevel < 0) {
            for ($j = 0; $j < -$selisihlevel; $j++) {
                $catcontent .= "</ol>\n";
            }
            $catcontent .= "<li id='list_$cat_id' data-catid='$cat_id'><div class=\"kat_item\"><div class=\"kat_nama\"><a href=\"?p=catalog&action=images&cat_id=".$cats->cats[$i]['id']."\">".$cats->cats[$i]['nama']."</a></div> ";
            //$catcontent .= "<a href=\"?p=catalog&action=images&cat_id=".$cats->cats[$i]['id']."\"><img alt=\""._OPEN."\" border=\"0\" src=\"../images/open.gif\"></a> ";
				$catcontent .= "<div class=\"kat_action\"><a href=\"?p=catalog&action=catedit&cat_id=".$cats->cats[$i]['id']."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=catalog&action=catdel&cat_id=" . $cats->cats[$i]['id'] . "\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></div></div></li>\n";
        }
        $currlevel = $cats->cats[$i]['level'];
    }
    $catcontent .= "</ol>\n";
    return $catcontent;
}

function show_catalog_grid()
{

global $isi,$sort,$ismobile,$showtype,$urlfunc,$mainblockwidth,$cfg_max_cols,$cfg_thumb_url,$cfg_app_url,$cfg_fullsizepics_url,$pakaicart;
$cat_id=$_GET['cat_id'];
            $sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, d.ishot, d.ketsingkat, 
					d.harganormal, d.diskon, d.matauang, 
					if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),d.diskon) 
					as nilaidiskon,
					d.harganormal-(if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),
						d.diskon)) as hargadiskon,
					c.nama  
					FROM catalogdata d, catalogcat c WHERE  d.publish='1' AND c.id=d.cat_id ";
				if(strlen($cat_id)>0)
				{
				$sql.=" AND d.cat_id='$cat_id'";
				}
				if(strlen($isi)>0)
				{
				$sql.=" AND d.ishot='$isi' ";
				}
          
    $sql .= " ORDER BY $sort ";
    $result = mysql_query($sql);
	$jum_product=mysql_num_rows($result);
    if ($jum_product > 0) {

        //thumbnail only
	    if ($showtype == 0) 
		{	$x = 1;
			ob_start();
            echo '<ul id="og-grid" class="og-grid"> ';
            while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangansingkat, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc, $catname) = mysql_fetch_row($result)) 
			{
							
               $lebar = $mainblockwidth / $cfg_max_cols;
							
			echo "<li>";
														
				if(strlen($nilai_disc)==0)
				{
				$harganormal='';
				
				}
				else
				{
				$harganormal=number_format($harganormal, 0, ',', '.');
				}
				$harga_disc=number_format($harga_disc, 0, ',', '.');
                $titleurl = array();
                $titleurl["pid"] = $photo_title;
                $titleurl["cat_id"] = $catname;
				$keterangansingkat=strip_tags($keterangansingkat);
				//href="' . $urlfunc->makePretty('?p=catalog&action=viewimages&pid=' . $photo_id . '&cat_id=' . $photo_cat_id, $titleurl) . '"
               echo '<a data-largesrc="' . $cfg_fullsizepics_url . '/' . $photo_filename . '"
					data-title="'.$photo_title.'" 
					data-description="'.$keterangansingkat.'" 
					data-photoid="'. $photo_id .'"
					data-harga="'. $harganormal .'"
					data-harga_disc="'. $harga_disc .'"
					data-urlpost="'.$urlfunc->makePretty("?p=cart&action=add").'"
					href="' . $urlfunc->makePretty('?p=catalog&action=viewimages&pid=' . $photo_id . '&cat_id=' . $photo_cat_id, $titleurl) . '"
					>';
                if ($photo_filename != '') {
                   echo '<img  src="' . $cfg_thumb_url . '/' . $photo_filename . '" border="0" alt="' . $photo_title . '" class="photos">';
                } else {
                   echo '<img itemprop="image" src="' . $cfg_app_url . '/images/none.gif" border="0" class="photos">';
                }
               echo "</a>\r\n";
			echo "</li>";

         $x++;
    		}
			
			 
		echo "</ul>";	
		
		echo "
		
		<script src=\"$cfg_app_url/modul/catalog/js/grid.js\" ></script>
		<script>
		$(function()
		{
		Grid.init();
		}); 
		</script>";
		
		$home.=ob_get_clean();
		}
			
	
    } else {
        $home .= "<p>" . _NOPROD . "</p>";
    }
	return $home;
}
function save_attribut_tambahan($id)
{
$hasil=array();
$serialize="";
$res=mysql_query("SELECT id,nama,type FROM catalog_atm order by id");
if($res and mysql_num_rows($res)>0)
{
	while($d=mysql_fetch_assoc($res))
	{
		$d['nama']=strtolower($d['nama']);
		$d['nama'] = preg_replace('/\s+/','',$d['nama']);
		$hasil[$d['id']]=$_POST[$d['nama']];
	
	}
	$serialize=serialize($hasil);
}	


$q=mysql_query("UPDATE catalogdata set attribut_tambahan='$serialize' WHERE id=$id");

}


function attribut_tambahan($pid="")
{
if($pid!='')
{
$r_serial=mysql_query("SELECT attribut_tambahan FROM catalogdata WHERE id=$pid");
list($serialdata)=mysql_fetch_row($r_serial);
$r_attribut=unserialize($serialdata);
}
$res=mysql_query("SELECT id,nama,type FROM catalog_atm ORDER BY id");
if($res and mysql_num_rows($res)>0)
{
	while($d=mysql_fetch_assoc($res))
	{
		//$d['nama']=strtolower($d['nama']);
		$d['nama'] = preg_replace('/\s+/','',$d['nama']);
		$d['nama'] = strtolower($d['nama']);
			if($d['type']=="1")
			{
			$customfield[]=$d['nama'];
			$ketcustomfield[]=$d['nama'];
			$typecustom[]='VARCHAR';
			$paracustom[]='M';
			$paravalue[]=$r_attribut[$d['id']];
			//$paravalue[]=$r_attribut[$d['nama']];
			}
			if($d['type']=="2")
			{
				$data=array();
				$resd=mysql_query("SELECT id,id_atm,value FROM catalog_atd WHERE id_atm=".$d['id']." ORDER BY id");
				if($resd and mysql_num_rows($resd)>0)
				{
					
					while($e=mysql_fetch_assoc($resd))
					{
						$data[$e['id']]=$e['value'];
						//$data[$e['value']]=$e['value'];
					}
					$serialize=serialize($data);
					$customfield[]=$d['nama'];
					$ketcustomfield[]=$d['nama'];
					$typecustom[]='ENUM';
					$paracustom[]=$serialize;
					$paravalue[]=$r_attribut[$d['id']];
				//	$paravalue[]=$r_attribut[$d['nama']];
				}
				
			}
	}
	
	$additional = count($customfield);

	for ($i=0;$i<$additional;$i++) 
	{
	
	switch ($typecustom[$i]) {
		case 'VARCHAR': 
			echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$paravalue[$i].'" maxlength="'.$paracustom[$i].'"></div>'."\n";
			break;
		
		case 'ENUM': 
			$splitparameter=unserialize($paracustom[$i]);
			$j=0;
			echo "<div class='catalog_label'>".$ketcustomfield[$i]."</div><div><select name=\"".$customfield[$i]."\">\n";
			echo "<option value=''>"._PILIH."</option>";
			foreach ($splitparameter as $id => $pilihan) 
			{
				echo "<option value=\"$pilihan\"";
				if ($pilihan==$paravalue[$i]) echo " selected='selected'";
				echo " > $pilihan </option>\n";
			}
			echo "</select></div>\n";
			break;
	
		}
	}
}	
}

function sort_by($urlstatus = '', $sb = '') {
	global $sort_by;
	
	if (isset($_GET['screen']) && $_GET['screen'] != '') {
		$status = $_GET['screen'];
	}
	$data = '<label>'._SORTBY.'</label>';
	$data .= "<select name=\"sortbyorder\" onchange=\"window.location='$urlstatus'+this.value+'/$status'\">";
	foreach($sort_by as $i => $val)  {
		$selected = ($sb == $i) ? 'selected' : '';
		$data .= '<option value="'.$i.'" '.$selected.'>'.$val.'</option>';
	}
	$data .= '</select>';	
	return $data;
}
?>
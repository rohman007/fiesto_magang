<?php
// include "urasi.php";
if($mode_catalog==0) {
	$loadmore = "";
	if ($jenis == 'featured' || $jenis == 'category') {
		switch ($jenis) {
			case 'featured':
				$kondisi = "";
				if($isi != '') {
					$kondisi=str_replace(";"," OR ",$isi);
					$kondisi=str_replace(":","=",$kondisi);
					$kondisi=" ($kondisi) AND ";
				}
				// $sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, d.ishot, d.keterangan, 
						// d.harganormal, d.diskon, d.matauang, 
						// if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),d.diskon) 
						// as nilaidiskon,
						// d.harganormal-(if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),
							// d.diskon)) as hargadiskon,c.nama ,isbest,ispromo,isnew,issold
						 
						// FROM catalogdata d inner join catalogcat c ON  c.id=d.cat_id WHERE $kondisi d.publish='1' ";
				$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, 
					harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon,isbest,ispromo,isnew,issold,idmerek 
					FROM catalogdata WHERE $kondisi publish='1' ";
					
				break;
			case 'category':
				$loadmore="?p=catalog&action=images&cat_id=$isi";
				
				$kondisi = "";
				if($isi != '') {
					$kondisi=" d.cat_id=$isi AND ";
				}
				
				$limit = "";
				$q = mysql_query("SELECT value FROM config WHERE name='cfg_per_page_home' and modul='catalog'");
				if($q AND mysql_num_rows($q)>0) {
					list($max_list)=mysql_fetch_row($q);
					if($max_list!='')$limit="LIMIT $max_list ";
				}
				// $sql = "SELECT d.id, d.cat_id, d.filename, d.title, d.publish, d.ishot, d.keterangan, 
						// d.harganormal, d.diskon, d.matauang, 
						// if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),d.diskon) 
						// as nilaidiskon,
						// d.harganormal-(if(LOCATE('%',d.diskon)>0,(TRIM(REPLACE(d.diskon,'%',''))/100*d.harganormal),
							// d.diskon)) as hargadiskon,c.nama ,isbest,ispromo,isnew,issold
										 
						// FROM catalogdata d INNER JOIN catalogcat c on c.id=d.cat_id WHERE $kondisi d.publish='1' ";
				$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, 
					harganormal, diskon, matauang, if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon, 					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal), diskon)) as hargadiskon, isbest, ispromo, isnew, issold, idmerek 
					FROM catalogdata d WHERE $kondisi publish='1' ";
				break;
		}
		$sql .= " ORDER BY $sort $limit";
		
		$result = mysql_query($sql);
		
		if ($result and mysql_num_rows($result) > 0) {
			$jum_product=mysql_num_rows($result);
			
			//thumbnail only
			if ($showtype == 0) {
				$x = 1;
				ob_start();
				$home .= '<div class="thumbnail-product" id="producttable'.$class_product_row.'"  itemscope itemtype="http://schema.org/Product">';
				while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangan, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc,$isbest,$ispromo,$isnew,$issold, $brand_id) = mysql_fetch_row($result)) {
						
					$brand_name = get_brand_name($brand_id);
					$cat_name = get_cat_name($photo_cat_id);
			
					$home .= "<div class='col-sm-4 col-md-3 col-xs-6'>";
					
					//original code here
					/*$cispromo=$ispromo==1?"<div class=\"ispromo\"></div>":"";
					$cisnew=$isnew==1?"<div class=\"isnew\"></div>":"";
					$cissold=$issold==1?"<div class=\"issold\"></div>":"";
					$cisbest=$isbest==1?"<div class=\"isbest\"></div>":"";*/
					//end of original code here
					
					//additional code here
					$isdiskon=0;
					if ($diskon != '') {
						$isdiskon=1;
						if (substr_count($diskon, "%") == 1) {
							$label_disc = str_replace("%", "", $diskon) . " %";
						} else {
							$label_disc = $matauang . ' ' . number_format($diskon, 0, ',', '.');
						}
					}
					
					$cisdiskon=$isdiskon==1?"<span class='reduction'><span>-".$label_disc."</span></span>":"";
					$cispromo=$ispromo==1?"<span class='reduction'><span>Promo</span></span>":"";
					$cisnew=$isnew==1?"<div class='new'></div>":"";
					$cissold=$issold==1?"<div class='sold_out'><span></span></div>":"";
					$cisbest=$isbest==1?"<span class='best'><span>Best</span></span>":"";
					//end of additional code here
					
					$titleurl = array();
					$titleurl["pid"] = $photo_title;
					$titleurl["cat_id"] = $cat_name;
					$actiondependent = "cat_id=$photo_cat_id";
					$home .= "<div class=\"product-col\">";
					$home .= "<div class=\"image-product\">";
					if ($photo_filename != '' && file_exists("$cfg_thumb_path/$photo_filename")) { 
						$home .= "<a itemprop=\"url\" foto=\"".$cfg_fullsizepics_url."/".$photo_filename."\"  nama=\"".$photo_title."\" hargadisc=\"".$harga_disc."\" nilaidisc=\"".$nilai_disc."\" ket=\"".strip_tags($keterangan)."\" href=\"" . $urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&$actiondependent", $titleurl) . "\"><img itemprop=\"image\" class=\"img-responsive\" alt=\"$photo_title\" src=\"$cfg_thumb_url/$photo_filename\"></a>";
					} else {
						$home .= "<img itemprop=\"image\"  src=\"$cfg_app_url/images/none.gif\" class=\"photos\">";
					}
					$home .= "</div>	<!-- /.image-product -->";
					
					$home .= "<div class=\"caption\">";
					// $home .= (($brand_name != '') ? "<div class=\"brand\"><a href=\"".$urlfunc->makePretty("?p=catalog&action=brand&merek=$brand_id", $titleurl)."\">$brand_name</a></div>	<!-- /.brand -->" : "");
					$home .= "<div itemprop=\"name\" class=\"title-product\">";
					$home .= "<h4><a itemprop=\"url\" foto=\"".$cfg_fullsizepics_url."/".$photo_filename."\"  nama=\"".$photo_title."\" hargadisc=\"".$harga_disc."\" nilaidisc=\"".$nilai_disc."\" ket=\"".strip_tags($keterangan)."\" href=\"" . $urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&$actiondependent", $titleurl) . "\">$photo_title</a></h4>";
					$home .= "</div>	<!-- /.title-product -->";
					
					if ($harganormal > 0) {
						if ($diskon == '') {
							$kelasnormal = 'normalwodisc';
						} else {
							$kelasnormal = 'normalwdisc';
						}			
						$home .= "<div class=\"price\">";
						// $home .= " <div class=\"price-old\">$matauang " . number_format($harganormal, 0, ',', '.') . "</div>";
						// $home .= "<div class=\"price-now\">$matauang " . number_format($harga_disc, 0, ',', '.') . "</div>";
						if ($diskon != '') {
							if (substr_count($diskon, "%") == 1) {
								$label_disc = str_replace("%", "", $diskon) . " %";
							} else {
								$label_disc = $matauang . ' ' . number_format($diskon, 0, ',', '.');
							}					
							$home .= " <div class=\"price-old\">$matauang " . number_format($harganormal, 0, ',', '.') . "</div>";
							$home .= "<div class=\"priace-now\">$matauang " . number_format($harga_disc, 0, ',', '.') . "</div>";
							$home .= " <span class='reductioan'><span>-".$label_disc."</span></span>";
						} else {
							$home .= "<div class=\"price-now\">$matauang " . number_format($harga_disc, 0, ',', '.') . "</div>";
						}					
						/* if ($pakaicart && !$issold) {
							$home .= "<div  class=\"cart-button button-group\">";
							$home .= "<form action=\"".$urlfunc->makePretty("?p=cart&action=add")."\" method=\"POST\">";
							$home .= "<input type=\"hidden\" name=\"pid\" value=\"$photo_id\" />";
							$home .= "<input type=\"hidden\" name=\"qty\" id=\"qty\" value=\"1\"/>";
							$home .= "<button class=\"btn btn-cart\" type=\"submit\">" . _ADDTOCART . "<i class=\"fa fa-shopping-cart\"></i></button>";
							$home .= "</form>";
							$home .= "</div>	<!-- /.cart-button -->";					
						} */
						
						$is_stock_valid = ($stock == 0 || substr_count($stock, '-')) ? false : true;
						if ($pakaicart) {
							$home .= "<div  class=\"cart-button button-group\">";
							// $home .= "<form action=\"".$urlfunc->makePretty("?p=cart&action=add")."\" method=\"POST\">";
							// $home .= "<input type=\"hidden\" name=\"pid\" value=\"$photo_id\" />";
							// $home .= "<input type=\"hidden\" name=\"qty\" id=\"qty\" value=\"1\"/>";
							// if ($issold /*  || !$is_stock_valid */) {
								// // $home .= "<button class=\"btn btn-cart btn-disabled\" type=\"submit\" disabled>" . _ADDTOCART . "<i class=\"fa fa-shopping-cart\"></i></button>";
								// $home .= "<span class=\"outofstock\">"._OUTOFSTOCK."</span>";
							// } else {
								// $home .= "<button class=\"btn btn-cart\" type=\"submit\">" . _ADDTOCART . "<i class=\"fa fa-shopping-cart\"></i></button>";
							// }
							// $home .= "</form>";
							
							$home .= "<a href=\"".$urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&$actiondependent", $titleurl)."\" class=\"btn btn-cart\" type=\"submit\">" . _ADDTOCART . "<i class=\"fa fa-shopping-cart\"></i></a>";
							$home .= "</div>	<!-- /.cart-button -->";					
						}
						$home .= "</div>	<!-- /.price -->";
					}

					$home .= "</div>	<!-- /.caption -->";					
					
					$home .= '<div class="icon-product">'.$cisnew.$cissold.$cisbest.'</div>';
					$home .= "</div>	<!-- /.product-col -->\r\n";
					$home .= "</div>";
					$x++;
				}
			
						
				if($loadmore!='' and $jenis == 'category') {			
					//cek apakah masih ada product diatas limit
					$sql = "select * from catalogdata where id not in ((select id from (SELECT d.id FROM catalogdata d WHERE $kondisi d.publish='1' ORDER BY $sort $limit) a)) and cat_id=$isi limit 1";
					$q=mysql_query($sql);
					if($q and mysql_num_rows($q)>0) {
						$home .= "<div class=\"load_more\"><a class=\"btn btn-default more\" href=".$urlfunc->makePretty($loadmore).">"._LOADMORE."</a></div>";
					}

				}
		
				$home .= "</div>";	
				$home .= ob_get_clean();
			}
			
			//thumbnail plus summary
			
			if ($showtype == 1) {
				$result = mysql_query($sql);
				
				while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangan, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc, $catname) = mysql_fetch_row($result)) {
					$titleurl = array();
					$titleurl["pid"] = $photo_title;
					$titleurl["cat_id"] = $catname;
					$home .= "<div class=\"productsummary\">";
					$home .= "<div class=\"pstitle\"><h1><a href=\"" . $urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&cat_id=$photo_cat_id", $titleurl) . "\">$photo_title</a></h1></div>\r\n";
					$home .= "<div class=\"psphoto\"><a href=\"" . $urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&cat_id=$photo_cat_id", $titleurl) . "\">";
					if ($photo_filename != '') {
						$home .= "<img src=\"$cfg_thumb_url/$photo_filename\" border=\"0\" alt=\"$photo_title\" title=\"$photo_title\" class=\"photos\">";
					} else {
						$home .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" class=\"photos\">";
					}
					$home .= "</a></div>\r\n";
					$home .= "<div class=\"pssummary\">";
					$home .= "<div class=\"pssummary-detail\">$keterangan</div>\r\n";

					$diskon = str_replace(" ", "", $diskon);
					if ($harganormal > 0) {
						if ($diskon == '') {
							$kelasnormal = 'normalwodisc';
						} else {
							$kelasnormal = 'normalwdisc';
						}
						$home .= "<div class=\"harga\">" . _PRICE . ": <span class=\"$kelasnormal\">$matauang " . number_format($harganormal, 0, ',', '.') . "</span>";
						if ($diskon != '') {
							if (substr_count($diskon, "%") == 1) {
								$label_disc = str_replace("%", "", $diskon) . "%";
							} else {
								$label_disc = $matauang . ' ' . number_format($diskon, 0, ',', '.');
							}
						}
						$home .= " <span class=\"discprice\">$matauang " . number_format($harga_disc, 0, ',', '.') . "</span>";
						if ($label_disc != '') $home .= " <span class=\"discvalue\">(" . _YOUSAVE . " " . $label_disc . ")</span>";
						$home .= "</div>\r\n";
						//START ADD TO CATALOG
						if ($pakaicart) {
							$home .= "<div class=\"addtocart\">";
							$home .= "<form action=\"".$urlfunc->makePretty("?p=cart&action=add")."\" method=\"POST\">";
							$home .= "<input type=\"hidden\" name=\"pid\" value=\"$photo_id\" />";
							$home .= "<input type=\"hidden\" name=\"qty\" id=\"qty\" value=\"1\"/>";
							$home .= "<input type=\"submit\" id=\"addtocartbutton\" value=\"" . _ADDTOCART . "\" />";
							$home .= "</form>";
							$home .= "</div>";
						}
						//END ADD TO CATALOG
					}
					$home .= "</div>\r\n";
					$home .= "<div class=\"psseparator\"></div>\r\n";
					$home .= "</div>\r\n";
				}
				
			}
		} else {
			$home .= "<p>" . _NOPROD . "</p>";
		}
	}
}
if($mode_catalog==1)
{
	if ($jenis == 'featured') 
	{
	
	$home .=show_catalog_grid();
		
	}
}

if ($jenis == 'featuredmarquee' || $jenis == 'categorymarquee') {
    switch ($jenis) {
        case 'featuredmarquee':
			if($isi!='') {
				$isi=str_replace(";"," OR ",$isi);
				$isi=str_replace(":","=",$isi);
				$isi=" ($isi) AND ";
			}
          	$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, 
					harganormal, diskon, matauang, 
					if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon,isbest,ispromo,isnew,issold,idmerek 
					FROM catalogdata WHERE $isi publish='1' ";
					
            break;
			
        case 'categorymarquee':
			if($isi!='') {
				$isi=" cat_id='$isi' AND ";
			}
			$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, 
					harganormal, diskon, matauang, if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon, 					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal), diskon)) as hargadiskon, isbest, ispromo, isnew, issold, idmerek 
					FROM catalogdata WHERE $isi publish='1' ";
				
            break;
    }
    $sql .= " ORDER BY $sort ";
	
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        if ($jcarouselcount == 0) {
            $jcarouselcount++;
        }
		
        $tempjs .= "	
		<script type=\"text/javascript\">
			$(function() {
				$('.carousel-product').owlCarousel({
				 
					autoPlay: 3000, //Set AutoPlay to 3 seconds
					items : 4,
					itemsDesktop : [1199,4],
					itemsDesktopSmall : [979,3],
					itemsTablet       : [768,3],
					itemsMobile       : [479,1],
					navigation : true
				 
				});
			});
		</script>
		";
		//$script_js['home']=$tempjs;
		//$style_css['home']=$tempccs.$tempjs;;
		$script_js['home']=$tempccs.$tempjs;

		$adaharganormal=false;
		$adadiskon=false;
		
		$home .= "<div class=\"col-lg-12\">";
		$home .= "<div id=\"owl-carousel\" class=\"owl-carousel carousel-product\">";
		// $home .= "<div class=\"item\">";
        // $home .= "<ul>";
        while (list($photo_id, $photo_cat_id, $photo_filename, $photo_title, $photo_published, $photo_ishot, $keterangansingkat, $harganormal, $diskon, $matauang, $nilai_disc, $harga_disc,$isbest,$ispromo,$isnew,$issold,$brand_id) = mysql_fetch_row($result)) {
			$brand_name = get_brand_name($brand_id);
			$cat_name = get_cat_name($photo_cat_id);

			$cisdiskon=$isdiskon==1?"<span class='reduction'><span>-".$label_disc."</span></span>":"";
			$cispromo=$ispromo==1?"<span class='reduction'><span>Promo</span></span>":"";
			$cisnew=$isnew==1?"<div class='new'></div>":"";
			$cissold=$issold==1?"<div class='sold_out'><span></span></div>":"";
			$cisbest=$isbest==1?"<span class='best'><span>Best</span></span>":"";
			
			// $cispromo=$ispromo==1?"<div class=\"promo\">Promo</div>":"";
			// $cisnew=$isnew==1?"<div class=\"new\"></div>":"";
			// $cissold=$issold==1?"<div class=\"sold\"></div>":"";
			// $cisbest=$isbest==1?"<div class=\"best\">Best</div>":"";
			$titleurl = array();
			$titleurl["pid"] = $photo_title;
			$titleurl["cat_id"] = $cat_name;
			$titleurl["merek"] = $brand_name;
			$actiondependent = "cat_id=$photo_cat_id";
			
			$home .= "<div class=\"item\">";
			$home .= "<div class=\"product-col\">";
			$home .= "<div class=\"image-product\">";
			if ($photo_filename != '' && file_exists("$cfg_thumb_path/$photo_filename")) { 
				$home .= "<a itemprop=\"url\" foto=\"".$cfg_fullsizepics_url."/".$photo_filename."\"  nama=\"".$photo_title."\" hargadisc=\"".$harga_disc."\" nilaidisc=\"".$nilai_disc."\" ket=\"".strip_tags($keterangan)."\" href=\"" . $urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&$actiondependent", $titleurl) . "\"><img itemprop=\"image\" class=\"img-responsive\" alt=\"$photo_title\" src=\"$cfg_thumb_url/$photo_filename\"></a>";
			} else {
				$home .= "<img itemprop=\"image\"  src=\"$cfg_app_url/images/none.gif\" class=\"photos\">";
			}
			$home .= "</div>	<!-- /.image-product -->";
				
			$home .= "<div class=\"caption\">";
			// $home .= (($brand_name != '') ? "<div class=\"brand\"><a href=\"".$urlfunc->makePretty("?p=catalog&action=brand&merek=$brand_id", $titleurl)."\">$brand_name</a></div>	<!-- /.brand -->" : "");
			$home .= "<div itemprop=\"name\" class=\"title-product\">";
			$home .= "<h4><a itemprop=\"url\" foto=\"".$cfg_fullsizepics_url."/".$photo_filename."\"  nama=\"".$photo_title."\" hargadisc=\"".$harga_disc."\" nilaidisc=\"".$nilai_disc."\" ket=\"".strip_tags($keterangan)."\" href=\"" . $urlfunc->makePretty("?p=catalog&action=detail&pid=$photo_id&$actiondependent", $titleurl) . "\">$photo_title</a></h4>";
			$home .= "</div>	<!-- /.title-product -->";
			
			if ($harganormal > 0) {
				if ($diskon == '') {
					$kelasnormal = 'normalwodisc';
				} else {
					$kelasnormal = 'normalwdisc';
				}			
				$home .= "<div class=\"price\">";
				// $home .= " <div class=\"price-old\">$matauang " . number_format($harganormal, 0, ',', '.') . "</div>";
				// $home .= "<div class=\"price-now\">$matauang " . number_format($harga_disc, 0, ',', '.') . "</div>";
				if ($diskon != '') {
					if (substr_count($diskon, "%") == 1) {
						$label_disc = str_replace("%", "", $diskon) . " %";
					} else {
						$label_disc = $matauang . ' ' . number_format($diskon, 0, ',', '.');
					}					
					$home .= " <div class=\"price-old\">$matauang " . number_format($harganormal, 0, ',', '.') . "</div>";
					$home .= "<div class=\"priace-now\">$matauang " . number_format($harga_disc, 0, ',', '.') . "</div>";
					$home .= " <span class='reductioan'><span>-".$label_disc."</span></span>";
				} else {
					$home .= "<div class=\"price-now\">$matauang " . number_format($harga_disc, 0, ',', '.') . "</div>";
				}					
				if ($pakaicart && !$issold) {
					$home .= "<div  class=\"cart-button button-group\">";
					$home .= "<form action=\"".$urlfunc->makePretty("?p=cart&action=add")."\" method=\"POST\">";
					$home .= "<input type=\"hidden\" name=\"pid\" value=\"$photo_id\" />";
					$home .= "<input type=\"hidden\" name=\"qty\" id=\"qty\" value=\"1\"/>";
					$home .= "<button class=\"btn btn-cart\" type=\"submit\">" . _ADDTOCART . "<i class=\"fa fa-shopping-cart\"></i></button>";
					$home .= "</form>";
					$home .= "</div>	<!-- /.cart-button -->";					
				}
				$home .= "</div>	<!-- /.price -->";
			}

			$home .= "</div>	<!-- /.caption -->";
			$home .= "<div class=\"icon-product\">";
			$home .= "$cisnew\n$cispromo\n$cisbest\n$cissold\n";
			$home .= "</div>	<!-- /.icon-product --> ";
			$home .= "</div>	<!-- /.product-col -->";
			$home .= "</div>	<!-- /.item -->";	
        }
        $home .= "</div>	<!-- /#owl-carousel -->";
        $home .= "</div>	<!-- /.col-lg-12 -->";
		
		$tinggimarquee = intval($cfg_thumb_width) + 18;
		if ($adaharganormal) $tinggimarquee += 18;
		if ($adadiskon) $tinggimarquee += 36;
		if ($pakaicart && $adaharganormal) $tinggimarquee += 32;
		$home .= "<style type=\"text/css\">#$posisi$i .jcarousel-skin .jcarousel-clip-horizontal {height: ".$tinggimarquee."px;}</style>";
    } else {
        $home .= "<div class=\"col-lg-12\"><p>" . _NOPROD . "</p></div>";
    }
}

?>

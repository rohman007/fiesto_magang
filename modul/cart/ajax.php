<?php
ini_set('error_reporting', 1);
session_start();
include "../../kelola/urasi.php";
include "../../kelola/fungsi.php";
include "urasi.php";
include "fungsi.php";
$sql = "SELECT konstanta,terjemahan FROM translation WHERE modul='cart' or modul='site' ";
$result = $mysql->query($sql);
while (list($name, $value) = $mysql->fetch_row($result)) {
    define($name, $value);
}

$product_id = fiestolaundry($_REQUEST['pid'], 11); //the product id from the URL post
$action = fiestolaundry($_REQUEST['action'], 20);  //the action from the URL post
$quantity = fiestolaundry($_REQUEST['qty'], 4);    //the quantity from the URL post
$do = fiestolaundry($_GET['do'], 8);    //the do from the URL post
$pid = fiestolaundry($_GET['pid'], 8);    //the do from the URL post
if ($action == "add") {
    if ($quantity < 1) {
        $quantity = 1;
    }
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity;
    echo showcartajax();
}
if ($action == "empty") {
    echo emptycartajax();
}
if ($action == "hapus") {
    unset($_SESSION['cart'][$pid]);
    $poin = 0;
    if (count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $product_id => $qty) {
            if (strlen($qty) > 0) {
                $poin++;
            }
        }
    }
    if ($poin == 0) {
        unset($_SESSION['cart']);
    }

    echo showcartajax();
}
if ($action == "cariongkir") {
    $city = trim($_GET['city']);
    $minimum_city_length = 2;
    if (strlen($city) >= $minimum_city_length) {
        $totalweight = ceil(trim($_GET['weight']));
        $tablecontent = "";
        /* get selected couriers */
        $sql = "SELECT value FROM config WHERE name = 'couriers'";
        $result = $mysql->query($sql);
        list($value) = $mysql->fetch_row($result);
        $cityfound = 0;
        if ($value != null || $value != "") {
            $values = explode(",", $value);

            /* -------- INTERNAL COURIERS -------- */
            if (in_array(0, $values)) {
                $sql = "SELECT id, kota, ongkos, ongkostetap, waktukirim FROM sc_postage WHERE kota LIKE '%$city%'";
                $result = $mysql->query($sql);
				if ($mysql->num_rows($result) > 0) {
					$tablecontent .= "<tr class=\"header-kurir\">\r\n";
					$tablecontent .= "<td colspan=\"4\">" . _KURIRINTERNAL . "</td></tr>\r\n";				
					while (list($id, $kota, $ongkos, $ongkostetap, $waktukirim) = $mysql->fetch_row($result)) {
						$cityfound++;
						/* -------- PERHITUNGAN ONGKIR -------- */
						$totalpostage = $ongkostetap + ($ongkos * $totalweight);
						$tablecontent .= "<tr>\r\n";
						$tablecontent .= "<td><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('" . _INTERNALCOURIER . " - $kota','$totalpostage','$waktukirim')\">" . _INTERNALCOURIER . " - " . $kota . "</a></td>\r\n";
						$tablecontent .= "<td align='right'>" . number_format($totalpostage) . "</td>\r\n";
						$tablecontent .= "<td align='right'>$waktukirim</td>\r\n";
						$tablecontent .= "<td align='right'><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('" . _INTERNALCOURIER . " - $kota','$totalpostage','$waktukirim')\">
							<img src=\"$cfg_app_url/images/check.png\" border=\"0\" alt=\"" . _CHOOSE . "\" />
							</a></td>\r\n";
						$tablecontent .= "</tr>\r\n";
					}
				}
            }

            /* -------- EXTERNAL COURIERS -------- */
			
            // $sql = "SELECT id FROM sc_couriers WHERE id != 0";
            // $result = $mysql->query($sql);
            // while (list($courier_id) = $mysql->fetch_row($result)) {
                // if (in_array($courier_id, $values)) {
                    // /* get master couriers */
                    // $sql = "SELECT cd.id, c.nama, c.kota as asal, cd.tujuan, cd.tipe, cd.waktu_kirim, cd.biaya "
                            // . "FROM sc_courier_fixed cd "
                            // . "JOIN sc_couriers c ON (c.id = cd.courier_id) "
                            // . "WHERE cd.courier_id = '$courier_id' AND cd.tujuan LIKE '%$city%'";
                    // $result = $mysql->query($sql);

                    // while (list($id, $nama, $asal, $kota, $tipe, $waktu_kirim, $biaya) = $mysql->fetch_row($result)) {
                        // $cityfound++;
                        // /* -------- PERHITUNGAN ONGKIR -------- */

                        // $totalbiaya = calculatePostage($biaya, $totalweight, $courier_id);
                        // /* -------- PERHITUNGAN ONGKIR -------- */

                        // $tablecontent .= "<tr>\r\n";
                        // $tablecontent .= "<td>$nama ($asal) - $kota ($tipe)</td>\r\n";
                        // $tablecontent .= "<td align='right'>" . number_format($totalbiaya) . "</td>\r\n";
                        // $tablecontent .= "<td align='right'>$waktu_kirim</td>\r\n";
                        // $tablecontent .= "<td align='right'><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('$nama - $kota ($tipe)','$totalbiaya','$waktu_kirim')\">
                        // <img src=\"$cfg_app_url/images/check.png\" border=\"0\" alt=\"" . _CHOOSE . "\" />
                        // </a></td>\r\n";
                        // $tablecontent .= "</tr>\r\n";
                    // }
                // }
            // // }

           if (in_array(1, $values)) {
               /* get master couriers */
				$sql = "SELECT cd.id, c.nama, c.kota as asal, cd.tujuan, cd.tipe, cd.waktu_kirim, cd.biaya "
                       . "FROM sc_courier_jne cd "
                       . "JOIN sc_couriers c ON (c.id = cd.courier_id) "
                       . "WHERE cd.courier_id = '1' AND cd.tujuan LIKE '%$city%' AND biaya > 0";
				$result = $mysql->query($sql);
				if ($mysql->num_rows($result) > 0) {
					$tablecontent .= "<tr class=\"header-kurir\">\r\n";
					$tablecontent .= "<td colspan=\"4\">" . _KURIRJNE . "</td></tr>\r\n";
					while (list($id, $nama, $asal, $kota, $tipe, $waktu_kirim, $biaya) = $mysql->fetch_row($result)) {
						$cityfound++;
						/* -------- PERHITUNGAN ONGKIR -------- */
						$totalbiaya = $biaya * $totalweight;
						/* -------- PERHITUNGAN ONGKIR -------- */
						$tablecontent .= "<tr>\r\n";
						// $tablecontent .= "<td>$nama ($asal) - $kota ($tipe)</td>\r\n";
						$tablecontent .= "<td><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('$nama - $kota ($tipe)','$totalbiaya','$waktu_kirim', '$kota')\">$nama ($asal) - $kota ($tipe)</a></td>\r\n";
						$tablecontent .= "<td align='right'>" . number_format($totalbiaya) . "</td>\r\n";
						$tablecontent .= "<td align='right'>$waktu_kirim</td>\r\n";
						$tablecontent .= "<td align='right'><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('$nama - $kota ($tipe)','$totalbiaya','$waktu_kirim', '$kota')\">
						   <img src=\"$cfg_app_url/images/check.png\" alt=\"" . _CHOOSE . "\" />
						   </a></td>\r\n";
						$tablecontent .= "</tr>\r\n";
					}
				}
			}
		   
			if (in_array(2, $values)) {
				/* get master couriers */
				$sql = "SELECT kode, kota, kgp, kgs, bppw, bpp, bpsw, bps, addrp FROM sc_courier_tiki WHERE kota LIKE '%$city%'";
				$result = $mysql->query($sql);
									
				if ($mysql->num_rows($result) > 0) {
					$tablecontent .= "<tr class=\"header-kurir\">\r\n";
					$tablecontent .= "<td colspan=\"4\">" . _KURIRTIKI . "</td></tr>\r\n";
					while (list($kode, $kota, $kgp, $kgs, $bppw, $bpp, $bpsw, $bps, $addrp) = $mysql->fetch_row($result)) {
						$cityfound++;
						/* -------- PERHITUNGAN ONGKIR -------- */

						$tariftiki = (ceil($totalweight)>1) ? $kgp+$kgs*(ceil($totalweight)-1) : $kgp;
						if ($bpsw!=0) $tarifpenerus = (ceil($totalweight)>$bppw) ? $bpp+$bps*ceil(($totalweight-$bppw)/$bpsw) : $bpp;
						$tariflain = ($tarifpenerus==0) ? 0 : $addrp;
						$tariftotalrp = $tariftiki+$tarifpenerus+$tariflain;

						//$totalbiaya = calculatePostage($tariftotalrp, $totalweight, $courier_id);
						/* -------- PERHITUNGAN ONGKIR -------- */

						$tablecontent .= "<tr>\r\n";
						// $tablecontent .= "<td>$kode - $kota</td>\r\n";
						// $tablecontent .= "<td>$kota</td>\r\n";
						$tablecontent .= "<td><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('"._KURIRTIKI." $kota', '$tariftotalrp', '' )\">$kota</a></td>\r\n";
						$tablecontent .= "<td align='right'>" . number_format($tariftotalrp) . "</td>\r\n";
						$tablecontent .= "<td align='right'>$waktu_kirim</td>\r\n";
						$tablecontent .= "<td align='center'><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('"._KURIRTIKI." $kota', '$tariftotalrp', '' )\">
						<img src=\"$cfg_app_url/images/check.png\" border=\"0\" alt=\"" . _CHOOSE . "\" />
						</a></td>\r\n";
						$tablecontent .= "</tr>\r\n";
					}
					$tablecontent .= "</tr>\r\n";
				}
			}
			/* -------- END OF EXTERNAL -------- */
		   
        }

		$manualshippingprice = 0;
		$manualshippingday = 0;
        $ajaxcontent = "";
        if ($cityfound == 0) {
           
            $ajaxcontent .= "<div style='margin-left: 10px;'>";
            $ajaxcontent .= "<h2>" . _MANUALSHIPPING . "</h2>";
            $ajaxcontent .= _SEARCH . " " . _CITY . ": " . $city;
            $ajaxcontent .= $citynotfoundmsg;
            //$ajaxcontent .= "<a class=\"button-link\" href=\"#postage\" onclick=\"getSelectedPostage('-','$manualshippingprice','$manualshippingday')\">" . _TUTUP . "</a>";
            $ajaxcontent .= "</div>";
			$ajaxcontent .= "<div class=\"ongkir_citynotfound\"><a class=\"button-link\" href=\"#postage\" onclick=\"getSelectedPostage('-','$manualshippingprice','$manualshippingday');nocourier();\">" . _TUTUP . "</a></div>";
        } else {
            $ajaxcontent .= _SEARCH . ": <label>$city</label><br/>";
            $ajaxcontent .= _CURRENTWEIGHT . ": <label>$totalweight kg</label>";
            $ajaxcontent .= "<table id=\"postagetable\" border='1'>\r\n";
            $ajaxcontent .= "<thead>\r\n";
            $ajaxcontent .= "<tr>\r\n";
            $ajaxcontent .= "<th width='50%'>" . _COURIER . "</th>\r\n";
            $ajaxcontent .= "<th width='25%'>" . _POSTAGE . "</th>\r\n";
            $ajaxcontent .= "<th width='25%'>" . _SHIPPINGTIME . "</th>\r\n";
            $ajaxcontent .= "<th></th>\r\n";
            $ajaxcontent .= "</tr>\r\n";
            $ajaxcontent .= "</thead>\r\n";
            $ajaxcontent .= "<tbody id='postagetablebody'>\r\n";
            $ajaxcontent .= $tablecontent;
            $ajaxcontent .= "</tbody>\r\n";
            $ajaxcontent .= "</table>\r\n";
            $ajaxcontent .= "<input type=\"hidden\" name=\"cityfound\" id=\"cityhidden\" value=\"$cityfound\" />";
            $ajaxcontent .= "<div class=\"ongkir_citynotfound\"><a class=\"button-link\" href=\"#postage\" onclick=\"getSelectedPostage('-','$manualshippingprice','$manualshippingday');nocourier();\">" . _TUTUP . "</a></div>";
			
        }
    } else {
        $ajaxcontent .= "<div style='margin-left: 10px;'>";
        $ajaxcontent .= "<h2>" . _ERROR . "</h2>";
        $ajaxcontent .= "<p>$minimum_city_length_notification $minimum_city_length huruf</p>";
        $ajaxcontent .= "<a class=\"button-link\" href=\"#\" onclick=\"closeMPopup()\">" . _TUTUP . "</a>";
        $ajaxcontent .= "</div>";
    }

    echo $ajaxcontent;
}

if ($action == 'get_insurance') {
	$status = $_GET['status'];
	$price = $_GET['price'];
	if ($status == 1) {
		$_SESSION['use_insurance'] = 1;
		$_SESSION['insurance_value'] = $price;
	} else {
		unset($_SESSION['use_insurance']);
		unset($_SESSION['insurance_value']);
	}
}

if ($action == 'get_packing') {
	// $urlfunc = new PrettyURL();
	$status = $_GET['status'];
	$price = $_GET['price'];
	
	if ($status == 1) {
		$_SESSION['use_packing'] = 1;
		$_SESSION['packing_value'] = $price;
	} else {
		unset($_SESSION['use_packing']);
		unset($_SESSION['packing_value']);
	}
	
}

if ($action == 'get_biaya_ongkir') {
	$city = $_GET['city'];
	echo get_biaya_ongkir($city);
}

// if ($action == 'cari_kecamatan') {
	// $keyword = $_GET['query'];
	// $data = array();
	// $sql = "SELECT id, kode, tujuan, kecamatan, biaya, tipe FROM sc_courier_jne WHERE tujuan LIKE '%$keyword%'
			// GROUP BY kode";
	// if ($result = $mysqli->query($sql)) {
		// while($row = $result->fetch_assoc()) {
			// $data[] = array(
				// 'value' 	=> $row['tujuan'],
				// 'data'		=> $row['id'],
				// 'kode'		=> $row['kode'],
				// 'tipe'		=> $row['tipe'],
				// 'biaya'		=> $row['biaya'],
				// 'kecamatan'	=> $row['kecamatan']
			// );
		// }
	// }

	// $json = json_encode(array('suggestions' => $data));
	// echo $json;
// }

// if ($action == 'list') {
	// $keyword = $_GET['query'];
	// $totalweight = $_GET['totalweight'];
	// $courier_name = _COURIERNAMEJNE;
	// $data = '';
	// $sql = "SELECT id, courier_id, kode, tujuan, kecamatan, tipe, biaya, waktu_kirim FROM sc_courier_jne WHERE tujuan LIKE '%$keyword%' AND biaya > 0 ORDER BY tujuan";
	// // echo "$sql<br>";
	// if ($result = $mysqli->query($sql)) {
		// if ($result->num_rows > 0) {
			// $data .= "<h4>"._JNECOURIER."</h4><ul class=\"list-ongkir\">";
			// $totalbiaya = 0;
			// while($row = $result->fetch_assoc()) {
				// /* -------- PERHITUNGAN ONGKIR -------- */
				// $totalbiaya = $row['biaya'] * $totalweight;
				// /* -------- PERHITUNGAN ONGKIR -------- */
				
				// $biaya = number_format($totalbiaya, 0, ',', '.');
				// $estimasi = ($row['waktu_kirim'] != '') ? "({$row['waktu_kirim']} " . _DAYS . ")" : "";
				// $data .= <<<HTML
				// <li>
					// <input type="radio" name="postageprice" id="postageprice{$row['courier_id']}{$row['id']}" value="$totalbiaya" data-courier_id="{$row['courier_id']}" data-tipe="$courier_name {$row['tipe']} {$row['tujuan']}" data-estimasi="$estimasi" required>
					// <label for="postageprice{$row['courier_id']}{$row['id']}">
						// <span class="courier_attr">$courier_name {$row['tipe']} {$row['kota']} $estimasi <strong>Rp $biaya</strong></span>
					// </label>
				// </li>
// HTML;
			// }
		// }
		// $data .= "</ul>";
		// $result->free();
	// }

	
	// $sql2 = "SELECT origin, courier_id, kota, kecamatan, biaya, via, estimasi FROM sc_courier_jnt WHERE kota LIKE '%$keyword%' AND biaya > 0 ORDER BY kota";
	// // echo "$sql2<br>";
	// $courier_name = _COURIERNAMEJNT;
	// if ($result2 = $mysqli->query($sql2)) {
		// if ($result2->num_rows > 0) {
			// $data .= "<h4>"._COURIERJNT."</h4><ul class=\"list-ongkir\">";
			// $totalbiaya = 0;
			// while($row = $result2->fetch_assoc()) {
				// /* -------- PERHITUNGAN ONGKIR -------- */
				// $totalbiaya = $row['biaya'] * $totalweight;
				// /* -------- PERHITUNGAN ONGKIR -------- */
				// $biaya = number_format($totalbiaya, 0, ',', '.');
				// $estimasi = ($row['estimasi'] != '') ? "{$row['estimasi']} " . _DAYS : "";
				// $data .= <<<HTML
				// <!--<tr>
					// <td>
						// <input type="radio" name="postageprice" value="$totalbiaya" data-courier_id="{$row['courier_id']}" data-tipe="$courier_name {$row['tipe']} {$row['kota']}" data-estimasi="$estimasi" required>
					// </td>
					// <td>$courier_name {$row['tipe']} {$row['kota']} $estimasi <strong>Rp $biaya</strong> </td>
				// </tr>-->
				// <li>
					// <input type="radio" name="postageprice" id="postageprice{$row['courier_id']}{$row['id']}" value="$totalbiaya" data-courier_id="{$row['courier_id']}" data-tipe="$courier_name {$row['tipe']} {$row['tujuan']}" data-estimasi="$estimasi" required>
					// <label for="postageprice{$row['courier_id']}{$row['id']}">
						// <span class="courier_attr">$courier_name {$row['tipe']} {$row['kota']} $estimasi <strong>Rp $biaya</strong></span>
					// </label>
				// </li>
// HTML;
			// }
			// $data .= "</ul>";
			// $result2->free();
		// }
	// }
	
	// $sql3 = "SELECT origin, courier_id, kota, kecamatan, biaya, via, estimasi, tipe FROM sc_courier_tiki2 WHERE kota LIKE '%$keyword%' AND biaya > 0 ORDER BY kota";
	// // echo "$sql3<br>";
	// $courier_name = _COURIERNAMETIKI;
	// if ($result3 = $mysqli->query($sql3)) {
		// if ($result3->num_rows > 0) {
			// $data .= "<h4>"._COURIERTIKI."</h4><ul class=\"list-ongkir\">";
			// $totalbiaya = 0;
			// while($row = $result3->fetch_assoc()) {
				// /* -------- PERHITUNGAN ONGKIR -------- */
				// $totalbiaya = $row['biaya'] * $totalweight;
				// /* -------- PERHITUNGAN ONGKIR -------- */
				// $biaya = number_format($totalbiaya, 0, ',', '.');
				// $estimasi = ($row['estimasi'] != '') ? "{$row['estimasi']} " . _DAYS : "";
				// $data .= <<<HTML
				// <tr>
					// <td>
						// <input type="radio" name="postageprice" value="$totalbiaya" data-courier_id="{$row['courier_id']}" data-tipe="$courier_name {$row['tipe']} {$row['kota']}" data-estimasi="$estimasi" required>
					// </td>
					// <td>$courier_name {$row['tipe']} {$row['kota']} $estimasi <strong>Rp $biaya</strong></td>
				// </tr>
// HTML;
			// }
			// $data .= "</ul>";
			// $result3->free();
		// }
	// }
	
	// echo $data;
// }

if ($action == 'cari_kecamatan') {
	$keyword = $_GET['query'];
	$data = array();
	$sql = "SELECT id, kode, tujuan, kecamatan, biaya, tipe FROM sc_courier_jne WHERE tujuan LIKE '%$keyword%'
			GROUP BY kode";
	if ($result = $mysql->query($sql)) {
		while($row = $mysql->fetch_assoc($result)) {
			$data[] = array(
				'value' 	=> $row['kecamatan'],
				'data'		=> $row['id'],
				'kode'		=> $row['kode'],
				'tipe'		=> $row['tipe'],
				'biaya'		=> $row['biaya'],
				'tujuan'	=> $row['tujuan']
			);
		}
	}
	
	// /* get selected couriers */
	// $sql = "SELECT value FROM config WHERE name = 'couriers'";
	// $result = $mysqli->query($sql);
	// list($value) = $result->fetch_row();
	// $cityfound = 0;
	// if ($value != null || $value != "") {
		// $values = explode(",", $value);

		// /* -------- INTERNAL COURIERS -------- */
		// if (in_array(0, $values)) {
			// $sql = "SELECT id, kota, ongkos, ongkostetap, waktukirim FROM sc_postage WHERE kota LIKE '%$keyword%'";
			// $result = $mysqli->query($sql);
			// if ($result->num_rows > 0) {
				// $tablecontent .= "<tr class=\"header-kurir\">\r\n";
				// $tablecontent .= "<td colspan=\"4\">" . _KURIRINTERNAL . "</td></tr>\r\n";				
				// while($row = $result->fetch_assoc()) {
					// $cityfound++;
					// /* -------- PERHITUNGAN ONGKIR -------- */
					// $totalpostage = $ongkostetap + ($ongkos * $totalweight);
					// $tablecontent .= "<tr>\r\n";
					// $tablecontent .= "<td><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('" . _INTERNALCOURIER . " - $kota','$totalpostage','$waktukirim')\">" . _INTERNALCOURIER . " - " . $kota . "</a></td>\r\n";
					// $tablecontent .= "<td align='right'>" . number_format($totalpostage) . "</td>\r\n";
					// $tablecontent .= "<td align='right'>$waktukirim</td>\r\n";
					// $tablecontent .= "<td align='right'><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('" . _INTERNALCOURIER . " - $kota','$totalpostage','$waktukirim')\">
						// <img src=\"$cfg_app_url/images/check.png\" border=\"0\" alt=\"" . _CHOOSE . "\" />
						// </a></td>\r\n";
					// $tablecontent .= "</tr>\r\n";
					
					// $data[] = array(
						// 'value' 	=> $row['kota'],
						// 'data'		=> array('category' => 'INTERNAL'), /* $row['id'], */
						// 'kode'		=> $row['kode'],
						// 'tipe'		=> $row['tipe'],
						// 'biaya'		=> $totalpostage,
						// 'kecamatan'	=> $row['kecamatan']
					// );
				// }
			// }
		// }
		
		// /* -------- JNE COURIERS -------- */
		// if (in_array(1, $values)) {
			// $sql = "SELECT id, kode, tujuan, kecamatan, biaya, tipe, courier_id FROM sc_courier_jne WHERE tujuan LIKE '%$keyword%' GROUP BY kode";
			// $result = $mysqli->query($sql);
			// if ($result->num_rows > 0) {
				// $tablecontent .= "<tr class=\"header-kurir\">\r\n";
				// $tablecontent .= "<td colspan=\"4\">" . _KURIRINTERNAL . "</td></tr>\r\n";				
				// while($row = $result->fetch_assoc()) {
					// $cityfound++;
					// /* -------- PERHITUNGAN ONGKIR -------- */
					// $totalpostage = $ongkostetap + ($ongkos * $totalweight);
					// $tablecontent .= "<tr>\r\n";
					// $tablecontent .= "<td><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('" . _INTERNALCOURIER . " - $kota','$totalpostage','$waktukirim')\">" . _INTERNALCOURIER . " - " . $kota . "</a></td>\r\n";
					// $tablecontent .= "<td align='right'>" . number_format($totalpostage) . "</td>\r\n";
					// $tablecontent .= "<td align='right'>$waktukirim</td>\r\n";
					// $tablecontent .= "<td align='right'><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('" . _INTERNALCOURIER . " - $kota','$totalpostage','$waktukirim')\">
						// <img src=\"$cfg_app_url/images/check.png\" border=\"0\" alt=\"" . _CHOOSE . "\" />
						// </a></td>\r\n";
					// $tablecontent .= "</tr>\r\n";
					
					// $data[] = array(
						// 'value' 	=> $row['tujuan'],
						// 'data'		=> array('category' => 'JNE'), /* $row['id'], */
						// 'kode'		=> $row['kode'],
						// 'tipe'		=> $row['tipe'],
						// 'biaya'		=> $row['biaya'],
						// 'kecamatan'	=> $row['kecamatan'],
						// 'courier_id' => $row['courier_id']
					// );
				// }
			// }
		// }
		
		// /* -------- JNE COURIERS -------- */
		// if (in_array(2, $values)) {
			// $sql = "SELECT origin, courier_id, kota, kecamatan, biaya, via, estimasi, courier_id FROM sc_courier_jnt WHERE kecamatan  LIKE '%$keyword%' AND biaya > 0 ORDER BY kota";
			// $result = $mysqli->query($sql);
			// if ($result->num_rows > 0) {
				// $tablecontent .= "<tr class=\"header-kurir\">\r\n";
				// $tablecontent .= "<td colspan=\"4\">" . _KURIRINTERNAL . "</td></tr>\r\n";				
				// while($row = $result->fetch_assoc()) {
					// $cityfound++;
					// /* -------- PERHITUNGAN ONGKIR -------- */
					// $totalpostage = $ongkostetap + ($ongkos * $totalweight);
					// $tablecontent .= "<tr>\r\n";
					// $tablecontent .= "<td><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('" . _INTERNALCOURIER . " - $kota','$totalpostage','$waktukirim')\">" . _INTERNALCOURIER . " - " . $kota . "</a></td>\r\n";
					// $tablecontent .= "<td align='right'>" . number_format($totalpostage) . "</td>\r\n";
					// $tablecontent .= "<td align='right'>$waktukirim</td>\r\n";
					// $tablecontent .= "<td align='right'><a style=\"text-decoration: none;\" href=\"#postage\" onclick=\"getSelectedPostage('" . _INTERNALCOURIER . " - $kota','$totalpostage','$waktukirim')\">
						// <img src=\"$cfg_app_url/images/check.png\" border=\"0\" alt=\"" . _CHOOSE . "\" />
						// </a></td>\r\n";
					// $tablecontent .= "</tr>\r\n";
					
					// $data[] = array(
						// 'value' 	=> $row['kecamatan'],
						// 'data'		=> array('category' => 'J&T'), /* $row['id'], */
						// 'kode'		=> $row['kode'],
						// // 'tipe'		=> $row['tipe'],
						// 'biaya'		=> $row['biaya'],
						// 'kecamatan'	=> $row['kecamatan'],
						// 'courier_id' => $row['courier_id']
					// );
				// }
			// }
		// }
	// }

	$json = json_encode(array('suggestions' => $data));
	echo $json;
}

if ($action == 'list') {
	$keyword = $_GET['query'];
	$totalweight = $_GET['totalweight'];
	$data = '';
	
	/* get selected couriers */
	$sql = "SELECT value FROM config WHERE name = 'couriers'";
	$result = $mysql->query($sql);
	list($value) = $mysql->fetch_row($result);
	$cityfound = 0;
	if ($value != null || $value != "") {
		$values = explode(",", $value);

		/* -------- INTERNAL COURIERS -------- */
		if (in_array(0, $values)) {
			$sql = "SELECT id, kota, ongkos, ongkostetap, waktukirim FROM sc_postage WHERE kota LIKE '%$keyword%'";
			$result = $mysql->query($sql);
			if ($mysql->num_rows($result) > 0) {
				$courier_name = _COURIERNAMEINTERNAL;
				$data .= "<h4>"._KURIRINTERNAL."</h4>";
				$data .= "<ul class=\"list-ongkir\">";
				$totalbiaya = 0;
				while($row = $mysql->fetch_assoc($result)) {
					/* -------- PERHITUNGAN ONGKIR -------- */
					$totalbiaya = $row['ongkostetap'] + ($row['ongkos'] * $totalweight);
					/* -------- PERHITUNGAN ONGKIR -------- */
					
					$biaya = number_format($totalbiaya, 0, ',', '.');
					$estimasi = ($row['waktu_kirim'] != '') ? "({$row['waktu_kirim']} " . _DAYS . ")" : "";
					$data .= <<<HTML
					<li>
						<input type="radio" name="postageprice" id="postageprice{$row['courier_id']}{$row['id']}" value="$totalbiaya" data-courier_id="{$row['courier_id']}" data-tipe="$courier_name {$row['tipe']} {$row['tujuan']}" data-estimasi="$estimasi" required>
						<label for="postageprice{$row['courier_id']}{$row['id']}">
							<span class="courier_attr">$courier_name {$row['tipe']} {$row['kota']} $estimasi <strong>Rp $biaya</strong></span>
						</label>
					</li>
HTML;
				}
				$data .= '</ul>';
			}
		}
		
		/* -------- JNE COURIERS -------- */
		if (in_array(1, $values)) {
			$sql = "SELECT id, courier_id, kode, tujuan, kecamatan, tipe, biaya, waktu_kirim FROM sc_courier_jne WHERE tujuan LIKE '%$keyword%' AND biaya > 0 ORDER BY tujuan";
			if ($result = $mysql->query($sql)) {
				if ($mysql->num_rows($result) > 0) {
					$courier_name = _COURIERNAMEJNE;
					$data .= "<h4>"._JNECOURIER."</h4>";
					$data .= "<ul class=\"list-ongkir\">";
					$totalbiaya = 0;
					while($row = $mysql->fetch_assoc($result)) {
						/* -------- PERHITUNGAN ONGKIR -------- */
						$totalbiaya = $row['biaya'] * $totalweight;
						/* -------- PERHITUNGAN ONGKIR -------- */
						
						$biaya = number_format($totalbiaya, 0, ',', '.');
						$estimasi = ($row['waktu_kirim'] != '') ? "({$row['waktu_kirim']} " . _DAYS . ")" : "";
						$data .= <<<HTML
						<li>
							<input type="radio" name="postageprice" id="postageprice{$row['courier_id']}{$row['id']}" value="$totalbiaya" data-courier_id="{$row['courier_id']}" data-tipe="$courier_name {$row['tipe']} {$row['tujuan']}" data-estimasi="$estimasi" required>
							<label for="postageprice{$row['courier_id']}{$row['id']}">
								<span class="courier_attr">$courier_name {$row['tipe']} {$row['kecamatan']} $estimasi <strong>Rp $biaya</strong></span>
							</label>
						</li>
HTML;
					}
					$data .= "</ul>";
				}
			}
		}
		
		/* -------- J&T COURIERS -------- */
		if (in_array(2, $values)) {
			$sql = "SELECT courier_id, origin, courier_id, kota, kecamatan, biaya, via, estimasi FROM sc_courier_jnt WHERE kecamatan LIKE '%$keyword%' AND biaya > 0 ORDER BY kota";
			$courier_name = _COURIERNAMEJNT;
			if ($result = $mysql->query($sql)) {
				if ($mysql->num_rows($result) > 0) {
					$data .= "<h4>"._COURIERNAMEJNT."</h4><ul class=\"list-ongkir\">";
					$totalbiaya = 0;
					while($row = $mysql->fetch_assoc($result)) {
						/* -------- PERHITUNGAN ONGKIR -------- */
						$totalbiaya = $row['biaya'] * $totalweight;
						/* -------- PERHITUNGAN ONGKIR -------- */
						$biaya = number_format($totalbiaya, 0, ',', '.');
						$estimasi = ($row['estimasi'] != '') ? "{$row['estimasi']} " . _DAYS : "";
						$data .= <<<HTML
						<li>
							<input type="radio" name="postageprice" id="postageprice{$row['courier_id']}{$row['id']}" value="$totalbiaya" data-courier_id="{$row['courier_id']}" data-tipe="$courier_name {$row['kecamatan']}, {$row['kota']}" data-estimasi="$estimasi" required>
							<label for="postageprice{$row['courier_id']}{$row['id']}">
								<span class="courier_attr">$courier_name {$row['kecamatan']} {$row['kota']} $estimasi <strong>Rp $biaya</strong></span>
							</label>
						</li>
HTML;
					}
					$data .= "</ul>";
				}
			}
		}		
		
		/* -------- TIKI COURIERS -------- */
		if (in_array(3, $values)) {
			$sql = "SELECT courier_id, kode, kota, kgp, kgs, bppw, bpp, bpsw, bps, addrp FROM sc_courier_tiki WHERE kota LIKE '%$keyword%' AND kgp > 0 ORDER BY kota";
			$courier_name = _COURIERNAMETIKI;
			if ($result = $mysql->query($sql)) {
				if ($mysql->num_rows($result) > 0) {
					$data .= "<h4>"._COURIERNAMETIKI."</h4><ul class=\"list-ongkir\">";
					$totalbiaya = 0;
					while($row = $mysql->fetch_assoc($result)) {
						/* -------- PERHITUNGAN ONGKIR -------- */
						$tariftiki = (ceil($totalweight)>1) ? $row['kgp']+$row['kgs']*(ceil($totalweight)-1) : $row['kgp'];
						if ($row['bpsw'] != 0) $tarifpenerus = (ceil($totalweight)>$bppw) ? $bpp+$bps*ceil(($totalweight-$row['bppw'])/$row['bpsw']) : $row['bpp'];
						$tariflain = ($tarifpenerus==0) ? 0 : $row['addrp'];
						$tariftotalrp = $tariftiki+$tarifpenerus+$tariflain;
						/* -------- PERHITUNGAN ONGKIR -------- */
						$biaya = number_format($tariftotalrp, 0, ',', '.');
						// $estimasi = ($row['estimasi'] != '') ? "{$row['estimasi']} " . _DAYS : "";
						$data .= <<<HTML
						<li>
							<input type="radio" name="postageprice" id="postageprice{$row['courier_id']}{$row['id']}" value="$tariftotalrp" data-courier_id="{$row['courier_id']}" data-tipe="$courier_name, {$row['kota']}" data-estimasi="$estimasi" required>
							<label for="postageprice{$row['courier_id']}{$row['id']}">
								<span class="courier_attr">$courier_name {$row['kota']} <strong>Rp $biaya</strong></span>
							</label>
						</li>
HTML;
					}
					$data .= "</ul>";
				}
			}
		}
		
		/* -------- WAHANA COURIERS -------- */
		// if (in_array(4, $values)) {
			// $sql = "SELECT courier_id, kode, kota, kgp, kgs, bppw, bpp, bpsw, bps, addrp FROM sc_courier_tiki WHERE kota LIKE '%$keyword%' AND kgp > 0 ORDER BY kota";
			// $courier_name = _COURIERNAMETIKI;
			// if ($result = $mysqli->query($sql)) {
				// if ($result->num_rows > 0) {
					// $data .= "<h4>"._COURIERNAMETIKI."</h4><ul class=\"list-ongkir\">";
					// $totalbiaya = 0;
					// while($row = $result->fetch_assoc()) {
						// /* -------- PERHITUNGAN ONGKIR -------- */
						// $tariftiki = (ceil($totalweight)>1) ? $row['kgp']+$row['kgs']*(ceil($totalweight)-1) : $row['kgp'];
						// if ($row['bpsw'] != 0) $tarifpenerus = (ceil($totalweight)>$bppw) ? $bpp+$bps*ceil(($totalweight-$row['bppw'])/$row['bpsw']) : $row['bpp'];
						// $tariflain = ($tarifpenerus==0) ? 0 : $row['addrp'];
						// $tariftotalrp = $tariftiki+$tarifpenerus+$tariflain;
						// /* -------- PERHITUNGAN ONGKIR -------- */
						// $biaya = number_format($tariftotalrp, 0, ',', '.');
						// // $estimasi = ($row['estimasi'] != '') ? "{$row['estimasi']} " . _DAYS : "";
						// $data .= <<<HTML
						// <li>
							// <input type="radio" name="postageprice" id="postageprice{$row['courier_id']}{$row['id']}" value="$totalbiaya" data-courier_id="{$row['courier_id']}" data-tipe="$courier_name, {$row['kota']}" data-estimasi="$estimasi" required>
							// <label for="postageprice{$row['courier_id']}{$row['id']}">
								// <span class="courier_attr">$courier_name {$row['kota']} <strong>Rp $biaya</strong></span>
							// </label>
						// </li>
// HTML;
					// }
					// $data .= "</ul>";
				// }
			// }
		// }
		
	}

	echo $data;
}


if ($action == 'remove_attr') {
	unset($_SESSION['insurance_value']);
	unset($_SESSION['use_insurance']);
	unset($_SESSION['use_packing']);
	unset($_SESSION['packing_value']);
}

if ($action == 'updateCart') {
	
    foreach ($_SESSION['cart'] as $product_id => $quantityUpdated) {
		
		if ($product_id == $_POST['product_id']) {
			$quantityUpdated = $_POST['quantity'];
		}
		
        if (is_numeric($quantityUpdated)) {
		    $_SESSION['cart'][$product_id] = $quantityUpdated; //change the quantity of the product with id $product_id and $quantity
            if ($_SESSION['cart'][$product_id] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            } //if the quantity is zero, remove it completely (using the 'unset' function) - otherwise is will show zero, then -1, -2 etc when the user keeps updating items. 
        }
    }
}
?>
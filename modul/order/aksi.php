<?php
//$_REQUEST tak ganti $_GET karena nga-bug seperti kemarin
$action = fiestolaundry($_GET['action'],20);
$txid = fiestolaundry($_GET['pid'],20);

//die($action);
$sql = "SELECT judulfrontend FROM module WHERE nama='order'";
$result = $mysql->query($sql);
list($title) = $mysql->fetch_row($result);

if ($action == '' || $action=='view') {
	$txid=$_GET['id'];
	$sql = "SELECT tanggal_buka_transaksi, tanggal_tutup_transaksi, customer_id, receiver_id, metode_pembayaran, ongkir, status, catatan, no_pengiriman, latestupdate, kodevoucher, tipe_voucher, nominal_voucher, packingvalue, insurancevalue, kurir FROM sc_transaction WHERE transaction_id='$txid'";
	$result = $mysql->query($sql);
	list($tanggal_buka_transaksi, $tanggal_tutup_transaksi, $customer_id, $receiver_id, $metode_pembayaran, $shipping, $status, $catatan, $no_pengiriman, $latestupdate, $kodevoucher, $tipe_voucher, $nominal_voucher, $packingvalue, $insurancevalue, $kurir) = $mysql->fetch_row($result);
	
	$couriers 		= json_decode($kurir, true);
	// $kecamatan 		= $couriers['kota'];
	$kurir 			= $couriers['tipe'] . ' ' . $couriers['kota'];
	$kurir_tipe		= $couriers['tipe'];
	$ongkos_kirim 	= number_format($shipping, 0, ',', '.');
	$estimasi 		= $couriers['estimasi'];
	
	$content .= "<div id=\"checkout-form\">\r\n";
	$content .= "<div class=\"status_order\"><h2>"._ORDERSTATUS."</h2>\r\n";
	$content .= "<p>";
	switch ($status) {
		case 0:
		$content .= _STATUS0;
		break;
		case 1:
		$content .= _STATUS1;
		break;
		case 2:
		$content .= _STATUS2;
		break;
		case 3:
		$content .= _STATUS3;
		break;
		case 4:
		$content .= _STATUS4." $no_pengiriman";
		break;
	}
	// $content .= " ("._LATESTUPDATE.": ".tglformat($latestupdate,true).")</p>\r\n";
	
	$content .= "</div>\r\n";
	$content .= "<div class=\"data_order\">\r\n";
	// $content .= "<h2>"._ORDERDETAILS."</h2>\r\n";
	$content .= "<table border=\"0\">\r\n";
	$content .= "<tr><td>"._CURRENTORDERTITLE."</td><td>:</td><td>$txid</td></tr>\r\n";
	$content .= "<tr><td>"._ORDERDATE."</td><td>:</td><td>".tglformat($tanggal_buka_transaksi,true)."</td></tr>\r\n";
	$content .= "</table></div>\r\n";								
	$content .= "<div class=\"table_block table-responsive table-small-gap\">\r\n";				
	$content .= "<table border=\"0\" cellpadding=\"3\" class=\"list-order-product table\" width=\"100%\">"; //format the cart using a HTML table
	//iterate through the cart, the $product_id is the key and $quantity is the value
	$content .= "<thead><tr style=\"background: #eee;font-weight: bold;\">";
	$content .= "<th>"._THUMBNAIL."</th>";
	$content .= "<th>"._PRODUCTNAME."</th>";
	$content .= "<th>"._PRICE."</th>";
	$content .= "<th>"._QUANTITY."</th>";
	$content .= "<th>"._LINECOST."</th>";
	$content .= "</tr></thead>";
	$totalweight = 0;
	
	// $sql = "SELECT jumlah, harga, product_name,filename FROM sc_transaction_detail WHERE transaction_id='$txid'";
	$sql = "SELECT jumlah,td.harga,product_name,td.filename, c.idmerek, attribut_id FROM sc_transaction_detail td INNER JOIN catalogdata c ON c.id=td.product_id  WHERE transaction_id='$txid' ORDER BY transaction_detail_id";
	$result = $mysql->query($sql);
	while (list($quantity, $price, $productname,$filename, $idmerek, $attribut_id) = $mysql->fetch_row($result)) {
		$brand_name = get_brand_name($idmerek);
		
		if ($pakaicatalogattribut) {
			$product_attribut = get_product_attribut($attribut_id);
			
			if ($product_attribut['price'] > 0) $price = $price + $product_attribut['price'];
		}
		
		$line_cost = $quantity * $price;  //work out the line cost
		$total = $total + $line_cost;   //add to the total cost
		$content .= "<tr>";
		$content .= "<td>";
		if(file_exists("$cfg_thumb_path/$filename")) {
			$content.="<img src=\"$cfg_thumb_url/$filename\" width=\"50px\" class=\"thumb_small\" >";
		} else {
			$content.="<img src=\"$cfg_app_url/images/none.gif\" width=\"50px\" class=\"thumb_small\" >";
		}
		$content .="</td>";
		$content .= "<td><small style=\"font-size: 15px;font-weight: bold;color: #000000;line-height: 1.5;text-transform:uppercase\" class=\"brand-name\">$brand_name</small><p class=\"product-name\">$productname</p>";
		
		if ($pakaicatalogattribut) {
			// if ($product_attribut['color'] != '' || $product_attribut['size'] != '') {
				// $content .= '<small class="product-attribut">'.$product_attribut['color'].', '.$product_attribut['size'].'</small>';
			// }
			
			switch($catalog_attribute_option) {
				case 0:
					if ($product_attribut['color'] != '' || $product_attribut['size'] != '') {
						$content .= '<small class="product-attribut">'.$product_attribut['color'].', '.$product_attribut['size'].'</small>';
					}
					break;
				case 1:
					if ($product_attribut['size'] != '') {
						$content .= '<small class="product-attribut">'.$product_attribut['size'].'</small>';
					}
					break;
				case 2:
					if ($product_attribut['color'] != '') {
						$content .= '<small class="product-attribut">'.$product_attribut['color'].'</small>';
					}
					break;
			}
		}
		$content .= '</td>';
		$content .= "<td align=\"right\">".number_format($price, 0, ',', '.')."</td>";
		$content .= "<td align=\"center\">".number_format($quantity, 0, ',', '.')."</td>";
		$content .= "<td align=\"right\">".number_format($line_cost, 0, ',', '.')."</td>";
		$content .= "</tr>";
	}
	
	$content .= "<tr class=\"noborder\">";
	$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;border-top:1px solid #ddd\">"._SUBTOTAL."</td>";
	$content .= "<td align=\"right\" style=\"border-top:1px solid #ddd\">".number_format($total, 0, ',', '.')."</td>";
	$content .= "</tr>";

	if ($pakaivoucher) {
		if ($nominal_voucher > 0) {
			
			if ($tipe_voucher == 1) {
				$voucher = number_format($nominal_voucher, 0, ',', '.');
				$disc = $total - $nominal_voucher;
				$total = $disc;
			} else if ($tipe_voucher == 2) {
				$disc = 100 - $nominal_voucher;
				$temp = (($disc/100) * $total);
				$voucher = (int)$nominal_voucher . '%';
				$total = $temp;
			}
		} else {
			$voucher = 0;
		}
		$content .= "<tr class=\"noborder\">";
		$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._DISCOUNT."</td>";
		$content .= "<td align=\"right\">".$voucher."</td>";
		$content .= "</tr>";
	}
	
	if ($pakaiadditional) {
		if ($insurancevalue > 0) $total += $insurancevalue;
		$content .= "<tr class=\"noborder\">";
		$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._USEINSURANCE."</td>";
		$content .= "<td align=\"right\">".number_format($insurancevalue, 0, ',', '.')."</td>";
		$content .= "</tr>";
		
		if ($packingvalue > 0) $total += $packingvalue;
		$content .= "<tr class=\"noborder\">";
		$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._USEPACKING."</td>";
		$content .= "<td align=\"right\">".number_format($packingvalue, 0, ',', '.')."</td>";
		$content .= "</tr>";
	}
		
	//START MENENTUKAN PENGGUNAAN ONGKOS KIRIM
	if ($pakaiongkir) {
		$content .= "<tr class=\"noborder\">";
		// $content .= "<td colspan=\"4\" align=\"right\">"._POSTAGE." ($kurir_tipe)</td>";
		$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._POSTAGE."</td>";
		$content .= "<td align=\"right\">".number_format($shipping, 0, ',', '.')."</td>";
		$content .= "</tr>";
		$total = $total + $shipping;
	}
	//END MENENTUKAN PENGGUNAAN ONGKOS KIRIM
	$content .= "<tr class=\"noborder total_order\">";
	$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._TOTAL."</td>";
	$content .= "<td align=\"right\">".number_format($total, 0, ',', '.')."</td>";
	$content .= "</tr>";
	$content .= "</table>";
	$content .= "</div>";

	// $sql = "SELECT nama_lengkap, email, alamat, alamat2, kota, provinsi, zip, telepon, cellphone1, cellphone2 FROM sc_customer WHERE customer_id='$customer_id'";
	// $result = $mysql->query($sql);
	// list($nama_lengkap, $email, $alamat, $alamat2, $kota, $provinsi, $zip, $telepon, $cellphone1, $cellphone2) = $mysql->fetch_row($result);
	// $content .= "<h2>"._PERSONALDETAILS."</h2>\r\n";
	// $content .= "<table border=\"0\">\r\n";
	// $content .= "<tr><td>"._FULLNAME."</td><td>:</td><td>$nama_lengkap</td></tr>\r\n";
	// $content .= "<tr><td>"._EMAIL."</td><td>:</td><td>$email</td></tr>\r\n";
	// $content .= "<tr valign=\"top\"><td>"._ADDRESS."</td><td>:</td><td>$alamat";
	// $content .= ($alamat2!='') ? "<br />$alamat2" : '';
	// $content .= "</td></tr>\r\n";
	// $content .= "<tr><td>"._STATE."</td><td>:</td><td>$provinsi</td></tr>\r\n";
	// $content .= "<tr><td>"._CITY."</td><td>:</td><td>$kota</td></tr>\r\n";
	// $content .= "<tr><td>"._ZIP."</td><td>:</td><td>$zip</td></tr>\r\n";
	// $content .= "<tr><td>"._TELEPHONE."</td><td>:</td><td>$telepon</td></tr>\r\n";
	// $content .= "<tr><td>"._CELLPHONE1."</td><td>:</td><td>$cellphone1</td></tr>\r\n";
	// $content .= "<tr><td>"._CELLPHONE2."</td><td>:</td><td>$cellphone2</td></tr>\r\n";
	// $content .= "</table>\r\n";

	$sql = "SELECT nama_lengkap, email, alamat, alamat2, kota, provinsi, zip, telepon, cellphone1, cellphone2, nama_pengirim, telp_pengirim, email_pengirim  FROM sc_receiver WHERE receiver_id='$receiver_id'";
	
	$result = $mysql->query($sql);
	list($nama_lengkap, $email, $alamat, $alamat2, $kota, $provinsi, $zip, $telepon, $cellphone1, $cellphone2, $sendername, $senderphone, $senderemail) = $mysql->fetch_row($result);
	
	// <th>" . _PERSONALDETAILS . "</th>
	// $content .= "<table border=\"1\" cellpadding=\"3\" style=\"margin-top:15px;color:#000;border-collapse: collapse;border: 1px solid #bbb;\" class=\"table table-bordered\" width=\"100%\"><tr style=\"background: #eee;font-weight: bold;\"><th>" . _RECEIVERDETAILS . "</th>\r\n";
	// if ($sendername != '') {
		// $content .= "<th>"._DATASENDER."</th>\r\n";
	// }
	// $content .= "</tr>";
	// $content .= "<tr>";
	// // $content .= "<td valign=\"top\">\r\n";
	// // $content .= "$fullname<br/>\r\n";
	// // $content .= "$email<br/>\r\n";
	// // $content .= "$address<br/>";
	// // $content .= ($address2 != '') ? "$address2<br/>" : '';
	// // $content .= "$state<br/>\r\n";
	// // $content .= "$city<br/>\r\n";
	// // $content .= "$zip<br/>\r\n";
	// // $content .= "$phone<br/>\r\n";
	// // $content .= "$cellphone1<br/>\r\n";
	// // $content .= "$cellphone2</td>\r\n";
	// $content .= "<td valign=\"top\">$nama_lengkap<br/>\r\n";
	// // $content .= "$email<br/>\r\n";
	// $content .= "$alamat<br/>";
	// $content .= ($alamat2 != '') ? "$alamat2<br/>" : '';
	// $content .= "$kecamatan<br/>\r\n";
	// $content .= "$provinsi<br/>\r\n";
	// $content .= "$kota<br/>\r\n";
	// $content .= "$zip<br/>\r\n";
	// // $content .= "$phoneReceiver<br/>\r\n";
	// $content .= "$cellphone1<br/>\r\n";
	// $content .= "$cellphone2</td>\r\n";
	
	// if ($sendername != '') {
		// $content .= "<td valign=\"top\">\r\n";
		// $content .= "<table border=\"0\" class=\"table-pengirim\">\r\n";
		// $content .= "<tr><td>"._FULLNAME."</td><td>:</td><td>$sendername</td></tr>\r\n";
		// $content .= "<tr><td>"._TELEPHONE."</td><td>:</td><td>$senderphone</td></tr>\r\n";
		// if (!isset($_SESSION['member_uid'])) {
			// $content .= "<tr><td>"._EMAIL."</td><td>:</td><td>$senderemail</td></tr>\r\n";
		// }
		// $content .= "</table></td>\r\n";
	// }
	// $content .= "</tr></table>\r\n";
	
	$content .= "<div class=\"receive_data\">";
	$content .= "<tr style=\"background: #eee;font-weight: bold;\">";
	$content .= "<h2>"._RECEIVERDETAILS."</h2>\r\n";
	$content .= "</tr><tr>\r\n";
	$content .= "<p>$nama_lengkap</p>\r\n";
	// $content .= "<tr><td>"._EMAIL."</td><td>:</td><td>$email</td></tr>\r\n";
	$content .= "<p>$alamat</p>";
	$content .= ($alamat2!='') ? "<p>$alamat2</p>" : '<br>';
	if ($kecamatan != '') $content .= "<p>$kecamatan</p>\r\n";
	if ($provinsi != "") $content .= "<p>$provinsi</p>\r\n";
	$content .= "<p>$kota</p>\r\n";
	$content .= "<p>$zip</p>\r\n";
	// $content .= "<tr><td>"._TELEPHONE."</td><td>:</td><td>$telepon</td></tr>\r\n";
	$content .= "<p>$cellphone1</p>\r\n";
	if ($cellphone2 != "") $content .= "<p>$cellphone2</p>\r\n";

	
	if ($sendername != '') {
	$content .= "<h2>"._DATASENDER."</h2>\r\n";
	}
	if ($sendername != '') {
		$content .= "<p>$sendername</p>\r\n";
		$content .= "<p>$senderphone</p>\r\n";
		if ($senderemail != '') {
			$content .= "<p>$senderemail</p>\r\n";
		}
	}
	
	$content .= "</div>\r\n";
	
	$sql = "SELECT logo,bank,cabang,norekening,atasnama FROM sc_payment WHERE bank = '$metode_pembayaran'";
	$result = $mysql->query($sql);
	list($logo,$bank,$cabang,$norekening,$atasnama) = $mysql->fetch_row($result);
	
	// $content .= "<table border=\"1\" cellpadding=\"3\" style=\"margin-top:15px;color:#000;border-collapse: collapse;border: 1px solid #bbb;\" class=\"table table-bordered\" width=\"100%\"><tr style=\"background: #eee;font-weight: bold;\"><th>"._POSTAGE."</th><th>"._PAYMENTINFO."</th>\r\n";
	
	// if ($catatan!='') {
		// $content .= "<th>"._NOTE."</th>\r\n";
	// }
	// $content .= "</tr>";
	// $content .= "<tr>";
	// $content .= "<td class='rekening_info'>
	// "._COURIER.": $kurir<br/>
	// "._POSTAGE.": $ongkos_kirim<br/>
	// "._SHIPPINGTIME.": $estimasi<br/>
	// </td>";
	// $content .= "<td valign=\"top\">";
	// $content .= "<table  class='rekening'>";
	// $content .= "<tr>";
	// if ($logo != '' && file_exists("$cfg_payment_path/$logo")) {
		// $content .= "<td class='rekening_logo'><img src='".$cfg_payment_logo."/$logo' alt='$bank'></td>";
	// }
	// $content .= "<td class='rekening_info'>
	// $bank $cabang<br/>
	// $norekening<br/>
	// $atasnama<br/>
	// </td>";
	// $content .= "</tr></table>";
	// $content .= "</td>";

	// if ($catatan!='') {
		// $content .= "<td valign=\"top\"><div id=\"ordernote\" style=\"color:#000\">$catatan</div></td>\r\n";
	// }
	// $content .= "</tr></table>\r\n";
	
	$content .= "<div class=\"data-lain\">\r\n";
	if ($pakaiongkir) $content .= "<h2>"._POSTAGE."</h2>\r\n";
	if ($pakaiongkir) {
		if ($catatan!='') {
			// $content .= "<td width=\"33.333%\">";
		} else {
		}
		
		$content .= "
		<p>$kurir</p>
		<p>$ongkos_kirim $estimasi</p>";
	}
	
	$content .= "<h2>"._PAYMENTINFO."</h2>\r\n";
	if ($catatan!='') {
		// $content .= "<td width=\"33.333%\">";
	} else {
		// $content .= "<td width=\"50%\">";
	}
	$content .= "<table class='rekening'>";
	$content .= "<tr>";
	if ($logo != '' && file_exists("$cfg_payment_path/$logo")) {
		$content .= "<td class='rekening_logo'><img src='".$cfg_payment_logo."/$logo' alt='$bank'></td>";
	}
	$content .= "<td class='rekening_info'>
	$bank $cabang<br/>
	No. Rek $norekening<br/>
	a/n $atasnama<br/>
	</td>";
	$content .= "</tr>";
	$content .= "</table>";
	
	if ($catatan!='') {
		$content .= "<h2>"._NOTE."</h2>\r\n";
	}
	if ($catatan!='') {
		$content .= "<p>$catatan</p>\r\n";
	}
	$content .= "</div>";
	$content .= "</div>";
	
	// if (!isset($_SESSION['member_uid']) && $status <=1) {
		// $content .= "<div id=\"confirmPayment\" class=\"row\"><div class=\"col-sm-12\">".payment_confirmation()."</div></div>";
	// }
	

}

/* if ($action == 'history') {
	$title = _ORDERHISTORY;
	if ($_SESSION['member_uid']!='') {
		
		$sql = "SELECT tx.transaction_id, tx.tanggal_buka_transaksi, tx.status, tx.ongkir,tx.no_pengiriman, status_pembayaran FROM sc_transaction tx, sc_customer c WHERE c.web_user_id='".$_SESSION['member_uid']."' AND c.customer_id=tx.customer_id ORDER BY autoid DESC";
		
		$result = $mysql->query($sql);
		$content .= "<table class=\"systemtable table-striped\" border=\"1\" width=\"100%\">\r\n";
		$content .= "<tr><th>"._CURRENTORDERTITLE."</th><th>"._STATUSPEMBAYARAN."</th><th>"._ORDERDATE."</th><th>"._TOTAL."</th><th>"._POSTAGE."</th><th>"._ORDERSTATUS."</th></tr>\r\n";
		while (list($txid, $txdate, $status, $ongkir,$no_pengiriman, $status_pembayaran) = $mysql->fetch_row($result)) {
			$sql1 = "SELECT jumlah, harga FROM sc_transaction_detail WHERE transaction_id='$txid'";
			$result1 = $mysql->query($sql1);
			$total = 0;
			while (list($qty, $harga) = $mysql->fetch_row($result1)) {
				$total = $total + $qty * $harga;
			}
			switch ($status) {
				case 0:
					$temp = _STATUS0;
					break;
				case 1:
					$temp = _STATUS1;
					break;
				case 2:
					$temp = _STATUS2;
					break;
				case 3:
					$temp = _STATUS3;
					break;
				case 4:
					$temp = _STATUS4." $no_pengiriman";
					break;
			}
			
			if ($status_pembayaran == 0) {
				$link_pembayaran = "<a href=\"".$urlfunc->makePretty("?p=pembayaran_gadget&action=add&id=$txid")."\">".$array_status_pembayaran[$status_pembayaran]."</a>";
			} else {
				$link_pembayaran = ($status_pembayaran < 3) ? "<a href=\"".$urlfunc->makePretty("?p=pembayaran_gadget&action=edit&id=$txid")."\">".$array_status_pembayaran[$status_pembayaran]."</a>" : $array_status_pembayaran[$status_pembayaran];
			}
			$content .= "<tr valign=\"top\"><td><a href=\"".$urlfunc->makePretty("?p=order&action=view&id=$txid")."\">$txid</td><td>$link_pembayaran</td><td>".tglformat($txdate,true)."</td><td align=\"right\">".number_format($total,0,',','.')."</td><td align=\"right\">".number_format($ongkir,0,',','.')."</td><td>$temp</td></tr>\r\n";
		}
		$content .= "</table>\r\n";
	}
	
} */

if ($action == 'history') {
	$title = _ORDERHISTORY;
	if ($_SESSION['member_uid']!='') {
		
		// $sql = "SELECT tx.transaction_id, tx.tanggal_buka_transaksi, tx.status, tx.ongkir,tx.no_pengiriman, status_pembayaran, tx.nominal, tx.tanggal_konfirmasi FROM sc_transaction tx, sc_customer c WHERE c.web_user_id='".$_SESSION['member_uid']."' AND c.customer_id=tx.customer_id ORDER BY autoid DESC";
		
		$sql = "SELECT tx.transaction_id, tx.tanggal_buka_transaksi, tx.status, tx.ongkir,tx.no_pengiriman, metode_pembayaran, tx.nominal, tx.tanggal_konfirmasi, kodevoucher, tipe_voucher, nominal_voucher, kurir FROM sc_transaction tx, sc_customer c WHERE c.web_user_id='".$_SESSION['member_uid']."' AND c.customer_id=tx.customer_id ORDER BY autoid DESC";
		
		$result = $mysql->query($sql);
		$content .= "<table class=\"table systemtable table-striped\" border=\"0\" width=\"100%\">\r\n";
		$content .= "<thead><tr><th>"._CURRENTORDERTITLE."</th><th>"._ORDERDATE."</th><th>"._TOTAL."</th><th>"._ORDERSTATUS."</th></tr></thead><tbody>\r\n";
		while (list($txid, $txdate, $status, $ongkir,$no_pengiriman, $status_pembayaran, $nominal, $tanggal_konfirmasi, $kodevoucher, $tipe_voucher, $nominal_voucher, $kurir) = $mysql->fetch_row($result)) {
			$sql1 = "SELECT jumlah, harga FROM sc_transaction_detail WHERE transaction_id='$txid'";
			$result1 = $mysql->query($sql1);
			$total = 0;
			while (list($quantity, $price) = $mysql->fetch_row($result1)) {
				// $total = $total + $qty * $harga;
				
				$line_cost = $quantity * $price;  //work out the line cost
				$total = $total + $line_cost;   //add to the total cost
			}
			switch ($status) {
				case 0:
					$temp = _STATUS0;
					break;
				case 1:
					$temp = _STATUS1;
					break;
				case 2:
					$temp = _STATUS2;
					break;
				case 3:
					$temp = _STATUS3;
					break;
				case 4:
					$temp = _STATUS4." $no_pengiriman";
					break;
			}
			
			if ($pakaivoucher) {
				if ($nominal_voucher > 0) {
			
					if ($tipe_voucher == 1) {
						$voucher = number_format($nominal_voucher, 0, ',', '.');
						$disc = $total - $nominal_voucher;
						$total = $disc;
					} else if ($tipe_voucher == 2) {
						$disc = 100 - $nominal_voucher;
						$temp_total = (($disc/100) * $total);
						$voucher = (int)$nominal_voucher . '%';
						$total = $temp_total;
					}
				} else {
					$voucher = 0;
				}
				// $content .= "<tr>";
				// $content .= "<td colspan=\"4\" align=\"right\">"._DISCOUNT."</td>";
				// $content .= "<td align=\"right\">".$voucher."</td>";
				// $content .= "</tr>";
			}
			
			if ($pakaiadditional) {
				if ($insurancevalue > 0) $total += $insurancevalue;
				// $content .= "<tr>";
				// $content .= "<td colspan=\"4\" align=\"right\">"._USEINSURANCE."</td>";
				// $content .= "<td align=\"right\">".number_format($insurancevalue, 0, ',', '.')."</td>";
				// $content .= "</tr>";
				
				if ($packingvalue > 0) $total += $packingvalue;
				// $content .= "<tr>";
				// $content .= "<td colspan=\"4\" align=\"right\">"._USEPACKING."</td>";
				// $content .= "<td align=\"right\">".number_format($packingvalue, 0, ',', '.')."</td>";
				// $content .= "</tr>";
			}
			
			//START MENENTUKAN PENGGUNAAN ONGKOS KIRIM
			if ($pakaiongkir) {
				// $content .= "<tr>";
				// $content .= "<td colspan=\"4\" align=\"right\">"._POSTAGE." ($kurir_tipe)</td>";
				// $content .= "<td align=\"right\">".number_format($shipping, 0, ',', '.')."</td>";
				// $content .= "</tr>";
				$total = $total + $shipping;
				
				if ($ongkir > 0) {
					$total = $total + $ongkir;
				}
			}
			//END MENENTUKAN PENGGUNAAN ONGKOS KIRIM
			
			// if ($status_pembayaran == 0) {
				// $link_pembayaran = "<a href=\"".$urlfunc->makePretty("?p=pembayaran_gadget&action=add&id=$txid")."\">".$array_status_pembayaran[$status_pembayaran]."</a>";
			// } else {
				// $link_pembayaran = ($status_pembayaran < 3) ? "<a href=\"".$urlfunc->makePretty("?p=pembayaran_gadget&action=edit&id=$txid")."\">".$array_status_pembayaran[$status_pembayaran]."</a>" : $array_status_pembayaran[$status_pembayaran];
			// }
			
			if ($status == 1 && $nominal == 0 && $tanggal_konfirmasi == '0000-00-00 00:00:00') {
				$temp ="<a href=\"".$urlfunc->makePretty("?p=order&action=confirm_payment&id=$txid")."\"><button type='button'>"._CONFIRMPAYMENT."</button></a>";
			}
			
			$content .= "<tr valign=\"top\"><td><a href=\"".$urlfunc->makePretty("?p=order&action=view&id=$txid")."\">$txid</td>";
			$content .= "<td>".tglformat($txdate,true)."</td>";
			$content .= "<td align=\"right\">".number_format($total,0,',','.')."</td>";
			$content .= "<td>$temp</td></tr>\r\n";
		}
		$content .= "</tbody></table>\r\n";
	}
	
}

if ($action == 'trackorder'){
	
		$orderid = fiestolaundry($_REQUEST['order_orderid'],20);
		$email = fiestolaundry($_REQUEST['order_email'],50);
		if(strlen($orderid)>0)
		{
		$sql = "
		SELECT tx.transaction_id, tx.tanggal_buka_transaksi, tx.status, tx.ongkir,tx.no_pengiriman 
		FROM sc_transaction tx, sc_customer c,sc_receiver rc 
		WHERE 
		tx.transaction_id='".$orderid."' AND 
		c.customer_id=tx.customer_id
		AND rc.receiver_id=tx.receiver_id
		";
		$result = $mysql->query($sql);
		if($result and $mysql->num_rows($result)>0)
		{
		$content .= "<table class=\"systemtable table table-striped\">\r\n";
		$content .= "<tr><th>"._CURRENTORDERTITLE."</th><th>"._ORDERDATE."</th><th>"._TOTAL."</th><th>"._POSTAGE."</th><th>"._ORDERSTATUS."</th></tr>\r\n";
		while (list($txid, $txdate, $status, $ongkir,$no_pengiriman) = $mysql->fetch_row($result)) {
			$sql1 = "SELECT jumlah, harga FROM sc_transaction_detail WHERE transaction_id='$txid'";
			$result1 = $mysql->query($sql1);
			$total = 0;
			while (list($qty, $harga) = $mysql->fetch_row($result1)) {
				$total = $total + $qty * $harga;
			}
			switch ($status) {
				case 0:
					$temp = _STATUS0;
					break;
				case 1:
					$temp = _STATUS1;
					break;
				case 2:
					$temp = _STATUS2;
					break;
				case 3:
					$temp = _STATUS3;
					break;
				case 4:
					$temp = _STATUS4." $no_pengiriman";
					break;
					
			}
			$content .= "<tr valign=\"top\"><td><a href=\"".$urlfunc->makePretty("?p=order&action=view&id=$txid")."\">$txid</td><td>".tglformat($txdate,true)."</td><td align=\"right\">".number_format($total,0,',','.')."</td><td align=\"right\">".number_format($ongkir,0,',','.')."</td><td>$temp</td></tr>\r\n";
		}
		$content .= "</table>\r\n";
		}
	 }
	 else
	 {
		 $content=_ORDERNOTFOUND;
	 }
}

if ($action == 'confirm_payment') {
	$title = _CONFIRMPAYMENT;
	$sql = "SELECT tanggal_buka_transaksi, tanggal_tutup_transaksi, customer_id, receiver_id, metode_pembayaran, ongkir, status, catatan, no_pengiriman, latestupdate, kodevoucher, tipe_voucher, nominal_voucher, packingvalue, insurancevalue, kurir, tanggal_konfirmasi, nominal FROM sc_transaction WHERE transaction_id='$txid'";
	
	$result = $mysql->query($sql);
	list($tanggal_buka_transaksi, $tanggal_tutup_transaksi, $customer_id, $receiver_id, $metode_pembayaran, $shipping, $status, $catatan, $no_pengiriman, $latestupdate, $kodevoucher, $tipe_voucher, $nominal_voucher, $packingvalue, $insurancevalue, $kurir, $tanggal_konfirmasi, $nominal) = $mysql->fetch_row($result);
	
	/* $couriers 		= json_decode($kurir, true);
	$kecamatan 		= $couriers['kota'];
	$kurir 			= $couriers['tipe'] . ' ' . $couriers['kota'];
	$kurir_tipe		= $couriers['tipe'];
	$ongkos_kirim 	= number_format($shipping, 0, ',', '.');
	$estimasi 		= $couriers['estimasi'];
	
	$content .= "<div id=\"checkout-form\">";
	$content .= "<h2>"._ORDERSTATUS."</h2>\r\n";
	$content .= "<p>";
	switch ($status) {
		case 1:
			$content .= _STATUS1;
			break;
		case 2:
			$content .= _STATUS2;
			break;
		case 3:
			$content .= _STATUS3." $no_pengiriman";
			break;
	}
	// $content .= " ("._LATESTUPDATE.": ".tglformat($latestupdate,true).")</p>\r\n";
	
	$content .= "<h2>"._ORDERDETAILS."</h2>\r\n";
	$content .= "<table border=\"0\">\r\n";
	$content .= "<tr><td>"._CURRENTORDERTITLE."</td><td>:</td><td>$txid</td></tr>\r\n";
	$content .= "<tr><td>"._ORDERDATE."</td><td>:</td><td>".tglformat($tanggal_buka_transaksi,true)."</td></tr>\r\n";
	$content .= "</table><br />\r\n";				
	$content .= "<div class=\"table_block table-responsive table-small-gap\">\r\n";
	$content .= "<table border=\"0\" class=\"table table-bordered\" cellpadding=\"3\">"; //format the cart using a HTML table
	//iterate through the cart, the $product_id is the key and $quantity is the value
	$content .= "<thead><tr style=\"background: #eee;font-weight: bold;\">";
	$content .= "<th>"._THUMBNAIL."</th>";
	$content .= "<th>"._PRODUCTNAME."</th>";
	$content .= "<th>"._PRICE."</th>";
	$content .= "<th>"._QUANTITY."</th>";
	$content .= "<th>"._LINECOST."</th>";
	$content .= "</tr></thead>";
	$totalweight = 0;
	
	// $sql = "SELECT jumlah, harga, product_name,filename FROM sc_transaction_detail WHERE transaction_id='$txid'";
	$sql = "SELECT jumlah,td.harga,product_name,td.filename, c.idmerek,attribut_id FROM sc_transaction_detail td INNER JOIN catalogdata c ON c.id=td.product_id  WHERE transaction_id='$txid' ";
	$result = $mysql->query($sql);
	while (list($quantity, $price, $productname,$filename,$idmerek,$attribut_id) = $mysql->fetch_row($result)) {
		$brand_name = get_brand_name($idmerek);
		
		if ($pakaicatalogattribut) {
			$product_attribut = get_product_attribut($attribut_id);
			if ($product_attribut['price'] > 0) $price = $price + $product_attribut['price'];
		}

		$line_cost = $quantity * $price;  //work out the line cost
		$total = $total + $line_cost;   //add to the total cost
		$content .= "<tr>";
		$content .= "<td>";
		if(file_exists("$cfg_thumb_path/$filename")) {
			$content.="<img src=\"$cfg_thumb_url/$filename\" width=\"50px\" class=\"thumb_small\" >";
		} else {
			$content.="<img src=\"$cfg_app_url/images/none.gif\" width=\"50px\" class=\"thumb_small\" >";
		}
		$content .="</td>";
		// $content .= "<td>$productname</td>";
		// $content .= "<td><small style=\"font-size: 15px;font-weight: bold;color: #000000;line-height: 1.5;text-transform:uppercase\" class=\"brand-name\">$brand_name</small><p class=\"product-name\">$productname</p></td>";
		$content .= "<td><small style=\"font-size: 15px;font-weight: bold;color: #000000;line-height: 1.5;text-transform:uppercase\" class=\"brand-name\">$brand_name</small><p class=\"product-name\">$productname</p>";
		if ($pakaicatalogattribut) {
			if ($product_attribut['color'] != '' || $product_attribut['size'] != '') {
				$content .= '<small class="product-attribut">'.$product_attribut['color'].', '.$product_attribut['size'].'</small>';
			}
		}
		$content .= '</td>';
		$content .= "<td align=\"right\">".number_format($price, 0, ',', '.')."</td>";
		$content .= "<td align=\"center\">".number_format($quantity, 0, ',', '.')."</td>";
		$content .= "<td align=\"right\">".number_format($line_cost, 0, ',', '.')."</td>";
		$content .= "</tr>";
	}
	
	$content .= "<tr>";
	$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;border-top:1px solid #ddd\">"._SUBTOTAL."</td>";
	$content .= "<td align=\"right\" style=\"border-top:1px solid #ddd\">".number_format($total, 0, ',', '.')."</td>";
	$content .= "</tr>";

	if ($pakaivoucher) {
		if ($nominal_voucher > 0) {
			
			if ($tipe_voucher == 1) {
				$voucher = number_format($nominal_voucher, 0, ',', '.');
				$disc = $total - $nominal_voucher;
				$total = $disc;
			} else if ($tipe_voucher == 2) {
				$disc = 100 - $nominal_voucher;
				$temp = (($disc/100) * $total);
				$voucher = (int)$nominal_voucher . '%';
				$total = $temp;
			}
		} else {
			$voucher = 0;
		}
		$content .= "<tr>";
		$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._DISCOUNT."</td>";
		$content .= "<td align=\"right\">".$voucher."</td>";
		$content .= "</tr>";
	}
	
	if ($pakaiadditional) {
		if ($insurancevalue > 0) $total += $insurancevalue;
		$content .= "<tr>";
		$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._USEINSURANCE."</td>";
		$content .= "<td align=\"right\">".number_format($insurancevalue, 0, ',', '.')."</td>";
		$content .= "</tr>";
		
		if ($packingvalue > 0) $total += $packingvalue;
		$content .= "<tr>";
		$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._USEPACKING."</td>";
		$content .= "<td align=\"right\">".number_format($packingvalue, 0, ',', '.')."</td>";
		$content .= "</tr>";
	}
	
	//START MENENTUKAN PENGGUNAAN ONGKOS KIRIM
	if ($pakaiongkir) {
		$content .= "<tr>";
		$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">$kurir_tipe</td>";
		$content .= "<td align=\"right\">".number_format($shipping, 0, ',', '.')."</td>";
		$content .= "</tr>";
		$total = $total + $shipping;
	}
	//END MENENTUKAN PENGGUNAAN ONGKOS KIRIM
	$content .= "<tr>";
	$content .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._TOTAL."</td>";
	$content .= "<td align=\"right\">".number_format($total, 0, ',', '.')."</td>";
	$content .= "</tr>";
	$content .= "</table>";
	$content .= "</div>"; */
	
	if ($tanggal_konfirmasi == '0000-00-00 00:00:00' && floatval($nominal) == 0 && $status <= 1) {
		$content .= "<div id=\"confirmPayment\" class=\"row\"><div class=\"col-sm-12\">".payment_confirmation()."</div></div>";
	}
	
	// $content .= "</div>";
}
if ($action == 'do_confirm') {
	/* $action = fiestolaundry($_REQUEST['action'],20);
	$tanggal_konfirmasi = fiestolaundry($_POST['tanggal_konfirmasi']);
	$bank_tujuan = fiestolaundry($_POST['bank_tujuan'], 50);
	$bank_asal = fiestolaundry($_POST['bank_asal'], 50);
	$atas_nama = fiestolaundry($_POST['atas_nama'], 100);
	$no_rekening = fiestolaundry($_POST['no_rekening'], 30);
	$nominal = fiestolaundry($_POST['nominal'], 13);
	$trans_id = $_POST['trans_id'];
	$is_valid = true;
	$errors = array();
	
	if ($tanggal_konfirmasi == '') {
		$is_valid = false;
		$errors[] = 'Tanggal konfirmasi harus diisi';
	}
	if ($bank_tujuan == '') {
		$is_valid = false;
		$errors[] = 'Bank tujuan harus diisi';
	}
	if ($bank_asal == '') {
		$is_valid = false;
		$errors[] = 'Bank asal harus diisi';
	}
	if ($nominal == '') {
		$is_valid = false;
		$errors[] = 'Nominal harus diisi';
	}
	if (!is_numeric($nominal)) {
		$is_valid = false;
		$errors[] = 'Nominal harus berupa angka';		
	}
	
	if (sizeof($errors) > 0) {
		$content .= '<ul>';
		foreach($errors as $error) {
			$content .= '<li>'.$error.'</li>';
		}
		$content .= '</ul>';
		$content .= '<a class="back" href="javascript:history.back(-1)">'._BACK.'</a>';
	}
	
	if ($is_valid) {
		// $temp = explode('-',$tanggal_konfirmasi);
		// $tanggal_konfirmasi = "$temp[2]-$temp[1]-$temp[0]";
		$sql = "UPDATE sc_transaction SET status=2, tanggal_konfirmasi='$tanggal_konfirmasi', bank_tujuan='$bank_tujuan', bank_asal='$bank_asal', atas_nama='$atas_nama', no_rekening='$no_rekening', nominal='$nominal' WHERE transaction_id='$trans_id'";
		// echo $sql;
		// die();
		$url = $urlfunc->makePretty("?p=order&action=history");
		$result = $mysql->query($sql);
		if ($result) {
			$content .= send_email_to_admin($trans_id);
			if (!isset($_SESSION['member_uid'])) {
				header("Location: $cfg_app_url");
				exit();
			} else {
				header("Location: $url");
				exit();
			}
		}
	} */
	
	$trans_id = fiestolaundry($_POST['trans_id'], 10);
	$attachment = array();
	$isValid = true;
	
	if (!isset($trans_id)) {
		$isValid = false;
	}
	
	if ($isValid) {
		
		if ($_FILES['filename']['error'] == UPLOAD_ERR_OK) {
			$pathinfo = pathinfo($_FILES['filename']['name']);
			if ($_FILES['filename']['size'] > $maxupload) {
				$is_valid = false;
				$errors[] = "Oops! Your files size is to large";
			}
			
			if (!in_array($pathinfo['extension'], $allowedtypes)) {
				$errors[] = "The uploaded file is not supported file type \r\n Only the following file types are supported: ".implode(', ',$allowedtypes);
				$is_valid = false;
			}
			
			$tmpFilePath = $_FILES['filename']['tmp_name'];
				
			if ($tmpFilePath != ""){
				$newFilePath = $cfg_thumb_path .'/'. $_FILES['filename']['name'];

				if(move_uploaded_file($tmpFilePath, $newFilePath)) {
					$attachment = "$newFilePath";
				}
			}
			
			$now = date('Y-m-d H:i:s');
			$sql = "UPDATE sc_transaction SET status=2, tanggal_konfirmasi='$now' WHERE transaction_id='$trans_id'";
			$url = $urlfunc->makePretty("?p=order&action=history");
			
			if ($result = $mysql->query($sql)) {
				
				$content .= send_email_to_admin_upload($trans_id);
				if (!isset($_SESSION['member_uid'])) {
					header("Location: $cfg_app_url");
					exit();
				} else {
					header("Location: $url");
					exit();
				}
			}
		}
	}
	
}
?>
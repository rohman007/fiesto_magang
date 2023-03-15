<?php
function hitungtotal($txid) {
	global $mysqli;
	$sql = "SELECT jumlah, harga, attribut_id FROM sc_transaction_detail WHERE transaction_id='$txid'";
	// $sql = "SELECT jumlah, harga, t.ongkir FROM sc_transaction_detail td INNER JOIN sc_transaction t ON t.transaction_id=td.transaction_id WHERE t.transaction_id='$txid'";
	$result = $mysqli->query($sql);
	$total = 0;
	while (list($jumlah,$harga,$attributId) = $result->fetch_row()) {
		$product_attribut = get_product_attribut($attributId);
		if ($product_attribut['price'] > 0) $harga = $harga + $product_attribut['price'];
		$total += $jumlah * $harga;
	}
	
	$sql = "SELECT ongkir, kodevoucher, tipe_voucher, nominal_voucher FROM sc_transaction WHERE transaction_id='$txid'";
	$result = $mysqli->query($sql);
	list($ongkir, $kodevoucher, $tipe_voucher, $nominal_voucher) = $result->fetch_row();
	
	if ($nominal_voucher > 0) {
		
		if ($tipe_voucher == 1) {
			$voucher = $nominal_voucher;
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
	
	if ($ongkir > 0) $total += $ongkir;
	if ($packingvalue > 0) $total += $packingvalue;
	if ($insurancevalue > 0) $total += $insurancevalue;
	
	
	return $total;
}

function sendemailstatus($txid) {
	global $mysqli, $pakaicatalogattribut, $catalog_attribute_option, $smtpsendername;
	global $smtpuser,$cfg_thumb_path,$cfg_thumb_url,$cfg_payment_logo, $orderconfirmation, $ordershipped;
	global $cfg_payment_path, $cfg_payment_url, $pakaiongkir, $urlfunc, $pakaivoucher, $pakaiadditional;
	$sql = "SELECT konstanta, terjemahan FROM translation";
	$result = $mysqli->query($sql);
	
	while (list($konstanta, $terjemahan) = $result->fetch_row()) {
		define($konstanta,$terjemahan);
	}
	
	$sql = "SELECT tanggal_buka_transaksi, tanggal_tutup_transaksi, customer_id, receiver_id, metode_pembayaran, ongkir, status, catatan, no_pengiriman, latestupdate, kodevoucher, tipe_voucher, nominal_voucher, kurir, is_dropship FROM sc_transaction WHERE transaction_id='$txid'";
	
	$result = $mysqli->query($sql);
	list($tanggal_buka_transaksi, $tanggal_tutup_transaksi, $customer_id, $receiver_id, $metode_pembayaran, $shipping, $status, $catatan, $no_pengiriman, $latestupdate, $kodevoucher, $tipe_voucher, $nominal_voucher, $kurir, $is_dropship) = $result->fetch_row();
	
	$couriers 		= json_decode($kurir, true);
	// $kecamatan 		= $couriers['kota'];
	$kurir 			= $couriers['tipe'] . ' ' . $couriers['kota'];
	$kurir_tipe		= $couriers['tipe'];
	$ongkos_kirim 	= number_format($shipping, 0, ',', '.');
	$estimasi 		= $couriers['estimasi'];
	
	// echo "$orderconfirmation, $ordershipped";
	$content .= "<h2>"._ORDERSTATUS."</h2>\r\n";
	$content .= "<p>";
	if ($status == 3) {
		$email_subject = _ORDERCONFIRMATION . " $txid";
		$subject = $orderconfirmation;
	}
	if ($status == 4) {
		$email_subject = _ORDERSHIPPED. " $txid";
		// $subject = $ordershipped;

		$urldetail = $urlfunc->makePretty("?p=order&action=view&pid=$txid");
		$html_str = array(
			'/#noresi/' => $no_pengiriman,
			'/#orderdetail/' => "<a href=\"$urldetail\">"._VIEWORDERDETAIL."</a>"
		);
		$subject = preg_replace(array_keys($html_str), array_values($html_str), $ordershipped);
	}
	// switch ($status) {
		// case 0:
		// $subject = _STATUS0;
		// break;
		// case 1:
		// $subject = _STATUS1;
		// break;
		// case 2:
		// $subject = _STATUS2;
		// break;
		// case 3:
		// $subject = _STATUS3;
		// break;
		// case 4:
		// $subject = _STATUS4." $no_pengiriman";
		// break;
	// }
	$content .= $subject;	//." ("._LATESTUPDATE.": ".tglformat($latestupdate,true).")</p>\r\n";
	
	$content .= <<<STYLE
		<style>
			*{font-family: arial}
		</style>
STYLE;
	$content .= "<h2>"._ORDERDETAILS."</h2>\r\n";
	$content .= "<table border=\"0\">\r\n";
	$content .= "<tr><td>"._CURRENTORDERTITLE."</td><td>:</td><td>$txid</td></tr>\r\n";
	$content .= "<tr><td>"._ORDERDATE."</td><td>:</td><td>".tglformat($tanggal_buka_transaksi,true)."</td></tr>\r\n";
	$content .= "</table><br />\r\n";				
	
	$content .= "<div class=\"table_block table-responsive table-small-gap\"><table border=\"0\" cellpadding=\"3\" style=\"border-collapse: collapse;\" class=\"table table-bordered\" width=\"100%\">"; //format the cart using a HTML table
	//iterate through the cart, the $product_id is the key and $quantity is the value
	$content .= "<tr style=\"background:#eee;font-weight:bold;\">";
	$content .= "<th>"._THUMBNAIL."</th>";
	$content .= "<th>"._PRODUCTNAME."</th>";
	$content .= "<th>"._PRICE."</th>";
	$content .= "<th>"._QUANTITY."</th>";
	$content .= "<th>"._LINECOST."</th>";
	$content .= "</tr>";
	$totalweight = 0;
	
	// $sql = "SELECT jumlah, harga, product_name,filename FROM sc_transaction_detail WHERE transaction_id='$txid'";
	$sql = "SELECT jumlah,td.harga,product_name,td.filename, c.idmerek, attribut_id FROM sc_transaction_detail td INNER JOIN catalogdata c ON c.id=td.product_id  WHERE transaction_id='$txid' ORDER BY transaction_detail_id";
	$result = $mysqli->query($sql);
	// while (list($quantity, $price, $productname,$filename) = $result->fetch_row()) {
	while (list($quantity, $price, $productname,$filename, $idmerek, $attributId) = $result->fetch_row()) {
		
		if ($pakaicatalogattribut) {
			$product_attribut = get_product_attribut($attributId);
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
		$brand_name = get_brand_name($idmerek);
		$content .= "<td><small style=\"font-size: 15px;font-weight: bold;color: #000000;line-height: 1.5;\" class=\"brand-name\">$brand_name</small><p style=\"line-height: 1.45;margin-bottom: 0;margin-top: 5px;line-height: 1.45;margin-bottom: 0;margin-top: 5px;\" class=\"product-name\">$productname</p>";
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
		$content .= "</td>";
		$content .= "<td align=\"right\">".number_format($price, 0, ',', '.')."</td>";
		$content .= "<td align=\"center\">".number_format($quantity, 0, ',', '.')."</td>";
		$content .= "<td align=\"right\">".number_format($line_cost, 0, ',', '.')."</td>";
		$content .= "</tr>";
	}
	
	$content .= "<tr>";
	$content .= "<td colspan=\"4\" align=\"right\">"._SUBTOTAL."</td>";
	$content .= "<td align=\"right\">".number_format($total, 0, ',', '.')."</td>";
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
		$content .= "<td colspan=\"4\" align=\"right\">"._DISCOUNT."</td>";
		$content .= "<td align=\"right\">".$voucher."</td>";
		$content .= "</tr>";
	}
	
	if ($pakaiadditional) {
		if ($insurancevalue > 0) $total += $insurancevalue;
		$content .= "<tr>";
		$content .= "<td colspan=\"4\" align=\"right\">"._USEINSURANCE."</td>";
		$content .= "<td align=\"right\">".number_format($insurancevalue, 0, ',', '.')."</td>";
		$content .= "</tr>";
		
		if ($packingvalue > 0) $total += $packingvalue;
		$content .= "<tr>";
		$content .= "<td colspan=\"4\" align=\"right\">"._USEPACKING."</td>";
		$content .= "<td align=\"right\">".number_format($packingvalue, 0, ',', '.')."</td>";
		$content .= "</tr>";
	}
	//START MENENTUKAN PENGGUNAAN ONGKOS KIRIM
	if ($pakaiongkir) {
		$content .= "<tr>";
		$content .= "<td colspan=\"4\" align=\"right\">"._POSTAGE."</td>";
		$content .= "<td align=\"right\">".number_format($shipping, 0, ',', '.')."</td>";
		$content .= "</tr>";
		$total = $total + $shipping;
	}
	//END MENENTUKAN PENGGUNAAN ONGKOS KIRIM
	$content .= "<tr>";
	$content .= "<td colspan=\"4\" align=\"right\">"._TOTAL."</td>";
	$content .= "<td align=\"right\">".number_format($total, 0, ',', '.')."</td>";
	$content .= "</tr>";
	$content .= "</table>";
	$content .= "</div>";

	if ($is_dropship == 1) {
		$sql = "SELECT web_user_id, nama_lengkap, email, cellphone1 FROM sc_customer WHERE customer_id='$customer_id'";
		$result = $mysqli->query($sql);
		list($web_user_id, $nama_lengkap_d, $email_d, $cellphone1_d) = $result->fetch_row();
	}
	// $sql = "SELECT nama_lengkap, email, alamat, alamat2, kota, provinsi, zip, telepon, cellphone1, cellphone2 FROM sc_customer WHERE customer_id='$customer_id'";
	// $result = $mysqli->query($sql);
	// list($nama_lengkap, $email, $alamat, $alamat2, $kota, $provinsi, $zip, $telepon, $cellphone1, $cellphone2) = $result->fetch_row();
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
	// echo "$sql<br>";
	$result = $mysqli->query($sql);
	list($nama_lengkap, $email, $alamat, $alamat2, $kota, $provinsi, $zip, $telepon, $cellphone1, $cellphone2, $sendername, $senderphone, $senderemail) = $result->fetch_row();
	// $content .= "<h2>"._RECEIVERDETAILS."</h2>\r\n";
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
	
	$content .= "<table border=\"1\" cellpadding=\"3\" style=\"margin-top:15px;color:#000;border-collapse: collapse;border: 1px solid #bbb;\" class=\"table table-bordered\" width=\"100%\"><tr style=\"background: #eee;font-weight: bold;\"><th>" . _RECEIVERDETAILS . "</th>\r\n";
	if ($is_dropship == 1) {
		$content .= "<th>"._DATASENDER."</th>\r\n";
	}
	
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
	$content .= "</tr>";
	$content .= "<tr>";
	$content .= "<td valign=\"top\">$nama_lengkap<br/>\r\n";
	// $content .= "$emailReceiver<br/>\r\n";
	$content .= "$alamat";
	$content .= ($alamat2 != '') ? "<br/>$alamat2<br/>" : '<br/>';
	if ($kecamatan != '') $content .= "$kecamatan<br/>\r\n";
	if ($provinsi != '') $content .= "$provinsi<br/>\r\n";
	$content .= "$kota<br/>\r\n";
	$content .= "$zip<br/>\r\n";
	// $content .= "$phoneReceiver<br/>\r\n";
	$content .= "$cellphone1<br/>\r\n";
	if ($cellphone2 != '') $content .= "$cellphone2</td>\r\n";
	
	if ($is_dropship == 1) {
		$content .= "<td valign=\"top\">\r\n";
		$content .= "<table border=\"0\" class=\"table-pengirim\">\r\n";
		$content .= "<tr><td>$sendername</td></tr>\r\n";
		$content .= "<tr><td>$senderphone</td></tr>\r\n";
		if ($web_user_id == 0) {
			$content .= "<tr><td>$senderemail</td></tr>\r\n";
		}
		$content .= "</table></td>\r\n";
	}
	$content .= "</tr></table>\r\n";
	
	/* $sql = "SELECT logo,bank,cabang,norekening,atasnama FROM sc_payment WHERE bank = '$metode_pembayaran'";
	$result = $mysqli->query($sql);
	list($logo,$bank,$cabang,$norekening,$atasnama) = $result->fetch_row();
	$content .= "<h2>"._PAYMENTINFO."</h2>\r\n";
	$content .= "<table class='rekening'>";
	$content .= "<tr>";
	$content .= "<td class='rekening_logo'><img src='".$cfg_payment_logo."/$logo' alt='$bank'></td>";
	$content .= "<td class='rekening_info'>
	$bank $cabang<br/>
	No. Rek $norekening<br/>
	a/n $atasnama<br/>
	</td>";
	$content .= "</tr>";
	$content .= "</table>";
	
	if ($catatan!='') {
		$content .= "<h2>"._NOTE."</h2>\r\n";
		$content .= "<div id=\"ordernote\">$catatan</div>\r\n";
	} */
	
	$sql = "SELECT logo,bank,cabang,norekening,atasnama FROM sc_payment WHERE bank = '$metode_pembayaran'";
	$result = $mysqli->query($sql);
	list($logo,$bank,$cabang,$norekening,$atasnama) = $result->fetch_row();
	$content .= "<table border=\"1\" cellpadding=\"3\" style=\"margin-top:15px;color:#000;border-collapse: collapse;border: 1px solid #bbb;\" class=\"table table-bordered\" width=\"100%\"><tr style=\"background: #eee;font-weight: bold;\"><th>"._PAYMENTINFO."</th>\r\n";
	if ($catatan!='') {
		$content .= "<th>"._NOTE."</th>\r\n";
	}
	$content .= "</tr><tr><td valign=\"top\">";
	$content .= "<table  class='rekening'>";
	$content .= "<tr>";
	if ($logo != '' && file_exists("$cfg_payment_path/$logo")) {
		$content .= "<td class='rekening_logo'><img src='".$cfg_payment_logo."/$logo' alt='$bank'></td>";
	}
	$content .= "<td class='rekening_info'>
	$bank $cabang<br/>
	$norekening<br/>
	$atasnama<br/>
	</td>";
	$content .= "</tr></table>";
	$content .= "</td>";

	if ($catatan!='') {
		$content .= "<td valign=\"top\"><div id=\"ordernote\" style=\"color:#000\">$catatan</div></td>\r\n";
	}
	$content .= "</tr></table></div></div>\r\n";
	
	$sql = "SELECT value FROM contact WHERE name='umbalemail'";
	$result = $mysqli->query($sql);
	list($umbalemail) = $result->fetch_row();
	// $umbalemail = explode(",", $umbalemail);
	// $umbalemail = $umbalemail[0];		
	
	$email = $is_dropship == 1 ? $senderemail : $email;
	// echo "$senderemail : $email<br>";
	// echo "$email, $email_subject, $content, $smtpuser, $smtpuser, $umbalemail, $content<br>";
	// echo $content;
	// die();
	fiestophpmailer($email, $email_subject, $content, $smtpuser, $smtpsendername, $smtpuser, $content);
   
}

function get_matauang() {
	global $mysqli;
	$sql = $mysqli->query("SELECT matauang FROM catalogdata WHERE 1 LIMIT 1");
	list($matauang) = $sql->fetch_row();
	
	return $matauang;
}

function get_dropdown() {
	global $mysqli;
	$sql = "SELECT id, bank FROM sc_payment";
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		$data = '<option value="">'._PILIH.'</option>';
		while($row = $result->fetch_assoc()) {
			$data .= '<option value="'.$row['bank'].'">'.$row['bank'].'</option>';
		}
	}
	
	return $data;
}

function send_email_to_admin($trans_id) {
	global $smtpuser, $confirm_thanks_client, $cfg_app_url, $pakaivoucher, $pakaiadditional;
	global $mysqli;
	
	$sql = $mysqli->query("SELECT transaction_id, receiver_id, tanggal_konfirmasi, bank_tujuan, bank_asal, atas_nama, no_rekening, nominal FROM sc_transaction WHERE transaction_id='$trans_id'");
	
	$row = $sql->fetch_assoc();
	$receiver_id = $row['receiver_id'];
	
	$subject = _DETAILCONFIRMATIONORDERDETAIL.$row['transaction_id'];

	$content = '<table>';
	$content .= '
	<tr>
		<td>'._TANGGALKONFIRMASI.'</td><td>:</td>
		<td>'.tglformat($row['tanggal_konfirmasi']).'</td>
	</tr>';
	$content .= '
	<tr>
		<td>'._BANKTUJUAN.'</td><td>:</td>
		<td>'.$row['bank_tujuan'].'</td>
	</tr>';
	$content .= '
	<tr>
		<td>'._BANKASAL.'</td><td>:</td>
		<td>'.$row['bank_asal'].'</td>
	</tr>';	$content .= '
	<tr>
		<td>'._ATASNAMA.'</td><td>:</td>
		<td>'.$row['atas_nama'].'</td>
	</tr>';
	$content .= '
	<tr>
		<td>'._NOREKENING.'</td><td>:</td>
		<td>'.$row['no_rekening'].'</td>
	</tr>';
	$content .= '
	<tr>
		<td>'._NOMINAL.'</td><td>:</td>
		<td>Rp '.number_format($row['nominal'],0,',','.').'</td>
	</tr>';
	$content .= '</table>';
	$data_admin = '<div class="content-email">';
	$data_admin .= '<h2>'.$subject.'</h2>';
	$url = "$cfg_app_url/kelola/index.php?p=order&action=detail&trns=$trans_id&origin=step1";
	$data_admin .= '<p>'._CHANGESTATUSTOADMIN.' <a href="'.$url.'" target="_blank">'._CHANGESTATUS.'</a></p>';
	$data_admin .= $content;
// Untuk melakukan ubah status pesanan ini, silakan klik link berikut
// Ubah Status
	// $url = "$cfg_app_url/kelola/index.php?p=order&action=detail&trns=$trans_id&origin=step1";
	// $data_admin .= '<p>'._CHANGESTATUSTOADMIN.' <a href="'.$url.'" target="_blank">'._CHANGESTATUS.'</a></p>';
	$data_admin .= '</div>';
	
	$q = $mysqli->query("SELECT value FROM contact WHERE name='umbalemail'");
	list($umbalemail) = $q->fetch_row();
	
	$q2 = $mysqli->query("SELECT user_email FROM webmember WHERE user_id='".$_SESSION['member_uid']."'");
	list($email) = $q2->fetch_row();
	
	$temp = array(
		"/#content/" => "$content"
	);
	
	$data_customer = preg_replace(array_keys($temp), array_values($temp), $confirm_thanks_client);
				
	$data = $data_admin;
	$data .= $data_customer;

	if (!isset($_SESSION['member_uid'])) {
		$sql_r = $mysqli->query("SELECT nama_pengirim, email, email_pengirim FROM sc_receiver WHERE receiver_id='$receiver_id'");
		// echo "SELECT nama_pengirim, email, email_pengirim FROM sc_receiver WHERE receiver_id='$receiver_id'<br>";
		list($sendername, $r_email, $r_email_pengirim) = $sql_r->fetch_row();
		if ($sendername != '') {
			$email = $r_email_pengirim;
		} else {
			$email = $r_email;
		}
	}
	// echo "$email<br>";
	// echo "$umbalemail, $subject, $data_admin, $smtpuser, $smtpuser, $email, $data_admin<br>";
	// echo "$email, $subject, $data_customer, $smtpuser, $smtpuser, $smtpuser, $data_customer";
	// die();
	//send to admin
	fiestophpmailer($umbalemail, $subject, $data_admin, $smtpuser, $smtpuser, $email, $data_admin);
	//send to customer
	fiestophpmailer($email, $subject, $data_customer, $smtpuser, $smtpuser, $smtpuser, $data_customer);
	
	return $data;
}

function send_email_to_admin_upload($trans_id) {
	global $smtpuser, $confirm_thanks_client, $cfg_app_url, $pakaivoucher, $pakaiadditional;
	global $mysqli, $attachment;
	
	$sql = $mysqli->query("SELECT transaction_id, receiver_id, tanggal_konfirmasi, bank_tujuan, bank_asal, atas_nama, no_rekening, nominal FROM sc_transaction WHERE transaction_id='$trans_id'");
	
	$row = $sql->fetch_assoc();
	
	$subject = _DETAILCONFIRMATIONORDERDETAIL.$row['transaction_id'];
	
	$data_admin = '<div class="content-email">';
	$data_admin .= '<h2>'.$subject.'</h2>';
	$url = "$cfg_app_url/kelola/index.php?p=order&action=detail&trns=$trans_id&origin=step1";
	$data_admin .= '<p>'._CHANGESTATUSTOADMIN.' <a href="'.$url.'" target="_blank">'._CHANGESTATUS.'</a></p>';
	$data_admin .= $content;
	$data_admin .= '</div>';
	
	$q = $mysqli->query("SELECT value FROM contact WHERE name='umbalemail'");
	list($umbalemail) = $q->fetch_row();
	
	$temp = array(
		"/#content/" => "$content"
	);
	
	$data_customer = preg_replace(array_keys($temp), array_values($temp), $confirm_thanks_client);
				
	$data = $data_admin;

	if (!isset($_SESSION['member_uid'])) {
		$sql_r = $mysqli->query("SELECT nama_pengirim, email, email_pengirim FROM sc_receiver WHERE receiver_id='$receiver_id'");
		// echo "SELECT nama_pengirim, email, email_pengirim FROM sc_receiver WHERE receiver_id='$receiver_id'<br>";
		list($sendername, $r_email, $r_email_pengirim) = $sql_r->fetch_row();
		if ($sendername != '') {
			$email = $r_email_pengirim;
		} else {
			$email = $r_email;
		}
	}
	
	print_r($attachment);
	
	//send to admin
	fiestophpmailer($umbalemail, $subject, $data_admin, $smtpuser, $smtpuser, $email, $data_admin, $attachment);
	
	return $data;
}

function payment_confirmation() {
	global $urlfunc, $txid, $msgasterisk;
	
	$now = date('Y-m-d');
	// $content .= '<h2>'._FORMCONFIRMPAYMENT.'</h2>';
	// $content .= '<form id="confirm_payment" method="POST">';
	$content .= '<form id="confirm_payment" method="POST" action="'.$urlfunc->makePretty("?p=order&action=do_confirm&trans_id=$txid").'" enctype="multipart/form-data">';
	$content .= '<input type="hidden" name="action" value="do_confirm">';
	$content .= '<input type="hidden" name="trans_id" value="'.$txid.'">';
	$content .= '<input type="hidden" id="confirm_msg" name="confirm_msg" value="'._MSGCONFIRM.'">';
	$content .= '<table>';
	/* $content .= '
	<tr>
		<td>'._TANGGALKONFIRMASI.' *</td>
		<td><input type="text" id="tanggal_konfirmasi" name="tanggal_konfirmasi" value="'.$now.'" required></td>
	</tr>';
	$content .= '
	<tr>
		<td>'._BANKTUJUAN.' *</td>
		<td><select name="bank_tujuan" required>'.get_dropdown().'</select></td>
	</tr>';
	$content .= '
	<tr>
		<td>'._BANKASAL.' *</td>
		<td><input type="text" name="bank_asal" placeholder="'._PLACEHOLDERBANKASAL.'" required></td>
	</tr>';
	$content .= '
	<tr>
		<td>'._ATASNAMA.' *</td>
		<td><input type="text" name="atas_nama" size="30" placeholder="'._PLACEHOLDERATASNAMA.'" required></td>
	</tr>';
	$content .= '
	<tr>
		<td>'._NOREKENING.' *</td>
		<td><input type="text" name="no_rekening" placeholder="'._PLACEHOLDERNOREK.'" required onkeydown="return ( event.ctrlKey || event.altKey 
							|| (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
							|| (95<event.keyCode && event.keyCode<106)
							|| (event.keyCode==8) || (event.keyCode==9) 
							|| (event.keyCode>34 && event.keyCode<40) 
							|| (event.keyCode==46) )"></td>
	</tr>';
	$content .= '
	<tr>
		<td>'._NOMINAL.' *</td>
		<td><input type="text" size="30" name="nominal" placeholder="'._PLACEHOLDERNOMINAL.'" required onkeydown="return ( event.ctrlKey || event.altKey 
							|| (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
							|| (95<event.keyCode && event.keyCode<106)
							|| (event.keyCode==8) || (event.keyCode==9) 
							|| (event.keyCode>34 && event.keyCode<40) 
							|| (event.keyCode==46) )""></td>
	</tr>'; */
	
	$content .= '
	<tr>
		<td>'._BUKTITRANSFER.' *</td>
		<td><input type="file" name="filename" accept="image/*,.pdf"required></td>
	</tr>';	
	$content .= '
	<tr>
		<td colspan="2"><input type="submit" class="btn btn-default more" name="konfirmasi" value="'._KONFIRMASI.'"></td>
	</tr>';
	$content .= '
	<tr>
		<td colspan="2">'.$msgasterisk.'</td>
	</tr>';
	
	
	$content .= '</table>';
	$content .= '</form>';
	
	return $content;
}

function get_product_attribut($attributId) {
	global $mysqli;
	
	$sql = "SELECT price, size, color FROM catalogattribut WHERE id='$attributId'";
	if ($result = $mysqli->query($sql)) {
		
		if ($result->num_rows > 0) {
			
			$data = array();
			$row = $result->fetch_assoc();
			$data = array('price' => $row['price'], 'size' => $row['size'], 'color' => $row['color']);
		}
	}
	
	return $data;
}
?>
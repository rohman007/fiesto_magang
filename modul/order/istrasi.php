<?php

if (!$isloadfromindex && !$isloadfromprint) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("fungsi.php");
    include ("../../kelola/lang/$defaultlang/definisi.php");
    include ("lang/$defaultlang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}

$keyword = fiestolaundry($_GET['keyword'], 100);
$screen = fiestolaundry($_GET['screen'], 11);
$pid = fiestolaundry($_REQUEST['pid'], 11);
$trns = fiestolaundry($_REQUEST['trns'], 15);
$action = fiestolaundry($_REQUEST['action'], 20);
$now = date('Y-m-d H:i:s');

if ($action == "detail") {
    $txid = fiestolaundry($_GET['trns'], 30);
    
    //START SUBMIT ACTION UPDATE STATUS
	if($_POST['submit']) {
		$d_status=$_POST['status'];
		$d_txtresi=$_POST['txtresi'];
		$action_before=$_POST['action_before'];
		$r_error_message=array();
		foreach($d_status as $trid=>$status) {
			$tstatus=fiestolaundry($status, 1);
			$transaction_id=fiestolaundry($trid, 50);
			$nopengiriman=fiestolaundry($d_txtresi[$trid], 50);
		
			$sql = "UPDATE sc_transaction SET status='$tstatus', latestupdate='$now' ";
			$sql .= ", no_pengiriman='$nopengiriman'";
			$sql .= " WHERE transaction_id='$transaction_id'";
			
			$r = $mysql->query($sql);
			if(!$r) {
				$r_error_message[]="Gagal update transaction id $transaction_id";
			} else {
				if($status==3 || $status==4) {
					$tr_success[] = array(
						'status' => $status, 
						'transaction_id' => $transaction_id,
						'no_resi' => $nopengiriman
					);
				}
			}
		}
		
		if(count($r_error_message)>0) {
			$error_message=join(";",$r_error_message);
			// $action = createmessage($error_message, "error", "$action_before");
		} else {
			// $action = createmessage( _SUCCESS, "success", "$action_before");
			$action = createmessage( _SUCCESS, "success", "success");
		}
		
		if (count($tr_success) > 0) {
			
			foreach($tr_success as $transactions) {
				if ($_GET['action'] == 'detail' && ($transactions['status'] == 3 || $transactions['status'] == 4)) {
					sendemailstatus($transactions['transaction_id']);
				}
			}
		}
	}
	//END SUBMIT ACTION UPDATE STATUS
     
    $sql = "SELECT transaction_id ,tanggal_buka_transaksi, tanggal_tutup_transaksi, customer_id, receiver_id, metode_pembayaran, ongkir, status, catatan, no_pengiriman, latestupdate, kodevoucher, tipe_voucher, nominal_voucher, packingvalue, insurancevalue, kurir, is_dropship FROM sc_transaction WHERE transaction_id='" . $_REQUEST['trns'] . "';";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) > 0) {
        list($transaction_id ,$tanggal_buka_transaksi, $tanggal_tutup_transaksi, $customer_id, $receiver_id, $metode_pembayaran, $shipping, $status, $catatan, $no_pengiriman, $latestupdate, $kodevoucher, $tipe_voucher, $nominal_voucher, $packingvalue, $insurancevalue, $kurir, $is_dropship) = $mysql->fetch_row($result);

		$couriers 		= json_decode($kurir, true);
		$courier_tipe = $couriers['tipe'];
		$courier_city = explode(',', $couriers['kota']);
		// $kecamatan = $courier_city[0];
		
		// $kecamatan 		= $couriers['kota'];
		$kurir 			= $couriers['tipe'] . ' ' . $couriers['kota'];
		$kurir_tipe		= $couriers['tipe'];
		$ongkos_kirim 	= number_format($shipping, 0, ',', '.');
		$estimasi 		= $couriers['estimasi'];
		
        $admincontent .= "<div class=\"position-left\"><h3>" . _ORDERSTATUS . "</h3>\r\n";
        $admincontent .= "<p>";
		$r_status[0]=_ORDERCANCEL;
		$r_status[1]=_WAITINGFORPAYMENT;
		$r_status[2]=_ORDERCONFIRMED;
		$r_status[3]=_WAITINGTOSEND;
		$r_status[4]=_SENT;
			
		if (!$isloadfromprint) {
			$admincontent .="<form method='post' action='?p=order&action=detail&trns=$txid'>";
			
			$admincontent .="<select name='status[$transaction_id]' id='sel_$transaction_id' autocomplete='off'>";
			foreach($r_status as $index => $tstatus) {
				$selected="";
				if($status==$index)$selected="selected='selected'";
				$admincontent .="<option value='$index' $selected>$tstatus</option>";
			}
			$admincontent .="</select>";
			$admincontent .="<span style='display:none;' id='resi_$transaction_id'>
				<input style='height:30px;width:150px;' size='15' type='text' value='$no_pengiriman' name='txtresi[$transaction_id]' placeholder='"._NORESI."' />
			</span>";
			$admincontent .=<<<END
			<script>
			$(document).ready(function(){
				val=($("#sel_$transaction_id").val());
				if(val==4) {
					$("#resi_$transaction_id").show();
				} else {
					$("#resi_$transaction_id").hide();
				}
				$("#sel_$transaction_id").change(function(){
				val=$(this).val();
				if(val==4) {
					$("#resi_$transaction_id").show();
				} else {
					$("#resi_$transaction_id").hide();
				}
				});
			});
			</script>
END;
		 
            $admincontent .= "<input type='submit' name='submit' class='buton' value='"._SAVE."'>";
        
        } else {
			if($status==4) {
				$admincontent .= $r_status[$status]. ", " . _NOPENGIRIMAN . " $no_pengiriman";
			} else {
				$admincontent .=$r_status[$status];
			}
	
		}
		
        
        $admincontent .= "</p>";
        // $admincontent .= "<p>" . _LATESTUPDATE . ": " . tglformat($latestupdate, true) . "</p></div>\r\n";
        /*
          if (isset($_GET['type']) && $_GET['type'] != 'history') {
          $admincontent .= "<form action=\"?p=order&action=detail&trns=$txid&type=order\" method=\"POST\">\r\n";
          for ($i=0;$i<=3;$i++) {
          $checked[$i]='';
          }
          $checked[$status]='checked';
          $admincontent .= "<input type=\"radio\" name=\"status\" value=\"1\" $checked[1] /> "._WAITINGFORPAYMENT."<br />";
          $admincontent .= "<input type=\"radio\" name=\"status\" value=\"2\" $checked[2] /> "._WAITINGTOSEND."<br />";
          $admincontent .= "<input type=\"radio\" name=\"status\" value=\"3\" $checked[3] /> "._SENT.", "._NOPENGIRIMAN.": <input type=\"text\" name=\"nopengiriman\" value=\"$no_pengiriman\" /><br />";
          $admincontent .= "<input type=\"submit\" value=\""._UBAHSTATUS."\" name=\"submit\" />";
          $admincontent .= "</form>";
          }
         */
        $admincontent .= "<div class=\"position-right\"><h3>" . _ORDERDETAILS . "</h3>\r\n";
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\r\n";
        $admincontent .= "<tr><td>" . _CURRENTORDERTITLE . "</td><td>$txid</td></tr>\r\n";
        $admincontent .= "<tr><td>" . _ORDERDATE . "</td><td>" . tglformat($tanggal_buka_transaksi, true) . "</td></tr>\r\n";
        $admincontent .= "</table></div><br />\r\n";

        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered list-product-order\">";
        $admincontent .= "<tr>";
        $admincontent .= "<th>" . _THUMBNAIL . "</th>";
        $admincontent .= "<th>" . _PRODUCTNAME . "</th>";
        $admincontent .= "<th>" . _PRICE . "</th>";
        $admincontent .= "<th>" . _QUANTITY . "</th>";
        $admincontent .= "<th>" . _LINECOST . "</th>";
        $admincontent .= "</tr>";
        $totalweight = 0;

        //$sql = "SELECT txd.jumlah, txd.harga, cd.title,cd.filename FROM sc_transaction_detail txd, catalogdata cd WHERE transaction_id='$txid' AND txd.product_id=cd.id";
        $sql = "SELECT jumlah,td.harga,product_name,td.filename, c.idmerek, attribut_id FROM sc_transaction_detail td LEFT JOIN catalogdata c ON c.id=td.product_id  WHERE transaction_id='$txid' ORDER BY transaction_detail_id";
        $result = $mysql->query($sql);
        while (list($quantity, $price, $productname, $filename, $idmerek, $attributId) = $mysql->fetch_row($result)) {
			$brand_name = get_brand_name($idmerek);
			
			if ($pakaicatalogattribut) {
				$product_attribut = get_product_attribut($attributId);
				if ($product_attribut['price'] > 0) $price = $price + $product_attribut['price'];
			}
            $line_cost = $quantity * $price;  //work out the line cost
            $total = $total + $line_cost;   //add to the total cost
            $admincontent .= "<tr>";
            $admincontent .= "<td>";
            if (file_exists("$cfg_thumb_path/$filename")) {
                $admincontent.="<img src=\"$cfg_thumb_url/$filename\" width=\"50px\" class=\"thumb_small\" >";
            } else {
                $admincontent.="<img src=\"$cfg_app_url/images/none.gif\" width=\"50px\" class=\"thumb_small\" >";
            }
            $admincontent .="</td>";
            $admincontent .= "<td><small class=\"brand-name\">$brand_name</small>$productname";
			
			if ($pakaicatalogattribut) {
				// if ($product_attribut['color'] != '' || $product_attribut['size'] != '') {
					// $admincontent .= '<br><small class="product-attribut">'.$product_attribut['color'].', '.$product_attribut['size'].'</small>';
				// }
				
				switch($catalog_attribute_option) {
					case 0:
						if ($product_attribut['color'] != '' || $product_attribut['size'] != '') {
							$admincontent .= '<br><small class="product-attribut">'.$product_attribut['color'].', '.$product_attribut['size'].'</small>';
						}
						break;
					case 1:
						if ($product_attribut['size'] != '') {
							$admincontent .= '<br><small class="product-attribut">'.$product_attribut['size'].'</small>';
						}
						break;
					case 2:
						if ($product_attribut['color'] != '') {
							$admincontent .= '<br><small class="product-attribut">'.$product_attribut['color'].'</small>';
						}
						break;
				}
			}
			$admincontent .= '</td>';
            $admincontent .= "<td align=\"right\">" . number_format($price, 0, ',', '.') . "</td>";
            $admincontent .= "<td align=\"right\">" . number_format($quantity, 0, ',', '.') . "</td>";
            $admincontent .= "<td align=\"right\">" . number_format($line_cost, 0, ',', '.') . "</td>";
            $admincontent .= "</tr>";
        }
		
		$admincontent .= "<tr>";
		$admincontent .= "<td colspan=\"4\" align=\"right\">"._SUBTOTAL."</td>";
		$admincontent .= "<td align=\"right\">".number_format($total, 0, ',', '.')."</td>";
		$admincontent .= "</tr>";

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
			$admincontent .= "<tr>";
			$admincontent .= "<td colspan=\"4\" align=\"right\">"._DISCOUNT."</td>";
			$admincontent .= "<td align=\"right\">".$voucher."</td>";
			$admincontent .= "</tr>";
		}
		
		if ($pakaiadditional) {
			if ($insurancevalue > 0) $total += $insurancevalue;
			$admincontent .= "<tr>";
			$admincontent .= "<td colspan=\"4\" align=\"right\">"._USEINSURANCE."</td>";
			$admincontent .= "<td align=\"right\">".number_format($insurancevalue, 0, ',', '.')."</td>";
			$admincontent .= "</tr>";
			
			if ($packingvalue > 0) $total += $packingvalue;
			$admincontent .= "<tr>";
			$admincontent .= "<td colspan=\"4\" align=\"right\">"._USEPACKING."</td>";
			$admincontent .= "<td align=\"right\">".number_format($packingvalue, 0, ',', '.')."</td>";
			$admincontent .= "</tr>";	
		}
        //START MENENTUKAN PENGGUNAAN ONGKOS KIRIM
        if ($pakaiongkir) {
            $admincontent .= "<tr>";
            $admincontent .= "<td colspan=\"4\" align=\"right\">" ._POSTAGE."</td>";
            $admincontent .= "<td align=\"right\">" . number_format($shipping, 0, ',', '.') . "</td>";
            $admincontent .= "</tr>";
            $total = $total + $shipping;
        }
        //END MENENTUKAN PENGGUNAAN ONGKOS KIRIM
        $admincontent .= "<tr>";
        $admincontent .= "<td colspan=\"4\" align=\"right\">" . _GRANDTOTAL . "</td>";
        $admincontent .= "<td align=\"right\">" . number_format($total, 0, ',', '.') . "</td>";
        $admincontent .= "</tr>";
        $admincontent .= "</table>";

        $radiostatus = $status;

        $sql = "SELECT nama_lengkap, email, alamat, alamat2, kota, provinsi, zip, telepon, cellphone1, cellphone2 FROM sc_customer WHERE customer_id='$customer_id'";
        // $result = mysql_query($sql);
        // list($nama_lengkap, $email, $alamat, $alamat2, $kota, $provinsi, $zip, $telepon, $cellphone1, $cellphone2) = mysql_fetch_row($result);
        // $admincontent .= "<div class=\"position-left\"><h3>" . _PERSONALDETAILS . "</h3>\r\n";
        // $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" border=\"0\">\r\n";
        // $admincontent .= "<tr><td>" . _FULLNAME . " :</td><td>&nbsp;$nama_lengkap</td></tr>\r\n";
        // $admincontent .= "<tr><td>" . _EMAIL . " :</td><td>&nbsp;$email</td></tr>\r\n";
        // $admincontent .= "<tr valign=\"top\"><td>" . _ADDRESS . " :</td><td>&nbsp;$alamat";
        // $admincontent .= ($alamat2 != '') ? "<br />&nbsp;$alamat2" : '';
        // $admincontent .= "</td></tr>\r\n";
        // $admincontent .= "<tr><td>" . _STATE . " :</td><td>&nbsp;$provinsi</td></tr>\r\n";
        // $admincontent .= "<tr><td>" . _CITY . " :</td><td>&nbsp;$kota</td></tr>\r\n";
        // $admincontent .= "<tr><td>" . _ZIP . " :</td><td>&nbsp;$zip</td></tr>\r\n";
        // $admincontent .= "<tr><td>" . _TELEPHONE . " :</td><td>&nbsp;$telepon</td></tr>\r\n";
        // $admincontent .= "<tr><td>" . _CELLPHONE1 . " :</td><td>&nbsp;$cellphone1</td></tr>\r\n";
        // if ($cellphone2 != '')
            // $admincontent .= "<tr><td>" . _CELLPHONE2 . " :</td><td>&nbsp;$cellphone2</td></tr>\r\n";
        // $admincontent .= "</table></div>\r\n";

        $sql = "SELECT nama_lengkap, email, alamat, alamat2, kota, provinsi, zip, telepon, cellphone1, cellphone2, nama_pengirim, telp_pengirim, email_pengirim FROM sc_receiver WHERE receiver_id='$receiver_id'";
        $result = $mysql->query($sql);
        list($nama_lengkap, $email, $alamat, $alamat2, $kota, $provinsi, $zip, $telepon, $cellphone1, $cellphone2, $nama_pengirim, $telp_pengirim, $email_pengirim) = $mysql->fetch_row($result);
        $admincontent .= "<div class=\"position-right\"><h3>" . _RECEIVERDETAILS . "</h3>\r\n";
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" border=\"0\">\r\n";
        $admincontent .= "<tr><td>" . _FULLNAME . "</td><td>&nbsp;$nama_lengkap</td></tr>\r\n";
		if ($nama_pengirim == '') {
			$admincontent .= "<tr><td>" . _EMAIL . "</td><td>&nbsp;$email</td></tr>\r\n";
		}
        $admincontent .= "<tr valign=\"top\"><td>" . _ADDRESS . "</td><td>&nbsp;$alamat";
        $admincontent .= ($alamat2 != '') ? "<br />&nbsp;$alamat2" : '';
        $admincontent .= "</td></tr>\r\n";
		if ($kecamatan != '') $admincontent .= "<tr><td>"._KECAMATAN."</td><td>$kecamatan</td></tr>\r\n";
        if ($provinsi != '') $admincontent .= "<tr><td>" . _STATE . "</td><td>&nbsp;$provinsi</td></tr>\r\n";
        $admincontent .= "<tr><td>" . _CITY . "</td><td>&nbsp;$kota</td></tr>\r\n";
        $admincontent .= "<tr><td>" . _ZIP . "</td><td>&nbsp;$zip</td></tr>\r\n";
        // $admincontent .= "<tr><td>" . _TELEPHONE . " :</td><td>&nbsp;$telepon</td></tr>\r\n";
        $admincontent .= "<tr><td>" . _CELLPHONE1 . "</td><td>&nbsp;$cellphone1</td></tr>\r\n";
        if ($cellphone2 != '')
            $admincontent .= "<tr><td>" . _CELLPHONE2 . "</td><td>&nbsp;$cellphone2</td></tr>\r\n";
        $admincontent .= "</table></div>\r\n";
		
		if ($nama_pengirim != '') {
			$admincontent .= "<div class=\"position-right\"><h3>" . _SENDERDETAILS . "</h3>\r\n";
			$admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" border=\"0\">\r\n";
			$admincontent .= "<tr><td>" . _FULLNAME . "</td><td>&nbsp;$nama_pengirim</td></tr>\r\n";
			$admincontent .= "<tr><td>" . _TELEPHONE . "</td><td>&nbsp;$telp_pengirim</td></tr>\r\n";
			if ($email_pengirim != '') $admincontent .= "<tr><td>" . _EMAIL . "</td><td>&nbsp;$email_pengirim</td></tr>\r\n";
			$admincontent .= "</table></div>\r\n";
		}
		$sql = "SELECT logo,bank,cabang,norekening,atasnama FROM sc_payment WHERE bank = '$metode_pembayaran'";
		$result = $mysql->query($sql);
		list($logo,$bank,$cabang,$norekening,$atasnama) = $mysql->fetch_row($result);
		if ($logo != '' && file_exists("$cfg_payment_logo/$logo")) {
			$payment_logo = "
				<td class='rekening_logo'><img src='".$cfg_payment_logo."/$logo' alt='$bank'></td>
				<td class='rekening_info'>
				$bank $cabang<br/>
				$norekening<br/>
				$atasnama<br/>
				</td>
				";
		} else {
			$payment_logo = "
				<td colspan='2' class='rekening_info'>
				$bank $cabang<br/>
				$norekening<br/>
				$atasnama<br/>
				</td>
			";
		}
		$admincontent .= "<div class=\"data-pembayaran\"><h3>"._PAYMENTINFO."</h3>\r\n";
		$admincontent .= "<table class='rekening'>";
		$admincontent .= "<tr>";
		$admincontent .= $payment_logo;
		// $admincontent .= "<td class='rekening_logo'><img src='".$cfg_payment_logo."/$logo' alt='$bank'></td>";
		// $admincontent .= "<td class='rekening_info'>
		// $bank $cabang<br/>
		// $norekening<br/>
		// $atasnama<br/>
		// </td>";
		$admincontent .= "</tr>";
		$admincontent .= "</table></div>";
        if ($catatan != '') {
            $admincontent .= "<h3>" . _NOTE . "</h3>\r\n";
            $admincontent .= "<div id=\"ordernote\"><table class=\"table table-bordered table-striped\"><tr><td>$catatan</td></tr></table></div>\r\n";
        }
    } else {
        $admincontent .= "<p>" . _WRONGCODE . "</p>";
    }
	$_GET['action'] = 'invoice';
    if (!$isloadfromprint) {
        $admincontent .= "<p><a class=\"buton\" href=\"javascript:history.back()\">" . _BACK . "</a>";
        $admincontent .= " <a class=\"buton\" href=\"orderprint.php?p=" . $_GET['p'] . "&action=" . $_GET['action'] . "&trns=" . $_REQUEST['trns'] . "\" target=_blank>" . _PRINT . "</a>";
        $admincontent .= "</p>";
    }
}
if ($action == "delete") {
    if (empty($trns)) {
        $action = createmessage(_TIDAKADATRANSAKSI, _INFO, "info", "");
    } else {
        $_SESSION['record_url_before'] = $_SERVER['HTTP_REFERER'];
        $admincontent .= "<h5>"._PROMPTDELORDER . " $trns?  "."</h5>";
        $admincontent .= "<a class=\"buton\" href=\"?p=order&action=delete_confirmed&trns=$trns\">" . _YES . "</a> &nbsp;<a class=\"buton\" href=\"#\" onclick=\"history.back();\">" . _NO . "</a></p>";
    }
}

if ($action == "delete_confirmed") {
	

	
    if (empty($trns)) {
        $action = createmessage(_TIDAKADATRANSAKSI, _INFO, "info", "");
    } else {
		$sql = "SELECT transaction_id, product_id, jumlah FROM sc_transaction_detail WHERE transaction_id='$trns'";
		$result = $mysql->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$pid 			= $row['product_id'];
				$jumlah 		= $row['jumlah'];
				$sql_catalog = "SELECT id, stock, is_stock FROM catalogdata WHERE id='$pid'";
				$res_catalog = $mysql->query($sql_catalog);
				while(list($id, $restock, $is_stock) = $mysql->fetch_row($res_catalog)) {
					if (!$is_stock) {
						$q = "UPDATE catalogdata SET stock = stock + $jumlah WHERE id='$pid'";
						$mysql->query($q);
					}
				}
			}
			
		}

        $sql = "DELETE FROM sc_transaction_detail WHERE transaction_id = '$trns'";
        $r = $mysql->query($sql);
        if ($r) {
            $sql = "DELETE FROM sc_transaction WHERE transaction_id = '$trns'";
            $r = $mysql->query($sql);
            if ($r) {
                $action = createmessage(_BERHASILHAPUSORDER, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_GAGALHAPUSORDER, _ERROR, "error", "");
            }
        } else {
            $action = createmessage(_GAGALHAPUSORDER, _ERROR, "error", "");
        }
    }

    $query = parse_url($_SESSION['record_url_before'], PHP_URL_QUERY);
    parse_str($query, $params);
    // $action = $params['action'];

    // //echo $action."==".$_SESSION['record_url_before'];
    // $_SESSION['record_url_before'] = "";
}

if ($action == "") {
    // $url = 'index.php';
    // header("Location: $url");
    // print '<script type="text/javascript">window.top.location.href = "'. $url .'";</script>';
    // $admincontent .= "
    // <ul>
    // <li class=\"jualan\"><a href=\"?p=order&action=step0\" class=\"menujual\">" . _LISTCANCELORDER . "</a></li>
    // <li class=\"jualan\"><a href=\"?p=order&action=step1\" class=\"menujual\">" . _LISTNEWORDER . "</a></li>
    // <li class=\"jualan\"><a href=\"?p=order&action=step2\" class=\"menujual\">" . _LISTORDERCONFIRMED . "</a></li>
    // <li class=\"jualan\"><a href=\"?p=order&action=step3\" class=\"menujual\">" . _LISTORDERPROCESSSENT . "</a></li>
    // <li class=\"jualan\"><a href=\"?p=order&action=timeinterval\" class=\"menujual\">" . _ORDERPROCESS . "</a></li>
    // <li class=\"jualan\"><a href=\"?p=order&action=search\" class=\"menujual\">" . _HISTORY . "</a></li>
    // </ul>
    // ";
}
if ($action == "search" || $action == "searchresult") {
    if ($action == "search") {
        $admincontent .= "<h3>" . _HISTORY . "</h3>\r\n";
        $admincontent .= "<p>" . _SEARCHDESC . "</p>\r\n";
        $admincontent .= "<form method=\"GET\">\r\n";
        $admincontent .= "<input type=\"hidden\" name=\"p\" value=\"order\" />\r\n";
        $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"searchresult\" />\r\n";
        $admincontent .= "<p><input type=\"text\" name=\"keyword\" class=\"carijual\">\r\n";
        $admincontent .= "<input type=\"submit\" class=\"buton\" value=\"" . _SEARCH . "\"></p>\r\n";
        $admincontent .= "</form>\r\n";
    }
    if ($action == "searchresult") {
        $keyword = fiestolaundry($_GET['keyword'], 200);
        checkrequired($keyword, _KEYWORD);
        $sql = "SELECT c.customer_id, c.nama_lengkap, t.transaction_id, 
					t.tanggal_buka_transaksi, t.status 
					FROM sc_transaction t left join sc_customer c 
					ON c.customer_id = t.customer_id WHERE
					((c.nama_lengkap LIKE '%$keyword%') OR (t.transaction_id LIKE '%$keyword%'))";
//echo $sql;
        $result = $mysql->query($sql);
        if ($mysql->num_rows($result) > 0) {
            $admincontent .= "<h3>" . _HISTORY . "</h3>\r\n";
            $admincontent .= "<h3>" . _KEYWORD . ": $keyword</h3>\r\n";
            $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\r\n";
            $admincontent .= "	<tr>\r\n";
            $admincontent .= "		<th>" . _NAMA . "</th>\r\n";
            $admincontent .= "		<th>" . _TANGGALPESAN . "</th>\r\n";
            $admincontent .= "		<th>" . _ORDERCODE . "</th>\r\n";
            $admincontent .= "		<th>" . _SUBTOTALHARGA . "</th>\r\n";
            $admincontent .= "		<th>" . _STATUS . "</th>\r\n";
            if (!$isloadfromprint)
                $admincontent .= "		<th>" . _ACTION . "</th>\r\n";
            $admincontent .= "	</tr>\r\n";

            while ($row = $mysql->fetch_row($result)) {
                $admincontent .= "	<tr>\r\n";
                $admincontent .= "		<td>" . $row['nama_lengkap'] . "</td>\r\n";
                $admincontent .= "		<td>" . tglformat($row['tanggal_buka_transaksi']) . "</td>\r\n";
                $admincontent .= "		<td>" . $row['transaction_id'] . "</td>\r\n";
                $admincontent .= "		<td align=\"right\">" . number_format(hitungtotal($row['transaction_id']), 0, ',', '.') . "</td>\r\n";
                $admincontent .= "<td>";
                if (!$isloadfromprint)
                    $admincontent .= "<a href=\"?p=order&action=changestatus&trns=" . $row['transaction_id'] . "&origin=searchresult&keyword=$keyword\">";
                switch ($row['status']) {
                    case '0':
                        $admincontent .= _ORDERCANCEL;
                        break;
                    case '1':
                        $admincontent .= _WAITINGFORPAYMENT;
                        break;
                    case '2':
                        $admincontent .= _ORDERCONFIRMED;
                        break;
                    case '3':
                        $admincontent .= _WAITINGTOSEND;
                        break;
                    case '4':
                        $admincontent .= _SENT;
                        break;
                }
                if (!$isloadfromprint)
                    $admincontent .= "</a>";
                $admincontent .= "</td>\r\n";
                if (!$isloadfromprint) {

                    $admincontent .= "<td><a href=\"?p=order&action=detail&trns=" . $row['transaction_id'] . "\"><img alt=\"" . _DETAILS . "\" border=\"0\" src=\"../images/view.png\"></a>
			<a href=\"?p=order&action=delete&trns=" . $row['transaction_id'] . "\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a>
			</td>\r\n";
                    // $admincontent .= "		<td><a href=\"?p=order&action=detail&trns=" . $row['transaction_id'] . "\"><img alt=\"" . _DETAILS . "\" border=\"0\" src=\"../images/view.png\"></a></td>\r\n";
                }
                $admincontent .= "	</tr>\r\n";
            }
            $admincontent .= "</table>\r\n";
            if (!$isloadfromprint) {
                $admincontent .= "<p><a class=\"buton\" href=\"javascript:history.back()\">" . _BACK . "</a>";
                $admincontent .= " <a class=\"buton\" href=\"orderprint.php?p=order&action=searchresult&keyword=$keyword\" target=_blank>" . _PRINT . "</a></p>";
            }
        } else {
            createnotification(_NOHISTORYFOUND, _INFO, "info");
            if (!$isloadfromprint)
                $admincontent .= "<p><a class=\"buton\" href=\"javascript:history.back()\">" . _BACK . "</a></p>";
        }
    }
}

if ($action == "changestatus") {
	if ($_POST['submit']) {
		$invoice_status = $_POST['status'];
		$r_txtresi = $_POST['txtresi'];
		$action_before = $_POST['action_before'];
		$r_error_message = array();
		$tr_success = array();
		
		// print_r($_POST);
		// die();
		foreach($invoice_status as $trid => $status) {
			$tstatus = fiestolaundry($status, 1);
			$transaction_id = fiestolaundry($trid, 50);
			$nopengiriman = fiestolaundry($r_txtresi[$trid], 50);
			
			if ($status == 4 && $nopengiriman == '') {
				$r_error_message[]="No resi untuk transaksi #$transaction_id harus diisi";
			} else {
				$sql = "UPDATE sc_transaction SET status='$tstatus', latestupdate='$now' ";
				if($status==4){	$sql.=", no_pengiriman='$nopengiriman'";}
				$sql.=" WHERE transaction_id='$transaction_id'";
			}
		
			// $sql = "UPDATE sc_transaction SET status='$tstatus', latestupdate='$now' ";
			// if($status==4){	$sql.=", no_pengiriman='$nopengiriman'";}
			// $sql.=" WHERE transaction_id='$transaction_id'";
			
			$r=$mysql->query($sql);
			if(!$r) {
				$r_error_message[]="Gagal update transaction id $transaction_id";
			} else {
				if($status==3 || $status==4) {
					$tr_success[]= array(
						'status' => $status, 
						'transaction_id' => $transaction_id,
						'no_resi' => $nopengiriman
					);
				}
			}
			
		}
		
		if(count($r_error_message) > 0) {
			$error_message=join(";",$r_error_message);
			$action = createmessage($error_message, _ERROR, "error", "$action_before");
		} else {
			$action = createmessage( _DBSUCCESS, _SUCCESS, "success", "$action_before");
		}
		
		// print_r($tr_success);
		foreach($tr_success as $transactions) {
			if ($action == 'step1' && $transactions['status'] == 3) {
				sendemailstatus($transactions['transaction_id']);
			} else if ($action == 'step3' && $transactions['status'] == 4) {
				sendemailstatus($transactions['transaction_id']);
			}
		}
	}
	

	/*
    if ($_POST['submit'] != '') {
        $txid = fiestolaundry($_GET['trns'], 15);
        $origin = fiestolaundry($_GET['origin'], 20);
        $status = fiestolaundry($_POST['status'], 1);
        $strback = fiestolaundry($_POST['strback'], 200);
        $nopengiriman = fiestolaundry($_POST['nopengiriman'], 20);
        if ($txid != '') {
            if ($_POST['status'] == '3') {
                $update = "no_pengiriman = '" . $_POST['nopengiriman'] . "'";
            } else {
                $update = "no_pengiriman = '' ";
            }
            // if ($_POST['status'] == '2')
            // $update = "tanggal_tutup_transaksi = '$now' ";
            $sql = "UPDATE sc_transaction SET status='$status', latestupdate='$now', no_pengiriman='$nopengiriman' WHERE transaction_id='$txid'";
            if ($mysql->query($sql)) {
//                pesan(_SUCCESS, _DBSUCCESS, $strback);
                 $action = createmessage(_DBSUCCESS, _SUCCESS, "success", ".$strback.");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "");
            }
            switch ($status) {
                case 1:
                case 2:
                    $action = 'step1';
                    break;
                case 3:
                    $action = 'step3';
                    break;
                case 4:
                    $action = 'timeinterval';
                    break;
            }
        }

    } 
	

	else {
        //$_SESSION['record_url_before']=$_SERVER['HTTP_REFERER'];
        $txid = fiestolaundry($_GET['trns'], 15);
        $origin = fiestolaundry($_GET['origin'], 20);
        switch ($origin) {
            case 'timeintervalgo':
                $tgl1 = fiestolaundry($_GET['tgl1'], 2);
                $tgl2 = fiestolaundry($_GET['tgl2'], 2);
                $bln1 = fiestolaundry($_GET['bln1'], 2);
                $bln2 = fiestolaundry($_GET['bln2'], 2);
                $thn1 = fiestolaundry($_GET['thn1'], 4);
                $thn2 = fiestolaundry($_GET['thn2'], 4);
                $jenis = fiestolaundry($_GET['jenis'], 1);
                break;
            case 'searchresult':
                $keyword = fiestolaundry($_GET['keyword'], 100);
                break;
        }
        if ($txid != '') {
            $sql = "SELECT status, no_pengiriman FROM sc_transaction WHERE transaction_id='" . $_REQUEST['trns'] . "'";
            $result = $mysql->query($sql);
            list($status, $no_pengiriman) = $result->fetch_row();
            $admincontent .= "<form action=\"?p=order&action=changestatus&trns=$txid&origin=$origin\" method=\"POST\">\r\n";
            for ($i = 0; $i <= 3; $i++) {
                $checked[$i] = '';
            }
            $checked[$status] = 'checked';
            $admincontent .= "<input type=\"radio\" name=\"status\" value=\"0\" $checked[0] /> " . _ORDERCANCEL . "<br />";
            $admincontent .= "<input type=\"radio\" name=\"status\" value=\"1\" $checked[1] /> " . _WAITINGFORPAYMENT . "<br />";
            //$admincontent .= "<input type=\"radio\" name=\"status\" value=\"2\" $checked[2] /> "._ORDERCONFIRMED."<br />";
            $admincontent .= "<input type=\"radio\" name=\"status\" value=\"3\" $checked[3] /> " . _WAITINGTOSEND . "<br />";
            $admincontent .= "<input type=\"radio\" name=\"status\" value=\"4\" $checked[4] /> " . _SENT . " <input type=\"text\" name=\"nopengiriman\" value=\"$no_pengiriman\" /><br />";
            switch ($origin) {
                case 'detail':
                    $strback = "?p=order&action=detail&trns=$txid";
                    break;
                case 'step0':
                case 'step1':
                case 'step2':
                case 'step3':
                    $strback = "?p=order&action=$origin";
                    break;
                case 'timeintervalgo':
                    $strback = "?p=order&action=timeintervalgo&tgl1=$tgl1&bln1=$bln1&thn1=$thn1&tgl2=$tgl2&bln2=$bln2&thn2=$thn2&jenis=$jenis";
                    break;
                case 'searchresult':
                    $strback = "?p=order&action=searchresult&keyword=$keyword";
                    break;
                case 'default':
                    $strback = "?p=order";
                    break;
            }
            $admincontent .= "<input type=\"hidden\" name=\"strback\" value=\"$strback\" />";
            $admincontent .= "<input type=\"submit\" name=\"submit\" class=\"buton\" value=\"" . _UBAHSTATUS . "\" />";
            $admincontent .= "</form>";
        }
    }
	*/
}

if ($action == "step0" || $action == "step1" || $action == "step2" || $action == "step3" || $action == "fullorder") 
{ // tanpa filter tanggal
    $type = fiestolaundry($_GET['type']);
    $sql = "";
    if ($action == "fullorder") {
        $judul = _LISTFULLORDER;
        $sql = "SELECT * FROM sc_transaction ORDER BY tanggal_buka_transaksi DESC";
    }
    // if ($action == "step0") {
    // $sql = "SELECT * FROM sc_transaction WHERE status=0 ORDER BY tanggal_buka_transaksi DESC";
    // }
    if ($action == "step1") {

        $judul = _LISTNEWORDER;
        $sql = "SELECT * FROM sc_transaction WHERE status=1 OR status=2 ORDER BY tanggal_buka_transaksi DESC";
    }
    // if ($action == "step2") {
    // $judul = _LISTORDERCONFIRMED;
    // $sql = "SELECT * FROM sc_transaction WHERE status=2 ORDER BY tanggal_buka_transaksi DESC";
    // }
    if ($action == "step3") {
        $judul = _LISTORDERPROCESSSENT;
        $sql = "SELECT * FROM sc_transaction WHERE status=3 ORDER BY tanggal_buka_transaksi DESC";
    }
    $result = $mysql->query($sql);
    $admintitle = "$judul";
    if ($mysql->num_rows($result) == 0) {
        //$admincontent .="<p>"._NOTRANSACTION."</p>\r\n";
        $admincontent .= createstatus(_NOTRANSACTION, "info");
        // $admincontent .= "<p><a class=\"buton\" href=\"?p=order\" class=\"menujual\">"._GOTOMAIN." $judulmodul</a></p>\r\n";
    } else {
        $admincontent .= "
		<form method='post' action='?p=order&action=changestatus'>
		<input type='hidden' name='action_before' value='$action' >
		<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\r\n";
        $admincontent .= "	<tr>\r\n";
        $admincontent .= "		<th>" . _NOMOR . "</th>\r\n";
        $admincontent .= "		<th>" . _TANGGALPESAN . "</th>\r\n";
        $admincontent .= "		<th>" . _FULLNAME . "</th>\r\n";
        $admincontent .= "		<th>" . _ORDERCODE . "</th>\r\n";
        $admincontent .= "		<th>" . _SUBTOTALHARGA . "</th>\r\n";
        $admincontent .= "		<th>" . _STATUS . "</th>\r\n";
        if (!$isloadfromprint)
            $admincontent .= "		<th>" . _DETAILS . "</th>\r\n";
        $admincontent .= "	</tr>";
        $i = 0;
        while ($row = $mysql->fetch_assoc($result)) {
			
			if ($row['is_dropship'] == 1) {
				$sqlcustomername = "SELECT nama_lengkap FROM sc_receiver WHERE receiver_id='" . $row['receiver_id'] . "'";
				$resultcustomername = $mysql->query($sqlcustomername);
				$rowcustomername = $mysql->fetch_assoc($resultcustomername);
			} else {
				$sqlcustomername = "SELECT nama_lengkap FROM sc_customer WHERE customer_id='" . $row['customer_id'] . "'";
				$resultcustomername = $mysql->query($sqlcustomername);
				$rowcustomername = $mysql->fetch_assoc($resultcustomername);
			}
            $i++;
            $admincontent .= "	<tr>\r\n";
            $admincontent .= "		<td>" . $i . "</td>\r\n";
            $admincontent .= "		<td>" . tglformat($row['tanggal_buka_transaksi'], true) . "</td>\r\n";
            $admincontent .= "		<td>" . $rowcustomername['nama_lengkap'] . "</td>\r\n";
            $admincontent .= "		<td>" . $row['transaction_id'] . "</td\r\n>";
            $admincontent .= "		<td align=\"right\">" . number_format(hitungtotal($row['transaction_id']), 0, ',', '.') . "</td>\r\n";
            $admincontent .= "<td>";
			$r_status[0]=_ORDERCANCEL;
			$r_status[1]=_WAITINGFORPAYMENT;
			$r_status[2]=_ORDERCONFIRMED;
			$r_status[3]=_WAITINGTOSEND;
			$r_status[4]=_SENT;
            if (!$isloadfromprint)
			{
			$transaction_id=$row['transaction_id'];
			$admincontent .="<select name='status[$transaction_id]' id='sel_$transaction_id' autocomplete='off'>";
			foreach($r_status as $index => $tstatus)
			{
			$selected="";
			// if ($action == 'step1') {
				// $row['status'] = 1;
			// }
			if($row['status']==$index)$selected="selected='selected'";
			$admincontent .="<option value='$index' $selected>$tstatus</option>";
			}
			$admincontent .="</select>";
			$admincontent .="<span style='display:none;' id='resi_$transaction_id'>
				<input style='height:30px;width:150px;' size='15' type='text' name='txtresi[$transaction_id]' placeholder='"._NORESI."' />
			</span>";
			$admincontent .=<<<END
			<script>
			$(document).ready(function(){
			val=($("#sel_$transaction_id").val());
			if(val==4)	
			{
			$("#resi_$transaction_id").show();
			}
			else
			{
			$("#resi_$transaction_id").hide();
			}
			$("#sel_$transaction_id").change(function(){
			val=$(this).val();
			if(val==4)
			{
			$("#resi_$transaction_id").show();
			}
			else
			{
			$("#resi_$transaction_id").hide();
			}
		
			});
			});
			</script>
END;
			    // $admincontent .= "<a href=\"?p=order&action=changestatus&trns=" . $row['transaction_id'] . "&origin=$action\">";
            	// $admincontent .=$r_status[$row['status']];		
                // $admincontent .= "</a>";
			}
			else
			{
				$admincontent .=$r_status[$row['status']];		
			}
            $admincontent .= "</td>\r\n";
            if (!$isloadfromprint) {
                $admincontent .= "<td><a href=\"?p=order&action=detail&trns=" . $row['transaction_id'] . "\"><img alt=\"" . _ACTION . "\" border=\"0\" src=\"../images/view.png\"></a>
			<a href=\"?p=order&action=delete&trns=" . $row['transaction_id'] . "\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a>
			</td>\r\n";
            }
            $admincontent .= "	</tr>";
        }
        $admincontent .= "</table><br />";
        if (!$isloadfromprint) {
            //$admincontent .= "<a href=\"?p=order\">"._GOTOMAIN." $judulmodul</a>";
            $admincontent .= "<input type='submit' name='submit' class='buton' value='"._SAVE."'>";
        }
		$admincontent .="</form>";
    }
}
if ($action == "timeinterval" || $action == "timeintervalgo") { // dengan filter tanggal
    $currmonth = date('m');
    $curryear = date('Y');
    $date = "01-" . $currmonth . "-" . $curryear;
    //$date = strtotime('-1 month');
    $currdate = strtotime("now");
    if ($_GET['startdate']!='')
	{  
		$t=explode("-",$_GET['startdate']);
		$t=array_reverse($t);
		$_GET['startdate']=join("-",$t);
        $startdate = date('Y-m-d', strtotime(fiestolaundry($_GET['startdate'], 20)));
	}
    else
	{
        $startdate = date('Y-m-d', strtotime($date));
	}
    if ($_GET['enddate']!='')
	{
		$t=explode("-",$_GET['enddate']);
		$t=array_reverse($t);
		$_GET['enddate']=join("-",$t);
        $enddate = date('Y-m-d', strtotime(fiestolaundry($_GET['enddate'], 20)));
	}
    else
	{
        $enddate = date('Y-m-d', $currdate);
	}
	
    $displaystartdate = date('d-m-Y', strtotime($startdate));
    $displayenddate = date('d-m-Y', strtotime($enddate));
    $admincontent .= "<script type='text/javascript'>
									  $(function() {
										$( '#startdate' ).datepicker({ dateFormat: 'dd-mm-yy' });
										$( '#enddate' ).datepicker({ dateFormat: 'dd-mm-yy' });
									});
									</script>";
    if ($action == 'timeintervalgo') {
        $jenis = fiestolaundry($_GET['jenis']);
		$tgl1 = fiestolaundry($_GET['tgl1'], 2);
		$tgl2 = fiestolaundry($_GET['tgl2'], 2);
		$bln1 = fiestolaundry($_GET['bln1'], 2);
		$bln2 = fiestolaundry($_GET['bln2'], 2);
		$thn1 = fiestolaundry($_GET['thn1'], 4);
		$thn2 = fiestolaundry($_GET['thn2'], 4);
        if ($jenis != '')
            $where .= " AND status = '$jenis'";
		if ($keyword != '') {
			$where .= " AND ( transaction_id LIKE '%$keyword%' || c.telepon LIKE '%$keyword%' || c.cellphone1 LIKE '%$keyword%' || c.cellphone2 LIKE '%$keyword%')";
		}
    }
    $admincontent .= "<h3>" . _ORDERPROCESS . "</h3>\r\n";
    if ($isloadfromprint) {
        $admincontent .= "<p>" . _TANGGALPESAN . ": " . tglformat("$startdate") . " - " . tglformat("$enddate");
        $admincontent .= "<br />" . _STATUS . ": ";
        switch ($jenis) {
            case '0':
            $admincontent .= _ORDERCANCEL;
            break;
            case '1':
                $admincontent .= _WAITINGFORPAYMENT;
                break;
            // case '2':
            // $admincontent .= _ORDERCONFIRMED;
            // break;
            case '3':
                $admincontent .= _WAITINGTOSEND;
                break;
            case '4':
            $admincontent .= _SENT;
            break;
			default:
			$admincontent .= _ALL;
			break;
        }
        $admincontent .= "</p>\r\n";
    } else {
        $admincontent .= "<form class=\"timeinterval-order form-inline\" method=\"GET\">\r\n";
        $admincontent .= "<input type=\"hidden\" name=\"p\" value=\"order\" />\r\n";
        $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"timeintervalgo\" />\r\n";
        $admincontent .= "<p>
							<input type=\"text\" name=\"startdate\" value=\"$displaystartdate\" id=\"startdate\"/> - 
							<input type=\"text\" name=\"enddate\" value=\"$displayenddate\" id=\"enddate\"/> 
							<select name=\"jenis\" id=\"jenis\">\r\n";
        $selectedjenis = array();
        for ($i = 0; $i <= 3; $i++) {
			if($_GET['jenis']!=''){$_GET['jenis']=intval($_GET['jenis']);}
            $selectedjenis[$i] =($i === $_GET['jenis'])?'selected':'';
			 }
        $admincontent .= " 		<option value=\"\" >" . _ALL . "</option>\r\n";
        $admincontent .= "		<option value=\"0\" $selectedjenis[0]>" . _ORDERCANCEL . "</option>\r\n";
        $admincontent .= "		<option value=\"1\" $selectedjenis[1]>" . _WAITINGFORPAYMENT . "</option>\r\n";
        //$admincontent .= "	<option value=\"2\" $selectedjenis[2]>" . _ORDERCONFIRMED . "</option>\r\n";
        $admincontent .= "		<option value=\"3\" $selectedjenis[3]>" . _WAITINGTOSEND . "</option>\r\n";
        $admincontent .= "		<option value=\"4\" $selectedjenis[4]>" . _SENT . "</option>\r\n";
        $admincontent .= "	</select>";
		$admincontent .= "	<input type=\"text\" name=\"keyword\" placeholder=\"Cari kode transaksi atau no hp\" value=\"$keyword\"/>";
		$admincontent .= "	<input type=\"submit\" class=\"buton\" value=\"" . _SEARCH . "\" />";
		$admincontent .= "</p></form>\r\n";
        $admincontent .= "<br />\r\n";
    }

    $sql = "SELECT t.* FROM sc_transaction t LEFT JOIN sc_customer c ON c.customer_id=t.customer_id WHERE tanggal_buka_transaksi >= '$startdate 00:00:00' AND tanggal_buka_transaksi <= '$enddate 23:59:59' $where ORDER BY tanggal_buka_transaksi DESC";
	$result = $mysql->query($sql);
    if ($mysql->num_rows($result) == 0) {
        $action = createmessage(_NOTRANSACTION, _INFO, "info", "");
//        if (!$isloadfromprint)
//            $admincontent .= "<p><a class=\"buton\" href=\"?p=order\">" . _GOTOMAIN . " $judulmodul</a></p>\r\n";
    } else {
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\r\n";
        $admincontent .= "	<tr>\r\n";
        $admincontent .= "		<th>" . _NOMOR . "</th>\r\n";
        $admincontent .= "		<th>" . _TANGGALPESAN . "</th>\r\n";
        $admincontent .= "		<th>" . _FULLNAME . "</th>\r\n";
        $admincontent .= "		<th>" . _ORDERCODE . "</th>\r\n";
        $admincontent .= "		<th>" . _SUBTOTALHARGA . "</th>\r\n";
        $admincontent .= "		<th>" . _STATUS . "</th>\r\n";
        if (!$isloadfromprint)
            $admincontent .= "		<th>" . _ACTION . "</th>\r\n";
        $admincontent .= "	</tr>\r\n";
        $i = 0;
        while ($row = $mysql->fetch_assoc($result)) {
            $i++;
            $admincontent .= "	<tr>\r\n";
            $admincontent .= "		<td>" . $i . "</td>\r\n";
            $sqlnama = "SELECT nama_lengkap FROM sc_customer WHERE customer_id = " . $row['customer_id'];
            $resultnama = $mysql->query($sqlnama);
            $rowsql = $mysql->fetch_assoc($resultnama);
            $admincontent .= "		<td>" . tglformat($row['tanggal_buka_transaksi']) . "</td>\r\n";
            $admincontent .= "		<td>" . $rowsql['nama_lengkap'] . "</td>\r\n";
            $admincontent .= "		<td>" . $row['transaction_id'] . "</td>\r\n";
            $admincontent .= "		<td align=\"right\">" . number_format(hitungtotal($row['transaction_id']), 0, ',', '.') . "</td>\r\n";
            $admincontent .= "<td>";
            // if (!$isloadfromprint)
                // $admincontent .= "<a  href=\"?p=order&action=changestatus&trns=" . $row['transaction_id'] . "&origin=timeintervalgo&tgl1=$tgl1&bln1=$bln1&thn1=$thn1&tgl2=$tgl2&bln2=$bln2&thn2=$thn2&jenis=$jenis\">";
		
            switch ($row['status']) {
                case '0':
                    $admincontent .= _ORDERCANCEL;
                    break;
                case '1':
                    $admincontent .= _WAITINGFORPAYMENT;
                    break;
                case '2':
                    $admincontent .= _ORDERCONFIRMED;
                    break;
                case '3':
                    $admincontent .= _WAITINGTOSEND;
                    break;
                case '4':
                    //$admincontent .= _SENT;
					$no_pengiriman=$row['no_pengiriman'];
					if ($no_pengiriman == '') {
					$admincontent .= _SENT;
					} else {
					$admincontent .= _SENT . ", " . _NOPENGIRIMAN . " $no_pengiriman";
					}
                    break;
            }
			
            // if (!$isloadfromprint)
                // $admincontent .= "</a>";
            $admincontent .= "</td>\r\n";

            if (!$isloadfromprint) {
                $admincontent .= "<td><a href=\"?p=order&action=detail&trns=" . $row['transaction_id'] . "\"><img alt=\"" . _DETAILS . "\" border=\"0\" src=\"../images/view.png\"></a>
			<a href=\"?p=order&action=delete&trns=" . $row['transaction_id'] . "\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a>
			</td>\r\n";
            }

            //   $admincontent .= "<td><a href=\"?p=order&action=detail&trns=" . $row['transaction_id'] . "\"><img alt=\"" . _DETAILS . "\" border=\"0\" src=\"../images/view.png\"></a></td>\r\n";
            $admincontent .= "</tr>\r\n";
        }
        $admincontent .= "</table>";
        if (!$isloadfromprint) {
            //$admincontent .= "<p><a href=\"?p=order\">"._GOTOMAIN." $judulmodul</a>";
            // $admincontent .= "<p><a class=\"buton\" href=\"orderprint.php?p=order&action=timeintervalgo&tgl1=$tgl1&bln1=$bln1&thn1=$thn1&tgl2=$tgl2&bln2=$bln2&thn2=$thn2&jenis=$jenis\" target=_blank>" . _PRINT . "</a></p>";
            $admincontent .= "<p><a class=\"buton\" href=\"orderprint.php?p=order&action=timeintervalgo&startdate=$startdate&enddate=$enddate&jenis=$jenis&keyword=$keyword\" target=_blank>" . _PRINT . "</a><a href=\"?p=order&action=downloadxls&startdate=$startdate&enddate=$enddate&jenis=$jenis&keyword=$keyword\" class=\"buton\">"._DOWLOADXLS."</a></p>";
        }
    }
}

if ($action == 'invoice') {
	$trns = $_REQUEST['trns'];
	$sql = "SELECT transaction_id ,tanggal_buka_transaksi, tanggal_tutup_transaksi, customer_id, receiver_id, metode_pembayaran, ongkir, status, catatan, no_pengiriman, latestupdate, kodevoucher, tipe_voucher, nominal_voucher, kurir FROM sc_transaction WHERE transaction_id='$trns'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) > 0) {
        list($transaction_id ,$tanggal_buka_transaksi, $tanggal_tutup_transaksi, $customer_id, $receiver_id, $metode_pembayaran, $shipping, $status, $catatan, $no_pengiriman, $latestupdate, $kodevoucher, $tipe_voucher, $nominal_voucher, $kurir) = $mysql->fetch_row($result);
		$couriers = json_decode($kurir, true);
		$courier_tipe = $couriers['tipe'];
		$courier_city = explode(',', $couriers['kota']);
		$kecamatan = $courier_city[0];
		
		$admincontent .= "<div id=\"block-content-invoice\">";
		$admincontent .= "<div id=\"invoice-container\">";
		$admincontent .= "<div class=\"invoice-header\">";
		$admincontent .= "<p><label>"._COURIER."</label><br/>";
		$admincontent .= "$courier_tipe</p>";
		
		$admincontent .= "<p><label>"._INVOICE."</label><br/>";
		$admincontent .= "$trns</p>";
		$admincontent .= "</div>";
		$admincontent .= "</div>	<!-- /.invoice-header -->\r\n";
		
		$sql = "SELECT nama_lengkap, email, alamat, alamat2, kota, provinsi, zip, telepon, cellphone1, cellphone2, nama_pengirim, telp_pengirim, email_pengirim FROM sc_receiver WHERE receiver_id='$receiver_id'";
        $result = $mysql->query($sql);
        list($nama_lengkap, $email, $alamat, $alamat2, $kota, $provinsi, $zip, $telepon, $cellphone1, $cellphone2, $nama_pengirim, $telp_pengirim, $email_pengirim) = $result->fetch_row();
		$admincontent .= "<div class=\"invoice-sub-header\">";
		$admincontent .= "<div class=\"invoice-from-sender\">";
		$admincontent .= "<p><label>"._INVOICEFROMSENDER."</label></p>";
		$dropship = "<div>$cfg_from_sender_order</div>";
		// if ($nama_pengirim != '') {
		if ($is_dropship == 1 || $nama_pengirim != '') {
			$dropship = "<div><strong>$nama_pengirim</strong></div>";
			$dropship .= "<div>$telp_pengirim</div>";
		}
		$admincontent .= $dropship;
		$admincontent .= "</div>	<!-- /.invoice-from-sender -->\r\n";
		
		$admincontent .= "<div class=\"invoice-to-receiver\">";
		
		$admincontent .= "<div class=\"invoice-customer\">";
		$admincontent .= "<p class=\"header-title\">"._TO."</p>";
		$admincontent .= "<div class=\"invoice-customer-data\">";
		$admincontent .= "<div>$nama_lengkap</div>";
		$admincontent .= "<div>$cellphone1 ".(($cellphone2 != '') ? "/ $cellphone2" : "")."</div>";
		$kecamatan = ($kecamatan != "") ? ", $kecamatan" : "";
		$state = ($state != "") ? ", $state" : "";
		$kota = ($kota != "") ? ", $kota" : "";
		$zip = ($zip != "") ? ", $zip" : "";
		$admincontent .= "<div>$alamat".(($alamat2 != '') ? $alamat2 : "")."$kecamatan$kota$state$zip</div>";
		$admincontent .= "</div>	<!-- /.invoice-customer-data -->\r\n";
		$admincontent .= "</div> 	<!-- /.invoice-customer -->\r\n";
		
		$admincontent .= "</div>	<!-- /.invoice-to-receiver -->\r\n";
		
		$admincontent .= "</div>	<!-- /.invoice-sub-header -->\r\n";
		$admincontent .= "</div>	<!-- /.invoice-sub-header -->\r\n";
		
		$admincontent .= "<div class=\"table-list-cart\"><div class=\"left-post\"><strong>"._PRODUCTLIST."</strong></div><div class=\"right-post\"><strong>"._INVOICE." ".$trns."</strong></div>";
        $admincontent .= "<table style=\"clear:both;margin-top:14px\" class=\"stat-table table table-stats table-striped table-sortable table-bordered list-product-order\">";
		$admincontent .= "
		<table class=\"stat-table table table-stats table-striped table-sortable table-bordered list-product-order\">";
		
        $admincontent .= "<tr>";
        $admincontent .= "<td align=\"center\">#</td>";
        $admincontent .= "<td>" . _PRODUCTNAME . "</td>";
        $admincontent .= "<td align=\"center\">" . _QUANTITY . "</td>";
        $admincontent .= "</tr>";
        $totalweight = 0;

        $sql = "SELECT jumlah,td.harga,product_name,td.filename, c.idmerek FROM sc_transaction_detail td INNER JOIN catalogdata c ON c.id=td.product_id  WHERE transaction_id='$trns' ";
        $result = $mysql->query($sql);
		$i = 1;
        while (list($quantity, $price, $productname, $filename, $idmerek) = $mysql->fetch_row($result)) {
			$brand_name = get_brand_name($idmerek);
            $line_cost = $quantity * $price;  //work out the line cost
            $total = $total + $line_cost;   //add to the total cost
            $admincontent .= "<tr>";
            $admincontent .= "<td align=\"center\">$i</td>";
            $admincontent .= "<td><small class=\"brand-name\">$brand_name</small>$productname</td>";
            $admincontent .= "<td align=\"center\">" . number_format($quantity, 0, ',', '.') . "</td>";
            $admincontent .= "</tr>";
			$i++;
        }
		$admincontent .= "</table>";

    } 
}

if ($action == 'downloadxls') {
    $currmonth = date('m');
    $curryear = date('Y');
    $date = "01-" . $currmonth . "-" . $curryear;
    //$date = strtotime('-1 month');
    $currdate = strtotime("now");
    if ($_GET['startdate']!='')
	{  
		$t=explode("-",$_GET['startdate']);
		$t=array_reverse($t);
		$_GET['startdate']=join("-",$t);
        $startdate = date('Y-m-d', strtotime(fiestolaundry($_GET['startdate'], 20)));
	}
    else
	{
        $startdate = date('Y-m-d', strtotime($date));
	}
    if ($_GET['enddate']!='')
	{
		$t=explode("-",$_GET['enddate']);
		$t=array_reverse($t);
		$_GET['enddate']=join("-",$t);
        $enddate = date('Y-m-d', strtotime(fiestolaundry($_GET['enddate'], 20)));
	}
    else
	{
        $enddate = date('Y-m-d', $currdate);
	}	
	
	
	$sql = "SELECT t.* FROM sc_transaction t LEFT JOIN sc_customer c ON c.customer_id=t.customer_id WHERE tanggal_buka_transaksi >= '$startdate 00:00:00' AND tanggal_buka_transaksi <= '$enddate 23:59:59' $where ORDER BY tanggal_buka_transaksi DESC";
	
	/** Error reporting */
	ob_get_clean();
	
	/** Include PHPExcel */
	//require_once "$cfg_app_path/aplikasi/phpexcel/Classes/PHPExcel.php";
	require_once dirname(__FILE__) . '/../../aplikasi/phpexcel/Classes/PHPExcel.php';
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	// Initialise the Excel row number
	$rowCount = 1; 
	
	if ($result = $mysql->query($sql)) {
		$total_records = $mysql->num_rows($result);
		if ($total_records == 0) {
			$action = createmessage(_NOTRANSACTION, _INFO, "info", "");
		} else {
			
			$pages = ceil($total_records / $max_page_list);
			
			$start = $screen * $max_page_list;
			$sql .= " LIMIT $start, $max_page_list";
			$result = $mysql->query($sql);
			
			$no_urut = $start;
			$subtotal = 0;

			$temp_columns = array(
				_TANGGALPESAN,
				_FULLNAME,
				_ORDERCODE,
				_PRODUCTNAME,
				_QUANTITY,
				_PRICE,
				_COURIER,
				_USEINSURANCE,
				_USEPACKING,
				_SUBTOTALHARGA,
				_STATUS
			);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $temp_columns[0]);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $temp_columns[1]);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $temp_columns[2]);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $temp_columns[3]);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $temp_columns[4]);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $temp_columns[5]);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $temp_columns[6]);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $temp_columns[7]);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $temp_columns[8]);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $temp_columns[9]);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $temp_columns[10]);
			$rowCount++;
			
			while($row = $mysql->fetch_assoc($result)) {
				$no_urut++;
				$tanggal_buka_transaksi = $row['tanggal_buka_transaksi'];
				$order_code 			= $row['transaction_id'];
				$subtotal 				= hitungtotal($order_code);
				$status_order 			= $row['status'];
				$is_dropship 			= $row['is_dropship'];
				$customer_id 			= $row['customer_id'];
				$receiver_id 			= $row['receiver_id'];
				$ongkir	 				= $row['ongkir'];
				$insurance_value		= $row['insurancevalue'];
				$packing_value			= $row['packingvalue'];
				
				if (!$is_dropship) {
				
					$sql_customer = "SELECT nama_lengkap, email, alamat alamat2, kota, provinsi, zip, 
									telepon, cellphone1, cellphone2					
									FROM sc_customer WHERE customer_id='$customer_id'";
				} else {
					$sql_customer = "SELECT nama_lengkap, email, alamat, alamat2, kota, provinsi, zip, 
									telepon, cellphone1, cellphone2, nama_pengirim, telp_pengirim, email_pengirim
									FROM sc_receiver WHERE receiver_id='$receiver_id'";
				}
				
				$result_customer = $mysql->query($sql_customer);
				list($fullname, $user_email, $address1, $address2, $city, $state, $zip, $telephone, $nama_pengirim, $telp_pengirim) = $mysql->fetch_row($result_customer);
				
				$sql_detail = "SELECT product_name, jumlah, harga FROM sc_transaction_detail WHERE transaction_id='$order_code'";
				$qty_arr 		= array();
				$priceidr_arr 	= array();
				$pesanan_arr 	= array();
				if ($res_detail = $mysql->query($sql_detail)) {
					while($row_detail = $mysql->fetch_assoc($res_detail)) {
						$pesanan_arr[] 		= $row_detail['product_name'];
						$qty_arr[]			= $row_detail['jumlah'];
						$priceidr_arr[]		= $row_detail['harga'];
					}
					
				}
				$total_record = count($qty_arr);
				$temp_pesanan = implode("\n", $pesanan_arr);
				$temp_qty = implode("\n", $qty_arr);
				$temp_priceidr = implode("\n", $priceidr_arr);
				
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, tglformat($tanggal_buka_transaksi));
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $fullname);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $order_code);
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $temp_pesanan);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $temp_qty);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $temp_priceidr);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $ongkir);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $insurance_value);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('#,##0');$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $packing_value);
				$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $subtotal);
				$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getNumberFormat()->setFormatCode('#,##0');
				
				switch ($status_order) {
					case '0':
						$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, _ORDERCANCEL);
						break;
					case '1':
						$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, _WAITINGFORPAYMENT);
						break;
					case '2':
						$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, _ORDERCONFIRMED);
						break;
					case '3':
						$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, _WAITINGTOSEND);
						break;
					case '4':
						$no_pengiriman = $row['no_pengiriman'];
						if ($no_pengiriman == '') {
							$admincontent .= _SENT;
							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, _SENT);
						} else {
							$send_to_courier = _SENT . ", " . _NOPENGIRIMAN . " $no_pengiriman";
							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $send_to_courier);
						}
						break;
				}
				
				// Increment the Excel row counter
				$objPHPExcel->getActiveSheet()->getColumnDimension('A'.$rowCount)->setAutoSize(true);
				$rowCount++; 
			}
			
			if ($total_record > 1) {
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:K1")->getFont()->setBold(true);
			
			$ext = ".xls";
			$filename = "order_ready_".date('Y-m-d H:i:s').$ext;
			
		}
	}
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// // If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}
?>
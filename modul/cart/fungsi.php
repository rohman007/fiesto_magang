<?php

function ceiling($number, $significance = 1) {
    // return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number / $significance) * $significance) : false;
	
	$nilai = SUBSTR($number, 0, 3);
	$number = ($nilai <= 0.5) ? 1 : $number;
    return ( is_numeric($number) && is_numeric($significance) ) ? (round($number / $significance) * $significance) : false;
}

//function to check if a product exists
function productExists($product_id) {
	global $mysql;
    //use sprintf to make sure that $product_id is inserted into the query as a number - to prevent SQL injection
    $sqlgetproduct = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, keterangan,
                    harganormal, diskon, matauang, berat,
                    if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
                    as nilaidiskon,
                    harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
                            diskon)) as hargadiskon 
                    FROM catalogdata WHERE id=%d;";
    $sql = sprintf($sqlgetproduct, $product_id);
    return $mysql->num_rows($mysql->query($sql)) > 0;
}

//function to show the cart
// Modified by Aly 2016-10-04
function showcart() {
	global $mysql;
    global $pakaicart, $cfg_app_url, $kursusdidr, $cfg_img_path, $cfg_img_url, $msg;
	global $cfg_insurance, $cfg_insurance, $cfg_insurance_plus, $pakaivoucher, $pakaiadditional;
	global $pakaicatalogattribut;
	
	$cfg_thumb_path = "$cfg_img_path/catalog/small";
	$cfg_thumb_url = "$cfg_img_url/catalog/small";	

    if ($pakaicart) {
        $urlfunc = new PrettyURL();
        $counter = 0;
		if (isset($msg) && $msg != '') {
			$content .= '<div class="alert alert-danger">'.$msg.'</div>';
		}
        if (!empty($_SESSION['cart'])) {
            $content .= '<div class="table_block table-responsive" id="order-detail-content">';
			$content .= '<table class="table table-striped" id="cart_summary">';
			$content .= '<tr>';
			$content .= '<th class="cart_product first_item">'._THUMBNAIL.'</th>';
			$content .= '<th class="cart_description item">'._PRODUCTNAME.'</th>';
			$content .= '<th class="cart_unit item">'._PRICE.'</th>';
			$content .= '<th class="cart_quantity item">'._QUANTITY.'</th>';
			$content .= '<th class="cart_total item">'._LINECOST.'</th>';
			$content .= '<th class="cart_delete last_item">&nbsp;</th>';
			$content .= '</tr>';
			
			$user_id = $_SESSION['member_uid'];
			$sql = "SELECT city FROM webmember WHERE user_id='$user_id'";
			$result = $mysql->query($sql);
			list($city) = $mysql->fetch_row($result);
			
			//iterate through the cart, the $product_id is the key and $quantity is the value	
			foreach ($_SESSION['cart'] as $product_id => $quantity) {
				$productId = base64_encode($product_id);
				
				if ($pakaicatalogattribut) {
					list($product_id, $product_attr_id, $product_color, $product_price, $product_price_plus, $product_size) = explode('|', $product_id);
				}
				// echo "$product_id, $product_attr_id, $product_color, $product_price, $product_price_plus, $product_size<br>";
				//get the name, description and price from the database - this will depend on your database implementation.
				//use sprintf to make sure that $product_id is inserted into the query as a number - to prevent SQL injection
				$sql = "SELECT id, cat_id, title, matauang, harganormal-(if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal), diskon)) as hargadiskon,filename, idmerek, sku FROM catalogdata WHERE id=$product_id";
				$result = $mysql->query($sql);
				
				//Only display the row if there is a product (though there should always be as we have already checked)
				if ($mysql->num_rows($result) > 0) {
					list($pid, $cat_id, $title, $matauang, $hargadiskon, $filename, $brand_id, $product_code) = $mysql->fetch_row($result);
					$brand_name = get_brand_name($brand_id);
					$cat_name = get_cat_name($cat_id);
					
					if ($pakaicatalogattribut) {
						if ($product_price_plus > 0) $hargadiskon = $hargadiskon + $product_price_plus;
					}
					if ($matauang == 'USD') {
						$hargadiskon = $hargadiskon * $kursusdidr;
					}
					$line_cost = $hargadiskon * $quantity;  //work out the line cost
					$total = $total + $line_cost;   //add to the total cost
					
					$titleurl = array();
					$titleurl['pid'] = $title;
					$titleurl['cat_id'] = $cat_name;
					
					//show this information in table cells
					$thumbnail = ($filename != '' && file_exists("$cfg_thumb_path/$filename")) ? "$cfg_thumb_url/$filename" : "$cfg_app_url/images/none.gif";
					$content .= '<tr class="cart_item first_item address_0 odd">';
					$content .= '<td class="cart_product">';
					$content .= '<img src="'.$thumbnail.'">';
					$content .= '</td>';
					
					$content .= '<td class="cart_description">';
					$content .= '<small class="brand-name">'.$brand_name.'</small>';
					$content .= '<p class="product-name"><a href="'.$urlfunc->makePretty("?p=catalog&action=detail&pid=$pid&cat_id=$cat_id", $titleurl).'">'.$title.'</a></p>';
					
					if ($pakaicatalogattribut) {
						if ($product_color != '' || $product_size != '') {
							$content .= '<small class="product-attribut">'.$product_color.', '.$product_size.'</small>';
						}
					}
					$content .= '</td>';
					
					/* $content .= '<td class="cart_unit" align="right">';
					$content .= '<span class="price">';
					$content .= '<span class="price-now">'. number_format($hargadiskon, 0, ',', '.') .'</span>';
					$content .= '</span>';
					$content .= '</td>'; */
					
					$content .= '<td class="cart_unit" align="right">';
					$content .= '<span class="price">';
					$content .= '<span class="price-now" data-price="'.$hargadiskon.'">'. number_format($hargadiskon, 0, ',', '.') .'</span>';
					$content .= '</span>';
					$content .= '</td>';
					
						
					$content .= '<td class="cart_quantity text-center">';
					$content .= '<form action="' . $urlfunc->makePretty("?p=cart&action=update") . '" method="POST">';
					// $content .= '<input class="cart_quantity_input form-control grey" name="quantityUpdated'.$counter.'" 
					// type="number" min="1" max="'.get_item_stock($product_id).'" size="2" maxlength="3" value="'.$quantity.'" style="text-align:right" /><br>';
					
					// $get_item_stock = get_item_stock($product_id)
					/* $content .= '<div class="css_quantity">';
					$content .= '<a id="qtyminus" class="qty-trigger" data-id="'.$counter.'"><i class="fa fa-minus"></i></a>';
					$content .= '<input type="hidden" name="quantityUpdated'.$counter.'" id="cart_quantity_input_'.$counter.'" class="cart_quantity_input form-control grey" data-min="1" data-max="'.$get_item_stock.'" value="'.$quantity.'">';
					$content .= '<input type="hidden" id="product_code" name="product_code'.$counter.'" value="'.$product_code.'" />';
					$content .= '<span id="lblcart_quantity_input_'.$counter.'" class="lblcart_quantity_input">'.$quantity.'</span>';
					$content .= '<a id="qtyplus" class="qty-trigger" data-id="'.$counter.'"><i class="fa fa-plus"></i></a>';
					$content .= '</div>';
					
					$content .= '<input class="btnupdatecart more" type="submit" value="' . _UPDATECART . '" />';
					$content .= '</form>';
					$content .= '</td>'; */
					
					if ($pakaistock) {
						$data_max =  'data-max="'.get_item_stock($product_id).'"';
					}
					$content .= '<div class="css_quantity">';
					$content .= '<a id="qtyminus" class="qty-trigger" data-id="'.$counter.'"><i class="fa fa-minus"></i></a>';
					$content .= '<input type="hidden" name="quantityUpdated'.$counter.'" id="cart_quantity_input_'.$counter.'" class="cart_quantity_input form-control grey" data-min="1" '.$data_max.' value="'.$quantity.'">';
					$content .= '<input type="hidden" id="product_id_'.$counter.'" name="product_id_'.$counter.'" value="'.$product_id.'" />';
					$content .= '<input type="hidden" id="product_code" name="product_code'.$counter.'" value="'.$product_code.'" />';
					$content .= '<span id="lblcart_quantity_input_'.$counter.'" class="lblcart_quantity_input">'.$quantity.'</span>';
					$content .= '<a id="qtyplus" class="qty-trigger" data-id="'.$counter.'"><i class="fa fa-plus"></i></a>';
					$content .= '</div>';
					
					/* $content .= '<td data-title="Total" class="cart_total" align="right">
					<span id="total_product_price_1_2_0" class="price">'. number_format($line_cost, 0, ',', '.') .'</span>';
					$content .= '</td>'; */
					
					$content .= '<td data-title="Total" class="cart_total" align="right">
					<span id="total_product_price_1_2_0" class="line-price" data-line-price="'.$line_cost.'">'. number_format($line_cost, 0, ',', '.') .'</span>';
					$content .= '</td>';
					
					$content .= '<td class="cart_delete text-center">';
					$content .= '<div>';
					$content .= '<a href="' . $urlfunc->makePretty("?p=cart&action=removeproduct&pid=$productId") . '"><i class="fa fa-trash fa-2"></i></a>';
					$content .= '</div>';
					$content .= '</td>';
					$content .= '</tr>	<!-- /.cart_item -->';
							
					$counter++;
				}
			}
			
			if ($pakaivoucher) {
				#region Voucher
				$is_valid = true;
				if ($_POST['submitcode'] != '' && !isset($_SESSION['voucher'])) {
					$kodevoucher = $_POST['kodekupon'];
					$q = "SELECT id, kodevoucher, tipe, nominaldiskon, tglstart, tglend, nominalbelanja, is_onetime FROM voucher WHERE BINARY kodevoucher='$kodevoucher' AND status=1";
					$qres = $mysql->query($q);
					if ($mysql->num_rows($qres) > 0) {
						$voucher = $mysql->fetch_array($qres);

						if (!$_SESSION['member_uid']) {
							$is_valid = false;
							$error = _COUPONMUSTMEMBER;
						}
						
						if ($voucher['is_onetime'] == 0) {
							if (!cek_valid_tgl($kodevoucher)) {
								$is_valid = false;
								$error = _COUPONDATEAUS;
							} else {
								/* if (!cek_min_belanja($kodevoucher, $total)) {
									$is_valid = false; 
									$error = _MINUSECOUPONONCAR . ' Rp ' . number_format($voucher['nominalbelanja'], 0, ',', '.');
								}  */
								if (!cek_used_voucher($voucher['id'], $_SESSION['member_uid'])) {
									$is_valid = false;
									$error = _COUPONUSED;
								}
							}
						}
						
					} else {
						$is_valid = false;
						$error = _COUPONNOTFOUND;
					}
					
					if ($is_valid) {
						$_SESSION['voucher']['vid'] 		= $voucher['id'];
						$_SESSION['voucher']['code'] 		= $voucher['kodevoucher'];
						$_SESSION['voucher']['tipe'] 		= $voucher['tipe'];
						$_SESSION['voucher']['nominal'] 	= $voucher['nominaldiskon'];
						$_SESSION['voucher']['tglstart'] 	= $voucher['tglstart'];
						$_SESSION['voucher']['tglend'] 		= $voucher['tglend'];
						$_SESSION['voucher']['is_onetime'] 	= $voucher['is_onetime'];
					}
				}
				
				if (isset($_SESSION['voucher']) && $_SESSION['voucher'] != '') {
					$content .= "<tr>";
					$content .= "<td colspan=\"4\" align=\"right\">"._SUBTOTAL."</td>";
					$content .= "<td align=\"right\">".number_format($total, 0, ',', '.')."</td>";
					$content .= "<td>&nbsp;</td>";
					$content .= "</tr>";
					
					// jika ada voucher, calculate total - voucher
					if ($_SESSION['voucher']['tipe'] == 1) {
						$voucher = number_format($_SESSION['voucher']['nominal'], 0, ',', '.');
						$total = $total - $_SESSION['voucher']['nominal'];
					} else if ($_SESSION['voucher']['tipe'] == 2) {
						$disc = 100 - $_SESSION['voucher']['nominal'];
						$temp = (($disc/100) * $total);
						$voucher = $_SESSION['voucher']['nominal'] . '%';
						$total = $temp;
					}				
					$content .= "<tr>";
					$content .= "<td colspan=\"4\" align=\"right\">"._DISCOUNT."</td>";
					$content .= "<td align=\"right\">".$voucher."</td>";
					$content .= "<td>&nbsp;</td>";
					$content .= "</tr>";
				}
				#end of Voucher	
			}			
			
			
			// // Insurance 			$result_insurance = (($cfg_insurance * $total) /100) + $cfg_insurance_plus; 			$is_ins = (isset($_SESSION['use_insurance'])) ? "checked" : ""; 			$content .= '<tr class="cart_total_price">'; 			$content .= '<td class="use_insurance_container text-right" colspan="5">'; 			$content .= '<span>'._INSURANCE.'</span>'; 			$content .= ' 						<div class="form-group"> 						  <div class="checkbox"> 							<label> 							  <input type="checkbox" name="use_insurance" id="use_insurance" value="1" '.$is_ins.'><span class="checkbox-material"><span class="check"></span></span> 							</label> 						  </div> 						</div>'; 			$content .= '</td>'; 			$content .= '<td id="use_insurance_container" class="insurance" align="right">'; 			$content .= '<input type="hidden" name="insurance_value" id="insurance_value" value="'.$result_insurance.'">'; 			$content .= '<span id="insurance_price">0</span>'; 			$content .= '</td>'; 			$content .= '</tr>'; 			 			// Packing 			// print_r($_SESSION); 			$biaya_packing = get_biaya_ongkir($city); 			$is_pack = (isset($_SESSION['use_packing'])) ? "checked" : ""; 			$result_packing = $biaya_packing; 			$content .= '<tr class="cart_total_price">'; 			$content .= '<td class="use_packing_container text-right" colspan="5">'; 			$content .= '<span>'._PACKING.'</span>'; 			$content .= ' 						<div class="form-group"> 						  <div class="checkbox"> 							<label> 							  <input type="checkbox" name="use_packing" id="use_packing" value="1" '.$is_pack.'><span class="checkbox-material"><span class="check"></span></span> 							  <input type="hidden" id="packing_value" name="packing_value" value="'.$result_packing.'"> 							</label> 						  </div> 						</div>'; 			$content .= '</td>'; 			$content .= '<td id="use_packing_container" class="packing" align="right">'; 			$content .= '<input type="hidden" name="result_packing" id="result_packing" value="'.$result_packing.'">'; 			$content .= '<span id="packing_price">0</span>'; 			$content .= '</td>'; 			$content .= '</tr>';
			
			$content .= '<tr class="cart_total_price">';
			$content .= '<td class="total_price_container text-right" colspan="4">';
			$content .= '<span>'._TOTAL.'</span>';
			$content .= '</td>';
			$content .= '<td id="total_price_container" class="price" align="right">';
			$content .= '<input type="hidden" id="amount_price" value="'.$total.'">';
			$content .= '<span id="total_price">'.number_format($total, 0, ',', '.').'</span>';
			$content .= '</td><td>&nbsp;</td>';
			$content .= '</tr>';
			$content .= '</table>	<!-- /#cart_summary -->';
			
			if ($pakaivoucher) {
				$voucher = 0;
				$content .="<form action=\"".$urlfunc->makePretty("?p=cart&action=update")."\" method=\"POST\">";
				$content .= '<tr>';
				$content .= '<td colspan="4" style="padding:0">';
				$content .= '<div class="cart-kupon">';
				
				if (isset($_SESSION['voucher']) && $_SESSION['voucher'] != '') {
					$content .= "<label>"._COUPON.":</label>";
					$content .= "<span class=\"ticket\">".$_SESSION['voucher']['code']."</span></label>";
					$content .= "<a class=\"removevoucher\" href=\"".$urlfunc->makepretty("?p=cart&action=removevoucher")."\"><img alt=\""._COUPONCANCELLED."\" src=\"$cfg_app_url/images/delete.gif\" /></a>";			
				} else {
					$content .= '<label>'._COUPON.':</label>';
					$content .= '<input type="text" name="kodekupon" class="kodekupon" value="">';
					$content .= '<input type="submit" id="submitcode" name="submitcode" class="submitcode belanja more" value="'._USECOUPON.'">';
					
					if (!$is_valid) $content .= '<div class="error">' . $error . '</div>';
				}

				if (!isset($voucher)) $voucher = 0;
				$content .= '</div>';
				$content .= '</td>';
				$content .= '</tr>';
				$content .= '</form>	<!-- /Form coupon -->';
			}
			
			$content .= '</div>	<!-- /#order-detail-content -->';
							
			// Link dibawah keranjang belanja
			$content .= '<p class="cart_navigation clearfix row">';
			$content .= '<span class="col-md-4 col-sm-4 col-xs-12 ">';
			$content .= '<a title="'._CONTINUESHOPPING.'" class="button-exclusive btn btn-default" href="'.$cfg_app_url.'"><i class="fa fa-angle-left fa-3"></i>'._CONTINUESHOPPING.'</a>';
			$content .= '</span>';
			
			$content .= '<span class="col-md-4 col-sm-4 col-xs-12 text-center">';
			$content .= '<a title="'._EMPTYCART.'" class="button-exclusive btn btn-default" href="'.$urlfunc->makePretty("?p=cart&action=empty").'"><i class="fa fa-trash fa-3"></i>'._EMPTYCART.'</a>';
					
			$content .= '</span>';
			$content .= '<span class="col-md-4 col-sm-4 col-xs-12 text-right">';
			$content .= '<a title="'._CHECKOUT.'" class="btn standard-checkout more" href="'.$urlfunc->makePretty("?p=cart&action=checkout").'" style="">
			'._CHECKOUT.'
			</a>';
			$content .= '</span>';
			$content .= '</p>	<!-- /#cart_navigation -->';
			// End link dibawah keranjang belanja
			
        } else {
            //otherwise tell the user they have no items in their cart
            $content .= _NOPRODINCART . " ";
        }
        return $content;
    } else {
        $content .= "<p>" . _NOMODULE . "</p>";
//        $content .= "<a href=\"javascript:history.go(-1)\">"._BACK."</a>";
        return $content;
    }
}

function hitung_berat() {
	global $mysql;
    $totalweight = 0;
    //START HITUNG BERAT BENDA
    foreach ($_SESSION['cart'] as $product_id => $qty) {
		list($product_id, $product_attr_id, $product_color, $product_price, $product_price_plus, $product_size) = explode('|', $product_id);
        if ($product_id != '') {
            $sql = "SELECT id, keterangan, title, harganormal-(if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal),
                    diskon)) as hargadiskon, harganormal, weight, 
                    cat_id, filename, publish, ishot, ketsingkat, diskon, matauang,
                    if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal), diskon)
                    as nilaidiskon
                    FROM catalogdata WHERE id=$product_id;
                    ";
            $result = $mysql->query($sql);
            if ($mysql->num_rows($result) > 0) {
                list($id, $description, $productname, $price, $normalprice, $weight) = $mysql->fetch_row($result);
                $totalweight += ($weight * $qty);
            }
        }
    }
    //END HITUNG BERAT BENDA
    return ceiling($totalweight, 1);
}

function info_beli($cityReceiver) {
    global $pakaiongkir, $mysql;
    $data = array();
    $total = 0;
    if ($pakaiongkir) {
        //START HITUNG BERAT BENDA
        $totalweight = hitung_berat();
        //END HITUNG BERAT BENDA

        //START MENENTUKAN PENGGUNAAN ONGKOS KIRIM
        if ($pakaiongkir) {
            $shipping = 0;
            //get ongkos from postage input = city
            $getongkosquery = "SELECT ongkos, ongkostetap FROM sc_postage WHERE LCASE(kota) = LCASE('$cityReceiver')";
            $totalshipping = 0;
            $result = $mysql->query($getongkosquery);
            if ($mysql->num_rows($result) > 0) {
                $row = $mysql->fetch_array($result);
                $shipping = (($row['ongkos'] * $totalweight) + $row['ongkostetap']);
                $totalshipping = $totalshipping + $shipping;
                $iscityfound = true;
            } else {
                $iscityfound = false;
            }
        }
        //END MENENTUKAN PENGGUNAAN ONGKOS KIRIM
        $data = array("shipping" => $shipping, "iscityfound" => $iscityfound, "totalweight" => $totalweight, "quantity" => $quantity);
    }
    return $data;
}

function calculatePostage($cost, $totalweight, $courier_id) {
    /* courier_id berdasarkan tabel 'sc_courier_fixed' */
    $postage = $cost * $totalweight;
    return $postage;
}

function alertbox($type, $msg) {
    if ($type == 'info')
        $alert_box.="<div class='alert alert-warning'><'>" . $msg . "</div>";
    if ($type == 'warning')
        $alert_box.="<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>&times;</button>
<i class='icon-exclamation-sign'></i><strong>' . _SUCCESS . '!</strong>" . $msg . "</div>";
    if ($type == 'success')
        $alert_box.="<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button>
<i class='icon-exclamation-sign'></i><strong>' . _SUCCESS . '!</strong>" . $msg . "</div>";
    if ($type == 'error')
        $alert_box.="<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button>
<i class='icon-exclamation-sign'></i><strong>' . _SUCCESS . '!</strong>" . $msg . "</div>";
    if ($type == 'validation')
        $alert_box.="<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button>
<i class='icon-exclamation-sign'></i><strong>' . _SUCCESS . '!</strong>" . $msg . "</div>";

    return $alert_box;
}

function get_biaya_ongkir($kota_tujuan, $berat = 1, $tipe = 'REG') {
	global $mysql;
	
	$sql = "SELECT biaya FROM sc_courier_jne WHERE tujuan='$kota_tujuan' AND tipe='$tipe'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) > 0) {
		list($biaya) = $mysql->fetch_row($result);
		return $biaya * $berat;
	}
	return false;
}

function check_voucher_is_valid($kodevoucher) {
	global $mysql;
	
	$sql = "SELECT kodevoucher, is_onetime, status FROM voucher WHERE kodevoucher='$kodevoucher'";
	$is_valid = true;
	if ($result = $mysql->query($sql)) {
		$total_rows = $mysql->num_rows($result);
		
		if ($total_rows > 0) {
			$row = $mysql->fetch_assoc($result);
			extract($row);
			
			if ($is_onetime == 1) {
				if ($status == 0) {
					$is_valid = false;
				}
			}
			// return $voucher;
		}
	}
	return $is_valid;
}
?>
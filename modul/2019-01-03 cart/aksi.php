<?php
error_reporting(1);
//panggil troli widget
//the quantity from the URL post
$do = fiestolaundry($_GET['do'], 8);    //the do from the URL post
$product_id = fiestolaundry($_REQUEST['pid'], 200);    //the do from the URL post

$id = fiestolaundry($_POST['usr_id'], 11);
$fullname = fiestolaundry($_POST['fullname'], 50);
$_POST['fullnameReceiver'] = $_POST['receivercheck'] != 1 ? $_POST['fullname'] : $_POST['fullnameReceiver'];
$fullnameReceiver = fiestolaundry($_POST['fullnameReceiver'], 50);
$email = fiestolaundry($_POST['email'], 100);
$_POST['emailReceiver'] = $_POST['receivercheck'] != 1 ? $_POST['email'] : $_POST['emailReceiver'];
$emailReceiver = fiestolaundry($_POST['emailReceiver'], 100);
$address = fiestolaundry($_POST['address'], 200);
$address2 = fiestolaundry($_POST['address2'], 200);
$_POST['addressReceiver'] = $_POST['receivercheck'] != 1 ? $_POST['address'] : $_POST['addressReceiver'];
$_POST['addressReceiver2'] = $_POST['receivercheck'] != 1 ? $_POST['address2'] : $_POST['addressReceiver2'];
$addressReceiver = fiestolaundry($_POST['addressReceiver'], 200);
$addressReceiver2 = fiestolaundry($_POST['addressReceiver2'], 200);
$state = fiestolaundry($_POST['state'], 50);
$_POST['stateReceiver'] = $_POST['receivercheck'] != 1 ? $_POST['state'] : $_POST['stateReceiver'];
$stateReceiver = fiestolaundry($_POST['stateReceiver'], 50);
$zip = fiestolaundry($_POST['zip'], 5);
$_POST['zipReceiver'] = $_POST['receivercheck'] != 1 ? $_POST['zip'] : $_POST['zipReceiver'];
$zipReceiver = fiestolaundry($_POST['zipReceiver'], 5);
$phone = fiestolaundry($_POST['phone'], 20);
$_POST['phoneReceiver'] = $_POST['receivercheck'] != 1 ? $_POST['phone'] : $_POST['phoneReceiver'];
$phoneReceiver = fiestolaundry($_POST['phoneReceiver'], 20);
$cellphone1 = fiestolaundry($_POST['cellphone1'], 20);
$cellphone2 = fiestolaundry($_POST['cellphone2'], 20);
$_POST['cellphone1Receiver'] = $_POST['receivercheck'] != 1 ? $_POST['cellphone1'] : $_POST['cellphone1Receiver'];
$_POST['cellphone2Receiver'] = $_POST['receivercheck'] != 1 ? $_POST['cellphone2'] : $_POST['cellphone2Receiver'];
$cellphone1Receiver = fiestolaundry($_POST['cellphone1Receiver'], 20);
$cellphone2Receiver = fiestolaundry($_POST['cellphone2Receiver'], 20);

$totalweight = fiestolaundry($_POST['weight'], 20);
$shipping = fiestolaundry($_POST['postagehidden'], 50);
$courier = fiestolaundry($_POST['cityhidden'], 0, false);
$cityfound = fiestolaundry($_POST['cityfound'], 11);
$usecourier = fiestolaundry($_POST['usecourier'], 1);

$sendername = fiestolaundry($_POST['sendername'], 100);
$senderphone = fiestolaundry($_POST['senderphone'], 20);
$senderemail = fiestolaundry($_POST['senderemail'], 255);
$senderconfirmemail = fiestolaundry($_POST['senderconfirmemail'], 255);

$postagecity = fiestolaundry($_POST['postagecity'], 100);
$postageprice = fiestolaundry($_POST['postageprice'], 50);
$postageday = fiestolaundry($_POST['postageday'], 50);
$insurance_value = fiestolaundry($_POST['insurance_value'], 13);
$packing_value = fiestolaundry($_POST['packing_value'], 13);
$kecamatan = fiestolaundry($_POST['kecamatan'], 50);

// $kecamatan = $_POST['receivercheck'] != 1 ? $_POST['kecamatan'] : $_POST['kecamatanReceiver'];

if ($cityfound == 0) {
	$iscityfound = false;
} else {
	$iscityfound = true;
}
$payment = fiestolaundry($_POST['payment'], 11);
$note = fiestolaundry($_POST['note']);
// if ($_POST['city'] == '') {
	// $city = fiestolaundry($_POST['othercityValue'], 50);
// } else {
	// $city = fiestolaundry($_POST['city'], 50);
// }
// $_POST['othercityValue'] = $_POST['receivercheck'] != 1 ? $_POST['city'] : $_POST['othercityValue'];
// $city = fiestolaundry($_POST['othercityValue'], 50);

// if ($_POST['receivercheck'] != 1) {
	// $cityReceiver = $city;
// } else {
	// if ($_POST['cityReceiver'] == '') {
		// $cityReceiver = fiestolaundry($_POST['othercityReceiverValue'], 50);
	// } else {
		// $cityReceiver = fiestolaundry($_POST['cityReceiver'], 50);
	// }
// }

// $cityReceiver = (isset($_SESSION['member_uid'])) ? fiestolaundry($_POST['othercityReceiverValue'], 50) : fiestolaundry($_POST['othercityValue'], 50);

if (isset($_SESSION['member_uid'])) {
	if ($_POST['receivercheck'] != 1) {
		$cityReceiver = $_POST['othercityValue'];
	} else {
		$cityReceiver = $_POST['othercityReceiverValue'];
	}
} else {
	if ($_POST['receivercheck'] != 1) {
		$cityReceiver = $_POST['othercityValue'];
	} else {
		$cityReceiver = $_POST['othercityReceiverValue'];
	}
	
}

$now = date('Y-m-d H:i:s');
$sql = "SELECT judulfrontend FROM module WHERE nama='cart'";
$result = $mysqli->query($sql);
list($title) = $result->fetch_row();

if ($action == '') {
	//print_r($_SESSION['cart']);
    $content .= showcart();
}

// print_r($_SESSION['voucher']);

if($action == 'add') 
{
	// $product_id=fiestolaundry($_POST['pid'],11);
	// if($product_id!="")
	// {
		// if ($quantity < 1) {  $quantity = 1;}	
		// $quantity = fiestolaundry($_POST['qty'], 4); 
		// $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity; //add one to the quantity of the product with id $product_id 
	// }
    // $content .= showcart();
	
	if ($pakaicatalogattribut) {
		$product_id=fiestolaundry($_POST['pid'],11);
		$product_code=fiestolaundry($_POST['product_code'],20);
		$param_color=fiestolaundry($_POST['param-color'],100);
	}
	
	if($product_id != "") {
		$quantity = fiestolaundry($_POST['qty'], 4); 
		if ($quantity < 1) {  $quantity = 1;}
		
		if ($pakaicatalogattribut) {
			if ($product_code != '' || $param_color != '') {
				$product_id .= "|$param_color";
			}
		}
		$_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity; //add one to the quantity of the product with id $product_id 
	}
	
    $content .= showcart();
}

if ($action == 'update') {

    $quantitycounter = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantityUpdated) {
//        $content .= "i: $quantityUpdated<br/>";
        $quantityUpdated = $_POST['quantityUpdated' . $quantitycounter];
		$product_code = $_POST['product_code' . $quantitycounter];
		
		// $stock = get_item_stock($product_id);
		// if ($quantityUpdated > $stock) {
			// $quantityUpdated = $stock;
		// }
        $quantitycounter++;
        if (is_numeric($quantityUpdated)) {
			// IWD CUSTOME SHOP
			// $is_valid_qty = get_stock_from_api($product_code, $quantityUpdated);
			// if (!$is_valid_qty) {
				// $msg = "<strong>$product_code</strong>" . ' ' . _STOCKNOTENOUGH;
			// } else {
				// $_SESSION['cart'][$product_id] = $quantityUpdated; //change the quantity of the product with id $product_id and $quantity
			// }
		    $_SESSION['cart'][$product_id] = $quantityUpdated; //change the quantity of the product with id $product_id and $quantity
            if ($_SESSION['cart'][$product_id] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            } //if the quantity is zero, remove it completely (using the 'unset' function) - otherwise is will show zero, then -1, -2 etc when the user keeps updating items. 
        }
    }
	
    $content .= showcart();
}

if ($action == 'removeproduct') {
	$productId = base64_decode($product_id);
    unset($_SESSION['cart'][$productId]); //remove a product completely (using the 'unset' function) - otherwise is will show zero, then -1, -2 etc when the user keeps removing items. 
    $content .= showcart();
}

if ($action == 'empty') {
    unset($_SESSION['cart']); //unset the whole cart, i.e. empty the cart.
    $content .= showcart();
}

if ($action == 'checkout') {
    if ($harusmember && !isset($_SESSION['member_uid']) && $do != 'checkout') {
        $hasLogin = false;
    } else {
        $user_id = $_SESSION['member_uid'];
        $hasLogin = true;
    }
    if (isset($_SESSION['member_uid'])) {
        $sql = "SELECT user_id, username, user_regdate, user_password, 
            user_email, activity, fullname, level, company, address1, address2, 
            city, state, zip, telephone, cellphone1, cellphone2, fax, 
            activedate, bandate, lastlogin, activationcode FROM webmember WHERE user_id='" . $_SESSION['member_uid'] . "'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $id = $row['user_id'];
        $fullnamedb = $row['fullname'];
        $emaildb = $row['user_email'];
        $addressdb = $row['address1'];
        $addressdb2 = $row['address2'];
        $statedb = $row['state'];
        $citydb = $row['city'];
        $zipdb = $row['zip'];
        $telephonedb = $row['telephone'];
        $cellphone1db = $row['cellphone1'];
        $cellphone2db = $row['cellphone2'];
    }

    if ($hasLogin) {
        if (!empty($_SESSION['cart'])) {
            if (!$harusmember or $do == 'checkout') {
                $content .= "<script>
                            $(document).ready(function() {
                                    init();
                                    // $('#fullname').bind('keyup blur', function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#email').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#address').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#address2').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });							
                                    // $('#state').bind('keyup blur', function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#city').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#othercityValue').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#zip').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });			
                                    // $('#phone').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });			
                                    // $('#cellphone1').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });			
                                    // $('#cellphone2').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });			
									// /////////////////////////////////////////////////////////
									// $('#fullnameReceiver').bind('keyup blur', function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#emailReceiver').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#addressReceiver').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#address2Receiver').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });							
                                    // $('#stateReceiver').bind('keyup blur', function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#cityReceiver').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#othercityValueReceiver').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });
                                    // $('#zipReceiver').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });			
                                    // $('#phoneReceiver').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });			
                                    // $('#cellphone1Receiver').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });			
                                    // $('#cellphone2Receiver').bind('keyup blur',function () {
                                            // localStorage[$(this).attr('id')] = $(this).val();
                                    // });		
									
                                    function init() {
                                            if (localStorage['fullname']) {
                                                    $('#fullname').val(localStorage['fullname']);
                                            }
                                            if (localStorage['email']) {
                                                    $('#email').val(localStorage['email']);
                                            }
                                            if (localStorage['address']) {
                                                    $('#address').val(localStorage['address']);
                                            }
                                            if (localStorage['address2']) {
                                                    $('#address2').val(localStorage['address2']);
                                            }							
                                            if (localStorage['state']) {
                                                    $('#state').val(localStorage['state']);
                                            }

                                            //if (localStorage['city']) {
                                            //	$('#city').val(localStorage['city']);
                                            //}
                                            if (localStorage['othercityValue']) {
                                                    $('#othercityValue').val(localStorage['othercityValue']);
                                            }
                                            if (localStorage['zip']) {
                                                    $('#zip').val(localStorage['zip']);
                                            }
                                            if (localStorage['phone']) {
                                                    $('#phone').val(localStorage['phone']);
                                            }
                                            if (localStorage['cellphone1']) {
                                                    $('#cellphone1').val(localStorage['cellphone1']);
                                            }
                                            if (localStorage['cellphone2']) {
                                                    $('#cellphone2').val(localStorage['cellphone2']);
                                            }
                                    }		

                            })
                    </script>";
            }
            $content .= "<script language=javascript type=\"text/javascript\">
                    function copyDetails() {
                        var receivercheck = document.getElementById('receivercheck');
                         if (!receivercheck.checked)
                         {
                        document.getElementById('fullnameReceiver').value=document.getElementById('fullname').value;
                        document.getElementById('emailReceiver').value=document.getElementById('email').value;
                        document.getElementById('addressReceiver').value=document.getElementById('address').value;
                        document.getElementById('addressReceiver2').value=document.getElementById('address2').value;
                        document.getElementById('stateReceiver').value=document.getElementById('state').value;
                      //  document.getElementById('cityReceiver').selectedIndex=document.getElementById('city').selectedIndex;
                        document.getElementById('othercityReceiverValue').value=document.getElementById('othercityValue').value;
                        document.getElementById('zipReceiver').value=document.getElementById('zip').value;
                        document.getElementById('phoneReceiver').value=document.getElementById('phone').value;
                        document.getElementById('cellphone1Receiver').value=document.getElementById('cellphone1').value;
                        document.getElementById('cellphone2Receiver').value=document.getElementById('cellphone2').value;
                        selectChange();
                        selectChangeReceiver();
											}
                    }
                    function receiverCheck() {
                     var receivercheck = document.getElementById('receivercheck');
                     if (!receivercheck.checked)
                     {

                             $('.hide_div').hide();
							 sessionStorage.removeItem('dropship');
							 
                                    // copyDetails();
                    }else
                            {
/*
                                            document.getElementById('fullnameReceiver').value='';
                                            document.getElementById('emailReceiver').value='';
                                            document.getElementById('addressReceiver').value='';
                                            document.getElementById('addressReceiver2').value='';
                                            document.getElementById('stateReceiver').value='';
    //document.getElementById('cityReceiver').selectedIndex=document.getElementById('city').selectedIndex;
                                            document.getElementById('othercityReceiverValue').value='';
                                            document.getElementById('zipReceiver').value='';
                                            document.getElementById('phoneReceiver').value='';
                                            document.getElementById('cellphone1Receiver').value='';
                                            document.getElementById('cellphone2Receiver').value='';
*/
                                            $('.hide_div').show();
											sessionStorage.setItem('dropship', 1);
                            }
                     var receivercheck = document.getElementById('receivercheck');
                     if (!receivercheck.checked)
                     {

                             $('.hide_div').hide();
                                    // copyDetails();
                    } else {/*
                                            document.getElementById('fullnameReceiver').value='';
                                            document.getElementById('emailReceiver').value='';
                                            document.getElementById('addressReceiver').value='';
                                            document.getElementById('addressReceiver2').value='';
                                            document.getElementById('stateReceiver').value='';
                        //document.getElementById('cityReceiver').selectedIndex=document.getElementById('city').selectedIndex;
                                            document.getElementById('othercityReceiverValue').value='';
                                            document.getElementById('zipReceiver').value='';
                                            document.getElementById('phoneReceiver').value='';
                                            document.getElementById('cellphone1Receiver').value='';
                                            document.getElementById('cellphone2Receiver').value='';
*/
                                            $('.hide_div').show();
                            }
                    }
                    
                    function selectChange() {
                       var combo = document.getElementById('city');
                       var optionValue = combo.options[combo.selectedIndex].text;
                       if (optionValue == '" . _OTHER . "') {
                           document.getElementById('othercity').hidden = false;
                       } else {
                           document.getElementById('othercity').hidden = true;
                       }
                    }
                    
                    function selectChangeReceiver() {
                       var combo = document.getElementById('cityReceiver');
                       var optionValue = combo.options[combo.selectedIndex].text;
                       if (optionValue == '" . _OTHER . "') {
                           document.getElementById('othercityReceiver').hidden = false;
                       } else {
                           document.getElementById('othercityReceiver').hidden = true;
                       }
                    }
                    </script>";

            //pembuatan form untuk proses penerimaan

            $content .= "<form id=\"checkout-form\" class=\"form-horizontal checkout-form\" action=\"" . $urlfunc->makePretty("?p=cart&action=review_order") . "\" method=\"POST\">
			<input type=\"hidden\" name=\"usr_id\" id=\"usr_id\" value=\"$id\" />
									<input class=\"form-control\" type=\"hidden\" name=\"email\" value=\"$emaildb\" />
                            <div class=\"row\"><div class=\"col-sm-6\">
							
							<div id=\"data-pemesan\">
							<h4>" . _RECEIVERDETAILS . "</h4>
                            <fieldset>
								
                                    <input type=\"hidden\" name=\"usr_id\" id=\"usr_id\" value=\"$id\" />
									<input class=\"form-control\" type=\"hidden\" name=\"email\" value=\"$emaildb\" />
                                    <div class=\"form-group\">
                                            <span class=\"col-sm-4 col-md-4 text-right\">" . _FULLNAME . "*:</span>
                                            <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"fullname\" id=\"fullname\" value=\"$fullnamedb\" required/></div>
                                    </div>";
			if (!isset($_SESSION['member_uid'])) {
				$content .= "									
                                    <div class=\"form-group\">
                                            <span class=\"col-sm-4 col-md-4 text-right\">" . _EMAIL . "*:</span>
                                            <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"email\" id=\"email\" value=\"$emaildb\" pattern=\"[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}\" required/></div>
                                    </div>";
			}
			$content .= "						
                                    <div class=\"form-group\">
                                            <span class=\"col-sm-4 col-md-4 text-right\">" . _ADDRESS . "*:</span>
                                            <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"address\" id=\"address\" value=\"$addressdb\"  required/></div>
                                    </div>
									<div class=\"form-group\">
                                            <div class=\"col-sm-8 col-md-8 col-md-offset-4 col-sm-offset-4\"><input class=\"form-control\" type=\"text\" name=\"address2\" id=\"address2\" value=\"$addressdb2\"  /></div>
                                    </div>
                                    <!--<div class=\"form-group\">
                                            <span class=\"col-sm-4 col-md-4 text-right\">" . _KECAMATAN . "*:</span>
                                            <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"kecamatan\" id=\"kecamatan\" value=\"$kecamatan\" required/></div>
                                    </div>-->
                                    <div class=\"form-group\">
                                            <span class=\"col-sm-4 col-md-4 text-right\">" . _STATE . ":</span>
                                            <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"state\" id=\"state\" value=\"$statedb\"   /></div>
                                    </div>";
            $content .= "<div class=\"form-group\">
                            <span class=\"col-sm-4 col-md-4 text-right\">" . _CITY . "*:</span>
                            <div class=\"col-sm-8 col-md-8\">";
           
			$content .="<input class=\"form-control elem_attr_req\" type=\"text\" name=\"othercityValue\" id=\"othercityValue\" value=\"$citydb\" required/>";
            $content .= "</div></div>";
            $content .= "<div class=\"form-group\">
                                <span class=\"col-sm-4 col-md-4 text-right\">" . _ZIP . "*:</span>
                                <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\"   name=\"zip\" id=\"zip\" value=\"$zipdb\" required/></div>
                        </div>
                        <!--<div class=\"form-group\">
                                <span class=\"col-sm-4 col-md-4 text-right\">" . _TELEPHONE . "*:</span>
                                <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\"   name=\"phone\" id=\"phone\" value=\"$telephonedb\" required/></div>
                        </div>-->
                        <div class=\"form-group\">
                                <span class=\"col-sm-4 col-md-4 text-right\">" . _CELLPHONE1 . "*:</span>
                                <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\"   name=\"cellphone1\" id=\"cellphone1\" value=\"$cellphone1db\" required/></div>
                        </div>
                        <div class=\"form-group\">
                                <span class=\"col-sm-4 col-md-4 text-right\">" . _CELLPHONE2 . ":</span>
                                <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\"   name=\"cellphone2\" id=\"cellphone2\" value=\"$cellphone2db\"  /></div>
                        </div>
						</fieldset>";
			$content .= "</div>	<!-- /#data-pemesan -->";
			
			 //pembuatan form untuk proses penerimaan
			$content .= "<div id=\"data-penerima\"  class=\"mfp-hide\" style=\"display:none\">";
            $content .= "<h4>" . _RECEIVERDETAILS . " </h4>";
            $content .= "<fieldset>
                            <div class=\"form-group\">
                                    <span class=\"col-sm-4 col-md-4 text-right\">" . _FULLNAME . "*:</span>
                                    <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"fullnameReceiver\" id=\"fullnameReceiver\" required/></div>
                            </div>
                            <!--<div class=\"form-group\">
                                    <span class=\"col-sm-4 col-md-4 text-right\">" . _EMAIL . ":</span>
                                    <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"emailReceiver\" id=\"emailReceiver\" /></div>
                            </div>-->
                            <div class=\"form-group\">
                                    <span class=\"col-sm-4 col-md-4 text-right\">" . _ADDRESS . "*:</span>
                                    <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"addressReceiver\" id=\"addressReceiver\" required/></div>
                            </div>
                            <div class=\"form-group\">
                                    <div class=\"col-sm-8 col-md-8 col-md-offset-4 col-sm-offset-4\"><input class=\"form-control\" type=\"text\" name=\"addressReceiver2\" id=\"addressReceiver2\" /></div>
                            </div>
							<!--<div class=\"form-group\">
									<span class=\"col-sm-4 col-md-4 text-right\">" . _KECAMATAN . "*:</span>
									<div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"kecamatanReceiver\" id=\"kecamatanReceiver\" required/></div>
							</div>-->
                            <div class=\"form-group\">
                                    <span class=\"col-sm-4 col-md-4 text-right\">" . _STATE . ":</span>
                                    <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"stateReceiver\" id=\"stateReceiver\" /></div>
                            </div>";
            $content .= "<div class=\"form-group\">
                            <span class=\"col-sm-4 col-md-4 text-right\">" . _CITY . "*:</span>
                            <div class=\"col-sm-8 col-md-8\">";
            $content .="<div  id=\"othercityReceiver\" $hidden><input class=\"form-control elem_attr_req\" type=\"text\" name=\"othercityReceiverValue\" id=\"othercityReceiverValue\" value=\"$othervalue\" required/></div>";
            
            $content .="</div></div>";
            $content .= "<div class=\"form-group\">
                                <span class=\"col-sm-4 col-md-4 text-right\">" . _ZIP . "*:</span>
                                <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"zipReceiver\" id=\"zipReceiver\" required/></div>
                        </div>
                        <!--<div class=\"form-group\">
                                <span class=\"col-sm-4 col-md-4 text-right\">" . _TELEPHONE . "*:</span>
                                <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"phoneReceiver\" id=\"phoneReceiver\" required/></div>
                        </div>-->
                        <div class=\"form-group\">
                                <span class=\"col-sm-4 col-md-4 text-right\">" . _CELLPHONE1 . "*:</span>
                                <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"cellphone1Receiver\" id=\"cellphone1Receiver\" required/></div>
                        </div>
                        <div class=\"form-group\">
                                <span class=\"col-sm-4 col-md-4 text-right\">" . _CELLPHONE2 . ":</span>
                                <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"cellphone2Receiver\" id=\"cellphone2Receiver\" /></div>
                        </div>
                </fieldset>";
			$content .= "</div>	<!-- /#data-penerima -->";
			
			$content .= "<div class=\"senderisreceiver\">
								<label for=\"receivercheck\"><input type=\"checkbox\" class=\"receivercheck\" id=\"receivercheck\" name=\"receivercheck\" onClick=\"receiverCheck()\" value=\"1\">" . _DROPSHIPPER . "</label>
							</div>";
            /* $content .="<div class=\"senderisreceiver\">" . _DROPSHIPPER;
            $content .="<input type=\"checkbox\" class=\"receivercheck\" id=\"receivercheck\" name=\"receivercheck\" onClick=\"receiverCheck()\" value=\"1\">"; */
			
            // $content .="
			// <div class=\"form-group\">
			  // <div class=\"checkbox\">
				// <label>
				  // <input type=\"checkbox\" class=\"receivercheck\" id=\"receivercheck\" name=\"receivercheck\" onClick=\"receiverCheck()\" value=\"1\"><span class=\"checkbox-material\"><span class=\"check\"></span></span>
				// </label>
			  // </div>
			// </div>
			// ";
            // $content .="</div>";
            $content .="</div>";
            $content.= "<div class=\"col-sm-6\"><div class='hide_div'>";
			
            // //pembuatan form untuk proses penerimaan
			// $content .= "<div id=\"data-penerima\">";
            // $content .= "<h4>" . _RECEIVERDETAILS . " </h4>";
            // $content .= "<fieldset>
                            // <div class=\"form-group\">
                                    // <span class=\"col-sm-4 col-md-3 text-right\">" . _FULLNAME . "*:</span>
                                    // <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"fullnameReceiver\" id=\"fullnameReceiver\" required/></div>
                            // </div>
                            // <div class=\"form-group\">
                                    // <span class=\"col-sm-4 col-md-3 text-right\">" . _EMAIL . ":</span>
                                    // <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"emailReceiver\" id=\"emailReceiver\" /></div>
                            // </div>
                            // <div class=\"form-group\">
                                    // <span class=\"col-sm-4 col-md-3 text-right\">" . _ADDRESS . "*:</span>
                                    // <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"addressReceiver\" id=\"addressReceiver\" required/></div>
                            // </div>
                            // <div class=\"form-group\">
                                    // <div class=\"col-sm-8 col-md-8 col-md-offset-3 col-sm-offset-4\"><input class=\"form-control\" type=\"text\" name=\"addressReceiver2\" id=\"addressReceiver2\" /></div>
                            // </div>
                            // <div class=\"form-group\">
                                    // <span class=\"col-sm-4 col-md-3 text-right\">" . _STATE . ":</span>
                                    // <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"stateReceiver\" id=\"stateReceiver\" /></div>
                            // </div>";
            // $content .= "<div class=\"form-group\">
                            // <span class=\"col-sm-4 col-md-3 text-right\">" . _CITY . "*:</span>
                            // <div class=\"col-sm-8 col-md-8\">";
            // $content .="<div  id=\"othercityReceiver\" $hidden><input class=\"form-control elem_attr_req\" type=\"text\" name=\"othercityReceiverValue\" id=\"othercityReceiverValue\" value=\"$othervalue\" required/></div>";
            
            // $content .="</div></div>";
            // $content .= "<div class=\"form-group\">
                                // <span class=\"col-sm-4 col-md-3 text-right\">" . _ZIP . ":</span>
                                // <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"zipReceiver\" id=\"zipReceiver\" /></div>
                        // </div>
                        // <div class=\"form-group\">
                                // <span class=\"col-sm-4 col-md-3 text-right\">" . _TELEPHONE . "*:</span>
                                // <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"phoneReceiver\" id=\"phoneReceiver\" required/></div>
                        // </div>
                        // <div class=\"form-group\">
                                // <span class=\"col-sm-4 col-md-3 text-right\">" . _CELLPHONE1 . ":</span>
                                // <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"cellphone1Receiver\" id=\"cellphone1Receiver\" /></div>
                        // </div>
                        // <div class=\"form-group\">
                                // <span class=\"col-sm-4 col-md-3 text-right\">" . _CELLPHONE2 . ":</span>
                                // <div class=\"col-sm-8 col-md-8\"><input class=\"form-control\" type=\"text\" name=\"cellphone2Receiver\" id=\"cellphone2Receiver\" /></div>
                        // </div>
                // </fieldset>";
			// $content .= "</div>	<!-- /#data-penerima -->";
			$content .= "<div id=\"data-pengirim\">";
			$content .= "<h4>" . _DATASENDER . " </h4>";
			$content .= "<fieldset>
                            <div class=\"form-group\">
                                    <span class=\"col-sm-4 col-md-3 text-right\">" . _FULLNAMEDROPSHIP . "*:</span>
                                    <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"sendername\" id=\"sendername\" required/></div>
                            </div>
                            <div class=\"form-group\">
                                    <span class=\"col-sm-4 col-md-3 text-right\">" . _TELEPHONE . "*:</span>
                                    <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"senderphone\" id=\"senderphone\" required/></div>
                            </div>";
			if (!$_SESSION['member_uid']) {
				$content .= "<div class=\"form-group\">
                                    <span class=\"col-sm-4 col-md-3 text-right\">" . _EMAIL . "*:</span>
                                    <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"senderemail\" id=\"senderemail\" pattern=\"[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}\" required/></div>
                            </div>
                            <div class=\"form-group\">
                                    <span class=\"col-sm-4 col-md-3 text-right\">" . _CONFIRMEMAIL . "*:</span>
                                    <div class=\"col-sm-8 col-md-8\"><input class=\"form-control elem_attr_req\" type=\"text\" name=\"senderconfirmemail\" id=\"senderconfirmemail\" pattern=\"[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}\" required/></div>
                            </div>";
			}
			$content .= "</fieldset>";
			$content .= "</div>	<!-- /#data-pengirim -->";
            $content .= "</div></div></div>";
			
            $content .= "
                        <script> 
                        $('.hide_div').hide();
                //	copyDetails();
                        </script>";


			#region payment info
			
			//generate payment method from payment
			$getpayment = "SELECT id,bank,cabang,atasnama,norekening FROM sc_payment";
			$result = $mysqli->query($getpayment);
			
			$content .= "<div class=\"row cart-payment-info\">";
			if($result && $result->num_rows > 0) {
				$content .= "<div class=\"col-sm-12 col-md-12\">";
				$content .= "<h4>" . _PAYMENTINFO . "</h4>\r\n";
			
				$content .= "<select name=\"payment\" required>\r\n";
				$content .= "<option value=''>"._PILIHCARAPEMBAYARAN."</option>";
				while ($row = $result->fetch_assoc()) {
					$content .= "<option value=\"" . $row['bank'] . "\">" . $row['bank'] ."</option>\r\n";
				}
				
				$content .= "</select>";
				$content .= "</div>";
			}
			$content .= "</div>	<!-- /.payment-info -->";
			#endregion payment info
			
			if ($pakaiongkir) {
				//ongkos kirim
				//@TODO
				$usecourier=false;
				$sql = "SELECT value FROM config WHERE name = 'couriers'";
				$result = $mysqli->query($sql);
				list($idcouriers) = $result->fetch_row();
				$idcouriers = explode(",", $idcouriers);
				foreach($idcouriers as $idc) {
					if($idc===0) {
						$sql_r[]= "(SELECT id FROM sc_postage limit 1)";
					} else {
						$sql_r[]= "(SELECT id FROM sc_courier_jne where courier_id=$idc limit 1)";
					}
				}
				
				if(count($sql_r)>0) {
					$sql=join(" UNION ALL ", $sql_r);
					
					$result = $mysqli->query($sql);
					if($result && $result->num_rows > 0) {
						$usecourier=true;
					} else {
						$usecourier=false;
					}
				}
				
				if($usecourier) {
					
					$totalweight = hitung_berat();
					
					#region postage
					$content .= "<div class=\"row cart-postage\">";
					$content .= "<div class=\"col-sm-12 col-md-12\">";
					$content .= "<h4>" . _POSTAGE . "</h4>\r\n";
					$content .= "<div class=\"block-postage\">";
					$content .= "<strong>"._CURRENTWEIGHT . ":</strong> $totalweight "._KG."\r\n<br/>";
					$content .= "<input type=\"hidden\" id=\"totalweight\" value=\"$totalweight\">";
					$content .= "<input type=\"text\" class=\"cari-ongkir-kota-kab\" name=\"cariongkirkotakab\" id=\"cari-ongkir-kota-kab\" placeholder=\""._SEARCHCITYDISTRICT."\" required>";
					$content .= "<img id=\"loader\" class=\"mfp-hidex\" src=\"$cfg_app_url/images/ajax-loader.gif\" alt=\"loader\" style=\"display:none\">
					<div id=\"form-ongkir\" class=\"hide_divx\"></div>	<!-- /#form-ongkir -->";
					$content .= "</div>	<!-- /.block-postage -->";
					
					$content .= "<input type=\"hidden\" name=\"weight\" id=\"weight\" value=\"$totalweight\" />";
					$content .= "<input type=\"hidden\" name=\"postagehidden\" id=\"postagehidden\" />";
					$content .= "<input type=\"hidden\" name=\"cityhidden\" id=\"cityhidden\" />";
					$content .= "<input type=\"hidden\" name=\"postagecity\" id=\"postagecity\" />";
					$content .= "<input type=\"hidden\" name=\"postageday\" id=\"postageday\" />";
					$content .= "<input type=\"hidden\" name=\"json_courier\" id=\"json_courier\" />";
					
					$content .= "
					<script type=\"text/javascript\">
						function formatNumber(number) {
							return Math.max(0, number).toFixed(0).replace(/(?=(?:\d{3})+$)(?!^)/g, ',');
						}
					</script>
					<script type=\"text/javascript\">
						$('input[name=\"choosecity\"]').keypress(function(e) {
							if (e.which == 13) {
								$('.button-link').trigger('click');
								return false;
							}
						})
						function checkPostage() {
							var city = $('#choosecity').val();
							var weight = $('#weight').val();
							
							$.ajax({
								type: 'POST',
								url: '$cfg_app_url/modul/cart/ajax.php?action=cariongkir&city=' + city + '&weight=' + weight,
								success: function(msg){
									$('#postagemodal').html(msg);
									
									$.magnificPopup.open({
										preloader: true,
										items: {
											src: $($('#postagemodal')),
											type:'inline'
										},
										modal: true
									});								
								}
							  });
							  
						}
						
						function closeMPopup() {
							$.magnificPopup.close();
						}
						function getSelectedPostage(selectedCity, selectedPostage, postageDay, tempCity) 
						{
							$('#postagecity').html(selectedCity);
							$('#postageprice').html(formatNumber(selectedPostage));
							$('#postageday').html(postageDay);
							$('#tempCity').val(tempCity);
							
							$('input[name=\"postagecity\"]').val(selectedCity);
							$('input[name=\"postageprice\"]').val(selectedPostage);
							$('input[name=\"postageday\"]').val(postageDay);
							
							//document.getElementById('postagecity').innerHtml=selectedCity;
							//document.getElementById('postageprice').innerHtml=formatNumber(selectedPostage);
							//document.getElementById('postageday').innerHtml=postageDay;
							document.getElementById('cityhidden').value=selectedCity;
							document.getElementById('postagehidden').value=selectedPostage;
							
							$('#result_choosecity').val(1);
							
							var urldata = '$cfg_app_url/modul/cart/ajax.php';
							if ($('#tempCity').val() != '') {
								$('#use_packing').attr('disabled', false);
								$.get(urldata, {action : 'get_biaya_ongkir', city : tempCity}, function(msg) {
									$('#packing_value').val(msg);
								});
							}
							
							closeMPopup();
						}
						function nocourier()
						{
						document.getElementById('usecourier').value=0;
						}
						// function closeSexyLightBox() {
							// SexyLightbox.close();
						// }
					</script>
					";
					
					$content .= "</div>";
					$content .= "</div>";
					#endregion postage
					
					// $content .= "<div class=\"row\"><div class=\"col-sm-6 col-md-4\"><h4>" . _POSTAGE . "</h4>\r\n";
					// $content .= "<strong>"._CURRENTWEIGHT . ":</strong> $totalweight "._KG."\r\n<br/>";
					// $content .= <<<HTML
					// <img id="loader" class="mfp-hidex" src="$cfg_app_url/images/ajax-loader.gif" alt="loader" style="display:none">
					// <div id="form-ongkirx" class="hide_divx">
					// </div>	<!-- /#form-ongkir -->
		// HTML;
					// $content .= "
						// <div id=\"#postage\">
						// <table border=\"0\">
						// <tr>
						// <td>" . _SEARCH . " " . _CITY . "</td>
						// <td> : </td>
						// <td>
							// <input type=\"hidden\" name=\"result_choosecity\" id=\"result_choosecity\" value=\"0\"/>
							// <input type=\"text\" name=\"choosecity\" id=\"choosecity\" required/>
							// <a class=\"button-link\" rel=\"sexylightbox\" href=\"#\" onclick=\"checkPostage()\">" . _SEARCH . "</a>
						// </td>
						// </tr>
						// <tr>
						// <td>" . _COURIER . "</td>
						// <td> : </td>
						// <td><span id=\"postagecity\"></span><input type=\"hidden\" name=\"postagecity\"></td>
						// </tr>
						// <tr>
						// <td>" . _POSTAGE . "</td>
						// <td> : </td>
						// <td><span id=\"postageprice\"></span><input type=\"hidden\" name=\"postageprice\"></td>
						// </tr>
						// <tr>
						// <td>" . _SHIPPINGTIME . "</td>
						// <td> : </td>
						// <td><span id=\"postageday\"></span><input type=\"hidden\" name=\"postageday\"></td>
						// </tr>";
					
					// $content .= "</table>
						// </div>
						// \r\n<br/>";
					/* $content .= "<input type=\"hidden\" name=\"weight\" id=\"weight\" value=\"$totalweight\" />";
					$content .= "<input type=\"hidden\" name=\"postagehidden\" id=\"postagehidden\" />";
					$content .= "<input type=\"hidden\" name=\"cityhidden\" id=\"cityhidden\" />";
					$content .= "<input type=\"hidden\" name=\"postagecity\" id=\"postagecity\" />";
					$content .= "<input type=\"hidden\" name=\"postageday\" id=\"postageday\" />";
					$content .= "<input type=\"hidden\" name=\"json_courier\" id=\"json_courier\" />"; */

					foreach ($_SESSION['cart'] as $product_id => $quantity) {
						
						list($product_id, $product_attr_id, $product_color, $product_price, $product_price_plus, $product_size) = explode('|', $product_id);
						
						//get the name, description and price from the database - this will depend on your database implementation.
						//use sprintf to make sure that $product_id is inserted into the query as a number - to prevent SQL injection
						$sql = "SELECT title, matauang, harganormal-(if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal), diskon)) as hargadiskon,filename, idmerek FROM catalogdata WHERE id=$product_id";
						$result = $mysqli->query($sql);
						
						//Only display the row if there is a product (though there should always be as we have already checked)
						if ($result && $result->num_rows > 0) {
							list($title2, $matauang, $hargadiskon, $filename, $brand_id) = $result->fetch_row();
							$brand_name = get_brand_name($brand_id);
							if ($product_price_plus > 0) $hargadiskon = $hargadiskon + $product_price_plus;
							if ($matauang == 'USD') {
								$hargadiskon = $hargadiskon * $kursusdidr;
							}
							$line_cost = $hargadiskon * $quantity;  //work out the line cost
							$total = $total + $line_cost;   //add to the total cost
						}
					}
					
					if (isset($_SESSION['voucher']) && $_SESSION['voucher'] != '') {
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
					}
					
					if ($pakaiadditional) {
						// $content .= '</div>';
						$content .= "<div class=\"col-sm-6 col-md-4\"><h4>" . _ADDITIONAL . "</h4>\r\n";
						$content .= '<table class="addditional-cart">';
						// Insurance
						$result_insurance = (($cfg_insurance * $total) /100) + $cfg_insurance_plus;
						$is_ins = (isset($_SESSION['use_insurance'])) ? "checked" : "";
						$content .= '<tr class="cart_total_price">';
						$content .= '<td class="use_insurance_container text-right">';
						$content .= '<span>'._INSURANCE.'</span>';
						$content .= '<label>
									  <input type="checkbox" name="use_insurance" id="use_insurance" value="1" '.$is_ins.'>
									</label>';
						// Material Design
						// $content .= '
									// <div class="form-group">
									  // <div class="checkbox">
										// <label>
										  // <input type="checkbox" name="use_insurance" id="use_insurance" value="1" '.$is_ins.'><span class="checkbox-material"><span class="check"></span></span>
										// </label>
									  // </div>
									// </div>';
						$content .= '</td>';
						$content .= '<td> : </td>';
						$content .= '<td id="use_insurance_container" class="insurance">';
						$content .= '<input type="hidden" name="insurance_value" id="insurance_value" value="'.$result_insurance.'">';
						$content .= '<span id="insurance_price">0</span>';
						$content .= '</td>';
						$content .= '</tr>';
						
						// Packing
						$biaya_packing = get_biaya_ongkir($city);
						$is_pack = (isset($_SESSION['use_packing'])) ? "checked" : "";
						$result_packing = $biaya_packing;
						$content .= '<tr class="cart_total_price">';
						$content .= '<td class="use_packing_container text-right">';
						$content .= '<span>'._PACKING.'</span>';
						$content .= '<label>
									  <input type="checkbox" name="use_packing" id="use_packing" value="1" '.$is_pack.' disabled="">
									  <input type="hidden" id="packing_value" name="packing_value" value="'.$result_packing.'">
									  <input type="hidden" id="tempCity" name="tempCity">
									</label>';
						// Material Design
						// $content .= '
									// <div class="form-group">
									  // <div class="checkbox">
										// <label>
										  // <input type="checkbox" name="use_packing" id="use_packing" value="1" '.$is_pack.' disabled=""><span class="checkbox-material"><span class="check"></span></span>
										  // <input type="hidden" id="packing_value" name="packing_value" value="'.$result_packing.'">
										  // <input type="hidden" id="tempCity" name="tempCity">
										// </label>
									  // </div>
									// </div>';
						$content .= '</td>';
						$content .= '<td> : </td>';
						$content .= '<td id="use_packing_container" class="packing">';
						$content .= '<input type="hidden" name="result_packing" id="result_packing" value="'.$result_packing.'">';
						$content .= '<span id="packing_price">0</span>';
						$content .= '</td>';
						$content .= '</tr>';
						$content .= "</table>\r\n";
					}
					
					//ongkos kirim
					//MODAL
					// $content .= "<div id=\"postagemodal\" class=\"mfp-hide white-popup-block\"></div>";
					//MODAL
					$content .="<input type='hidden' name='usecourier' id='usecourier' value='1' />";
				} else {
					$content .="<input type='hidden' name='usecourier' id='usecourier' value='0' />";
				}
			}
            
            // $content .= "<span class=\"paymentinfo\">$paymentnote</span>\r\n";
            // $content .= "</div><div class=\"col-sm-6 col-md-4\"><h4>" . _PAYMENTINFO . "</h4>\r\n";
            // //generate payment method from payment
            // $getpayment = "SELECT id,bank,cabang,atasnama,norekening FROM sc_payment";
            // $result = mysql_query($getpayment);
            // $content .= "<select name=\"payment\" required>\r\n";
			// if($result and (mysql_num_rows($result)>1  or mysql_num_rows($result)==0))
			// {
			// $content .= "<option value=''>"._PILIHCARAPEMBAYARAN."</option>";
			// }
            // while ($row = mysql_fetch_assoc($result)) 
			// {
                // $content .= "<option value=\"" . $row['bank'] . "\">" . $row['bank'] ."</option>\r\n";
            // }
            // $content .= "</select>";
            // $content .= "</div></div>";

			$content .= "<div class=\"row cart-note\">";
			$content .= "<div class=\"col-sm-12 col-md-12\">";
            $content .= "<h4>" . _NOTE . "</h4>\r\n";
            $content .= "<textarea cols=\"50\" rows=\"5\" name=\"note\" class=\"form-control\" id=\"note\" /></textarea><br />";
            $content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i>" . _BACK . "</a><input class=\"btn btn-default more\" type=\"submit\" name=\"checkout\" value=\"" . _CARTCHECKOUT . "\" /></p>";
			$content .= "</div>";
			$content .= "</div>";
            $content .= "</form>";
            // $content .= "<a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i>" . _BACK . "</a>";
            $content .="<script>receiverCheck();</script>";
        } else {
            //otherwise tell the user they have no items in their cart
            $content .= "<p>" . _NOPRODINCART . "</p> ";
            $content .="<a href=\"" . $urlfunc->makePretty("?p=catalog") . "\" class=\"more\">" . _CONTINUESHOPPING . "</a> ";
        }
    } else {
        //$content .= _LOGINFIRST." ";
        //$content.="Apakah anda sudah terdaftar ya / tidak ?";
        //header("location:?p=webmember&action=register");
		$_SESSION['URL_BEFORE_LOGIN'] = $urlfunc->makePretty("?p=cart");
        // header('location:' . $urlfunc->makePretty("?p=webmember&action=checkout&pid=gadget"));
        header('location:' . $urlfunc->makePretty("?p=webmember&action=checkout"));
    }
}


if ($action == "order") {
	
    if (!empty($_SESSION['cart'])) {
        $isvalid = true;
			
		if ($pakaiongkir) {
			if ($shipping == '' /* and $usecourier */) {
				$errormessages .= "<li>" . _INVALIDPOSTAGE . "</li>\r\n";
				$isvalid = false;
			}
		}
		
		if (isset($_SESSION['member_uid'])) {
			if ($fullname == '') {
				$errormessages .= "<li>" . _INVALIDFULLNAME . "</li>\r\n";
				$isvalid = false;
			}
			// if ($email == '' || !validatemail($email)) {
				// $errormessages .= "<li>" . _INVALIDEMAIL . "</li>\r\n";
				// $isvalid = false;
			// }
			if ($address == '') {
				$errormessages .= "<li>" . _INVALIDADDRESS . "</li>\r\n";
				$isvalid = false;
			}
			// if ($city == '') {
				// $errormessages .= "<li>" . _INVALIDCITY . "</li>\r\n";
				// $isvalid = false;
			// }
			// if ($kecamatan == '') {
				// $errormessages .= "<li>" . _INVALIDKECAMATAN . "</li>\r\n";
				// $isvalid = false;
			// }
			// if ($phone == '' || strlen($phone) < 3) {
				// $errormessages .= "<li>" . _INVALIDPHONE . "</li>\r\n";
				// $isvalid = false;
			// }     
			}
		
		if ($_POST['receivercheck'] == 1) 
		{
			if ($fullnameReceiver == '') {
				$errormessages .= "<li>" . _INVALIDFULLNAMERECEIVER . "</li>\r\n";
				$isvalid = false;
			}
			if ($emailReceiver != '' && !validatemail($emailReceiver)) {
				$errormessages .= "<li>" . _INVALIDEMAILRECEIVER . "</li>\r\n";
				$isvalid = false;
			}
			if ($addressReceiver == '') {
				$errormessages .= "<li>" . _INVALIDADDRESSRECEIVER . "</li>\r\n";
				$isvalid = false;
			}
			if ($cityReceiver == '') {
				$errormessages .= "<li>" . _INVALIDCITYRECEIVER . "</li>\r\n";
				$isvalid = false;
			}
			// if ($phoneReceiver == '' || strlen($phoneReceiver) < 3) {
				// $errormessages .= "<li>" . _INVALIDPHONERECEIVE . "</li>\r\n";
				// $isvalid = false;
			// }
			if ($sendername == '') {
				$errormessages .= "<li>" . _INVALIDSENDERNAME . "</li>\r\n";
				$isvalid = false;
			}
			if ($senderphone == '') {
				$errormessages .= "<li>" . _INVALIDSENDERPHONE . "</li>\r\n";
				$isvalid = false;
			}
			// if ($senderconfirmemail != $senderemail) {
				// $errormessages .= "<li>" . _INVALIDSENDERCONFIRMEMAIL . "</li>\r\n";
				// $isvalid = false;
			// }
		}
		if ($payment == '') {
            $errormessages .= "<li>" ._INVALIDPAYMENT . "</li>\r\n";
            $isvalid = false;
        }

        $customerIncrement = 0;
        $receiverIncrement = 0;
        $transactionIncrement = 0;
        if ($isvalid) {

            $qShowStatusCustomer = "SHOW TABLE STATUS LIKE 'sc_customer'";
            $qShowStatusResult = $mysqli->query($qShowStatusCustomer);
            $row = $qShowStatusResult->fetch_assoc();
            $customerIncrement = $row['Auto_increment'];

            $qShowStatusReceiver = "SHOW TABLE STATUS LIKE 'sc_receiver'";
            $qShowStatusResult = $mysqli->query($qShowStatusReceiver);
            $row = $qShowStatusResult->fetch_assoc();
            $receiverIncrement = $row['Auto_increment'];

            // $qShowStatusTranscation = "SHOW TABLE STATUS LIKE 'sc_transaction'";
            // $qShowStatusResult = $mysqli->query($qShowStatusTranscation);
            // $row = $qShowStatusResult->fetch_assoc();
            // $transactionIncrement = $row['Auto_increment'];
// //            $transactionIncrement = $transId;
            // $Guid = Guid($transactionIncrement);
            // $transId = $Guid;
			
			
			$y = date('y');
			$m = date('m');
			$format_ym = "$y$m";
			$qShowStatusTranscation = "SELECT MAX(substr(transaction_id,7,4))+0 AS jml FROM sc_transaction WHERE substr(transaction_id,3,4) = '$format_ym' ";
			$qShowStatusResult = $mysqli->query($qShowStatusTranscation);
			$row_result = $qShowStatusResult->fetch_assoc();
			// $total_transaction = mysql_num_rows($qShowStatusResult)+1;
			$total_transaction = $row_result['jml']+1;
			$transId = NewGuid($total_transaction);
			
            $qShowStatusTranscationDetail = "SHOW TABLE STATUS LIKE 'sc_transaction_detail'";
            $qShowStatusResult = $mysqli->query($qShowStatusTranscationDetail);
            $row = $qShowStatusResult->fetch_assoc();
            $transactionDetailIncrement = $row['Auto_increment'];

			$voucher_id = $_SESSION['voucher']['vid'];
			$voucher_code = $_SESSION['voucher']['code'];
			$voucher_tipe = $_SESSION['voucher']['tipe'];
			$voucher_nominal = $_SESSION['voucher']['nominal'];
			$voucher_tglstart = $_SESSION['voucher']['tglstart'];
			$voucher_tglend = $_SESSION['voucher']['tglend'];
			$voucher_onetime = $_SESSION['voucher']['is_onetime'];
			
			$is_voucher_exists = check_voucher_is_valid($_SESSION['voucher']['code']);
			if (!$is_voucher_exists) {
				$_SESSION['view_order']['msg'] = _VOUCHERNOTVALID;
				$url = $urlfunc->makePretty("?p=cart&action=review_order&do=notvalid");
				header("Location: $url");
				exit();
			}
			
			$use_insurance = $_SESSION['insurance_value'];
			$use_packing = $_SESSION['packing_value'];
			$web_user_id = $_SESSION['member_uid'];

			// $courier = htmlentities($_POST['json_courier']);
			if (isset($_SESSION['view_order']['json_courier']) && $_SESSION['view_order']['json_courier'] != '') {
				$couriers = explode(';', $_SESSION['view_order']['json_courier']);
			} else {
				$couriers = explode(';', $_POST['json_courier']);
			}
			// print_r($_SESSION);
			// print_r($_POST);
			// die();
			$data_courier = array(
				'id'	=> $couriers[0],
				'tipe'	=> $couriers[1],
				'kota'	=> $couriers[2],
				'estimasi' => $couriers[3]
			);
			$courier = json_encode($data_courier);
			
            $orderSuccess = true;
            //insert order to database
			/* if (isset($_POST['receivercheck']) && $_POST['receivercheck'] == 1) {
				// pembeli mencentang status dropship
				$sql = "INSERT INTO sc_customer(nama_lengkap, email, cellphone1) 
						VALUES ('$sendername','$senderemail','$senderphone')";
			} else {
				$sql = "INSERT INTO sc_customer(nama_lengkap, web_user_id, email, alamat, alamat2, 
						kota, provinsi, zip, telepon, cellphone1, cellphone2) 
						VALUES ('$fullname','$web_user_id','$email','$address','$address2','$city', 
						'$state','$zip','$phone','$cellphone1','$cellphone2')";
			} */
            $sql = "INSERT INTO sc_customer(nama_lengkap, web_user_id, email, alamat, alamat2, 
                kota, provinsi, zip, telepon, cellphone1, cellphone2) 
                VALUES ('$fullname','$web_user_id','$email','$address','$address2','$city', 
                '$state','$zip','$phone','$cellphone1','$cellphone2')";
            $resultCustomer = $mysqli->query($sql);
            if ($resultCustomer) { //$resultCustomer success
                $sql = "INSERT INTO sc_receiver(nama_lengkap, email, alamat, alamat2, 
					kota, provinsi, zip, telepon, cellphone1, cellphone2, nama_pengirim, telp_pengirim, email_pengirim) 
					VALUES ('$fullnameReceiver','$emailReceiver','$addressReceiver','$addressReceiver2','$cityReceiver', 
					'$stateReceiver','$zipReceiver','$phoneReceiver','$cellphone1Receiver',
					'$cellphone2Receiver', '$sendername', '$senderphone', '$senderemail')";
                $resultReceiver = $mysqli->query($sql);
                if ($resultReceiver) {
					$info_item = info_beli($cityReceiver);
					// $shipping = $info_item['shipping'];
					$totalweight = $info_item['totalweight'];
					$iscityfound = $info_item['iscityfound'];
                    //end set ongkir disini
					
					$is_dropship = (isset($_POST['receivercheck']) && $_POST['receivercheck'] == 1) ? 1 : 0;
                    $sql = "INSERT INTO sc_transaction(transaction_id, customer_id, 
                            receiver_id, metode_pembayaran, ongkir, kurir, tanggal_buka_transaksi, 
                            status, catatan, latestupdate, kodevoucher, tipe_voucher, nominal_voucher, tglstart_voucher, tglend_voucher, is_onetime, packingvalue, insurancevalue, is_dropship) 
                            VALUES ('$transId',$customerIncrement,$receiverIncrement,
                            '$payment','$shipping', '$courier', '$now',1,'$note','$now','$voucher_code','$voucher_tipe','$voucher_nominal','$voucher_tglstart','$voucher_tglend', '$voucher_onetime', '$use_packing', '$use_insurance', '$is_dropship')";
							
                    $resultTransaction = $mysqli->query($sql);
                    if ($resultTransaction) { //$resultTransaction success 
                        $total = 0;

                        foreach ($_SESSION['cart'] as $product_id => $quantity) {
							
							if ($pakaicatalogattribut) {
								list($product_id, $product_attr_id, $product_color, $product_price, $product_price_plus, $product_size) = explode('|', $product_id);
							}
							
                            if ($product_id != '') {
                                $sql = "SELECT harganormal-(if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal),
									diskon)) as hargadiskon,title,filename FROM catalogdata WHERE id='$product_id'";
                                $result = $mysqli->query($sql);
                                list($hargadiskon, $productname, $filename) = $result->fetch_row();
								$productname=addslashes($productname);
								
								// if ($pakaicatalogattribut) {
									$sql = "INSERT INTO sc_transaction_detail(transaction_id, product_id, product_name ,filename , jumlah, harga, attribut_id) VALUES ('$transId','$product_id','$productname','$filename',$quantity,$hargadiskon,'$product_attr_id')";
								// }
								$resultTranscationDetail = $mysqli->query($sql);
								
								if ($pakaistok) {
									$sql_stock = "UPDATE catalogdata SET stock=stock-$quantity WHERE id='$product_id'";
									$result_stock = $mysqli->query($sql_stock);
								}
                            }
                        }
						
						$member_id = $_SESSION['member_uid'];
						$voucher_onetime = $_SESSION['voucher']['is_onetime'];
						if ($voucher_onetime == 1) {
							$q1 = $mysqli->query("UPDATE voucher SET status=0 WHERE id='$voucher_id'");
						}
						$q2 = $mysqli->query("INSERT INTO voucher_detail (voucher_id, member_id) VALUES ('$voucher_id', '$member_id') ");
						
                        if ($resultTranscationDetail) {
                            $transactionupdate = true;
                        } else {
                            $orderSuccess = false;
                            $sql = "DELETE FROM sc_transaction_detail WHERE transaction_detail_id = $transactionDetailIncrement";
                            $result = $mysqli->query($sql);
                            $sql = "DELETE FROM sc_transaction WHERE transaction_id = '$transactionIncrement'";
                            $result = $mysqli->query($sql);
                            $sql = "DELETE FROM sc_receiver WHERE receiver_id = $receiverIncrement";
                            $result = $mysqli->query($sql);
                            $sql = "DELETE FROM sc_customer WHERE customer_id = $customerIncrement";
                            $result = $mysqli->query($sql);
                            $kodeerror = "001";
                        }
                    } else {
                        $orderSuccess = false;
                        $sql = "DELETE FROM sc_transaction WHERE transaction_id = '$transactionIncrement'";
                        $result = $mysqli->query($sql);
                        $sql = "DELETE FROM sc_receiver WHERE receiver_id = $receiverIncrement";
                        $result = $mysqli->query($sql);
                        $sql = "DELETE FROM sc_customer WHERE customer_id = $customerIncrement";
                        $result = $mysqli->query($sql);
                        $kodeerror = "002";
                    }
                } else {
                    $orderSuccess = false;
                    $sql = "DELETE FROM sc_receiver WHERE receiver_id = $receiverIncrement";
                    $result = $mysqli->query($sql);
                    $sql = "DELETE FROM sc_customer WHERE customer_id = $customerIncrement";
                    $result = $mysqli->query($sql);
                    $kodeerror = "003";
                }
            } else {
                $orderSuccess = false;
                $sql = "DELETE FROM sc_customer WHERE customer_id = $customerIncrement";
                $result = $mysqli->query($sql);
                $kodeerror = "004";
            }
            if ($orderSuccess) {
                // $htmlmsg = "<div style=\"color:#000\"><div id=\"cart-order\" style=\"color:#000\"><div id=\"thanksmessage\">$thanksmessage</div>\r\n";
				
				// $htmlmsg .= <<<STYLE
					// <style>
						// *{font-family: arial}
					// </style>
// STYLE;
				
                // $htmlmsg .= "<h2 style=\"color:#000\">" . _ORDERDETAILS . "</h2>\r\n";
                // $htmlmsg .= "<p>" . _CURRENTORDERTITLE . ": $transId<br />\r\n";
                // $htmlmsg .= _CURRENTORDERURL . ": <a class=\"link-list\" style=\"display:block\" href=\"" . $urlfunc->makePretty("$cfg_app_url/index.php?p=order&action=view&id=$transId") . "\">" . $urlfunc->makePretty("$cfg_app_url/index.php?p=order&action=view&id=$transId") . "</a></p>\r\n";
				
				// // if (!isset($_SESSION['member_uid'])) {
					// // $htmlmsg .="<p>" . _CONFIRMPAYMENT . ": <a class=\"link-list\" style=\"display:block\" href=\"" . $urlfunc->makePretty("$cfg_app_url/index.php?p=order&action=view&id=$transId") . "#confirmPayment\">" . $urlfunc->makePretty("$cfg_app_url/index.php?p=order&action=view&id=$transId") . "</a></p>\r\n";
					// $htmlmsg .="<p>" . _CONFIRMPAYMENT . ": <a class=\"link-list\" style=\"display:block\" href=\"" . $urlfunc->makePretty("$cfg_app_url/index.php?p=order&action=confirm_payment&id=$transId") . "#confirmPayment\">" . $urlfunc->makePretty("$cfg_app_url/index.php?p=order&action=confirm_payment&id=$transId") . "</a></p>\r\n";
				// // }
				
                $htmlmsg .= "<div class=\"list-order-product table_block table-responsive table-small-gap\"><table border=\"0\" cellpadding=\"3\" style=\"border-collapse: collapse;\" class=\"table\" width=\"100%\">"; //format the cart using a HTML table
                //iterate through the cart, the $product_id is the key and $quantity is the value
                $htmlmsg .= "<tr style=\"font-weight: bold;\">";
                $htmlmsg .= "<td width=\"90px\" style=\"background: #7a7a7a;color: #fff;border: 0;padding: 22px 20px 22px 20px;text-align: center;font-weight:bold\">" . _THUMBNAIL . "</td>";
                $htmlmsg .= "<td style=\"background: #7a7a7a;color: #fff;border: 0;padding: 22px 20px 22px 20px;text-align: center;font-weight:bold\">" . _PRODUCTNAME . "</td>";
                $htmlmsg .= "<td style=\"background: #7a7a7a;color: #fff;border: 0;padding: 22px 20px 22px 20px;text-align: center;font-weight:bold\">" . _PRICE . "</td>";
                $htmlmsg .= "<td style=\"background: #7a7a7a;color: #fff;border: 0;padding: 22px 20px 22px 20px;text-align: center;font-weight:bold\">" . _QUANTITY . "</td>";
                $htmlmsg .= "<td style=\"background: #7a7a7a;color: #fff;border: 0;padding: 22px 20px 22px 20px;text-align: center;font-weight:bold\">" . _LINECOST . "</td>";
                $htmlmsg .= "</tr>";

                foreach ($_SESSION['cart'] as $product_id => $quantity) {
					
					if ($pakaicatalogattribut) {
						list($product_id, $product_attr_id, $product_color, $product_price, $product_price_plus, $product_size) = explode('|', $product_id);
					}
					
                    if ($product_id != '') {
                        //get the name, description and price from the database - this will depend on your database implementation.
                        //use sprintf to make sure that $product_id is inserted into the query as a number - to prevent SQL injection
                        $sql = "SELECT id, keterangan, title, harganormal-(if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal),
                            diskon)) as hargadiskon, harganormal, weight, 
                            filename, idmerek,cat_id,  publish, ishot, ketsingkat, diskon, matauang,
                            if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal), diskon)
                            as nilaidiskon
                            FROM catalogdata WHERE id=$product_id;
                            ";
						
                        $result = $mysqli->query($sql);
                        $counter = 0;
                        //Only display the row if there is a product (though there should always be as we have already checked)
                        if ($result->num_rows > 0) {
                            $counter++;

                            list($id, $description, $productname, $price, $normalprice, $weight, $filename, $idmerek) = $result->fetch_row();

							$brand_name = get_brand_name($idmerek);
							if ($product_price_plus > 0) $price = $price + $product_price_plus;
                            $line_cost = $price * $quantity;  //work out the line cost
                            $total = $total + $line_cost;   //add to the total cost
                            // $totalweight = $totalweight + ($weight * $quantity);
                            $htmlmsg .= "<tr>";
                            //show this information in table cells

							$filename = str_replace(' ', '-', $filename);
                            $htmlmsg .= "<td style=\"padding: 20px 20px;border-bottom: 1px solid #aaa;\"><img src=\"$cfg_thumb_url/$filename\" style=\"width:50px;\" ></td>";
                            $htmlmsg .= "<td style=\"padding: 20px 20px;border-bottom: 1px solid #aaa;font-weight:bold\"><small style=\"font-size: 15px;font-weight: bold;color: #000000;line-height: 1.5;text-transform:uppercase\" class=\"brand-name\">$brand_name</small><p style=\"line-height: 1.45;margin-bottom: 0;margin-top: 5px;line-height: 1.45;margin-bottom: 0;margin-top: 5px;\" class=\"product-name\">$productname</p>";
							if ($pakaicatalogattribut) {
								/* if ($product_color != '' || $product_size != '') {
									$htmlmsg .= '<small class="product-attribut">'.$product_color.', '.$product_size.'</small>';
								} */
								
								switch($catalog_attribute_option) {
									case 0:
										if ($product_color != '' || $product_size != '') {
											$htmlmsg .= '<small class="product-attribut">'.$product_color.', '.$product_size.'</small>';
										}
										break;
									case 1:
										if ($product_size != '') {
											$htmlmsg .= '<small class="product-attribut">'.$product_size.'</small>';
										}
										break;
									case 2:
										if ($product_color != '') {
											$htmlmsg .= '<small class="product-attribut">'.$product_color.'</small>';
										}
										break;
								}	
							}
							$htmlmsg .= '</td>';
                            $htmlmsg .= "<td style=\"padding: 20px 20px;border-bottom: 1px solid #aaa;\" align=\"right\">" . number_format($price, 0, ',', '.') . "</td>";
                            //along with a 'remove' link next to the quantity - which links to this page, but with an action of remove, and the id of the current product
                            $htmlmsg .= "<td style=\"padding: 20px 20px;border-bottom: 1px solid #aaa;\" align=\"center\">" . number_format($quantity, 0, ',', '.') . "</td>";
                            $htmlmsg .= "<td style=\"padding: 20px 20px;border-bottom: 1px solid #aaa;\" align=\"right\">" . number_format($line_cost, 0, ',', '.') . "</td>";
                            $htmlmsg .= "</tr>";
                        }
                    }
                }
				
				$htmlmsg .= "<tr class=\"noborder\">";
				$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;color: #999;padding:20px 20px 6px\">"._SUBTOTAL."</td>";
				$htmlmsg .= "<td align=\"right\" style=\"padding:20px 20px 6px\">".number_format($total, 0, ',', '.')."</td>";
				$htmlmsg .= "</tr>";
				
				if ($pakaivoucher) {
					$voucher = 0;
					if (isset($_SESSION['voucher']) && $_SESSION['voucher'] != '') {
						
						if ($_SESSION['voucher']['tipe'] == 1) {
							$voucher = number_format($_SESSION['voucher']['nominal'], 0, ',', '.');
							$total = $total - $_SESSION['voucher']['nominal'];
						} else if ($_SESSION['voucher']['tipe'] == 2) {
							$disc = 100 - $_SESSION['voucher']['nominal'];
							$temp = (($disc/100) * $total);
							$voucher = $_SESSION['voucher']['nominal'] . '%';
							$total = $temp;
						}
					}
					
					$htmlmsg .= "<tr class=\"noborder\">";
					$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;color: #999;padding:20px 20px 6px\">"._DISCOUNT."</td>";
					$htmlmsg .= "<td align=\"right\" style=\"padding:20px 20px 6px\">".$voucher."</td>";
					$htmlmsg .= "</tr>";
				}
				
				if ($pakaiadditional) {
					if (isset($use_insurance)) $total += $use_insurance;
					$htmlmsg .= "<tr class=\"noborder\">";
					$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;color: #999;padding:20px 20px 6px\">"._INSURANCE."</td>";
					$htmlmsg .= "<td align=\"right\" style=\"padding:20px 20px 6px\">".number_format($use_insurance, 0, ',', '.')."</td>";
					$htmlmsg .= "</tr>";
					
					if (isset($use_packing)) $total += $use_packing;
					$htmlmsg .= "<tr class=\"noborder\">";
					$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;color: #999;padding:20px 20px 6px\">"._PACKING."</td>";
					$htmlmsg .= "<td align=\"right\" style=\"padding:20px 20px 6px\">".number_format($use_packing, 0, ',', '.')."</td>";
					$htmlmsg .= "</tr>";
				}
				
                //START MENENTUKAN PENGGUNAAN ONGKOS KIRIM
				$shipping=$shipping==''?0:$shipping;
                if ($pakaiongkir) {
                    // $htmlmsg .= "<tr>";
                    // $htmlmsg .= "<td colspan=\"4\" align=\"right\">" . _POSTAGE;
                    // if($courier!='') $htmlmsg .= " (" . $courier . ")";
                    // $htmlmsg .= "</td>";
                    // /*$htmlmsg .= "<td colspan=\"4\" align=\"right\">" . _POSTAGE . " (" . $courier . ")</td>";*/
                    // $htmlmsg .= "<td align=\"right\">" . number_format($shipping, 0, ',', '.') . "</td>";
                    // $htmlmsg .= "</tr>";
                    // $total = $total + $shipping;
					
					//get ongkos from postage input = city
					// $getongkosquery = "SELECT ongkos, ongkostetap FROM sc_postage WHERE kota = '$cityReceiver'";
					// $result = $mysqli->query($getongkosquery);
					// if (mysql_num_rows($result) > 0) {
						// $row = mysql_fetch_array($result);
						// $shipping = (($row['ongkos'] * $totalweight) + $row['ongkostetap']);
						$htmlmsg .= "<tr>";
						// $htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">".$couriers[1]."</td>";
						$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;color: #999;padding:20px 20px 6px\">"._POSTAGE."</td>";
						$htmlmsg .= "<td align=\"right\" style=\"padding:20px 20px 6px\">".number_format($shipping, 0, ',', '.')."</td>";
						$htmlmsg .= "</tr>";
						$total = $total + $shipping;
						// $iscityfound = true;
					// } else {
						// $iscityfound = false;
					// }
					$iscityfound = true;
                } else {
					$iscityfound = false;
				}
                //END MENENTUKAN PENGGUNAAN ONGKOS KIRIM
                $htmlmsg .= "<tr class=\"noborder total_order\">";
                $htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"background: #fff;padding: 14px 20px !important;margin-top: 10px;border-top: 20px solid #f3f3f3 !important;font-size: 16pt;color: #000 !important;font-weight:bold\">" . _TOTAL . "</td>";
                $htmlmsg .= "<td align=\"right\" style=\"background: #fff;padding: 14px 20px !important;margin-top: 10px;border-top: 20px solid #f3f3f3 !important;font-size: 16pt;color: #000 !important;font-weight:bold\">" . number_format($total, 0, ',', '.') . "</td>";
                $htmlmsg .= "</tr>";
                $htmlmsg .= "</table></div>";
				
				// $htmlmsg_customer_email = "";
				$htmlmsg_customer_email = $htmlmsg;
				// $htmlmsg_customer_email .= "<p>Detail transaksi telah kami kirimkan ke email anda.</p>";
				
				// $htmlmsg_admin_email = "<h3 style=\"font-size:12pt;font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-weight:bold;color:transparent\">"._ORDERDETAILS."</h3>\r\n";
				$htmlmsg_admin_email .= $htmlmsg;

               if (!$iscityfound)
                    $htmlmsg .= "<div id=\"kotatakada\" style=\"color:#000\">$paymentnote</div>";

                if ($ismobile) {
                    $htmlmsg .= "<h2>" . _PERSONALDETAILS . "</h2>\r\n";
                    $htmlmsg .= "<ul>\r\n";
                    $htmlmsg .= "<li>" . _FULLNAME . ": $fullname</li>\r\n";
                    $htmlmsg .= "<li>" . _EMAIL . ": $email</li>\r\n";
                    $htmlmsg .= "<li>" . _ADDRESS . ": $address";
                    $htmlmsg .= ($address2 != '') ? "<br />$address2" : '';
                    $htmlmsg .= "</li>\r\n";
                    $htmlmsg .= "<li>" . _STATE . ": $state</li>\r\n";
                    $htmlmsg .= "<li>" . _CITY . ": $city</li>\r\n";
                    $htmlmsg .= "<li>" . _ZIP . ": $zip</li>\r\n";
                    $htmlmsg .= "<li>" . _TELEPHONE . ": $phone</li>\r\n";
                    $htmlmsg .= "<li>" . _CELLPHONE1 . ": $cellphone1</li>\r\n";
                    $htmlmsg .= "<li>" . _CELLPHONE2 . ": $cellphone2</li>\r\n";
                    $htmlmsg .= "</ul>\r\n";
                    $htmlmsg .= "<h2>" . _RECEIVERDETAILS . "</h2>\r\n";
                    $htmlmsg .= "<ul>\r\n";
                    $htmlmsg .= "<li>" . _FULLNAME . ": $fullnameReceiver</li>\r\n";
                    $htmlmsg .= "<li>" . _EMAIL . ": $emailReceiver</li>\r\n";
                    $htmlmsg .= "<tr valign=\"top\"><td>" . _ADDRESS . ": $addressReceiver";
                    $htmlmsg .= ($addressReceiver2 != '') ? "<br />$addressReceiver2" : '';
                    $htmlmsg .= "</li>\r\n";
                    $htmlmsg .= "<li>" . _STATE . ": $stateReceiver</li>\r\n";
                    $htmlmsg .= "<li>" . _CITY . ": $cityReceiver</li>\r\n";
                    $htmlmsg .= "<li>" . _ZIP . ": $zipReceiver</li>\r\n";
                    $htmlmsg .= "<li>" . _TELEPHONE . ": $phoneReceiver</li>\r\n";
                    $htmlmsg .= "<li>" . _CELLPHONE1 . ": $cellphone1Receiver</li>\r\n";
                    $htmlmsg .= "<li>" . _CELLPHONE2 . ": $cellphone2Receiver</li>\r\n";
                    $htmlmsg .= "</ul>\r\n";
					
					if ($sendername != '') {
						$htmlmsg .= "<h2>"._DATASENDER."</h2>\r\n";
						$htmlmsg .= "<ul>\r\n";
						$htmlmsg .= "<li>" . _FULLNAME . ": $sendername</li>\r\n";
						$htmlmsg .= "<li>" . _TELEPHONE . ": $senderphone</li>\r\n";
						$htmlmsg .= "</ul>\r\n";
					}
                } else {
					// <th>" . _PERSONALDETAILS . "</th>
                    $htmlmsg .= "<table border=\"1\" cellpadding=\"3\" style=\"margin-top:15px;color:#000;border-collapse: collapse;border: 1px solid #bbb;\" class=\"table table-bordered\" width=\"100%\"><tr style=\"background: #eee;font-weight: bold;\"><th>" . _RECEIVERDETAILS . "</th>\r\n";
					if ($sendername != '') {
						$htmlmsg .= "<th>"._DATASENDER."</th>\r\n";
					}
                    $htmlmsg .= "</tr>";
					$htmlmsg .= "<tr>";
					// $htmlmsg .= "<td valign=\"top\">\r\n";
                    // $htmlmsg .= "$fullname<br/>\r\n";
                    // $htmlmsg .= "$email<br/>\r\n";
                    // $htmlmsg .= "$address<br/>";
                    // $htmlmsg .= ($address2 != '') ? "$address2<br/>" : '';
                    // $htmlmsg .= "$state<br/>\r\n";
                    // $htmlmsg .= "$city<br/>\r\n";
                    // $htmlmsg .= "$zip<br/>\r\n";
                    // $htmlmsg .= "$phone<br/>\r\n";
                    // $htmlmsg .= "$cellphone1<br/>\r\n";
                    // $htmlmsg .= "$cellphone2</td>\r\n";
                    $htmlmsg .= "<td valign=\"top\">$fullnameReceiver<br/>\r\n";
                    // $htmlmsg .= "$emailReceiver<br/>\r\n";
                    $htmlmsg .= "$addressReceiver";
                    $htmlmsg .= ($addressReceiver2 != '') ? "<br/>$addressReceiver2<br/>" : '<br/>';
                    if ($kecamatan != '') $htmlmsg .= "$kecamatan\r\n";
                    if ($stateReceiver != "") $htmlmsg .= "$stateReceiver<br/>\r\n";
                    $htmlmsg .= "$cityReceiver<br/>\r\n";
                    $htmlmsg .= "$zipReceiver<br/>\r\n";
                    // $htmlmsg .= "$phoneReceiver<br/>\r\n";
                    $htmlmsg .= "$cellphone1Receiver<br/>\r\n";
                    if ($cellphone2Receiver != "") $htmlmsg .= "$cellphone2Receiver</td>\r\n";
					
					if ($sendername != '') {
						$htmlmsg .= "<td valign=\"top\">\r\n";
						$htmlmsg .= "<table border=\"0\" class=\"table-pengirim\">\r\n";
						$htmlmsg .= "<tr><td>$sendername</td></tr>\r\n";
						$htmlmsg .= "<tr><td>$senderphone</td></tr>\r\n";
						if (!isset($_SESSION['member_uid'])) {
							$htmlmsg .= "<tr><td>$senderemail</td></tr>\r\n";
						}
						$htmlmsg .= "</table></td>\r\n";
					}
                    $htmlmsg .= "</tr></table>\r\n";
                }
              
				$sql = "SELECT logo,bank,cabang,norekening,atasnama FROM sc_payment WHERE bank = '$payment'";
				$result = $mysqli->query($sql);
				list($logo,$bank,$cabang,$norekening,$atasnama) = $result->fetch_row();
				$htmlmsg .= "<table border=\"1\" cellpadding=\"3\" style=\"margin-top:15px;color:#000;border-collapse: collapse;border: 1px solid #bbb;\" class=\"table table-bordered\" width=\"100%\"><tr style=\"background: #eee;font-weight: bold;\">";
				if ($pakaiongkir) {
					$htmlmsg .= "<th>"._SHIPPINGINFORMATION."</th>\r\n";
				}
				$htmlmsg .= "<th>"._PAYMENTINFORMATION."</th>\r\n";
				if ($note!='') {
					$htmlmsg .= "<th>"._NOTE."</th>\r\n";
				}
				$htmlmsg .= "</tr>";
				$htmlmsg .= "<tr>\r\n";
				
				if ($pakaiongkir) {
					if ($note != '') {
						$htmlmsg .= "<td width=\"33.333%\" valign=\"top\" style=\"vertical-align:top !important\">\r\n";
					} else {
						$htmlmsg .= "<td width=\"50%\" valign=\"top\" style=\"vertical-align:top !important\">\r\n";
					}
					
					$htmlmsg .= "$postagecity<br/><input type=\"hidden\" name=\"postagecity\" value=\"$postagecity\" />\r\n";
					$htmlmsg .= "".number_format($postageprice, 0, ',', '.')."<br/><input type=\"hidden\" name=\"postageprice\" value=\"$postageprice\" /><input type=\"hidden\" name=\"postagehidden\" value=\"$postageprice\" />\r\n";
					$htmlmsg .= "$postageday<br/><input type=\"hidden\" name=\"postageday\" value=\"$postageday\" />";
					$htmlmsg .= "<input type=\"hidden\" name=\"json_courier\" id=\"json_courier\" value=\"$json_data\" />";
				}
				$htmlmsg .= "</td>";
				$htmlmsg .= "<td valign=\"top\" style=\"vertical-align:top !important\">";
				$htmlmsg .= "<table  class='rekening'>";
				$htmlmsg .= "<tr>";
				if ($logo != '' && file_exists("$cfg_payment_path/$logo")) {
					$htmlmsg .= "<td class='rekening_logo'><img src='".$cfg_payment_logo."/$logo' alt='$bank'></td>";
				}
				$htmlmsg .= "<td class='rekening_info'>
				$bank $cabang<br/>
				$norekening<br/>
				$atasnama<br/>
				</td>";
				$htmlmsg .= "</tr></table>";
				$htmlmsg .= "</td>";

				if ($note!='') {
					$htmlmsg .= "<td valign=\"top\" style=\"vertical-align:top !important\"><div id=\"ordernote\" style=\"color:#000\">$note</div></td>\r\n";
				}
				$htmlmsg .= "</tr></table></div></div>\r\n";
              					
				/**
				 *  Aly
				 *  
				 *  @revisi Master Paket
				 */
				$tglTransaksi = tglformat($now, true);
				$urlStatusTransaksi = $urlfunc->makePretty("$cfg_app_url/index.php?p=order&action=view&id=$transId");
				$urlUploadPembayaran = $urlfunc->makePretty("$cfg_app_url/index.php?p=order&action=confirm_payment&id=$transId");
				
				$strArr = array(
					'/#hargatotal/' => number_format($total, 0, ',', '.'),
					'/#logobank/' => "<img src='".$cfg_payment_logo."/$logo' alt='$bank'>",
					'/#namabank/' => $bank,
					'/#cabang/' => $cabang,
					'/#norekening/' => $norekening,
					'/#an/' => $atasnama,
					'/#notransaksi/' => $transId,
					'/#tgltransaksi/' => $tglTransaksi,
					'/#urlstatustransaksi/' => "<a href=\"$urlStatusTransaksi\">$urlStatusTransaksi</a>",
					'/#urluploadpembayaran/' => "<a href=\"$urlUploadPembayaran\">$urlUploadPembayaran</a>"
				);
				$cart_content_front = preg_replace(array_keys($strArr), array_values($strArr), $cart_content_front);
				$htmlmsg_front = $cart_content_front;
				$htmlmsg_front .= "<p><strong>"._TRANSACTIONDETAIL."</strong></p>";
				
				$htmlmsg_content_email = "<div class=\"checkout-form\" style=\"background: #f3f3f3;overflow: hidden;color: #000;padding: 30px;margin: 0 -30px;border: 2px solid #ccc;border-radius: 3px;\">";
				$htmlmsg_content_email .= $cart_content_front;
				// $htmlmsg_content_email .= "<h3 style=\"color:transparent\">"._ORDERDETAILS."</h3>\r\n";
				$htmlmsg_content_email .= $htmlmsg_customer_email;
				$htmlmsg_content_email .= "</div>";
				
                $txtmsg = "$thanksmessage\r\n";
                $txtmsg .= "\r\n";
                // $txtmsg .= _ORDERDETAILS . "\r\n";
                $txtmsg .= _CURRENTORDERTITLE . ": $transId\r\n";
                $txtmsg .= _CURRENTORDERURL . ": " . $urlfunc->makePretty("$cfg_app_url/index.php?p=order&action=view&id=$transId") . "\r\n";
                $txtmsg .= "\r\n";


                foreach ($_SESSION['cart'] as $product_id => $quantity) {

					list($product_id, $product_attr_id, $product_color, $product_price, $product_price_plus, $product_size) = explode('|', $product_id);
					
                    if ($product_id != '') {
                        //get the name, description and price from the database - this will depend on your database implementation.
                        //use sprintf to make sure that $product_id is inserted into the query as a number - to prevent SQL injection
                        $sql = "SELECT id, keterangan, title, harganormal-(if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal),
							diskon)) as hargadiskon, harganormal, weight, 
							cat_id, filename, publish, ishot, ketsingkat, diskon, matauang,
							if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal), diskon)
							as nilaidiskon
							FROM catalogdata WHERE id=$product_id;
							";

                        $result = $mysqli->query($sql);
                        $counter = 0;
                        //Only display the row if there is a product (though there should always be as we have already checked)
                        if ($result->num_rows > 0) {
                            $counter++;

                            list($id, $description, $productname, $price, $normalprice, $weight) = $result->fetch_row();

                            $line_cost = $price * $quantity;  //work out the line cost
                            //$total = $total + $line_cost;   //add to the total cost
                            //	$totalweight = $totalweight + ($weight * $quantity);
                            //show this information in table cells
                            $txtmsg .= _PRODUCTNAME . ": $productname\r\n";
                            $txtmsg .= _PRICE . ": " . number_format($price, 0, ',', '.') . "\r\n";
                            $txtmsg .= _QUANTITY . ": " . number_format($quantity, 0, ',', '.') . "\r\n";
                            $txtmsg .= _LINECOST . ": " . number_format($line_cost, 0, ',', '.') . "\r\n";
                            $txtmsg .= "\r\n";
                        }
                    }
                }
                //START MENENTUKAN PENGGUNAAN ONGKOS KIRIM
                if ($pakaiongkir) {

                    $txtmsg .= _POSTAGE . ": " . number_format($shipping, 0, ',', '.') . "\r\n";
                    $txtmsg .= "\r\n";
                }

                //END MENENTUKAN PENGGUNAAN ONGKOS KIRIM
                $txtmsg .= _TOTAL . ": " . number_format($total, 0, ',', '.') . "\r\n";
                $txtmsg .= "\r\n";

                if (!$iscityfound)
                    $txtmsg .= "$citynotfoundmsg\r\n";

                $txtmsg .= "---" . _PERSONALDETAILS . "---\r\n";
                $txtmsg .= _FULLNAME . ": $fullname\r\n";
                $txtmsg .= _EMAIL . ": $email\r\n";
                $txtmsg .= _ADDRESS . ": $address\r\n";
                $txtmsg .= ($address2 != '') ? "$address2\r\n" : '';
                $txtmsg .= _STATE . ": $state\r\n";
                $txtmsg .= _CITY . ": $city\r\n";
                $txtmsg .= _ZIP . ": $zip\r\n";
                $txtmsg .= _TELEPHONE . ": $phone\r\n";
                $txtmsg .= _CELLPHONE1 . ": $cellphone1\r\n";
                $txtmsg .= _CELLPHONE2 . ": $cellphone2\r\n";
                $txtmsg .= "\r\n";
                $txtmsg .= "---" . _RECEIVERDETAILS . "---\r\n";
                $txtmsg .= _FULLNAME . ": $fullnameReceiver\r\n";
                $txtmsg .= _EMAIL . ": $emailReceiver\r\n";
                $txtmsg .= _ADDRESS . ": $addressReceiver\r\n";
                $txtmsg .= ($addressReceiver2 != '') ? "$addressReceiver2\r\n" : '';
                $txtmsg .= _STATE . ": $stateReceiver\r\n";
                $txtmsg .= _CITY . ": $cityReceiver\r\n";
                $txtmsg .= _ZIP . ": $zipReceiver\r\n";
                $txtmsg .= _TELEPHONE . ": $phoneReceiver\r\n";
                $txtmsg .= _CELLPHONE1 . ": $cellphone1Receiver\r\n";
                $txtmsg .= _CELLPHONE2 . ": $cellphone2Receiver\r\n";
                $txtmsg .= "\r\n";
				
				if ($sendername != '') {
					$txtmsg .= "---" . _DATASENDER . "---\r\n";
					$txtmsg .= _FULLNAME . ": $sendername\r\n";
					$txtmsg .= _TELEPHONE . ": $senderphone\r\n";
				}

                $sql = "SELECT logo,bank,cabang,norekening,atasnama FROM sc_payment WHERE bank = '$payment'";
                $result = $mysqli->query($sql);
                list($logo,$bank,$cabang,$norekening,$atasnama) = $result->fetch_row();
                $txtmsg .= "---" . _PAYMENTINFO . "---\r\n";
                $txtmsg .= "$bank $cabang\r\n";
                $txtmsg .= "$norekening\r\n";
                $txtmsg .= "$atasnama\r\n";
                $txtmsg .= "\r\n";
                if ($note != '') {
                    $txtmsg .= "---" . _NOTE . "---\r\n";
                    $txtmsg .= "$note\r\n";
                }

				$htmlmsg_content = "<div style=\"color:#000\"><div id=\"cart-order\" style=\"color:#000\"><div id=\"thanksmessage\">$afterplaceorder</div>\r\n";
				$htmlmsg_content2 = $htmlmsg;
                // $content .= $htmlmsg_content.$htmlmsg;
				$content = "<div id=\"checkout-form\">";
				$content .= $htmlmsg_front;
				$content .= "</div>";
                $content .= "<p>&nbsp;</p><p><a class=\"link_kembali\" href=\"$cfg_app_url\"><i class=\"fa fa-angle-left fa-3\"></i> " . _GOTOMAIN . "</a>";
                //update shipping and total
                $sql = "UPDATE sc_transaction SET ongkir=$shipping,total=$total 
                                WHERE autoid = $transactionIncrement";
                $resultTranscationUpdate = $mysqli->query($sql);

                $sql = "SELECT value FROM contact WHERE name='umbalemail'";
                $result = $mysqli->query($sql);
                list($umbalemail) = $result->fetch_row();
				
				if (isset($_SESSION['member_uid'])) {
					$sql = "SELECT user_email FROM webmember WHERE user_id='" . $_SESSION['member_uid'] . "'";
					$result = $mysqli->query($sql);
					$row = $result->fetch_assoc();
					$email = $row['user_email'];
				} else if (!isset($_SESSION['member_uid'])) {
					if ($sendername != '') {
						$email = $senderemail;
						$fullname = $sendername;
					} else {
						$email = $email;
						$fullname = $fullnameReceiver;
					}
				} 
				
				// $htmlmsg_email = "<div style=\"color:#000\"><div id=\"cart-order\" style=\"color:#000\"><div id=\"thanksmessage\">$thanksmessage</div>\r\n";
				// $htmlmsg_content = $htmlmsg_email.$htmlmsg_content2;
				
				// echo 'send mail to customer<br>';
				// echo "$email, _CUSTOMERNEWORDERSUBJECT, $txtmsg, $smtpuser, $smtpuser, $smtpuser, $htmlmsg_content_email<br>";
				// echo "=================================================<br>";
				// echo 'send mail to admin<br>';
				// echo "$umbalemail, _ADMINNEWORDERSUBJECT, $txtmsg, $smtpuser, $fullname, $email, $htmlmsg_admin_email<br>";
				// die();
			
				// Customer
                fiestophpmailer($email, _CUSTOMERNEWORDERSUBJECT . " (#$transId)", $txtmsg, $smtpuser, $smtpsendername, $smtpuser, $htmlmsg_content_email);

                // Admin
				fiestophpmailer($umbalemail, _ADMINNEWORDERSUBJECT . " (#$transId)", $txtmsg, $smtpuser, $fullname, $email, $htmlmsg_admin_email);

                //unset the whole cart, i.e. empty the cart.
                unset($_SESSION['cart']);
				unset($_SESSION['voucher']);
				unset($_SESSION['use_insurance']);
				unset($_SESSION['insurance_value']);
				unset($_SESSION['use_packing']);
				unset($_SESSION['packing_value']);
				unset($_SESSION['view_order']);
            } else {
                $content .= "<p>" . _DATABASEERROR . ": $kodeerror</p>";
                $content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i> " . _BACK . "</a></p>";
            }
        } else {
            $content .= "<ul class=\"error\">\r\n";
            $content .= $errormessages;
            $content .= "</ul>\r\n";
            $content .= "<p><a class=\"link_kembali\" href=\"javascript:history.go(-1)\"><i class=\"fa fa-angle-left fa-3\"></i> " . _BACK . "</a></p>\r\n";
        }
    } else {
        //otherwise tell the user they have no items in their cart
        $content .= "<p>";
        $content .= _NOPRODINCART . " ";
        $content .="</p><p><a id=\"cart_continue\"  href=\"" . $urlfunc->makePretty("?p=catalog") . "\"><i class=\"fa fa-angle-left fa-3\"></i> " . _CONTINUESHOPPING . "</a></p>";
    }
}

if ($action == 'removevoucher') {
	unset($_SESSION['voucher']);
	$content .= showcart();
}

if ($action == 'review_order') {
	
	if (!empty($_SESSION['cart'])) {
		
		if (isset($_GET['pid']) && $_GET['pid'] == 'notvalid') {
			
			$shipping = $_SESSION['view_order']['postagehidden'];
			$fullname = $_SESSION['view_order']['fullname'];
			if (!isset($_SESSION['member_uid'])) {
				$email = $_SESSION['view_order']['email'];
			}
			$senderemail = $_SESSION['view_order']['senderemail'];
			$address = $_SESSION['view_order']['address'];
			$address2 = $_SESSION['view_order']['address2'];
			$kecamatan = $_SESSION['view_order']['kecamatan'];
			$state = $_SESSION['view_order']['state'];
			$othercityValue = $_SESSION['view_order']['othercityValue'];
			$othercityReceiverValue = $_SESSION['view_order']['othercityReceiverValue'];
			$zip = $_SESSION['view_order']['zip'];
			$cellphone1 = $_SESSION['view_order']['cellphone1'];
			$cellphone2 = $_SESSION['view_order']['cellphone2'];
			
			
			$fullnameReceiver = $_SESSION['view_order']['fullnameReceiver'];
			$emailReceiver = $_SESSION['view_order']['emailReceiver'];
			$addressReceiver = $_SESSION['view_order']['addressReceiver'];
			$addressReceiver2 = $_SESSION['view_order']['addressReceiver2'];
			$cityReceiver = $_SESSION['view_order']['cityReceiver'];
			$stateReceiver = $_SESSION['view_order']['stateReceiver'];
			$zipReceiver = $_SESSION['view_order']['zipReceiver'];
			$cellphone1Receiver = $_SESSION['view_order']['cellphone1Receiver'];
			$cellphone2Receiver = $_SESSION['view_order']['cellphone2Receiver'];
			
			$sendername = $_SESSION['view_order']['sendername'];
			$senderphone = $_SESSION['view_order']['senderphone'];
			$senderemail = $_SESSION['view_order']['senderemail'];
			$payment = $_SESSION['view_order']['payment'];
			
			$use_insurance = $_SESSION['view_order']['use_insurance'];
			$use_packing = $_SESSION['view_order']['use_packing'];
			
			$temp_insurance_value = $use_insurance;
			$temp_packing_value = $use_packing;
			
			$postagecity = $_SESSION['view_order']['postagecity'];
			$postageprice = $_SESSION['view_order']['postageprice'];
			$postagehidden = $_SESSION['view_order']['postageprice'];
			$postageday = $_SESSION['view_order']['postageday'];
			$json_courier = $_SESSION['view_order']['json_courier'];
			
			$couriers = explode(';', $json_courier);
			
			unset($_SESSION['voucher']);
		} else {
			$couriers = explode(';', $_POST['json_courier']);
		}
		
		// die();
        $isvalid = true;		
		if (!isset($_POST['receivercheck'])) {
			
			if ($pakaiongkir) {
				if ($shipping == '' /* and $usecourier */) {
					$errormessages .= "<li>" . _INVALIDPOSTAGE . "</li>\r\n";
					$isvalid = false;
				}
			}
			
			if ($fullname == '') {
				$errormessages .= "<li>" . _INVALIDFULLNAME . "</li>\r\n";
				$isvalid = false;
			}
			if (!isset($_SESSION['member_uid'])) {
				if ($email == '' || !validatemail($email)) {
					$errormessages .= "<li>" . _INVALIDEMAIL . "</li>\r\n";
					$isvalid = false;
				}
			}
			if ($address == '') {
				$errormessages .= "<li>" . _INVALIDADDRESS . "</li>\r\n";
				$isvalid = false;
			}
			// if ($city == '') {
				// $errormessages .= "<li>" . _INVALIDCITY . "</li>\r\n";
				// $isvalid = false;
			// }
			// if ($kecamatan == '') {
				// $errormessages .= "<li>" . _INVALIDKECAMATAN . "</li>\r\n";
				// $isvalid = false;
			// }
			// if ($phone == '' || strlen($phone) < 3) {
				// $errormessages .= "<li>" . _INVALIDPHONE . "</li>\r\n";
				// $isvalid = false;
			// }
		}
		
		if ($_POST['receivercheck'] == 1) 
		{

			if ($fullnameReceiver == '') {
				$errormessages .= "<li>" . _INVALIDFULLNAMERECEIVER . "</li>\r\n";
				$isvalid = false;
			}
			if ($emailReceiver != '' && !validatemail($emailReceiver)) {
				$errormessages .= "<li>" . _INVALIDEMAILRECEIVER . "</li>\r\n";
				$isvalid = false;
			}
			if ($addressReceiver == '') {
				$errormessages .= "<li>" . _INVALIDADDRESSRECEIVER . "</li>\r\n";
				$isvalid = false;
			}
			if ($cityReceiver == '') {
				$errormessages .= "<li>" . _INVALIDCITYRECEIVER . "</li>\r\n";
				$isvalid = false;
			}
			// if ($phoneReceiver == '' || strlen($phoneReceiver) < 3) {
				// $errormessages .= "<li>" . _INVALIDPHONERECEIVE . "</li>\r\n";
				// $isvalid = false;
			// }
			if ($sendername == '') {
				$errormessages .= "<li>" . _INVALIDSENDERNAME . "</li>\r\n";
				$isvalid = false;
			}
			if ($senderphone == '') {
				$errormessages .= "<li>" . _INVALIDSENDERPHONE . "</li>\r\n";
				$isvalid = false;
			}
		} 
		if ($payment == '') {
            $errormessages .= "<li>" ._INVALIDPAYMENT . "</li>\r\n";
            $isvalid = false;
        }
		if ($senderconfirmemail != $senderemail) {
			$errormessages .= "<li>" ._INVALIDSENDERCONFIRMEMAIL . "</li>\r\n";
            $isvalid = false;
		}
		$json_data = $_POST['json_courier'];
		
        $customerIncrement = 0;
        $receiverIncrement = 0;
        $transactionIncrement = 0;
        if ($isvalid) {
			
                $htmlmsg .= "<h3>" . _REVIEWORDERDETAILS . "</h3>\r\n";
                $htmlmsg .= "<div class=\"table_block table-responsive table-small-gap\"><table border=\"0\" class=\"table list-order-product\" width=\"100%\">"; //format the cart using a HTML table
                //iterate through the cart, the $product_id is the key and $quantity is the value
                $htmlmsg .= "<tr style=\"background: #eee;font-weight: bold;\">";
                $htmlmsg .= "<th width=\"90px\">" . _THUMBNAIL . "</th>";
                $htmlmsg .= "<th>" . _PRODUCTNAME . "</th>";
                $htmlmsg .= "<th>" . _PRICE . "</th>";
                $htmlmsg .= "<th style=\"text-align:center\">" . _QUANTITY . "</th>";
                $htmlmsg .= "<th>" . _LINECOST . "</th>";
                $htmlmsg .= "</tr>";

                foreach ($_SESSION['cart'] as $product_id => $quantity) {
					
					list($product_id, $product_attr_id, $product_color, $product_price, $product_price_plus, $product_size) = explode('|', $product_id);
					
                    if ($product_id != '') {
                        //get the name, description and price from the database - this will depend on your database implementation.
                        //use sprintf to make sure that $product_id is inserted into the query as a number - to prevent SQL injection
                        $sql = "SELECT id, keterangan, title, harganormal-(if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal),
                            diskon)) as hargadiskon, harganormal, weight, 
                            filename, diskon, matauang,
                            if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal), diskon)
                            as nilaidiskon, idmerek
                            FROM catalogdata WHERE id=$product_id;
                            ";
						
                        $result = $mysqli->query($sql);
                        $counter = 0;
                        //Only display the row if there is a product (though there should always be as we have already checked)
                        if ($result->num_rows > 0) {
                            $counter++;

                            list($id, $description, $productname, $price, $normalprice, $weight, $filename, $diskon, $matauang, $nilaidiskon, $idmerek) = $result->fetch_row();
							
							$brand_name = get_brand_name($idmerek);
							if ($product_price_plus > 0) $price = $price + $product_price_plus;
                            $line_cost = $price * $quantity;  //work out the line cost
                            $total = $total + $line_cost;   //add to the total cost
                            // $totalweight = $totalweight + ($weight * $quantity);
                            $htmlmsg .= "<tr>";
                            //show this information in table cells

                            $htmlmsg .= "<td><img src=\"$cfg_thumb_url/$filename\" style=\"width:50px;\" ></td>";
                            $htmlmsg .= "<td><small style=\"font-size: 15px;font-weight: bold;color: #000000;line-height: 1.5;text-transform:uppercase\" class=\"brand-name\">$brand_name</small><p class=\"product-name\">$productname</p>";
							
							if ($pakaicatalogattribut) {
								// if ($product_color != '' || $product_size != '') {
									// $htmlmsg .= '<small class="product-attribut">'.$product_color.', '.$product_size.'</small>';
								// }
								
								switch($catalog_attribute_option) {
									case 0:
										if ($product_color != '' || $product_size != '') {
											$htmlmsg .= '<small class="product-attribut">'.$product_color.', '.$product_size.'</small>';
										}
										break;
									case 1:
										if ($product_size != '') {
											$htmlmsg .= '<small class="product-attribut">'.$product_size.'</small>';
										}
										break;
									case 2:
										if ($product_color != '') {
											$htmlmsg .= '<small class="product-attribut">'.$product_color.'</small>';
										}
										break;
								}	
							}
							$htmlmsg .= "</td>";
                            $htmlmsg .= "<td align=\"right\">" . number_format($price, 0, ',', '.') . "</td>";
                            //along with a 'remove' link next to the quantity - which links to this page, but with an action of remove, and the id of the current product
                            $htmlmsg .= "<td align=\"center\">" . number_format($quantity, 0, ',', '.') . "</td>";
                            $htmlmsg .= "<td align=\"right\">" . number_format($line_cost, 0, ',', '.') . "</td>";
                            $htmlmsg .= "</tr>";
                        }
                    }
                }
				
				$htmlmsg .= "<tr class=\"noborder\">";
				$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;border-top:1px solid #ddd\">"._SUBTOTAL."</td>";
				$htmlmsg .= "<td align=\"right\" style=\"border-top:1px solid #ddd\">".number_format($total, 0, ',', '.')."</td>";
				$htmlmsg .= "</tr>";
				
				if ($pakaivoucher) {
					$voucher = 0;
					if (isset($_SESSION['voucher']) && $_SESSION['voucher'] != '') {
						
						if ($_SESSION['voucher']['tipe'] == 1) {
							$voucher = number_format($_SESSION['voucher']['nominal'], 0, ',', '.');
							$total = $total - $_SESSION['voucher']['nominal'];
						} else if ($_SESSION['voucher']['tipe'] == 2) {
							$disc = 100 - $_SESSION['voucher']['nominal'];
							$temp = (($disc/100) * $total);
							$voucher = $_SESSION['voucher']['nominal'] . '%';
							$total = $temp;
						}
					}
					
					$htmlmsg .= "<tr class=\"noborder\">";
					$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._DISCOUNT."</td>";
					$htmlmsg .= "<td align=\"right\">".$voucher."</td>";
					$htmlmsg .= "</tr>";
				}
				
				if ($pakaiadditional) {
					if (isset($_SESSION['insurance_value']) && isset($_POST['use_insurance']) && $_POST['use_insurance'] == 1) {
						$total += $_SESSION['insurance_value'];
						$temp_insurance_value = $_SESSION['insurance_value'];
					}
					
					if (isset($_GET['pid']) && $_GET['pid'] == 'notvalid' && $use_insurance > 0) {
						$total += $use_insurance;
						$temp_insurance_value = $use_insurance;
					}
					
					if (isset($_GET['pid']) && $_GET['pid'] == 'notvalid' && $use_packing > 0) {
						$total += $use_packing;
						$temp_packing_value = $use_packing;
					}
				
					$htmlmsg .= "<tr class=\"noborder\">";
					$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._INSURANCE."</td>";
					$htmlmsg .= "<td align=\"right\">".number_format($temp_insurance_value, 0, ',', '.')."</td>";
					$htmlmsg .= "</tr>";
					
					if (isset($_SESSION['packing_value']) && (isset($_POST['use_packing']) && $_POST['use_packing'] == 1)) {
						$total += $_SESSION['packing_value'];
						$temp_packing_value = $_SESSION['packing_value'];
					}
						
					$htmlmsg .= "<tr class=\"noborder\">";
					$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._PACKING."</td>";
					$htmlmsg .= "<td align=\"right\">".number_format($temp_packing_value, 0, ',', '.')."</td>";
					$htmlmsg .= "</tr>";
				}
				
                //START MENENTUKAN PENGGUNAAN ONGKOS KIRIM
				$shipping=$shipping==''?0:$shipping;
                if ($pakaiongkir) {
					
                    // $htmlmsg .= "<tr>";
                    // $htmlmsg .= "<td colspan=\"4\" align=\"right\">" . _POSTAGE;
                    // if($courier!='') $htmlmsg .= " (" . $courier . ")";
                    // $htmlmsg .= "</td>";
                    // /*$htmlmsg .= "<td colspan=\"4\" align=\"right\">" . _POSTAGE . " (" . $courier . ")</td>";*/
                    // $htmlmsg .= "<td align=\"right\">" . number_format($shipping, 0, ',', '.') . "</td>";
                    // $htmlmsg .= "</tr>";
                    // $total = $total + $shipping;
					
					//get ongkos from postage input = city
					// $getongkosquery = "SELECT ongkos, ongkostetap FROM sc_postage WHERE kota = '$cityReceiver'";
					// $result = $mysqli->query($getongkosquery);
					// if (mysql_num_rows($result) > 0) {
						// $row = mysql_fetch_array($result);
						// $shipping = (($row['ongkos'] * $totalweight) + $row['ongkostetap']);
						$htmlmsg .= "<tr class=\"noborder\">";
						// $htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._POSTAGE."</td>";
						// $htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">".$couriers[1]."</td>";
						$htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">"._POSTAGE."</td>";
						$htmlmsg .= "<td align=\"right\">".number_format($shipping, 0, ',', '.')."</td>";
						$htmlmsg .= "</tr>";
						$total = $total + $shipping;
						// $iscityfound = true;
					// } else {
						// $iscityfound = false;
					// }
                }
                //END MENENTUKAN PENGGUNAAN ONGKOS KIRIM
                $htmlmsg .= "<tr class=\"noborder total_order\">";
                $htmlmsg .= "<td colspan=\"4\" align=\"right\" style=\"font-weight: bold;\">" . _TOTAL . "</td>";
                $htmlmsg .= "<td align=\"right\">" . number_format($total, 0, ',', '.') . "</td>";
                $htmlmsg .= "</tr>";
                $htmlmsg .= "</table></div>";
				
			// echo '<pre>';
			// print_r($_SESSION);
			// print_r($_POST);
			// die();
			
			if (isset($_SESSION['view_order']['msg']) && $_SESSION['view_order']['msg'] != '' && $_GET['pid'] == 'notvalid') {
				$content .= '<div class="error">'.$_SESSION['view_order']['msg'].'</div>';
			}
			$content .= "<form action=\"".$urlfunc->makePretty("?p=cart&action=order")."\" method=\"POST\"  class=\"checkout-review-form\" id=\"checkout-review-form\">";
			$content .='<div id="checkout-review-termsandcond">';
			// $content .= "<form action=\"".$cfg_app_url . '/cart/order' ."\" method=\"POST\">";
			// $htmlmsg .= "<table border=\"0\" cellpadding=\"3\" style=\"margin-top:15px;color:#000;border-collapse: collapse;border: 1px solid #bbb;\" class=\"table\" width=\"100%\"><tr style=\"background: #eee;font-weight: bold;\">";
			// if (isset($_SESSION['member_uid'])) {
				// if ($sendername != '') {
				// $htmlmsg .= "<div class=\"col-sm-4\"><h3>"._PERSONALDETAILS."</h3>\r\n";
				// }else{
				// $htmlmsg .= "<div class=\"col-sm-6\"><h3>"._PERSONALDETAILS."</h3>\r\n";
				// }
			// }
			// if (isset($_SESSION['member_uid'])) {
				// $htmlmsg .= "<table border=\"0\">\r\n";
				// $htmlmsg .= "<tr><td>"._FULLNAME."</td><td>:</td><td>$yth_title $fullname</td><input type=\"hidden\" name=\"fullname\" id=\"fullname\" value=\"$fullname\" /><input type=\"hidden\" name=\"yth_title\" id=\"yth_title\" value=\"$yth_title\" /></tr>\r\n";
				// $htmlmsg .= "<tr><td>"._EMAIL."</td><td>:</td><td>$email</td><input type=\"hidden\" name=\"email\" id=\"email\" value=\"$email\" /></tr>\r\n";
				// $htmlmsg .= "<tr valign=\"top\"><td>"._ADDRESS."</td><td>:</td><td>".htmlspecialchars($address)."";
				// $htmlmsg .= ($address2!='') ? "<br />$address2" : '';
				// // $htmlmsg .= "</td><input type=\"hidden\" name=\"address\" id=\"address\" value=\"".htmlspecialchars($address)."\" /></tr>\r\n";
				// $htmlmsg .= "</td><textarea style=\"display:none\" name=\"address\" id=\"address\">".htmlspecialchars($address)."</textarea></tr>\r\n";
				// $htmlmsg .= "<tr><td>"._CITY."</td><td>:</td><td>$city</td><input type=\"hidden\" name=\"city\" id=\"city\" value=\"$city\" /></tr>\r\n";
				// $htmlmsg .= "<tr><td>"._STATE."</td><td>:</td><td>$state</td><input type=\"hidden\" name=\"state\" id=\"state\" value=\"$state\" /></tr>\r\n";
				// $htmlmsg .= "<tr><td>"._ZIP."</td><td>:</td><td>$zip</td><input type=\"hidden\" name=\"zip\" id=\"zip\" value=\"$zip\" /></tr>\r\n";
				// $htmlmsg .= "<tr><td>"._TELEPHONE."</td><td>:</td><td>$phone</td><input type=\"hidden\" name=\"phone\" id=\"phone\" value=\"$phone\" /></tr>\r\n";
				// $htmlmsg .= "<tr><td>"._CELLPHONE1."</td><td>:</td><td>$cellphone1</td><input type=\"hidden\" name=\"cellphone1\" id=\"cellphone1\" value=\"$cellphone1\" /></tr>\r\n";
				// $htmlmsg .= "<tr><td>"._CELLPHONE2."</td><td>:</td><td>$cellphone2</td><input type=\"hidden\" name=\"cellphone2\" id=\"cellphone2\" value=\"$cellphone2\"  /></tr>\r\n";
				// $htmlmsg .= "</table></div>\r\n";
			// }
			// if (isset($_SESSION['member_uid'])) {
				// if ($sendername != '') {
				// $htmlmsg .= "<td><h4>"._RECEIVERDETAILS."</h4>\r\n";
				// }else{
				// $htmlmsg .= "<td><h4>"._RECEIVERDETAILS."</h4>\r\n";
				// }
			// } else {
				// $htmlmsg .= "<td><h4>"._RECEIVERDETAILS."</h4>\r\n";
			// }
			$htmlmsg .= "<div class=\"receive_data\">\r\n";
			$htmlmsg .= "<h2>"._RECEIVERDETAILS."</h2>\r\n";
			
			
			$htmlmsg .= "<input type=\"hidden\" name=\"receivercheck\" value=\"{$_POST['receivercheck']}\">";
			
			if (isset($_SESSION['member_uid']) && $_SESSION['member_uid'] != '' && isset($_POST['receivercheck']) && $_POST['receivercheck'] == 1) {
				$sql = "SELECT user_id, username, user_regdate, user_password, 
					user_email, activity, fullname, level, company, address1, address2, 
					city, state, zip, telephone, cellphone1, cellphone2, fax, 
					activedate, bandate, lastlogin, activationcode FROM webmember WHERE user_id='" . $_SESSION['member_uid'] . "'";
				$result = $mysqli->query($sql);
				$row = $result->fetch_assoc();
				$emaildb = $row['user_email'];
			
				$_SESSION['view_order']['fullname'] = $fullnameReceiver;
				$_SESSION['view_order']['email'] = $emaildb;
				$_SESSION['view_order']['senderemail'] = $emaildb;
				$_SESSION['view_order']['address'] = $addressReceiver;
				$_SESSION['view_order']['address2'] = $addressReceiver2;
				$_SESSION['view_order']['kecamatan'] = $kecamatan;
				$_SESSION['view_order']['state'] = $stateReceiver;
				$_SESSION['view_order']['othercityValue'] = $cityReceiver;
				$_SESSION['view_order']['othercityReceiverValue'] = $cityReceiver;
				$_SESSION['view_order']['zip'] = $zip;
				$_SESSION['view_order']['cellphone1'] = $cellphone1Receiver;
				$_SESSION['view_order']['cellphone2'] = $cellphone2Receiver;
				
				$htmlmsg .= "<input type=\"hidden\" name=\"fullname\" value=\"$fullnameReceiver\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"email\" value=\"$emaildb\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"senderemail\" value=\"$emaildb\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"address\" value=\"$addressReceiver\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"address2\" value=\"$addressReceiver2\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"kecamatan\" value=\"$kecamatan\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"state\" value=\"$stateReceiver\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"othercityValue\" value=\"$cityReceiver\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"othercityReceiverValue\" value=\"$cityReceiver\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"zip\" value=\"$zip\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"cellphone1\" value=\"$cellphone1Receiver\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"cellphone2\" value=\"$cellphone2Receiver\">";
			} else {
				
				$_SESSION['view_order']['fullname'] = $fullname;
				$_SESSION['view_order']['email'] = $email;
				$_SESSION['view_order']['senderemail'] = $emaildb;
				$_SESSION['view_order']['address'] = $address;
				$_SESSION['view_order']['address2'] = $address2;
				$_SESSION['view_order']['kecamatan'] = $kecamatan;
				$_SESSION['view_order']['state'] = $state;
				$_SESSION['view_order']['othercityValue'] = $cityReceiver;
				$_SESSION['view_order']['othercityReceiverValue'] = $cityReceiver;
				$_SESSION['view_order']['zip'] = $zip;
				$_SESSION['view_order']['cellphone1'] = $cellphone1;
				$_SESSION['view_order']['cellphone2'] = $cellphone2;
				
				$htmlmsg .= "<input type=\"hidden\" name=\"fullname\" value=\"$fullname\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"email\" value=\"$email\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"address\" value=\"$address\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"address2\" value=\"$address2\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"kecamatan\" value=\"$kecamatan\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"state\" value=\"$state\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"othercityValue\" value=\"$cityReceiver\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"othercityReceiverValue\" value=\"$cityReceiver\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"zip\" value=\"$zip\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"cellphone1\" value=\"$cellphone1\">";
				$htmlmsg .= "<input type=\"hidden\" name=\"cellphone2\" value=\"$cellphone2\">";
			}
			
			$htmlmsg .= "
			<!--<input type=\"hidden\" name=\"receivercheck\" value=\"{$_POST['receivercheck']}\">
			<input type=\"hidden\" name=\"fullname\" value=\"$fullname\">
			<input type=\"hidden\" name=\"email\" value=\"$email\">
			<input type=\"hidden\" name=\"address\" value=\"$address\">
			<input type=\"hidden\" name=\"address2\" value=\"$address2\">
			<input type=\"hidden\" name=\"kecamatan\" value=\"$kecamatan\">
			<input type=\"hidden\" name=\"state\" value=\"$state\">
			<input type=\"hidden\" name=\"othercityValue\" value=\"$cityReceiver\">
			<input type=\"hidden\" name=\"zip\" value=\"$zip\">
			<input type=\"hidden\" name=\"cellphone1\" value=\"$cellphone1\">
			<input type=\"hidden\" name=\"cellphone2\" value=\"$cellphone2\">
			<input type=\"hidden\" name=\"fullnameReceiver\" id=\"fullnameReceiver\" value=\"$fullnameReceiver\" />
			<input type=\"hidden\" name=\"addressReceiver\" id=\"addressReceiver\" value=\"$addressReceiver\" />
			<input type=\"hidden\" name=\"addressReceiver2\" id=\"addressReceiver2\" value=\"$addressReceiver2\" />
			<input type=\"hidden\" name=\"emailReceiver\" id=\"emailReceiver\" value=\"$emailReceiver\" />-->
			
			<!--<input type=\"hidden\" name=\"use_insurance\" value=\"{$_POST['use_insurance']}\">
			<input type=\"hidden\" name=\"use_packing\" value=\"{$_POST['use_packing']}\">-->
			";
			
			$_SESSION['view_order']['use_insurance'] = $_SESSION['insurance_value'];
			$_SESSION['view_order']['use_packing'] = $_SESSION['packing_value'];
			
			// $htmlmsg .= "<table border=\"0\">\r\n";
			// $htmlmsg .= "<tr><td>"._FULLNAME."</td><td>:</td><td>$yth_title_Receiver $fullnameReceiver</td><input type=\"hidden\" name=\"fullnameReceiver\" id=\"fullnameReceiver\" value=\"$fullnameReceiver\" /><input type=\"hidden\" name=\"yth_title_Receiver\" id=\"yth_title_Receiver\" value=\"$yth_title_Receiver\" /></tr>\r\n";
			// // $htmlmsg .= "<tr><td>"._EMAIL."</td><td>:</td><td>$emailReceiver</td><input type=\"hidden\" name=\"emailReceiver\" id=\"emailReceiver\" value=\"$emailReceiver\" /></tr>\r\n";
			// $htmlmsg .= "<tr valign=\"top\"><td>"._ADDRESS."</td><td>:</td><td>$addressReceiver";
			// $htmlmsg .= ($addressReceiver2!='') ? "<br />$addressReceiver2" : '';
			// $htmlmsg .= "</td><input type=\"hidden\" name=\"addressReceiver\" id=\"addressReceiver\" value=\"$addressReceiver\" /></tr>\r\n";
			// $htmlmsg .= "<tr><td>"._KECAMATAN."</td><td>:</td><td>$kecamatan</td><input type=\"hidden\" name=\"kecamatan\" id=\"kecamatan\" value=\"$kecamatan\" /></tr>\r\n";
			// $htmlmsg .= "<tr><td>"._CITY."</td><td>:</td><td>$cityReceiver</td><input type=\"hidden\" name=\"cityReceiver\" id=\"cityReceiver\" value=\"$cityReceiver\" /></tr>\r\n";
			// $htmlmsg .= "<tr><td>"._STATE."</td><td>:</td><td>$stateReceiver</td><input type=\"hidden\" name=\"stateReceiver\" id=\"stateReceiver\" value=\"$stateReceiver\" /></tr>\r\n";
			// $htmlmsg .= "<tr><td>"._ZIP."</td><td>:</td><td>$zipReceiver</td><input type=\"hidden\" name=\"zipReceiver\" id=\"zipReceiver\" value=\"$zipReceiver\" /></tr>\r\n";
			// // $htmlmsg .= "<tr><td>"._TELEPHONE."</td><td>:</td><td>$phoneReceiver</td><input type=\"hidden\" name=\"phoneReceiver\" id=\"phoneReceiver\" value=\"$phoneReceiver\" /></tr>\r\n";
			// $htmlmsg .= "<tr><td>"._CELLPHONE1."</td><td>:</td><td>$cellphone1Receiver</td><input type=\"hidden\" name=\"cellphone1Receiver\" id=\"cellphone1Receiver\" value=\"$cellphone1Receiver\" /></tr>\r\n";
			// if ($cellphone2Receiver != '') $htmlmsg .= "<tr><td>"._CELLPHONE2."</td><td>:</td><td>$cellphone2Receiver</td><input type=\"hidden\" name=\"cellphone2Receiver\" id=\"cellphone2Receiver\" value=\"$cellphone2Receiver\"  /></tr>\r\n";
			// // $htmlmsg .= "<tr><td>"._NOTE."</td><td>:</td><td>$note</td><input type=\"hidden\" name=\"note\" id=\"note\" value=\"$note\"  /></tr>\r\n";
			// $htmlmsg .= "</table></div>\r\n";
			
			if (!isset($_GET['pid'])) {
				$_SESSION['view_order']['fullnameReceiver'] = $fullnameReceiver;
				$_SESSION['view_order']['addressReceiver'] = $addressReceiver;
				$_SESSION['view_order']['addressReceiver2'] = $addressReceiver2;
				$_SESSION['view_order']['kecamatan'] = $kecamatan;
				$_SESSION['view_order']['cityReceiver'] = $cityReceiver;
				$_SESSION['view_order']['stateReceiver'] = $stateReceiver;
				$_SESSION['view_order']['zipReceiver'] = $cellphone1Receiver;
				$_SESSION['view_order']['cellphone1Receiver'] = $zip;
				$_SESSION['view_order']['cellphone2Receiver'] = $cellphone2Receiver;
				$_SESSION['view_order']['sendername'] = $sendername;
				$_SESSION['view_order']['senderphone'] = $senderphone;
				$_SESSION['view_order']['senderemail'] = $senderemail;
				$_SESSION['view_order']['postagecity'] = $postagecity;
				$_SESSION['view_order']['postageprice'] = $postageprice;
				$_SESSION['view_order']['postagehidden'] = $postageprice;
				$_SESSION['view_order']['postageday'] = $postageday;
				$_SESSION['view_order']['json_courier'] = $json_data;
				$_SESSION['view_order']['payment'] = $payment;
			}
			
			$htmlmsg .= "<p>$yth_title_Receiver $fullnameReceiver<input type=\"hidden\" name=\"fullnameReceiver\" id=\"fullnameReceiver\" value=\"$fullnameReceiver\" /><input type=\"hidden\" name=\"yth_title_Receiver\" id=\"yth_title_Receiver\" value=\"$yth_title_Receiver\" /></p>\r\n";
			$htmlmsg .= "<p>$addressReceiver</p>";
			$htmlmsg .= ($addressReceiver2!='') ? "<p>$addressReceiver2</p>" : '';
			$htmlmsg .= "<p><input type=\"hidden\" name=\"addressReceiver\" id=\"addressReceiver\" value=\"$addressReceiver\" /></p>\r\n";
			if ($kecamatan != '') $htmlmsg .= "<p>$kecamatan<input type=\"hidden\" name=\"kecamatan\" id=\"kecamatan\" value=\"$kecamatan\" /></p>\r\n";
			$htmlmsg .= "<p>$cityReceiver<input type=\"hidden\" name=\"cityReceiver\" id=\"cityReceiver\" value=\"$cityReceiver\" /></p>\r\n";
			if ($stateReceiver != "") $htmlmsg .= "<p>$stateReceiver<input type=\"hidden\" name=\"stateReceiver\" id=\"stateReceiver\" value=\"$stateReceiver\" /></p>\r\n";
			$htmlmsg .= "<p>$zipReceiver<input type=\"hidden\" name=\"zipReceiver\" id=\"zipReceiver\" value=\"$zipReceiver\" /></p>\r\n";
			$htmlmsg .= "<p>$cellphone1Receiver<input type=\"hidden\" name=\"cellphone1Receiver\" id=\"cellphone1Receiver\" value=\"$cellphone1Receiver\" /></p>\r\n";
			if ($cellphone2Receiver != '') $htmlmsg .= "<p>$cellphone2Receiver<input type=\"hidden\" name=\"cellphone2Receiver\" id=\"cellphone2Receiver\" value=\"$cellphone2Receiver\"  /></p>\r\n";
			
			// if ($sendername != '') {
				// $htmlmsg .= "<div class=\"col-sm-4\"><h4>"._DATASENDER."</h4>\r\n";
				// $htmlmsg .= "<table border=\"0\">\r\n";
				// $htmlmsg .= "<tr><td>"._FULLNAME."</td><td>:</td><td>$sendername</td><input type=\"hidden\" name=\"sendername\" id=\"sendername\" value=\"$sendername\" /></tr>\r\n";
				// $htmlmsg .= "<tr><td>"._TELEPHONE."</td><td>:</td><td>$senderphone</td><input type=\"hidden\" name=\"senderphone\" id=\"senderphone\" value=\"$senderphone\" /></tr>\r\n";
				// if (!$_SESSION['member_uid']) {
					// $htmlmsg .= "<tr><td>"._EMAIL."</td><td>:</td><td>$senderemail</td><input type=\"hidden\" name=\"senderemail\" id=\"senderemail\" value=\"$senderemail\" /></tr>\r\n";
					// // $htmlmsg .= "<tr><td>"._CONFIRMEMAIL."</td><td>:</td><td>$senderconfirmemail</td><input type=\"hidden\" name=\"senderconfirmemail\" id=\"senderconfirmemail\" value=\"$senderconfirmemail\" /></tr>\r\n";
				// }
				// $htmlmsg .= "</table></div>\r\n";
			// }
			if ($sendername != '') {
				$htmlmsg .= "<h2>"._DATASENDER."</h2>\r\n";
			}
			if ($sendername != '') {
				$htmlmsg .= "<p>$sendername<input type=\"hidden\" name=\"sendername\" id=\"sendername\" value=\"$sendername\" /></p>\r\n";
				$htmlmsg .= "<p>$senderphone<input type=\"hidden\" name=\"senderphone\" id=\"senderphone\" value=\"$senderphone\" /></p>\r\n";
				if (!$_SESSION['member_uid']) {
					$htmlmsg .= "<br>$senderemail<input type=\"hidden\" name=\"senderemail\" id=\"senderemail\" value=\"$senderemail\" />\r\n";
					// $htmlmsg .= "<tr><td>"._CONFIRMEMAIL."</td><td>:</td><td>$senderconfirmemail</td><input type=\"hidden\" name=\"senderconfirmemail\" id=\"senderconfirmemail\" value=\"$senderconfirmemail\" /></tr>\r\n";
				}
			}
			$htmlmsg .= "</div>\r\n";
			$htmlmsg .= "<div class=\"data-lain\">\r\n";
			if ($pakaiongkir) {
				$htmlmsg .= "<h2>"._SHIPPINGINFORMATION."</h2>\r\n";
			}
			if ($pakaiongkir) {
				if ($note != '') {
				} else {
				}
				
				$htmlmsg .= "<p>$postagecity<input type=\"hidden\" name=\"postagecity\" value=\"$postagecity\" /></p>\r\n";
				$htmlmsg .= "<p>".number_format($postageprice, 0, ',', '.')."<input type=\"hidden\" name=\"postageprice\" value=\"$postageprice\" /><input type=\"hidden\" name=\"postagehidden\" value=\"$postageprice\" /> \r\n";
				$htmlmsg .= "$postageday<input type=\"hidden\" name=\"postageday\" value=\"$postageday\" />";
				$htmlmsg .= "<input type=\"hidden\" name=\"json_courier\" id=\"json_courier\" value=\"$json_data\" /></p>\r\n";
			}
			
			$sql = "SELECT logo,bank,cabang,norekening,atasnama FROM sc_payment WHERE bank = '$payment'";
			$result = $mysqli->query($sql);
			list($logo,$bank,$cabang,$norekening,$atasnama) = $result->fetch_row();
			$htmlmsg .= "<h2>"._PAYMENTINFORMATION."</h2>\r\n";
			$htmlmsg .= "<table class=\"rekening\">\r\n";
			$htmlmsg .= "<tr>\r\n";
			if ($note != '') {
				$htmlmsg .= "<td valign=\"top\" style=\"vertical-align:top !important\"><input type=\"hidden\" name=\"payment\" value=\"$payment\" />";
			} else {
				$htmlmsg .= "<td width=\"50%\" valign=\"top\" style=\"vertical-align:top !important\"><input type=\"hidden\" name=\"payment\" value=\"$payment\" />";
			}
			if ($logo != '' && file_exists("$cfg_payment_path/$logo")) {
				$htmlmsg .= "<img src='".$cfg_payment_logo."/$logo' alt='$bank'></td>\r\n";
			}
			$htmlmsg .= "<td>
			$bank $cabang<br/>
			$norekening<br/>
			$atasnama<br/>\r\n";
			$htmlmsg .= "</td></tr>\r\n";
			$htmlmsg .= "</table>\r\n";
			
			if ($note != '') {
				$htmlmsg .= "<h2>"._NOTE."</h2>\r\n";
			}
			if ($note != '') {
				// $htmlmsg .= "<div class=\"col-sm-4\"><h4>"._NOTE."</h4>\r\n";
				$htmlmsg .= "<p>".nl2br($note)."<input type=\"hidden\" name=\"note\" id=\"note\" value=\"".nl2br($note)."\"  /></p>\r\n";
			}
			
			$htmlmsg .= "</div>";
			// $htmlmsg .= "</tr></table>";
			$content .= $htmlmsg;
			// $content .= "<h3>"._SURETOCHECKOUT."</h3>";
			$content .= "</div>"; 
			$content .= "<h3 style=\"clear:both\">&nbsp;</h3>";
           
			$content .= <<<HTML
			<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  </div>
				  <div class="modal-body">
					$termsofcond
				  </div>
				</div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
HTML;

			$content .= '
				<label>
				  <input type="checkbox" name="termsandcond" id="termsandcond" value="1"><span class="checkbox-material">
				  '._TERMOFCONDITIONAGREE.'
				</label>
				<a data-toggle="modal" data-target="#myModal">'._TERMOFCONDITIONTEXT.'</a>';
			// Material Design
			// $content .= '
			// <div class="form-group">
			  // <div class="checkbox">
				// <label>
				  // <input type="checkbox" name="terms" value="1" required><span class="checkbox-material"><span class="check"></span></span>
				  // '._TERMOFCONDITIONAGREE.'
				// </label>
				// <a data-toggle="modal" data-target="#myModal">'._TERMOFCONDITIONTEXT.'</a>
			  // </div>
			// </div>';

			// $content .= "<label><input type=\"checkbox\" value=\"1\" required><p>"._TERMOFCONDITIONAGREE." </label><a data-toggle=\"modal\" data-target=\"#myModal\">"._TERMOFCONDITIONTEXT."</a></p>";
			
			// $content .= "<a class=\"acart\" href=\"javascript:history.go(-1)\">"._BACK."</a>&nbsp;<input class=\"btn\" type=\"submit\" name=\"checkout\" value=\""._CHECKOUTYES."\" /><br /><br />";
			$content .= "<p>&nbsp;</p>";            
			$content .= "<div class=\"biodatabawah\"><a class=\" link_kembali\" href=\"javascript:history.back();\"><i class=\"fa fa-angle-left fa-3\"></i>"._BACK."</a>&nbsp;&nbsp;&nbsp;<input class=\"btn btn-default more\" type=\"submit\" name=\"checkout\" id=\"complete-order\" value=\""._COMPLETEORDER."\" /></div><br />";
			$content .= "</form>";            

        } else {
            $content .= "<ul class=\"error\">\r\n";
            $content .= $errormessages;
            $content .= "</ul>\r\n";
            $content .= "<p><a href=\"javascript:history.go(-1)\">"._BACK."</a></p>\r\n";
        }
    } else {
        //otherwise tell the user they have no items in their cart
        $content .= _NOPRODINCART." ";
        // $content .="<p><a href=\"".$urlfunc->makePretty("?p=catalog")."\">"._CONTINUESHOPPING."</a></p>";
        $content .="<p><a href=\"".$cfg_app_url . '/' . $menus['url'] ."\">"._CONTINUESHOPPING."</a></p>";
    }
}
?>
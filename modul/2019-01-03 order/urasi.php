<?php
global $mysqli;

$cfg_thumb_path = "$cfg_img_path/catalog/small";
$cfg_thumb_url = "$cfg_img_url/catalog/small";
$cfg_payment_path = "$cfg_img_path/sc_payment";
$cfg_payment_logo = "$cfg_img_url/sc_payment";
$sql = "SELECT name, value FROM config WHERE modul='order' ";
$result = mysql_query($sql);
while (list($name, $value) = mysql_fetch_row($result)) {
	$$name=$value;
}

$maxupload = 50000;	// 10MB
$allowedtypes = array('jpg', 'jpeg', 'png', 'gif', 'pdf');

$sql = "SELECT name, value FROM contact WHERE grup=1";
if ($result = $mysqli->query($sql)) {
	while(list($name, $value) = $result->fetch_row()) {
		$$name = $value;
	}
}

$array_status_pembayaran = array(
0 => "Belum Bayar",
1 => "Menunggu Konfirmasi",
2 => "Lunas (Paypal)",
3 => "Lunas (Transfer)"
);

if($admin)
{
//css untuk admin
ob_start();
echo <<<END
<!--================================awal Style Catalog=============================================-->
<link type="text/css" href="$cfg_app_url/modul/catalog/css/admin.css" rel="stylesheet">
<!--================================akhir Style Catalog=============================================-->
END;
ob_get_clean();

}

$payment_method=array("1"=>_PAY_WITH_CREDIT_CARD,"2"=>_PAY_WITH_BANK_TRANSFER);
$jsRequired = _JSREQUIRED;
ob_start();
echo <<<SCRIPT
<link href="$cfg_app_url/js/zebradatetimepicker/css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="$cfg_app_url/js/zebradatetimepicker/js/zebra_datepicker.js"></script>
<script src="$cfg_app_url/js/jquery.validate.min.js"></script>
<script>
	
	$.validator.setDefaults( {
		debug: true,
		success: "valid"
	});
	
	$(function() {
		$('#tanggal_konfirmasi').Zebra_DatePicker();
		var confirm_msg = $("#confirm_msg").val();
		
		$("#confirm_payment").validate({
			rules: {
				required: true,
				accept: "audio/*"
			},
			messages: {
				filename: "$jsRequired"
			},
			submitHandler: function (form) {
				var pilihan = confirm(confirm_msg);
				
				if (pilihan) {
					return true;
				} else {
					return false;
				}
				
			}
		});		
	})
	
</script>
SCRIPT;
$script_js['order'] = ob_get_clean();
?>
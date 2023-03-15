<?php
global $mysqli;

$cfg_thumb_path = "$cfg_img_path/catalog/small";
$cfg_thumb_url = "$cfg_img_url/catalog/small";
$cfg_payment_path = "$cfg_img_path/sc_payment";
$cfg_payment_logo = "$cfg_img_url/sc_payment";
$sql = "SELECT name, value FROM config WHERE modul='order' ";
$result = $mysql->query($sql);
while (list($name, $value) = $mysql->fetch_row($result)) {
	$$name=$value;
}

$maxupload = 50000;	// 10MB
$allowedtypes = array('jpg', 'jpeg', 'png', 'gif', 'pdf');

$sql = "SELECT name, value FROM contact WHERE grup=1";
if ($result = $mysql->query($sql)) {
	while(list($name, $value) = $mysql->fetch_row($result)) {
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
<link type="text/css" href="$cfg_app_url/modul/order/css/admin.css" rel="stylesheet">
<!--================================akhir Style Catalog=============================================-->
END;
ob_get_clean();

}

$payment_method=array("1"=>_PAY_WITH_CREDIT_CARD,"2"=>_PAY_WITH_BANK_TRANSFER);
$jsRequired = _JSREQUIRED;
ob_start();
echo <<<SCRIPT
<!--<link href="$cfg_app_url/js/zebradatetimepicker/css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="$cfg_app_url/js/zebradatetimepicker/js/zebra_datepicker.js"></script>-->
<script src="$cfg_app_url/js/jquery-validation/jquery.validate.min.js"></script>
<script src="$cfg_app_url/js/jquery-validation/additional-methods.min.js"></script>

<script>
	
	$(function() {
		// $('#tanggal_konfirmasi').Zebra_DatePicker();
		var confirm_msg = $("#confirm_msg").val();
		
		jQuery.validator.addMethod("uploadFile", function (val, element) {

		  var size = element.files[0].size;
			console.log(size);

		   if (size > 1048576)// checks the file more than 1 MB
		   {
			   console.log("returning false");
				return false;
		   } else {
			   console.log("returning true");
			   return true;
		   }

		}, "File type error max file 1MB");
		
		$("#confirm_payment").validate({
			rules: {
				filename: {
					required: true,
					extension:'jpe?g,png',
					uploadFile: true,
				},
			},
			messages: {
				filename: {
					required: "$jsRequired",
					uploadFile: "Max file yang boleh diupload 1MB"
				}
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
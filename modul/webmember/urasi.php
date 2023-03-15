<?php
$tabelwebmember = "webmember";
$prohibitedpasses = array('password','123456','abcdef');
$expired_periode = 3; // Jumlah dalam hari

$cfg_fullsizepics_path = "$cfg_img_path/webmember/large";
$cfg_fullsizepics_url = "$cfg_img_url/webmember/large";
$cfg_thumb_path = "$cfg_img_path/webmember/small";
$cfg_thumb_url = "$cfg_img_url/webmember/small";

$maxfilesize = 500000;		//Max file size allowed to be uploaded

////////////WEB MEMBER
//REGISTER
$is_public_register = true; // Apakah Pengunjung boleh register
//ACTIVATION
// 0 aktivasi hanya oleh admin,
// 1 aktivasi lwt email,
// 2 auto aktivasi ketika daftar,
// 3 auto aktivasi dan otomatis login
$is_public_activation = 1; 


ob_start();
echo <<<END
<!--================================Awal Style Webmember=============================================-->
<link type="text/css" href="$cfg_app_url/modul/webmember/css/basic.css" rel="stylesheet">
<!--================================Akhir Style Webmember=============================================-->
END;

$style_css['webmember']=ob_get_clean();

ob_start();
echo <<<JS
<script src="$cfg_app_url/js/jquery.validate.min.js"></script>
<script>
	function refreshCaptcha()
	{
		var img = document.images["captchaimg"];
		img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
	}
	$(document).ready(function()
	{
		$(".menu-edit-password > a").click(function()
		{
			$(this).addClass("active");
			$(this).siblings().removeClass("active");
		});
		
		$('#signupform').validate({
			rules: {
				txtEmail: {
					required: true,
					email: true
				},
				txtNama: {
					required: true,
					minlength: 3
				},
				txtPass1: {
					required: true,
					minlength: 6
				},
				txtPass2: {
					required: true,
					minlength: 6,
					equalTo: "#txtPass1"
				},
				txtAlamat1: {
					required: true,
					minlength: 3
				},
				txtTelepon: {
					required: true,
					number: true
				}/* ,
				txtPonsel1: {
					required: true,
					number: true
				} */
			}, 
			messages: {
				txtPass2: "Please enter the same password as above",
			}
		})
	});
</script>
JS;

$script_js['webmember'] = ob_get_clean();

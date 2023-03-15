<?php 

function imgPopup() {
	$content = "<script>
		$(document).ready(function() {
			$('#tipe_diskon').change(function() {
				if ($(this).val() == 3) {
					$('.imgpopup2').show();
					$('.imgpopup1').hide();
				} else if ($(this).val() == 1 || $(this).val() == 2) {
					$('.imgpopup1').show();
					$('.imgpopup2').hide();
				} else {
					$('.imgpopup1').hide();
					$('.imgpopup2').hide();
				}
				
			});
		});
	</script>";	
	
	return $content;
}

// function cek_valid_tgl($kodevoucher) {
	// $tglsekarang = date('Y-m-d');
	// $q = mysql_query("SELECT kodevoucher, tipe, nominaldiskon, tglstart, tglend, nominalbelanja FROM voucher WHERE kodevoucher='$kodevoucher' AND '$tglsekarang' <= tglend");
	// if (mysql_num_rows($q) > 0) {
		// return true;
	// } else {
		// return false;
	// }
// }
?>
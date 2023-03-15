<?php

$cfg_po_tax = 1.07;
$cfg_insurance = 0.2;
$cfg_insurance_plus = 5000;

/* --- NOTIFICATIONS --- */
$minimum_city_length_notification = "Pencarian kota paling sedikit harus memuat";
/* --- NOTIFICATIONS --- */

$cfg_thumb_path = "$cfg_img_path/catalog/small";
$cfg_thumb_url = "$cfg_img_url/catalog/small";
$cfg_payment_path = "$cfg_img_path/sc_payment";
$cfg_payment_logo = "$cfg_img_url/sc_payment";

$sql = "SELECT name, value FROM config WHERE modul='cart'";
$result = $mysql->query($sql);
while (list($name, $value) = $mysql->fetch_row($result)) {
	$$name=$value;
}

$sql2 = "SELECT name, value FROM contact WHERE grup=1";
$result2= $mysql->query($sql2);
while (list($name, $value) = $mysql->fetch_row($result2)) {
	$$name=$value;
}
ob_start();

echo <<<END
<!--================================awal Style Cart=============================================-->
<link type="text/css" href="$cfg_app_url/modul/cart/css/basic.css?ver=1.1" rel="stylesheet">
<link type="text/css" href="$cfg_app_url/js/busyload/app.css" rel="stylesheet">

<style>
.button-link {
    text-decoration:  none;
    padding: 5px 10px 5px 10px;
    background: #4479BA;
    color: #FFF;
}

.autocomplete-suggestions { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-no-suggestion { padding: 2px 5px;}
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: bold; color: #000; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }
</style>
<!--================================akhir Style Cart=============================================-->
END;

$style_css['cart']= ob_get_clean();

ob_start();
// $is_insurance = $_SESSION['use_insurance'];
// $is_packing = $_SESSION['use_packing'];
$batas_max = _MSGMAXQTY;

$jsRequired = _JSREQUIRED;
$jsIsNumber = _JSREQUIREDISNUMBER;
$jsIsMinLength = _JSREQUIREDISMINLENGTH;

echo <<<SCRIPT
<script src="$cfg_app_url/js/accounting.min.js"></script>
<script src="$cfg_app_url/js/jquery.autocomplete.min.js"></script>
<script src="$cfg_app_url/js/jquery-validation/jquery.validate.min.js"></script>

<!-- busy-load -->
<script>

	// $.validator.setDefaults( {
		// submitHandler: function () {
			// console.log( "Berhasil!" );
		// }
	// });

	$(function() {
		
		$("#checkout-review-form").validate({
			rules: {
				termsandcond: "required"
			}, 
			messages: {
				termsandcond: "$jsRequired"
			},
			submitHandler: function(form) { // <- pass 'form' argument in
				$("body").busyLoad("show", { text: "LOADING ..."});
				$("#complete-order").attr("disabled", true);
				form.submit(); // <- use 'form' argument here.
			}
		});
		
		$("#checkout-form").validate({
			submitHandler: function(form) {
				form.submit();
			},
			
			rules: {
				fullname: {
					minlength: 5
				},
				fullnameReceiver: {
					required: true
				},
				address: {
					required: true
				},
				addressReceiver: {
					required: true
				},
				othercityValue: "required",
				othercityReceiverValue: "required",
				zip: {
					required: true,
					number: true
				},
				zipReceiver: {
					required: true,
					number: true
				},
				cellphone1: {
					required: true,
					number: true
				},
				cellphone1Receiver: {
					required: true,
					number: true
				},
				sendername: {
					required: true,
					minlength: 5
				},
				senderphone: {
					required: true,
					number: true
				},
				payment: "required",
				cariongkirkotakab: "required",
				postageprice: "required"
			},
			messages: {
				fullname: {
					required: "$jsRequired",
					minlength: "$jsIsMinLength"
				},
				fullnameReceiver: {
					required: "$jsRequired",
					minlength: "$jsIsMinLength"
				},
				address: {
					required: "$jsRequired",
					minlength: "$jsIsMinLength"
				},
				addressReceiver: {
					required: "$jsRequired",
					minlength: "$jsIsMinLength"
				},
				othercityValue: "$jsRequired",
				othercityReceiverValue: "$jsRequired",
				zip: {
					required: "$jsRequired",
					number: "$jsIsNumber"
				},
				zipReceiver: {
					required: "$jsRequired",
					number: "$jsIsNumber"
				},
				cellphone1: {
					required: "$jsRequired",
					number: "$jsIsNumber"
				},
				cellphone1Receiver: {
					required: "$jsRequired",
					number: "$jsIsNumber"
				},
				sendername: {
					required: "$jsRequired",
					minlength: "$jsIsMinLength"
				},
				senderphone: {
					required: "$jsRequired",
					number: "$jsIsNumber"
				},
				payment: "$jsRequired",
				cariongkirkotakab: "$jsRequired",
				postageprice: "$jsRequired"
			}
		});
		
		var total = 0;
		var amount = 0;
		$('.cart_quantity_input').each(function() {
			items = parseInt($(this).val());
			total += items;
		})
		
		$(".qty-trigger").click(function() {
			var ID = $(this).attr("data-id");
			var room_qty = parseInt($("#cart_quantity_input_"+ID).val());
			var id_tombol = $(this).attr("id");
			var qty_max = parseInt($("#cart_quantity_input_"+ID).attr("data-max"));
			var quantityUpdated = $("#cart_quantity_input_"+ID).attr('name');
			var product_id = $("#product_id_"+ID).val();
			var closes = $(this).closest('tr');
			var price_now = closes.find('.price-now').attr('data-price');
			var price = closes.find('.line-price');
			var data_line_price = closes.find('.line-price').attr('data-line-price');
			var total_price = $('#total_price');
			var amount_price = $('#amount_price');
			var line_cost = 0;
			var product_attribut = closes.find('.product-attribut');
			var iscatalogattribut;
			
			if (product_attribut.length > 0) {
				iscatalogattribut = 1
			} else {
				iscatalogattribut = 0;
			}
			
			if(id_tombol == "qtyplus") {
				if (room_qty >= qty_max) {
					alert('$batas_max');
				} else {
					room_qty++;
				}
			} else if(room_qty > 1) {
				room_qty--;
			}
			
			line_cost = price_now * room_qty;
			price.text(accounting.formatNumber(line_cost, 0, '.', ','));
			price.attr('data-line-price', line_cost);
			
			$("#cart_quantity_input_"+ID).val(room_qty);
			$("#lblcart_quantity_input_"+ID).html(room_qty);
			
			$.post("$cfg_app_url/modul/cart/ajax.php", {action: 'updateCart', product_id: product_id, quantity: room_qty, updated: quantityUpdated, iscatalogattribut: iscatalogattribut}, function( data ) {
				var j = 1;
			  
				if(id_tombol == "qtyplus") {
					total += j;
				} else if(room_qty > 1) {
					total -= j;
				}
				$('#b-nav').find('span').text(total);
			});
			
			update_amounts();
		});

		/* $(".qty-trigger").click(function() {
			var ID = $(this).attr("data-id");
			var room_qty = parseInt($("#cart_quantity_input_"+ID).val());
			var id_tombol = $(this).attr("id");
			var qty_max = parseInt($("#cart_quantity_input_"+ID).attr("data-max"));

			if(id_tombol == "qtyplus") {
				if (room_qty >= qty_max) {
					alert('$batas_max');
				} else {
					room_qty++;
				}
			} else if(room_qty > 1) {
				room_qty--;
			}
			
			$("#cart_quantity_input_"+ID).val(room_qty);
			$("#lblcart_quantity_input_"+ID).html(room_qty);
		}); */
		
		var is_dropship = sessionStorage.getItem('use_dropship');
		
		// hapus ini karna menimbulkan masalah jika user klik tombol back
		if ($('input[name="use_insurance"]').is(':checked')) {
			// $('#insurance_price').html(format_rupiah($('input[name="insurance_value"]').val()));
			$('input[name="use_insurance"]').attr('checked', false);
			
			var urldata = cfg_app_url + '/modul/cart/ajax.php';
			$.get(urldata, {action : 'get_insurance', status : 0}, function(msg) {});
			$.get(urldata, {action : 'get_packing', status : 0}, function(msg) {});
		}
		
		use_checked_elem("use_insurance");
		use_checked_elem("use_packing");
		
		// Dropship
		var elem_attr_req 		= $('.elem_attr_req');
		
		$('#receivercheck').click(function() {
			
			var checked = $(this);
			if (!checked.is(':checked')) {
				// remove_attr_req(elem_attr_req);
				remove_attr_req($('#data-penerima').find('.elem_attr_req'));
				remove_attr_req($('#data-pengirim').find('.elem_attr_req'));
				add_attr_req($('#data-pemesan').find('.elem_attr_req'));
				$('#data-pemesan').show(200);
				$('#data-penerima').hide(200).addClass('mfp-hide');
				// elem_autocomplete($('input[name="kecamatan"]'));
				
				$('#data-pengirim, #data-penerima').find('.form-control').each(function(i, v) {
					var input = $(this);
					input.val('');
				})
			} else {
				remove_attr_req($('#data-pemesan').find('.elem_attr_req'));
				add_attr_req($('#data-penerima').find('.elem_attr_req'));
				
				$('#data-penerima').removeClass('mfp-hide');
				$('#data-penerima, #data-pengirim').show(200);
				// $('#data-pengirim').show(200);
				$('#kecamatanReceiver').attr('name', 'kecamatan');
				elem_autocomplete($('#cari-ongkir-kota-kab'));

				$('#data-pemesan').find('.form-control').each(function(i, v) {
					var input = $(this);
					input.val('');
				})
				$('#data-pemesan').hide(200);
			}
		})
		
		if ($('#receivercheck').is(':checked')) {
			remove_attr_req($('#data-pemesan').find('.elem_attr_req'));
			add_attr_req($('#data-penerima').find('.elem_attr_req'));
			// $('#data-pemesan').show(200);
			$('#data-pemesan').find('.form-control').each(function(i, v) {
				var input = $(this);
				input.val('');
			})
			$('#data-pemesan').hide();
			$('#data-penerima').removeClass('mfp-hide').show();
			$('#data-pengirim').show();
			$('#kecamatanReceiver').attr('name', 'kecamatan');
			elem_autocomplete($('input[name="othercityValue"]'));
		} else {
			remove_attr_req($('#data-penerima').find('.elem_attr_req'));
		}
		
		if ($('#result_choosecity').val() == 1) {
			var postagecity = $('input[name="postagecity"]').val();
			var postageprice = $('input[name="postageprice"]').val();
			var postageday = $('input[name="postageday"]').val();
			
			$('#postagecity').text(postagecity);
			$('#postageprice').text(postageprice);
			$('#postageday').text(postageday);
			
			// var is_insurance = sessionStorage.getItem('use_insurance');
			var insurance_value = $('input[name="insurance_value"]').val();
			var packing_value = $('input[name="packing_value"]').val();
			if (is_insurance == 1) {
				$('#insurance_price').html(format_rupiah(insurance_value));
			}
			if (is_packing == 1) {
				$('input[name="use_packing"]').attr('checked', true);
				$('input[name="use_packing"]').removeAttr('disabled');
				$('#packing_price').text(format_rupiah(packing_value));
				
				// // $('input[name="use_packing"]').attr({'checked': true, 'disabled': false});
				// if ($('#tempCity').val() != '') {
					
					// // $('input[name="use_packing"]').attr({'checked': true, 'disabled': false});
					// // $('#packing_price').text(format_rupiah(packing_value));
					// // alert($('#tempCity').val());
					// $('input[name="use_packing"]').removeAttr('disabled');
				// } 
				
			} else {
				$('input[name="use_packing"]').removeAttr('disabled');
			}
		} else {
			// $('input[name="use_packing"]').attr('checked', false);
		}
		
		
		if ($('#othercityReceiverValue').length > 0) {
			elem_autocomplete($('#othercityReceiverValue'));
		} 
		elem_autocomplete($('#cari-ongkir-kota-kab'));
		
		
	})
	
	function elem_autocomplete(elem) {
		//Autocomplate
		elem.autocomplete({
			serviceUrl: '$cfg_app_url/modul/cart/ajax.php?action=cari_kecamatan',
			dataType: 'json',
			onSelect: function (suggestion) {
				// console.log('You selected: ' + suggestion.value + ', ' + suggestion.data + ', ' + suggestion.kecamatan);				
				var loader 			= $('#loader');
				var form_ongkir 	= $('#form-ongkir');
				var postagehidden 	= $('#postagehidden');
				var cityhidden 		= $('#cityhidden');
				var postagecity 	= $('#postagecity');
				var postageday 		= $('#postageday');
				var json_courier 	= $('#json_courier');
				var totalweight 	= $('#totalweight').val();
				
				loader.show();
				form_ongkir.hide();
				
				$('#tempCity').val(suggestion.kecamatan);
				
				$.ajax({
					data: {action: 'list', totalweight: totalweight, query : suggestion.value},
					url: '$cfg_app_url/modul/cart/ajax.php',
					success: function(msg) {
						form_ongkir.html(msg).show();
						loader.hide();
						
						var couriers = new Object();
						$('input:radio[name="postageprice"]').click(function() {
							var html = '';
							var biaya = $(this).val();
							var courier_id = $(this).attr('data-courier_id');
							var courier_tipe = $(this).attr('data-tipe');
							var estimasi = $(this).attr('data-estimasi');
							
							// var courier_id = $(this).next().val();
							// var courier_tipe = $(this).next().next().val();
							// var estimasi = $(this).next().next().next().val();
							
							var urldata = cfg_app_url + '/modul/cart/ajax.php';
							
							couriers.id = courier_id;
							couriers.tipe = courier_tipe;
							couriers.kota = suggestion.value;
							var json = JSON.stringify(couriers);
							// console.log(courier_tipe);
							html += courier_id + ";";
							html += courier_tipe + ";";
							html += suggestion.value + ";";
							html += estimasi;
							json_courier.val(html);
							cityhidden.val(courier_tipe);
							postagehidden.val(biaya);
							postagecity.val(cityhidden.val());
							postageday.val(estimasi);
							
							if (courier_tipe == 'J&T ') {
								if ($('#use_insurance').is(':checked')) {
									$('#use_insurance').attr('checked', false);
									// $('#insurance_value').val(0);
									$('#insurance_price').html(0);
								}
								if ($('#use_packing').is(':checked')) {
									$('#use_packing').attr('checked', false);
									// $('#packing_value').val(0);
									$('#packing_price').html(0);
								}
								$('#use_insurance').attr('disabled', true);
								$('#use_packing').attr('disabled', true);
								$.get(urldata, {action : 'remove_attr', status : 1}, function(msg) {});
							} else {
								$('#use_insurance').attr('disabled', false);
								$('#use_packing').attr('disabled', false);
							}
							//Packing kayu
							$('#packing_value').val(biaya);
							if ($('#use_packing').is(':checked')) {
								$('#packing_price').text(format_rupiah(biaya));
								
								$.get(urldata, {action : 'get_packing', status : 1, price : biaya}, function(msg) {});
							}
							// $('#use_packing').attr('disabled', false);
							
							
						})
					}
				})
			},
			groupBy: 'category'
		});
	}
	
	function use_checked_elem(elem) {

		$('input[name="'+elem+'"]').bind('click', function() {
			
			var insurance_value = $('input[name="insurance_value"]').val();
			var packing_value = $('input[name="packing_value"]').val();
			var insurance_price = $('input[name="insurance_price"]');
			var checked = $(this);
			
			var use_elem = elem == 'use_insurance' ? 'use_insurance' : 'use_packing';
			/* 
			
			if (elem == 'use_insurance') {
				if (checked.is(':checked')) {
					$('#insurance_price').html(format_rupiah(insurance_value));
					sessionStorage.setItem(use_elem, 1);
				} else {
					$('#insurance_price').html(0);
					sessionStorage.removeItem(use_elem);
				}
				
			} else {
				if (checked.is(':checked')) {
					$('#packing_price').html(format_rupiah(packing_value));
					sessionStorage.setItem(use_elem, 1);
				} else {
					$('#packing_price').html(0);
					sessionStorage.removeItem(use_elem);
				}
				
			} */
			
			
			var urldata = cfg_app_url + '/modul/cart/ajax.php';
			
			var temp_elem = $(this);
			if (elem == 'use_insurance') {
				var insurance_value = $('#insurance_value').val();
				if (temp_elem.is(':checked')) {
					$.get(urldata, {action : 'get_insurance', status : 1, price : insurance_value}, function(msg) {});
					$('#insurance_price').html(format_rupiah(insurance_value));
					sessionStorage.setItem(use_elem, 1);
					// recalculate();
				} else {
					$.get(urldata, {action : 'get_insurance', status : 0}, function(msg) {});
					$('#insurance_price').html(0);
					sessionStorage.removeItem(use_elem);
					// recalculate();
				}
			}
			
			// special packing kayu
			if (elem == 'use_packing') {
				var packing_value = $('#packing_value').val();
				if (temp_elem.is(':checked')) {
					$.get(urldata, {action : 'get_packing', status : 1, price : packing_value}, function(msg) {});
					$('#packing_price').html(format_rupiah(packing_value));
					sessionStorage.setItem(use_elem, 1);
					// recalculate();
					
					/* $('#total_price').text('Loading...');
					$.get(urldata, {action : 'get_packing', status : 1, price : packing_value}, function(msg) {
						if (msg != '') {
							response = JSON.parse(msg);
							if (response.results.error == 1) {
								temp_elem.attr('checked', false);
								// alert(response.results.status);
								result = confirm(response.results.status);
								if (result == true) {
									window.location = response.results.link;
								} else {
									recalculate();
								}
							}
						}
						recalculate();
					});
					if ($('#total_price').text() != 'Loading...') {
						
						recalculate();
					} */
				} else {
					$.get(urldata, {action : 'get_packing', status : 0}, function(msg) {});
					$('#packing_price').html(0);
					sessionStorage.removeItem(use_elem);
					// recalculate();
				}
			}
			
		});
	}

	function format_rupiah(str) {
		var symbol = '';
		return accounting.formatMoney(str, symbol, 0, ".", ",");
	}	

	function add_attr_req(elem) {
		elem.attr('required', true);
	}
	
	function remove_attr_req(elem) {
		elem.removeAttr('required');
	}
	
	function update_amounts() {
		var sum = 0;
		$('.cart_item').each(function() {
			var qty = parseInt($(this).find('.lblcart_quantity_input').text());
			var price = parseInt($(this).find('.price-now').attr('data-price'));
			var amount = qty * price;
			
			sum += amount;
		})
		
		$('#amount_price').val(sum);
		$('#total_price').text(accounting.formatNumber(sum, 0, '.', ','));
	}
	
</script>
SCRIPT;
$script_js['cart'] = ob_get_clean();
?>

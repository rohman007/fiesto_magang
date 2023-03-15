<?php
$cfg_fullsizepics_path = "$cfg_img_path/catalog/large";
$cfg_fullsizepics_url = "$cfg_img_url/catalog/large";
$cfg_thumb_path = "$cfg_img_path/catalog/small";
$cfg_thumb_url = "$cfg_img_url/catalog/small";
$cfg_frame_url = "$cfg_img_url/catalog/frame";
$cfg_temp_unzip_path = "$cfg_img_path/catalog/zip";

// $cfg_tambahlarge_path = "$cfg_img_path/catalog/tambah_large";
// $cfg_tambahlarge_url = "$cfg_img_url/catalog/tambah_large";
// $cfg_tambahsmall_path = "$cfg_img_path/catalog/tambah_small";
// $cfg_tambahsmall_url = "$cfg_img_url/catalog/tambah_small";


$topcatnamecombo = "";		//Name of top level category
$separatorstyle = " / ";	//Categories separator in categories navigation at the top part of the page
$maxfilesize = 5242880;		// 5MB Max file size allowed to be uploaded
$ispickerused = FALSE;		//IS date time picker javascript used? --> hanya untuk field tambahan
$autoscrolltime = 3; // dalam detik
$mode_catalog=0; //0=biasa 1=efek grid

//$thisfile="http://".param_url();
///////////////////attribut_tambahan
$attribut_type=array(1=>"Text",2=>"Drop Down");
///////////////////
$sql = "SELECT judulfrontend FROM module WHERE nama='catalog' ";
$result = $mysql->query($sql);
list($topcatnamenav) = $mysql->fetch_row($result);

$sql = "SELECT name, value FROM config WHERE modul='catalog' ";
$result = $mysql->query($sql);
while (list($name, $value) = $mysql->fetch_row($result)) {
	$$name=$value;
}

//special case checkbox 
//untuk add/edit
$sc=array(_PUBLISHED=>"publish:1",_ISBEST=>"isbest",_ISPROMO=>"ispromo",_ISNEW=>"isnew",_ISSOLD=>"issold");

//bulk edit
$sc1=array(_ISBEST=>"isbest",_ISPROMO=>"ispromo",_ISNEW=>"isnew",_ISSOLD=>"issold");

//untuk add/edit/bulk edit
//$prc=array(_HARGA1=>"harga1",_HARGA2=>"harga2");

$sort_by = array('title ASC' => _TITLEASC, 'title DESC' => _TITLEDESC, 'hargadiskon ASC' => _PRICEASC, 'hargadiskon DESC' => _PRICEDESC/* , 'diskon ASC' => _DISCONTASC, 'diskon DESC' => _DISCONTDESC */);


switch($sorttype)
{	case 0 :	$sort = "title ASC";
				break;
	case 1 :	$sort = "harganormal ASC";
				break;
	case 2 :	$sort = "harganormal DESC";
				break;
	case 3 :	$sort = "hargadiskon ASC";
				break;
	case 4 :	$sort = "hargadiskon DESC";
				break;
}


/**********************************************
CUSTOM FIELDS
Mata Uang

	Dollar:
		$typecustom[n] = 'DOLLAR';
		$paracustom[n] = '';

	Rupiah:
		$typecustom[n] = 'RUPIAH';
		$paracustom[n] = '';

Bilangan desimal (Contoh: berat dalam kilogram --> bisa koma)

	$typecustom[n] = 'DECIMAL';
	$paracustom[n] = 'M,D';

		Dimana:
		M = Panjang digit maksimal (termasuk titik desimal dan angka di belakang koma, tidak termasuk tanda minus jika ada)
		D = Jumlah angka di belakang koma

Bilangan bulat (Contoh: jumlah penonton)

	Jika mungkin negatif:

		-128 sampai 127
			$typecustom[n] = 'TINYINT';
			$paracustom[n] = '';

		-32.768 sampai 32.767
			$typecustom[n] = 'SMALLINT';
			$paracustom[n] = '';

		-8.388.608 sampai 8.388.607
			$typecustom[n] = 'MEDIUMINT';
			$paracustom[n] = '';

		-2.147.483.648 sampai 2.147.483.647
			$typecustom[n] = 'INT';
			$paracustom[n] = '';

	Jika tidak mungkin negatif:

		0 sampai 255
			$typecustom[n] = 'TINYINT';
			$paracustom[n] = 'UNSIGNED';

		0 sampai 65.535
			$typecustom[n] = 'SMALLINT';
			$paracustom[n] = 'UNSIGNED';

		0 sampai 16.777.215
			$typecustom[n] = 'MEDIUMINT';
			$paracustom[n] = 'UNSIGNED';

		0 sampai 4.294.967.295 
			$typecustom[n] = 'INT';
			$paracustom[n] = 'UNSIGNED';

String untuk combo box (Disediakan pilihan. Contoh: "Pria/Wanita")

	$typecustom[n] = 'ENUM';
	$paracustom[n] = 'value1/value2/.../valueX';

String untuk input box (User isi sendiri. Contoh: nama, nomor telepon)

	$typecustom[n] = 'VARCHAR';
	$paracustom[n] = 'M';

		Dimana:
		M = Panjang digit (maksimum 255)

String untuk text area (Contoh: keterangan yang panjangnya bisa sampai 65.535 karakter)

	$typecustom[n] = 'TEXT';
	$paracustom[n] = '';
 
Tanggal

	$typecustom[n] = 'DATE';
	$paracustom[n] = '';

Tanggal dan jam

	$typecustom[n] = 'DATETIME';
	$paracustom[n] = '';
 
**********************************************/

if ($pakaikurs) {
	$customfield[]='matauang';
	$ketcustomfield[]=_CURRENCY;
	$typecustom[]='ENUM';
	$paracustom[]='Rp/USD';
}


if ($pakaicart && $pakaiongkir) {
	$customfield[]='weight';
	$ketcustomfield[]=_WEIGHT;
	$typecustom[]='DECIMAL';
	$paracustom[]='3,2';
}

if ($pakaicart && $pakaistock) {
	$customfield[] = 'stock';
	$ketcustomfield[] = _STOCK;
	$typecustom[] = 'INT';
	$paracustom[] = '11';
}

if ($pakaimarketplaceurl) {
	
	$marketplaces = array('tokopedia' => _TOKOPEDIA);
	foreach($marketplaces as $market => $name) {
		$customfield[] = "$market";
		$ketcustomfield[] = $name;
		$typecustom[] = 'VARCHAR';
		$paracustom[] = '255';
	}
}

/*
foreach($prc as $label => $name)
{
	$customfield[]="$name";
	$ketcustomfield[]=$label;
	$typecustom[]='DECIMAL';
	$paracustom[]='13,2';
}
*/
/**********************************************
SEARCH FIELD

Array is independent --> has no relationship with custom fields array!!!

**********************************************/
$searchedcf = array();

//Cantumkan nama table yang langsung terkait dengan modul ini, penamaan wajib namamodul'_drop'.
$catalog_drop = array('catalogcat', 'catalogdata', 'catalogdatapic');
ob_start();

$translation_ADDTOCART=_ADDTOCART;
if($admin)
{
//css untuk admin
echo <<<END
<!--================================awal Style Catalog=============================================-->
<link type="text/css" href="$cfg_app_url/modul/catalog/css/admin.css" rel="stylesheet">
<link href="css/bootstrap-editable.css" rel="stylesheet">
<!--================================akhir Style Catalog=============================================-->
END;

}
else
{


//css untuk  front end
echo <<<END
<!--================================awal Style Catalog=============================================-->
<link type="text/css" href="$cfg_app_url/modul/catalog/css/basic.css" rel="stylesheet">
<style>
.bx-wrapper {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px;
    margin-bottom: 10px;
}
ul#shop-slider {
    padding: 0;
    list-style: none;
}
div#shop-slider-pager {text-align:center}
div#shop-slider-pager img {
    width: 90px;
    padding: 4px;
    border: 1px solid #eee;
    margin: 0 7px;
}
</style>
<!--================================akhir Style Catalog=============================================-->
END;
}

echo <<<END
<script>
var pakaicart='$pakaicart';
var _ADDTOCART='$translation_ADDTOCART'
</script>
END;

$style_css['catalog']=ob_get_clean();

ob_start();
$l_stok=_STOK;
$l_price=_PRICE;
$l_size=_SIZE;
$l_color=_COLOR;
$l_hexa=_HEXA;
$l_order=_ORDER;


//$l_stok : <input type="text" name="stok[]"  size="2">

$batas_max = _MSGMAXQTY;
if($admin) 	
{
	
	switch($catalog_attribute_option) {
		case 0:
			$script_js_attr = "attr_size_and_color.js";
			break;
		case 1:
			$script_js_attr = "attr_size.js";
			break;
		case 2:
			$script_js_attr = "attr_color.js";
			break;
	}
//js untuk admin
echo <<<END
<script>
var attr_size = '$l_size';
var attr_price = '$l_price';
var attr_color = '$l_color';
var attr_hexa = '$l_hexa';
var attr_order = '$l_order';
</script>
<script src="$cfg_app_url/js/jquery.ui.touch-punch.js"></script>
<script src="$cfg_app_url/js/jquery.mjs.nestedSortable.js"></script>
<script src="$cfg_app_url/modul/catalog/js/attr/$script_js_attr"></script>
<script src="$cfg_app_url/modul/catalog/js/catalog-backend.js"></script>
<script src="js/bootstrap-editable.js"></script>
<script src="$cfg_app_url/modul/catalog/js/jscolor.js"></script>
END;

echo <<<SCRIPT
<script>
$(function() {
	$( "#product_foto_group" ).sortable();
	$( "#product_foto_group" ).disableSelection();
})		
</script>
SCRIPT;
}
else
{
//js untuk front end
echo <<<END
<script type="text/javascript" src="$cfg_app_url/js/zoom/jquery.elevateZoom-3.0.8.min.js?v=1"></script>
<script src="$cfg_app_url/modul/catalog/js/catalog.js?v=1"></script>
<script src="$cfg_app_url/modul/catalog/js/jquery.bxslider.min.js?v=1.1"></script>

END;
}
if($prod_attribut==1)
{
echo <<<END
<script>
$(document).ready(function(){
$(".addtocart form").submit(function(){
	pilih=$("#pilihukuran").val();
	if(pilih.length>0)
	{
	return true;
	}
	else
	{
	alert("Pilih ukuran terlebih dahulu");
	return false;
	}
	});
});	
</script>


END;
}

echo <<<SCRIPT
<script>
	$(function() {
				/* BX Slider launch */
			$('#shop-slider').bxSlider({
				pagerCustom: '#shop-slider-pager'
			});		
			$('#shop-slider9').bxSlider({
				pagerCustom: '#shop-slider-pager9'
			});			
			$('#shop-slider7').bxSlider({
				pagerCustom: '#shop-slider-pager7'
			});			
			$('#shop-slider4').bxSlider({
				pagerCustom: '#shop-slider-pager4'
			});			
			$('#shop-slider1').bxSlider({
				pagerCustom: '#shop-slider-pager1'
			});			
			$('#shop-slider9').bxSlider({
				pagerCustom: '#shop-slider-pager9'
			});	

		$(".qty-trigger-detail").click(function() {
			var ID = $(this).attr("data-id");
			var room_qty = parseInt($("#quantity_wanted_"+ID).val());
			var id_tombol = $(this).attr("id");
			var qty_max = parseInt($("#quantity_wanted_"+ID).attr("data-max"));

			if(id_tombol == "qtyplus") {
				if (room_qty >= qty_max) {
					alert('$batas_max');
				} else {
					room_qty++;
				}
			} else if(room_qty > 1) {
				room_qty--;
			}
			
			$("#quantity_wanted_"+ID).val(room_qty);
			$("#lblcart_quantity_input_detail_"+ID).html(room_qty);
		});
		
		$('#param-size').change(function() {
			var selectOption = $(this).find(":selected");
			var pid = $(this).attr('data-id');
			var param_color = $('#param-color');
			
				$.ajax({
					url: '$cfg_app_url/modul/catalog/ajax.php',
					data: {action: 'param_size', pid: pid, size: selectOption.text()},
					success: function(data) {
						
						if (selectOption.val() != '') {
							param_color.html(data);
							
							var select = $("#param-color option:selected").val().split('|');
							if ($('#tHargaDiskon').length > 0) {
								$('.tHargaDiskon').html(select[2]);
							} else {
								$('.tAmount').html(select[2]);
							}
						} else {
							param_color.html($('<option />').text('Pilih'));
						}
					}
				})
		})
		
		$('#param-color').change(function() {
			var select = $(this).find(":selected").val().split('|');
			if ($('#tHargaDiskon').length > 0) {
				$('.tHargaDiskon').html(select[2]);
			} else {
				$('.tAmount').html(select[2]);
			}
		})			
		
		// $('#param-color').change(function() {
			// var selectOption = $(this).find(":selected");
			// var pid = $(this).attr('data-id');
			// // var param_color = $('#param-color');
			
				// $.ajax({
					// url: '$cfg_app_url/modul/catalog/ajax.php',
					// data: {action: 'param_color', pid: pid, color: selectOption.val()},
					// success: function(data) {
						// alert(data);
						// if (selectOption.val() != '') {
							// param_color.html(data);
							
							// var select = $("#param-color option:selected").val().split('|');
							// if ($('#tHargaDiskon').length > 0) {
								// $('.tHargaDiskon').html(select[2]);
							// } else {
								// $('.tAmount').html(select[2]);
							// }
						// } else {
							// param_color.html($('<option />').text('Pilih'));
						// }
					// }
				// })
		// })
		
		$(".form_beli").submit(function(e) {
			if ($("#param-size option:selected").val() == '') {
				alert("Silakan pilih ukuran.");
				
				//stop the form from submitting
				return false;
			}

			return true;
		});
	})
</script>
SCRIPT;
$script_js['catalog']=ob_get_clean();


?>

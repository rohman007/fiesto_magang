

function tambah_cat(v)
{
 if(v=='do_tambah_cat')
 {
 	$.ajax({                                      
      url: '../modul/catalog/ajax.php',                  //the script to call to get data          
      data:"action=tambah_cat",      
	  success: function(data)
      {
	 
		$("#catalog_cat").html(data);
      } 
    });
 }
}
function tambah_brand(v)
{
 if(v=='do_tambah_brand')
 {
	$("#catalog_brand").html("<div><input type='text' name='text_brand' id='text_brand' value='' /></div><input class='buton' type='button' name='save_brand' id='save_brand' value='save' /><input type='button' class='buton' name='batal' id='batal_brand' value='batal' />");
	
	$('#save_brand').click(function() {
	
	brand=$('#text_brand').val();
	$.ajax({                                      
      url: '../modul/catalog/ajax.php',                  //the script to call to get data          
      data:"action=tambah_brand&brand="+brand,      
	  success: function(data)
      {
		$("#catalog_brand").html(data);
      } 
    });
	
	});
	
	$('#batal_brand').click(function() {
	$.ajax({                                      
      url: '../modul/catalog/ajax.php',                  //the script to call to get data          
      data:"action=batal_brand",      
	  success: function(data)
      {
		$("#catalog_brand").html(data);
      } 
    });
	
	});
}
}
function pilih_type(value)
{
	if(value=='2')
	$('#'+id).show();
	else
	$('#'+id).hide();
}

	////////////////// INLINE EDIRO
	$(function () {
		$.fn.editable.defaults.mode = 'inline';
		$('.nama-produk').editable({
			type: 'text',
			url: '../modul/catalog/ajax.php?action=updatenamaproduk',
			title: 'Nama produk'
		});
		
		$('.harga-produk').editable({
			type: 'text',
			url: '../modul/catalog/ajax.php?action=updatehargaproduk',
			title: 'Input harga'
		});
		
		$('.diskon-produk').editable({
			type: 'text',
			url: '../modul/catalog/ajax.php?action=updatediskonproduk',
			title: 'Input harga'
		});
		
	});
	
	
	//////////////////
	
function ajaxchangestatus(vurl,vdata,id)
{
		$.ajax({                                      
		url:vurl,                  //the script to call to get data          
		data:vdata,      
		success: function(data)
		{
			if(id!='')$("#"+id).html(data);	
		} 
		});
}
	
$(document).ready(function(){
	
	$('#cpublish,#cisslide,#cissold,#cisnew,#cispromo,#cisbest').click(function(){
	var c = this.checked;
	type=$(this).attr('data-type');	
	if(c){ $('.'+type).prop('checked',true);c=1;}
	else{ $('.'+type).prop('checked',false);c=0}
	
	var arrtampilkan = new Array();
	//var arrvalue = new Array();
        var n = $("."+type).length;
        if (n > 0){
            $("."+type).each(function()
			{
				id=$(this).attr('data-id');
				arrtampilkan.push(id);
				//arrvalue.push(this.checked);
            });
        }
    arrtampilkan.toString();
	data='type='+type+'&checked='+c+'&value='+arrtampilkan;
	ajaxchangestatus("../modul/catalog/ajax.php?action=changestatusall",data,'');

	});
	
	$('.publish,.isslide,.issold,.isnew,.ispromo,.isbest').click(function(){
	var c = this.checked;
	type=$(this).attr('data-type');
	pid=$(this).attr('data-id');
	if(c){c=1;}else{c=0;}
	data='type='+type+'&pid='+pid+'&value='+c;
	ajaxchangestatus("../modul/catalog/ajax.php?action=changestatus",data,'');
	});
	
	
	$('.pilih_type').change(function(){
	valtype=$(this).val();
		if(valtype=='2')
		$(this).parent().next().children().show();
		else
		$(this).parent().next().children().hide();
		
	});

	$('.attribut_add').click(function(){
		a=Math.random();
		data_type=$("#data_attribut_type").html();
		$('#attribut_table').append("<tr><td><input type='text' name='nama_attribut[]' class='input-small' /></td><td>"+data_type+"</td><td><div  class='attribut_value'><input type='text' name='attribut_value[]' placeholder='dipisah koma ex: Merah,Biru' id='attribut"+a+"' class='tagsinput'/></td></tr>");		

		$('.pilih_type').change(function(){
		valtype=$(this).val();
		if(valtype=='2')
		$(this).parent().next().children().show();
		else
		$(this).parent().next().children().hide();
		});
		
	
	
	});
	
	$('.product_add_ukuran').click(function() {
		// $('#product_ukuran_group').append('<input type="text" name="ukuran[]" size="10" maxlength="20" placeholder="size"><input type="text" name="color[]" size="10" maxlength="20" placeholder="color"> <br\/>');										
		// $('.attribut-price-table').append('<tr><td>'+attr_size+'<input type="text" name="ukuran[]" size=\"10\" maxlength=\"20\"></td><td>'+attr_color+'<input type="text" name="color[]" size=\"10\" maxlength=\"20\"></td><td>'+attr_hexa+'<input name="hexa[]" class="jscolor"></td><td><a class="remove-attr" href="javascript:void(0)"><img src=\"images/delete.gif\"></a></td></tr>');										
		addColorJs();
		removeAttr();
	});
	
	$('.remove-attr').click(function() {
		$(this).closest('tr').remove();
	})
	
	$('.product_add_foto').click(function() {
		$('#product_foto_group').append('<div><input type="file" size="9"  name="foto_add[]"> </div>');										
	});
	//tags
	
	
	$('.catalog_foto_del').click(function() {
	var r = confirm("Apakah anda yakin?");
	if (r == true)
	{
		img=$(this).attr('data-img');
		div=$(this).attr('data-div');
		pid=$(this).attr('data-id');

		$.ajax({                                      
		url: '../modul/catalog/ajax.php',                  //the script to call to get data          
		data:"action=hapusthumb&img="+img+"&pid="+pid,      
		success: function(data)          //on recieve of reply
		{
		if(data=='berhasil')
		{
		//$("#"+div).html("");
		$("#"+div).remove();
		}
		} 
		});
	}
	});
	
	
	});
	
	
	
	
function addColorJs() {
	var ukuran = document.createElement('INPUT')
	ukuran.type = "text";
	ukuran.name = "ukuran[]";
	
	var price = document.createElement('INPUT')
	price.type = "text";
	price.name = "prices[]";	
	
	var warna = document.createElement('INPUT')
	warna.type = "text";
	warna.name = "color[]";
	
	var hexa = document.createElement('INPUT')
	hexa.type = "text";
	hexa.name = "hexa[]";

	var order = document.createElement('INPUT')
	order.type = "text";
	order.name = "order[]";
	
	hexa.className = "jscolor {refine:false}";
	
	// colorvalue.className = "jscolor {required:false}";
	var a = document.createElement('A')
	a.href = "javascript:void(0)";
	a.className = "remove-attr";
	var img = document.createElement('IMG')
	img.src = "../images/delete.gif";

	var picker = new jscolor(hexa);
	hexa.value = "";
	
	var newContent = document.createTextNode("Hi there and greetings!"); 

	// keterangan jika pakai script ini jika titak ada label sebelum textbox
	// var new_row = document.createElement('tr');
	// new_row.className = "color-row";
	// new_row.insertCell(0).appendChild(ukuran);
	// new_row.insertCell(1).appendChild(warna);
	// new_row.insertCell(2).appendChild(hexa);
	// a.appendChild(img);
	// new_row.insertCell(3).appendChild(a);
	// $('.attribut-price-table > tbody:last-child').append(new_row);
	
	// keterangan jika pakai script ini jika ada label sebelum textbox
	var row = document.createElement("tr");
	// var cellColor = row.insertCell(0);
	var cellSize  = row.insertCell(0);
	var cellPrice  = row.insertCell(1);
	// var cellHexa  = row.insertCell(3);
	var cellOrder  = row.insertCell(2);
	var cellRemove  = row.insertCell(3);
	
	// var textColor = document.createTextNode(attr_color);
	var textSize = document.createTextNode(attr_size);
	var textPrice = document.createTextNode(attr_price);
	// var textHexa = document.createTextNode(attr_hexa);
	var textOrder = document.createTextNode(attr_order);
	
	// cellColor.appendChild(textColor);
	// cellColor.appendChild(warna);
	cellSize.appendChild(textSize);
	cellSize.appendChild(document.createElement('br'));
	cellSize.appendChild(ukuran);
	cellPrice.appendChild(textPrice);
	cellPrice.appendChild(document.createElement('br'));
	cellPrice.appendChild(price);
	// cellHexa.appendChild(textHexa);
	// cellHexa.appendChild(hexa);
	cellOrder.appendChild(textOrder);
	cellOrder.appendChild(document.createElement('br'));
	cellOrder.appendChild(order);
	a.appendChild(img);
	cellRemove.appendChild(a);
	$('.attribut-price-table > tbody:last-child').append(row);
	removeAttr();
}
function removeAttr() {
	$('.remove-attr').click(function() {
		$(this).closest('tr').remove();
	})
}

function update_kategori_select() {
		$.ajax({
			url: '../modul/catalog/ajax.php?action=refresh_kategori',
			success: function(data) {
				$('#kategori').html(data);
				load_nested();
			}
		});
}	
function load_nested()
{
$('.sortable').nestedSortable({
		listType: 'ol',
		handle: 'div',
		items: 'li',
		opacity: .6,
		toleranceElement: '> div',
		tabSize: 15,
		maxLevels: 5,
		update: function() {
			var sorted = $('.sortable').nestedSortable('serialize');
				
			$.ajax({
				type: 'POST',
				url: '../modul/catalog/ajax.php?action=update_parent',
				data: sorted,
				error: function() {
				},
				success: function(data) {
				update_kategori_select();
				}
	
			});

		}
	});
}	
$(function() {
// load_nested();

});



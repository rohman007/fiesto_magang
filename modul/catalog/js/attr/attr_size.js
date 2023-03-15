$('.product_add_ukuran').click(function() {
	// $('#product_ukuran_group').append('<input type="text" name="ukuran[]" size="10" maxlength="20" placeholder="size"><input type="text" name="color[]" size="10" maxlength="20" placeholder="color"> <br\/>');										
	// $('.attribut-price-table').append('<tr><td>'+attr_size+'<input type="text" name="ukuran[]" size=\"10\" maxlength=\"20\"></td><td>'+attr_color+'<input type="text" name="color[]" size=\"10\" maxlength=\"20\"></td><td>'+attr_hexa+'<input name="hexa[]" class="jscolor"></td><td><a class="remove-attr" href="javascript:void(0)"><img src=\"images/delete.gif\"></a></td></tr>');										
	addProductAttrJs();
	removeAttr();
});

$('.remove-attr').click(function() {
	$(this).closest('tr').remove();
})

function addProductAttrJs() {
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
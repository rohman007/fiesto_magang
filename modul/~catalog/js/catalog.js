$(document).ready(function(){
	$('#zproductfullthumb-awal').elevateZoom({
			zoomWindowOffetx: 10,
			zoomWindowFadeIn: 500,
			zoomWindowFadeOut: 750
			});

	 $('.productthumb').mouseover(function(){
	  id=$(this).attr("data-id");
	 $("#productfullposition").html($("#"+id).html());
	 
			$('#z'+id).elevateZoom({
			zoomWindowOffetx: 10,
			zoomWindowFadeIn: 500,
			zoomWindowFadeOut: 750
			});
	 });

	// $('.productthumb').mouseover(function(){
	// id=$(this).attr("data-id");
	// $("#"+id).show();
	// $("#productfullposition").hide();
	// });
	
	// $('.productthumb').mouseleave(function(){
	// id=$(this).attr("data-id");
	// $("#"+id).hide();
	// $("#productfullposition").show();
	// });
	
	$('.pilihukuran').click(function(){
		
		id=$(this).attr("id");
		size=$("#"+id).attr("size");
		stok=$("#"+id).attr("stok");
		if(stok<='0')
		{
		alert('Tidak tersedia');
		}
		else
		{
		$("#pilihukuran").val(size);
				
		$(".pilihukuran").removeClass("terpilih").addClass("belumpilih");
		$("#"+id).removeClass("belumpilih").addClass("terpilih");
		
		}
		
	});
	
	
	
	$('#cpublish').click(function() {
	var c = this.checked;
	if(c){ $(':checkbox[name^=bulkpublished]').attr('checked','checked');}
	else{ $(':checkbox[name^=bulkpublished]').removeAttr('checked');}
	});
	$('#cisslide').click(function() {
	var c = this.checked;
	if(c){ $(':checkbox[name^=isslide]').attr('checked','checked');}
	else{ $(':checkbox[name^=isslide]').removeAttr('checked');}
	});	
	$('#cissold').click(function() {
	var c = this.checked;
	if(c){ $(':checkbox[name^=issold]').attr('checked','checked');}
	else{ $(':checkbox[name^=issold]').removeAttr('checked');}
	});	
	$('#cisnew').click(function() {
	var c = this.checked;
	if(c){ $(':checkbox[name^=isnew]').attr('checked','checked');}
	else{ $(':checkbox[name^=isnew]').removeAttr('checked');}
	});
	$('#cispromo').click(function() {
	var c = this.checked;
	if(c){ $(':checkbox[name^=ispromo]').attr('checked','checked');}
	else{ 	$(':checkbox[name^=ispromo]').removeAttr('checked');}
	});

});
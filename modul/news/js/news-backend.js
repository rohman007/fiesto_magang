	
function update_kategori_select() {
		$.ajax({
			url: '../modul/news/ajax.php?action=refresh_kategori',
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
		maxLevels: 1,
		update: function() {
			var sorted = $('.sortable').nestedSortable('serialize');
				
			$.ajax({
				type: 'POST',
				url: '../modul/news/ajax.php?action=update_parent',
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
load_nested();
	$('.catalog_foto_del').click(function() {
	var r = confirm("Apakah anda yakin?");
	if (r == true)
	{
		img=$(this).attr('data-img');
		div=$(this).attr('data-div');
		pid=$(this).attr('data-id');

		$.ajax({                                      
		url: '../modul/news/ajax.php',                  //the script to call to get data          
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




function update_kategori_select() {
		$.ajax({
			url: '../modul/menu/ajax.php?action=refresh_kategori',
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
		sort: function(event, ui) {
		
			
		},
		beforeStop: function(event, ui) {
//		alert(ui.item.prev().attr('id'));
		idparent=ui.item.parent().parent().attr('id');
			//if (ui.item.prev().hasClass("nosub")) {
			
			if ($("#"+idparent).hasClass("nosub")) 
			{
			return false;
			}
			
		},      
		update: function(event, ui) {
				
		//alert(id);
		
			var sorted = $('.sortable').nestedSortable('serialize');
				
			$.ajax({
				type: 'POST',
				url: '../modul/menu/ajax.php?action=update_parent',
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

});



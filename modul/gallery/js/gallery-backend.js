function tambah_cat(v,dcat_id)
{
 if(v=='do_tambah_cat')
 {
 	$.ajax({                                      
      url: '../modul/gallery/ajax.php',                  //the script to call to get data          
      data:"action=tambah_cat&dcat_id="+dcat_id,      
	  success: function(data)
      {
	 
		$("#gallery_cat").html(data);
      } 
    });
 }
}
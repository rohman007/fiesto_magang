<?php
$del=_DEL;
echo <<<END
<div class="controls">
	<div id="selectedFiles"></div>
</div>
<div class="input_foto_tambahan controls">
	<div data-provides="fileupload" class="fileupload fileupload-new">
		<div class="input-append">
			<div class="uneditable-input span2">
				<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
			</div>
			<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">Ganti</span>
			<input type="file" id="files" name="foto_add[]" multiple accept="image/*" />
			</span>
		</div>

	</div>
</div>

<script>
	var selDiv = "";
	document.addEventListener("DOMContentLoaded", init, false);
	function init() {
		document.querySelector('#files').addEventListener('change', handleFileSelect, false);
		selDiv = document.querySelector("#selectedFiles");
	}
	function handleFileSelect(e) {
		
		if(!e.target.files || !window.FileReader) return;
		
		selDiv.innerHTML = "";
		
		var files = e.target.files;
		var filesArr = Array.prototype.slice.call(files);
		i=1;
		filesArr.forEach(function(f) {
			if(!f.type.match("image.*")) {
				return;
			}
			
			var reader = new FileReader();
			reader.onload = function (e) {
			var html = "<div class='catalog_foto_add' id='catalog_foto_add_new"+i+"'><img src=\"" + e.target.result + "\"></div>";
				selDiv.innerHTML += html;				
				//" + f.name + "
				
			i++;
			}
			
			reader.readAsDataURL(f); 
			
		});
		
		//<a class='catalog_foto_del_new' data-id='catalog_foto_add_new"+i+"' ><img alt=\"$del\" border=\"0\" src=\"../images/delete.gif\"></a>
		// for(x=1;x<10;x++)
		// {
		// a=x;
		
		// $('#catalog_foto_add_new'+a).click(function(){
			// $(this).remove();
			// });	
		// }
	}
	</script>
 
END;

?>
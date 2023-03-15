<?php
$del=_DEL;
$admincontent .= <<<END

<div class="controls">
	<div id="selectedFiles"></div>
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
		
	}
	</script>
 
END;

?>
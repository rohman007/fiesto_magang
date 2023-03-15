<?php
if ($jenis=='single') {
	$sql = 'SELECT id, judul FROM page ORDER BY judul';
	$result = $mysql->query($sql);
	$modulurasi .= "<div id=\"pilihan$k\" class=\"control-group\" style=\"display:$display\"><label class=\"control-label\">"._TITLE."</label>\n";
	$modulurasi .= "<div class=\"controls\">";
	$modulurasi .= "<select name=\"menuconfig[$k]\">\r\n";
	$selected = ($isi==0) ? 'selected' : '';
	while(list($id,$judulhalaman) = $mysql->fetch_row($result)) {
		$selected = ($id==$isi) ? 'selected' : '';
		$modulurasi .= "<option value=\"$id\" $selected>$judulhalaman</option>";
	}
	$modulurasi .= "</select></div></div>";
}

?>

<?php
if ($jenis=='newest' || $jenis=='archive') {
	list($menucatid,$menumaxnews)=explode(';',$isi);
	$sql = 'SELECT id, nama FROM newscat ORDER BY urutan';
	$result = $mysql->query($sql);
	
	$modulurasi .="<div class=\"control-group\" id=\"pilihan$k\" style=\"display:$display\"><label class=\"control-label\">"._CATEGORY."</label>";
	$modulurasi .="<div class=\"controls\">";
	
	$modulurasi .= "<select name=\"menuconfig[$k]\">\r\n";
	$selected = ($menucatid==0) ? 'selected' : '';
	$modulurasi .= "<option value=\"0\" $selected>"._ALL."</option>\r\n";
	if($result and $mysql->num_rows($result)>0)
	{
	while(list($id,$nama) = $mysql->fetch_row($result)) {
		$selected = ($id==$menucatid) ? 'selected' : '';
		$modulurasi .= "<option value=\"$id\" $selected>$nama</option>\r\n";
	}
	}
	$modulurasi .= '</select></div></div>';
	
}
?>

<?php
if ($jenis=='category' || $jenis=='singletree') {
	$modulurasi .="<div class=\"control-group\" id=\"pilihan$k\" style=\"display:$display\"><label class=\"control-label\">"._CATEGORY."</label>";
	$modulurasi .="<div class=\"controls\">";
	$modulurasi .= "<select name=\"menuconfig[$k]\">\n";
	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama, parent FROM catalogcat';
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);
	mysql_free_result($result);

	for ($i=0; $i<count($cats->cats); $i++) {
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		$cat_name = $cats->cats[$i]['nama'];
		$modulurasi .= '<option value="'.$cats->cats[$i]['id'].'"';
		$modulurasi .= ($cats->cats[$i]['id']==$isi) ? " selected>" : ">";
		for ($a=0; $a<count($cats->cat_map); $a++) {
			$cat_parent_id = $cats->cat_map[$a]['id'];
			$cat_parent_name = $cats->cat_map[$a]['nama'];
			$modulurasi .= ($a==0) ? "$cat_parent_name" : " / $cat_parent_name";
		}
		$modulurasi .= ($a==0) ? "$cat_name</option>" : " / $cat_name</option>";
	}
	$modulurasi .= '</select></div></div>';
}

/*
26/02/2010 matikan agar tidak terlalu kompleks. dari pengalaman juga gak perlu blas.

if ($jenis=='single') {
	$modulurasi .= "<tr id=\"pilihan$k\" style=\"display:$display\"><td align=\"right\">"._PRODUCT."</td>\n";
	$modulurasi .= "<td><select name=\"menuconfig[$k]\">\n";
	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, title FROM catalogdata ORDER BY title';
	$result = mysql_query($sql);
	while(list($id, $namaproduk) = mysql_fetch_row($result)) {
		$modulurasi .= "<option value=\"$id\"";
		$modulurasi .= ($id==$isi) ? " selected>" : ">";
		$modulurasi .= "$namaproduk</option>\r\n";
	}
	$modulurasi .= '</select></td></tr>';
}
*/
?>

<?php
if ($jenis=='category') {
	$modulurasi .= "<div id=\"pilihan$k\" class=\"control-group\" style=\"display:$display\"><label class=\"control-label\">"._CATEGORY."</label>\n";
	$modulurasi .= "<div class=\"controls\"><select name=\"menuconfig[$k]\">\n";
	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama, parent FROM gallerycat ';
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);
	$mysql->free_result($result);
	$modulurasi .="<option value='0'>/</option>";
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
	$sql = 'SELECT id, title FROM gallerydata ORDER BY title';
	$result = $mysql->query($sql);
	while(list($id, $namaproduk) = $mysql->fetch_row($result)) {
		$modulurasi .= "<option value=\"$id\"";
		$modulurasi .= ($id==$isi) ? " selected>" : ">";
		$modulurasi .= "$namaproduk</option>\r\n";
	}
	$modulurasi .= '</select></td></tr>';
}
*/
?>

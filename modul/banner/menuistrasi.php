<?php
if ($jenis=='list') {
	$modulurasi .= "<div id=\"pilihan$k\" class=\"control-group\" style=\"display:$display\"><label class=\"control-label\">"._CATEGORY."</label>\n";
	$modulurasi .= "<div class=\"controls\"><select name=\"menuconfig[$k]\">\n";
	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama FROM bannercat';
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama']);
	}
	$cats->get_cats($mycats);
	$mysql->free_result($result);

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
?>

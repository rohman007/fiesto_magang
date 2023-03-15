<?php

if (substr($jenis, 0, 8) == 'category') {
    //default value harus ada!!! default: ""
    $default_value ="0";
    $sql = 'SELECT id FROM gallerycat ORDER BY urutan ASC LIMIT 1';
	$result = $mysql->query($sql);
	if($result and $mysql->num_rows($result)>0)
	{
		while (list($id) = $mysql->fetch_row($result)) {
			$default_value = $id;
		}
	}
    //default value harus ada!!! default: ""

    $modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">" . _CATEGORY . "</label>\n";
    $modulurasi .= "<div class=\"controls\"><select name=\"homeconfig[]\">\n";
    $cats = new categories();
    $mycats = array();
    $sql = 'SELECT id, nama, parent FROM gallerycat';
    $result = $mysql->query($sql);
    while ($row = $mysql->fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'level' => 0);
    }
    $cats->get_cats($mycats);
    $mysql->free_result($result);
	$modulurasi .= '<option value="0">/</a>';
    for ($i = 0; $i < count($cats->cats); $i++) {
        $cats->cat_map($cats->cats[$i]['id'], $mycats);
        $cat_name = $cats->cats[$i]['nama'];
        $modulurasi .= '<option value="' . $cats->cats[$i]['id'] . '"';
        $modulurasi .= ($cats->cats[$i]['id'] == $isi) ? " selected>" : ">";
        for ($a = 0; $a < count($cats->cat_map); $a++) {
            $cat_parent_id = $cats->cat_map[$a]['id'];
            $cat_parent_name = $cats->cat_map[$a]['nama'];
            $modulurasi .= ($a == 0) ? "$cat_parent_name" : " / $cat_parent_name";
        }
        $modulurasi .= ($a == 0) ? "$cat_name</option>" : " / $cat_name</option>";
    }
    $modulurasi .= '</select></div></div>';
}
?>

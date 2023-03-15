<?php
if ($jenis=='category') {
    //default value harus ada!!! default: ""
    $default_value = "";
    $sql = 'SELECT id FROM newscat ORDER BY urutan ASC LIMIT 1';
    $result = $mysql->query($sql);
    while (list($id) = $mysql->fetch_row($result)) {
        $default_value = $id;
    }
    //default value harus ada!!! default: ""
	list($homecatid,$homemaxnews)=explode(';',$isi);
	$sql = 'SELECT id, nama FROM newscat ORDER BY urutan';
	$result = $mysql->query($sql);
	$modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">"._CATEGORY."</label>\r\n";
	$modulurasi .= "<div class=\"controls\"><select name=\"homeconfig[]\">\r\n";
	$selected = ($homecatid==0) ? 'selected' : '';
	$modulurasi .= "<option value=\"0\" $selected>"._ALL."</option>";
	
	while(list($id,$nama) = $mysql->fetch_row($result)) {
		$selected = ($id==$homecatid) ? 'selected' : '';
		$modulurasi .= "<option value=\"$id\" $selected>$nama</option>";
	}
	$modulurasi .= '</select></div></div>';
	$modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">"._MAXDISPLAYED."</label>\r\n";
	$modulurasi .= "<div class=\"controls\"><input type=\"text\" size=\"2\" name=\"homeconfig[]\" value=\"".$homemaxnews."\"></div></div>\r\n";
}
if ($jenis == 'newcategory') {
    //default value harus ada!!! default: ""
    $default_value = "";
    $sql = 'SELECT id FROM newscat ORDER BY urutan ASC LIMIT 1';
    $result = $mysql->query($sql);
    while (list($id) = $mysql->fetch_row($result)) {
        $default_value = $id;
    }
    //default value harus ada!!! default: ""
	list($homemaxnews)=explode(';',$isi);
	$sql = 'SELECT id, nama FROM newscat ORDER BY urutan';
	$result = $mysql->query($sql);
	
	$modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">"._MAXDISPLAYED."</label>\r\n";
	$modulurasi .= "<div class=\"controls\"><input type=\"text\" size=\"2\" name=\"homeconfig[]\" value=\"".$homemaxnews."\"></div></div>\r\n";
}
?>
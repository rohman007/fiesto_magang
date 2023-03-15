<?php
include ("../../kelola/urasi.php");
include ("../../kelola/fungsi.php");
include ("urasi.php");

//untuk menyiapkan data katalog kategori yang akan ditampilkan ke blackberry
/*$sql = "SELECT nama from catalogcat"; 
$result=$mysql->query($sql);
$i=0;*/

echo '|||';

$cats = new categories();
$mycats = array();
$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
$result = $mysql->query($sql);
while($row = $mysql->fetch_array($result)) {
	$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
}
$cats->get_cats($mycats);
for ($i=0; $i<count($cats->cats); $i++) {
	$sql_cek = "SELECT * FROM catalogcat WHERE parent='".$cats->cats[$i]['id']."'";
	$query_cek = $mysql->query($sql_cek);
	if ($mysql->num_rows($query_cek)==0){
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		$cat_name = $cats->cats[$i]['nama'];
		$catcontent .= $cats->cats[$i]['id'] . '|';
		for ($a=0; $a<count($cats->cat_map); $a++) {
			$cat_parent_id = $cats->cat_map[$a]['id'];
			$cat_parent_name = $cats->cat_map[$a]['nama'];
			$catcontent .= "/.";
		}
		$catcontent .= "/$cat_name";
		if ($i<count($cats->cats)-1) $catcontent .= "||";
	}
}
echo $catcontent;

?>

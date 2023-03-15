<?php
if (substr($jenis,0,8)=='featured') 
{
	$default_value = "isbest:1;ispromo:1;isnew:1;issold";
	$sc=array(_ISBEST=>"isbest",_ISPROMO=>"ispromo",_ISNEW=>"isnew",_ISSOLD=>"issold");
	if($action=='edit')
	{
	$q=$mysql->query("select isi from home where id=$pid");
	list($isi)=$mysql->fetch_array($q);
	$r_isi=explode(";",$isi);
		foreach($r_isi as $val)
		{	
			$t=explode(":",$val);
			$hasil[$t[0]]=$t[1];
		}
	}

foreach($sc as $label => $tname)
	{
	list($name,$default)=explode(":",$tname);
	if($action=='edit')
	{
	$checked=$hasil[$name]==1?"checked='checked'":"";
	}
	else
	{
	$checked=$default==1?"checked='checked'":"";
	}
	$modulurasi .= "<div class=\"control-group\" style=\"display:inline-block\"><label class=\"control-label\">".$label."</label><div class=\"controls\">";
	$modulurasi .= "<input type='checkbox' name='homeconfig[]' value='$name:1' $checked /> ";
	$modulurasi .= "</div></div>";
	}

	
}

if (substr($jenis,0,8)=='category') {

// echo "<div id='catalog_cat'><select name=\"cat_id\" onchange='tambah_cat(this.value)'>\n";
// $cats = new categories();
// $mycats = array();
// $sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
// $result = $mysql->query($sql);
// while($row = $mysql->fetch_array($result)) {
	// $mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
// }
// $cats->get_cats($mycats);
// echo "<option value=\"\" >"._PILIHCATEGORY."</option>\r\n";
// for ($i=0; $i<count($cats->cats); $i++) {
	// //cek apakah ada sub cat dalam cat
	// $sql_cek = "SELECT * FROM catalogcat WHERE parent='".$cats->cats[$i]['id']."'";
	// $query_cek = $mysql->query($sql_cek);
	// if ($mysql->num_rows($query_cek)==0){
		// $cats->cat_map($cats->cats[$i]['id'],$mycats);
		// $cat_name = $cats->cats[$i]['nama'];
		// echo '<option value="'.$cats->cats[$i]['id'].'"';
		// echo ($cats->cats[$i]['id']==$cat_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
		// for ($a=0; $a<count($cats->cat_map); $a++) {
			// $cat_parent_id = $cats->cat_map[$a]['id'];
			// $cat_parent_name = $cats->cat_map[$a]['nama'];
			// echo " / $cat_parent_name";
		// }
		// echo " / $cat_name</option>";
	// }
	
// }
// echo "<option  class=\"tambah_cat\" value='do_tambah_cat' >"._TAMBAHKATEGORI."</option>\r\n";
// echo "</select></div>\r\n";

			$modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">"._CATEGORY."</label>\n";
			$modulurasi .= "<div class=\"controls\"><select name=\"homeconfig[]\">\n";
			$cats = new categories();
			$mycats = array();
			$sql = 'SELECT id, nama, parent FROM catalogcat';
			$result = $mysql->query($sql);
			while($row = $mysql->fetch_array($result)) {
				$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
			}
			$cats->get_cats($mycats);
			$mysql->free_result($result);

			for ($i=0; $i<count($cats->cats); $i++) {
				$cats->cat_map($cats->cats[$i]['id'],$mycats);
				$cat_name = $cats->cats[$i]['nama'];
				$sql_cek = "SELECT * FROM catalogcat WHERE parent='".$cats->cats[$i]['id']."'";
				$query_cek = $mysql->query($sql_cek);
				if ($mysql->num_rows($query_cek)==0)
				{
					//default value untuk pertama dipanggil ajax
					if($default_value == "")
					{
					$default_value=$cats->cats[$i]['id'];
					}
					//default value untuk pertama
				$modulurasi .= '<option value="'.$cats->cats[$i]['id'].'"';
				$modulurasi .= ($cats->cats[$i]['id']==$isi) ? " selected>" : ">";
				for ($a=0; $a<count($cats->cat_map); $a++) {
					$cat_parent_id = $cats->cat_map[$a]['id'];
					$cat_parent_name = $cats->cat_map[$a]['nama'];
					$modulurasi .= ($a==0) ? "$cat_parent_name" : " / $cat_parent_name";
				}
				$modulurasi .= ($a==0) ? "$cat_name</option>" : " / $cat_name</option>";
				}
			}
			$modulurasi .= '</select></div></div>';
}
?>
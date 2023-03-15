<?php
session_start();
include "../../kelola/urasi.php";
include "../../kelola/fungsi.php";
include "urasi.php";
include "fungsi.php";
include "lang/id/definisi.php";


$action=$_GET['action'];
if($action=="batal_cat")
{
$dcat_id=$_GET['dcat_id'];
echo "<select name=\"cat_id\" onchange=\"tambah_cat(this.value,$dcat_id)\">\n";
		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent FROM gallerycat ORDER BY urutan ';
		$result = $mysql->query($sql);
		while($row = $mysql->fetch_array($result)) {
			$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
		}
		$cats->get_cats($mycats);
		echo "<option value=\"0\" >"._TOP."</option>\r\n";
		for ($i=0; $i<count($cats->cats); $i++) {
			//cek apakah ada sub cat dalam cat
			$sql_cek = "SELECT * FROM gallerycat WHERE parent='".$cats->cats[$i]['id']."'";
			$query_cek = $mysql->query($sql_cek);
			if ($mysql->num_rows($query_cek)==0){
				$cats->cat_map($cats->cats[$i]['id'],$mycats);
				$cat_name = $cats->cats[$i]['nama'];
				$selected=$dcat_id==$cats->cats[$i]['id']?"selected='selected'":"";
				echo '<option '.$selected.' value="'.$cats->cats[$i]['id'].'"';
			
				echo ($cats->cats[$i]['id']==$cat_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
				for ($a=0; $a<count($cats->cat_map); $a++) {
					$cat_parent_id = $cats->cat_map[$a]['id'];
					$cat_parent_name = $cats->cat_map[$a]['nama'];
					echo " / $cat_parent_name";
				}
				echo " / $cat_name</option>";
			}
			
		}
		echo "<option value='do_tambah_cat' class=\"tambah_cat\"  >(+) tambah kategori</option>\r\n";
		echo "</select>\r\n";
}
if($action=="save_cat")
{
$cat_id=$_GET['cat_id'];
$text_cat=$_GET['text_cat'];
$newid=last_auto_increment('gallerycat');
$s=$mysql->query("INSERT INTO gallerycat(id,parent,nama,urutan) values($newid,$cat_id,'$text_cat',0)");
/////////

echo "<select name=\"cat_id\" onchange=\"tambah_cat(this.value,$newid)\">\n";
		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent FROM gallerycat ORDER BY urutan ';
		$result = $mysql->query($sql);
		while($row = $mysql->fetch_array($result)) {
			$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
		}
		$cats->get_cats($mycats);
	
		echo "<option value=\"0\" >"._TOP."</option>\r\n";
		for ($i=0; $i<count($cats->cats); $i++) {
			//cek apakah ada sub cat dalam cat
			$sql_cek = "SELECT * FROM gallerycat WHERE parent='".$cats->cats[$i]['id']."'";
			$query_cek = $mysql->query($sql_cek);
			if ($mysql->num_rows($query_cek)==0){
				
				$cats->cat_map($cats->cats[$i]['id'],$mycats);
				$cat_name = $cats->cats[$i]['nama'];
				$selected=$newid==$cats->cats[$i]['id']?"selected='selected'":"";
				echo '<option '. $selected .' value="'.$cats->cats[$i]['id'].'"';
				echo ($cats->cats[$i]['id']==$cat_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
				for ($a=0; $a<count($cats->cat_map); $a++) {
					$cat_parent_id = $cats->cat_map[$a]['id'];
					$cat_parent_name = $cats->cat_map[$a]['nama'];
					echo " / $cat_parent_name";
				}
				echo " / $cat_name</option>";
			}
			
		}
		echo "<option  class=\"tambah_cat\"  value='do_tambah_cat'>(+) tambah kategori</option>\r\n";
		echo "</select>\r\n";
///////
}
if($action=="tambah_cat")
{
$dcat_id=$_GET['dcat_id'];
echo "<div><input type='text' name='text_cat' id='text_cat' placeholder='ketik kategori baru' /></div>";
echo "<div>"._SUBPARENT."</div>";
echo "<div>";
echo "<select name=\"cat_id\" id=\"cat_id\">\n";
		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent FROM gallerycat ORDER BY urutan ';
		$result = $mysql->query($sql);
		while($row = $mysql->fetch_array($result)) {
			$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
		}
		$cats->get_cats($mycats);
		
		echo "<option value=\"0\" >"._TOP."</option>\r\n";
		for ($i=0; $i<count($cats->cats); $i++) {
		
				$cats->cat_map($cats->cats[$i]['id'],$mycats);
				$cat_name = $cats->cats[$i]['nama'];
				echo '<option value="'.$cats->cats[$i]['id'].'"';
				echo ($cats->cats[$i]['id']==$cat_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
				for ($a=0; $a<count($cats->cat_map); $a++) {
					$cat_parent_id = $cats->cat_map[$a]['id'];
					$cat_parent_name = $cats->cat_map[$a]['nama'];
					echo " / $cat_parent_name";
				}
				echo " / $cat_name</option>";
			
			
		}
		echo "</select></div>\r\n";
		
echo "</div>";
echo "<div><input type='button' name='save_cat' id='save_cat' class='buton' value='save' /><input type='button' class='buton' name='batal' id='batal_cat' value='batal' />";
echo <<<END
<script>
$('#save_cat').click(function(){
	cat_id=$('#cat_id').val();
	text_cat=$('#text_cat').val();
	$.ajax({                                      
      url: '../modul/gallery/ajax.php',                  //the script to call to get data          
      data:"action=save_cat&cat_id="+cat_id+"&text_cat="+text_cat,      
	  success: function(data)
      {
		$("#gallery_cat").html(data);
      } 
    });
	});	
$('#batal_cat').click(function(){
	$.ajax({                                      
      url: '../modul/gallery/ajax.php',                  //the script to call to get data          
      data:"action=batal_cat&dcat_id=$dcat_id",      
	  success: function(data)
      {
		$("#gallery_cat").html(data);
      } 
    });
	});		
</script>
END;
echo "</div>";
}

if($action=='refresh_kategori')
{
echo catstructure('SELECT id, nama, parent FROM gallerycat order by urutan');
}
if($action=='changestatusall')
{
	$sc=array("publish","isbest","ispromo","isnew","issold");
	$type=$_GET['type'];
	$checked=$_GET['checked'];
	$value=$_GET['value'];
	if(in_array($type,$sc) and $value!='')
	{
	$rvalue=explode(",",$value);
	$joinv="'".join("','",$rvalue)."'";
	$sql="UPDATE gallerydata SET  $type='$checked' WHERE id IN ($joinv)";
	$r=$mysql->query($sql);if($r){echo true;}else{echo false;}
	}
	
}


?>
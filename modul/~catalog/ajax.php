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
echo "<select name=\"cat_id\" onchange=\"tambah_cat(this.value)\">\n";
		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) {
			$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
		}
		$cats->get_cats($mycats);
		echo "<option value=\"\" >"._PILIHCATEGORY."</option>\r\n";
		for ($i=0; $i<count($cats->cats); $i++) {
			//cek apakah ada sub cat dalam cat
			$sql_cek = "SELECT * FROM catalogcat WHERE parent='".$cats->cats[$i]['id']."'";
			$query_cek = mysql_query($sql_cek);
			if (mysql_num_rows($query_cek)==0){
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
			
		}
		echo "<option value='do_tambah_cat' class=\"tambah_cat\"  >(+) tambah kategori</option>\r\n";
		echo "</select>\r\n";
}
if($action=="save_cat")
{
$cat_id=$_GET['cat_id'];
$text_cat=$_GET['text_cat'];

$s=mysql_query("INSERT INTO catalogcat(parent,nama,urutan) values($cat_id,'$text_cat',0)");
/////////
echo "<select name=\"cat_id\" onchange=\"tambah_cat(this.value)\">\n";
		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) {
			$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
		}
		$cats->get_cats($mycats);
		echo "<option value=\"\" >"._PILIHCATEGORY."</option>\r\n";
		for ($i=0; $i<count($cats->cats); $i++) {
			//cek apakah ada sub cat dalam cat
			$sql_cek = "SELECT * FROM catalogcat WHERE parent='".$cats->cats[$i]['id']."'";
			$query_cek = mysql_query($sql_cek);
			if (mysql_num_rows($query_cek)==0){
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
			
		}
		echo "<option  class=\"tambah_cat\"  value='do_tambah_cat'>(+) tambah kategori</option>\r\n";
		echo "</select>\r\n";
///////
}
if($action=="tambah_cat")
{
echo "<div><input type='text' name='text_cat' id='text_cat' placeholder='ketik kategori baru' /></div>";
echo "<div>"._SUBPARENT."</div>";
echo "<div>";
echo "<select name=\"cat_id\" id=\"cat_id\">\n";
		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) {
			$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
		}
		$cats->get_cats($mycats);
		
		echo "<option value=\"0\" >"._TOP."</option>\r\n";
		for ($i=0; $i<count($cats->cats); $i++) {
			//cek apakah ada sub cat dalam cat
		//	$sql_cek = "SELECT * FROM catalogcat WHERE parent='".$cats->cats[$i]['id']."'";
		//	$query_cek = mysql_query($sql_cek);
			//if (mysql_num_rows($query_cek)==0){
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
			//}
			
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
      url: '../modul/catalog/ajax.php',                  //the script to call to get data          
      data:"action=save_cat&cat_id="+cat_id+"&text_cat="+text_cat,      
	  success: function(data)
      {
		$("#catalog_cat").html(data);
      } 
    });
	});	
$('#batal_cat').click(function(){
	$.ajax({                                      
      url: '../modul/catalog/ajax.php',                  //the script to call to get data          
      data:"action=batal_cat",      
	  success: function(data)
      {
		$("#catalog_cat").html(data);
      } 
    });
	});		
</script>
END;
echo "</div>";
}
if($action=="batal_brand")
{
$result = mysql_query("SELECT id,nama FROM catalogmerek ORDER BY nama ");
echo '<select name="merek" onchange="tambah_brand(this.value)">';
	echo "<option value=\"\" >"._NOBRAND."</option>\r\n";
	while(list($idmerek,$namamerek) = mysql_fetch_row($result)) {
		$selected = ($brand==$namamerek) ? 'selected' : '' ;
		echo "<option value=\"$idmerek\" $selected>$namamerek</option>\r\n";
	}
	echo "<option class=\"tambah_brand\" value=\"do_tambah_brand\" >(+) tambah merek</option>\r\n";
	echo"</select>";
}
if($action=="tambah_brand")
{
$brand=fiestolaundry($_GET['brand'],200);
$s=mysql_query("INSERT INTO catalogmerek(nama) values('$brand')");
echo '<select name="merek" onchange="tambah_brand(this.value)">';
	$result = mysql_query("SELECT id,nama FROM catalogmerek ORDER BY nama ");
	echo "<option value=\"\" >"._NOBRAND."</option>\r\n";
	while(list($idmerek,$namamerek) = mysql_fetch_row($result)) {
		$selected = ($brand==$namamerek) ? 'selected' : '' ;
		echo "<option value=\"$idmerek\" $selected>$namamerek</option>\r\n";
	}
	echo "<option class=\"tambah_brand\" value=\"do_tambah_brand\" >(+) tambah merek</option>\r\n";
	echo"</select>";
}
if($action=="hapusthumb")
{

	$berhasil=1;
	$img=$_GET['img'];
	$pid=$_GET['pid'];
	if($img!='')
	{
	
	if(file_exists("$cfg_fullsizepics_path/$img"))
	{
		if(unlink("$cfg_fullsizepics_path/$img"))
		{
			//$berhasil=1;
		}
		
	}
	
	if(file_exists("$cfg_thumb_path/$img"))
	{
		if(unlink("$cfg_thumb_path/$img"))
		{
		//$berhasil=1;
		}
	}
	
	}
	
	if($berhasil)
	{
	//ambil data thumb awal
	$thumb_awalr=array();
	$s=mysql_query("SELECT thumb from catalogdata WHERE id='$pid' AND thumb<>''");
	if($s and mysql_num_rows($s)>0)
	{
	list($thumb_awal)=mysql_fetch_row($s);
	$thumb_awalr=explode(":",$thumb_awal);
	}
	$filter=array();
	foreach($thumb_awalr as $i => $v)
	{
	
	if($v!=$img)
	{
	$filter[]=$v;
	}
	}
	$join_filter=join(":",$filter);
	$sql =	"UPDATE catalogdata SET thumb='$join_filter' WHERE id='$pid'";
	if (mysql_query($sql))
	{
	echo "berhasil";
	}
	
	//end ambil data thumb awal
	}
}
if($action=='update_parent')
{

$list=$_POST['list'];

	$urutan=1;
	print_r($list);
	foreach($list as $cat_id =>$parent)
	{
	
		if($parent!='' and $parent!='null')
		{
		$s=mysql_query("UPDATE catalogcat SET parent='$parent',urutan=$urutan WHERE id='$cat_id'");
		}
		else
		{
		$s=mysql_query("UPDATE catalogcat SET parent='0',urutan=$urutan WHERE id='$cat_id'");
		}
	$urutan++;	
	}

}

if($action=='refresh_kategori')
{
echo catstructure('SELECT id, nama, parent FROM catalogcat order by urutan');
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
	$sql="UPDATE catalogdata SET  $type='$checked' WHERE id IN ($joinv)";
	$r=mysql_query($sql);if($r){echo true;}else{echo false;}
	}
	
}
if($action=='changestatus')
{
	$sc=array("publish","isbest","ispromo","isnew","issold");
	$type=$_GET['type'];
	$value=$_GET['value'];
	$pid=$_GET['pid'];
	if(in_array($type,$sc) AND $pid!='')
	{
	$sql="UPDATE catalogdata SET $type='$value' WHERE id='$pid'";
	$r=mysql_query($sql);if($r){echo true;}else{echo false;}
	}
}
if($action=='updatenamaproduk')
{
$pid=fiestolaundry($_POST['pk'],20);
$namaproduk=fiestolaundry($_POST['value'],255);
$sql="UPDATE catalogdata SET title='$namaproduk' WHERE id='$pid'";
$r=mysql_query($sql);if($r){echo true;}else{echo false;}
}
if($action=='updatehargaproduk')
{
$pid=fiestolaundry($_POST['pk'],20);
$harga=str_replace(".","",fiestolaundry($_POST['value'],15));
$sql="UPDATE catalogdata SET harganormal='$harga' WHERE id='$pid'";
$r=mysql_query($sql);if($r){echo true;}else{echo false;}
}
if($action=='updatediskonproduk')
{
$pid=fiestolaundry($_POST['pk'],20);
$diskon=str_replace(".","",fiestolaundry($_POST['value'],15));
$sql="UPDATE catalogdata SET diskon='$diskon' WHERE id='$pid'";
$r=mysql_query($sql);if($r){echo true;}else{echo false;}
}

// ob_start();
// echo "POST <BR>";
// print_r($_POST);
// echo "GET <BR>";
// print_r($_GET);
// $string=ob_get_clean();
    
// $handle = fopen("../../logs/testing.txt",'a');
// fwrite($handle, $string);
// fclose($handle);

if ($action == 'param_size') {
	$size = fiestolaundry($_GET['size'], 30);
	$pid = fiestolaundry($_GET['pid'], 11);
	
	$html = '';
	$sql = "SELECT id, size, color, price FROM catalogattribut WHERE catalog_id='$pid' AND size='$size' ORDER BY urutan";
	if ($result = $mysqli->query($sql)) {
		if ($result->num_rows > 0) {
			
			$sql_catalog = "SELECT harganormal, if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
					as nilaidiskon,
					harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
						diskon)) as hargadiskon FROM catalogdata WHERE id='$pid'";
			$result_catalog = $mysqli->query($sql_catalog);
			list($harganormal, $nilaidiskon, $hargadiskon) = $result_catalog->fetch_row();
			
			$html .= '';
			while($row = $result->fetch_assoc()) {
				$amount = $hargadiskon+$row['price'];
				$amount = number_format($amount, 0, ',', '.');
				$html .= "<option value=\"".$row['id']."|{$row['color']}|$amount|{$row['price']}|{$row['size']}\">".$row['color']." (+".number_format($row['price'], 0, ',', '.').")</option>";
			}
		} else {
			$html .= "Data tidak ada";
		}
	} else {
		$html .= "Error: " . $mysql->error;
	}
	
	echo $html;
}

?>
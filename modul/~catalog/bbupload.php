<?php
include ("../../kelola/urasi.php");
include ("../../kelola/fungsi.php");
include ("urasi.php");

//upload dulu sebelum insert record
$hasilupload = fiestoupload('upload_field',$cfg_fullsizepics_path,'',$maxfilesize,$allowedtypes="gif,jpg,jpeg,png");

echo "upload berhasil";

//ambil informasi basename dan extension
$temp = explode(".",$_FILES['upload_field']['name']);
$extension = $temp[count($temp)-1];
$basename = '';
for ($i=0;$i<count($temp)-1;$i++) {
	$basename .= $temp[$i];
}
/*$pecah =  explode("/",$_POST['katalog']);
//$pecah =  explode("/","/Mobile/bekas");

if( count($pecah)==2){
	$katpilih = $pecah[1];
	$sqlid = "select id from catalogcat where nama='$katpilih'";
	$queryid = mysql_query($sqlid);
	$row=mysql_fetch_array($queryid);
	$idparent= $row['id'];
}
else if(count($pecah)>2){
	$katparent = $pecah[1];
	
	$sqlidparent = "select id from catalogcat where nama='$katparent'";
	$queryidparent = mysql_query($sqlidparent);
	
	$row=mysql_fetch_array($queryidparent);
	$idparent = $row['id'];
	
	for($i=2;$i<count($pecah);$i++){	
		$katchild = $pecah[$i];
		
		
		$sqlidchild = "select id from catalogcat where nama='$katchild' and parent='$idparent'";
		$queryidchild = mysql_query($sqlidchild);
		$rowchild=mysql_fetch_array($queryidchild);
		$idchild= $rowchild['id'];
		
		$idparent=$idchild;
		
	}

*/

$idparent = intval($_POST['katalog']);

//jika berhasil, baru add record
$sql =	"INSERT INTO catalogdata (cat_id, date, title, publish, idmerek, harganormal, ketsingkat) 
		VALUES ('".$idparent."', NOW(), '".$_POST['judul']."', '1', '0',".$_POST['harga'].",'".$_POST['ketsingkat']."')";

echo $sql;
		
if (mysql_query($sql)) {
	$newid = mysql_insert_id();
	$modifiedfilename = "$basename-$newid.$extension";
	echo "insert data success. ";
	$sql =	"UPDATE catalogdata SET filename='$modifiedfilename' WHERE id='$newid'";
	if (mysql_query($sql)) 
		echo "update filename success. ";

} else {
	pesan(_ERROR,_DBERROR);
}

list($filewidth,$fileheight,$filetype,$fileattr) = getimagesize("$cfg_fullsizepics_path/".$_FILES['upload_field']['name']);

//create thumbnail
$hasilresize = fiestoresize("$cfg_fullsizepics_path/".$_FILES['upload_field']['name'],"$cfg_thumb_path/$modifiedfilename",'l',$cfg_thumb_width);

if ($filewidth > $cfg_max_width) {
	//rename sambil resize gambar asli sesuai yang diizinkan
	$hasilresize = fiestoresize("$cfg_fullsizepics_path/".$_FILES['upload_field']['name'],"$cfg_fullsizepics_path/$modifiedfilename",'w',$cfg_max_width);
	//del gambar asli
	unlink("$cfg_fullsizepics_path/".$_FILES['upload_field']['name']);
} else {
	//create thumbnail
	rename("$cfg_fullsizepics_path/".$_FILES['upload_field']['name'],"$cfg_fullsizepics_path/$modifiedfilename");
}

echo "resize berhasil";

?>
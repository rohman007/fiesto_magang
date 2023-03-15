<?php
session_start();
include "../../kelola/urasi.php";
include "../../kelola/fungsi.php";
include "urasi.php";
include "fungsi.php";
include "lang/id/definisi.php";

$action=$_GET['action'];

if($action=='update_parent')
{

	$list=$_POST['list'];
	$urutan=1;
	$kondisiprev = '';
	$kondisi = '';
	foreach($list as $cat_id =>$parent)
	{
	
		if($parent!='' and $parent!='null')
		{
		    $result = urutkan('newscat', $urutan, $kondisi, $cat_id, $kondisiprev);
            if ($kondisi != $kondisiprev) {
                urutkansetelahhapus('newscat', $kondisiprev);
            }
		}
		else
		{
		    $result = urutkan('newscat', $urutan, $kondisi, $cat_id, $kondisiprev);
            if ($kondisi != $kondisiprev) {
                urutkansetelahhapus('newscat', $kondisiprev);
            }		
		}
	$urutan++;	
	}

}

if($action=='refresh_kategori')
{
	echo catstructure('SELECT id, nama FROM newscat order by urutan');
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
	$s=$mysql->query("SELECT thumb from newsdata WHERE id='$pid' AND thumb<>''");
	if($s and $mysql->num_rows($s)>0)
	{
	list($thumb_awal)=$s->fetch_row();
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
	$sql =	"UPDATE newsdata SET thumb='$join_filter' WHERE id='$pid'";
	if ($mysql->query($sql))
	{
	echo "berhasil";
	}
	
	//end ambil data thumb awal
	}
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

?>
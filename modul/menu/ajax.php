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
	foreach($list as $cat_id =>$parent)
	{
	
		if($parent!='' and $parent!='null')
		{
		$s=$mysql->query("UPDATE menu SET parent='$parent',urutan=$urutan WHERE id='$cat_id'");
		}
		else
		{
		$s=$mysql->query("UPDATE menu SET parent='0',urutan=$urutan WHERE id='$cat_id'");
		}
	$urutan++;	
	}

}

if($action=='refresh_kategori')
{
echo catstructure('SELECT id, judul, parent,type FROM menu ORDER BY urutan');
}

?>
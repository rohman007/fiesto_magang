<?php
if($action=='' or $action=='latest')
{	$urlfunc->setParts('p','aksi','cat_id') ;
	$data1 = explode($id_separator,$cat_id);
	$cat_id = $data1[0];
	$_GET['cat_id'] = $cat_id;
}	
if ($action=='archive') 
{	$jumlah_bagian = count($query_parts);
	if($jumlah_bagian==3)
	{	$urlfunc->setParts('p','aksi','cat_id') ;
		$data1 = explode($id_separator,$cat_id);
		$cat_id = $data1[0];
	}
	if($jumlah_bagian==4)
	{	$urlfunc->setParts('p','aksi','tahun', 'bulan') ;
	}
	if($jumlah_bagian==5)
	{	$urlfunc->setParts('p','aksi','cat_id','tahun', 'bulan') ;
		$data1 = explode($id_separator,$cat_id);
		$cat_id = $data1[0];
	}
	
	$_GET['tahun'] = $tahun;
	$_GET['bulan'] = $bulan;
	$_GET['cat_id'] = $cat_id;
}
if ($action=='shownews') 
{	$urlfunc->setParts('p','aksi','pid') ;
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	$_GET['pid'] = $pid;
	$_REQUEST['pid'] = $pid;

}
if ($action=='search') 
{	$urlfunc->setParts('p','aksi','keyword', 'screen') ;
	$_GET['keyword'] = $keyword;
	$_GET['screen'] = $screen;
}
?>
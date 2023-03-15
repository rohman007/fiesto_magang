<?php
if ($action=='archive') 
{	$urlfunc->setParts('p','aksi','tahun', 'bulan') ;
	$_GET['tahun'] = $tahun;
	$_GET['bulan'] = $bulan;
}
if ($action=='view') 
{	$urlfunc->setParts('p','aksi','pid') ;
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	$_GET['pid'] = $pid;
}
?>
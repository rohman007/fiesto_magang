<?php
if ($action=='removeproduct') 
{	$urlfunc->setParts('p','aksi','pid') ;
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	$_REQUEST['pid'] = $pid;
}
if ($action=='checkout') 
{	$urlfunc->setParts('p','aksi','do') ;
	$data = explode($id_separator,$do);
	$do = $data[0];
	$_GET['do'] = $do;
}
?>

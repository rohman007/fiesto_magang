<?php
if ($action=='view') 
{	$urlfunc->setParts('p','aksi','pid') ;
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	$_GET['pid'] = $pid;
	$_REQUEST['pid'] = $pid;
}
?>
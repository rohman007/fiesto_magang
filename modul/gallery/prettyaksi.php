<?php
if ($action=='search') 
{	$urlfunc->setParts('p','aksi','keyword', 'screen') ;
	$_GET['keyword'] = $keyword;
	$_GET['screen'] = $screen;
}	
if ($action=='images')
{	$urlfunc->setParts('p','aksi','cat_id', 'screen') ;
	$data1 = explode($id_separator,$cat_id);
	$cat_id = $data1[0];
	$_GET['cat_id'] = $cat_id;
	$_GET['screen'] = $screen;
}
if ($action=='viewsearch') 
{	$urlfunc->setParts('p','aksi','pid','keyword') ;
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	$_GET['pid'] = $pid;
	$_GET['keyword'] = $keyword;
}
if ($action=='viewimages') 
{	$urlfunc->setParts('p','aksi','pid','cat_id') ;
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	
	$data1 = explode($id_separator,$cat_id);
	$cat_id = $data1[0];
	
	$_GET['pid'] = $pid;
	$_GET['cat_id'] = $cat_id;
}
?>
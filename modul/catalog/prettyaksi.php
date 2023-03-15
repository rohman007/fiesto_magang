<?php

if ($action=='images')
{	$urlfunc->setParts('p','aksi','kategori','sb','screen');
	$data = explode($id_separator,$kategori);
	$cat_id = $data[0];
	$_GET['cat_id'] = $cat_id;
	$_GET['sb'] = $sb;
	$_GET['screen'] = $screen;
}
if ($action=='promo' || $action=='new' || $action=='best' || $action=='sale')
{	$urlfunc->setParts('p','aksi','sb','screen');
	$data = explode($id_separator,$kategori);
	$cat_id = $data[0];
	$_GET['sb'] = $sb;
	$_GET['screen'] = $screen;
}
if ($action=='search') 
{	$urlfunc->setParts('p','aksi','keyword','sb','screen') ;
	// if ($_POST['keyword']=='') $_REQUEST['keyword'] = $keyword;
	$_GET['keyword'] = $keyword;
	$_REQUEST['screen'] = $screen;
	$_REQUEST['sb'] = $sb;
}				
if ($action == 'brand')
{	$urlfunc->setParts('p','aksi','merek','sb','screen');
	$data = explode($id_separator,$merek);
	$merek = $data[0];
	$_REQUEST['merek'] = $merek;
	$_GET['sb'] = $sb;
	$_GET['screen'] = $screen;
}
if ($action=='viewsearch') 
{	$urlfunc->setParts('p','aksi','pid','keyword');
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	$_GET['pid'] = $pid;
	$_REQUEST['keyword'] = $keyword;
}
if ($action=='viewimages') 
{	$urlfunc->setParts('p','aksi','pid','cat_id');
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	
	$data1 = explode($id_separator,$cat_id);
	$cat_id = $data1[0];
	
	$_GET['pid'] = $pid;
	$_GET['cat_id'] = $cat_id;
}
if ($action=='detail') 
{	$urlfunc->setParts('p','aksi','pid','cat_id') ;
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	
	$data1 = explode($id_separator,$cat_id);
	$cat_id = $data1[0];
	
	$_GET['pid'] = $pid;
	$_GET['cat_id'] = $cat_id;
}
if ($action=='viewbrand') 
{	$urlfunc->setParts('p','aksi','pid','merek') ;
	$data = explode($id_separator,$pid);
	$pid = $data[0];
	
	$data1 = explode($id_separator,$merek);
	$merek = $data1[0];
	
	$_GET['pid'] = $pid;
	$_GET['merek'] = $merek;
}

?>
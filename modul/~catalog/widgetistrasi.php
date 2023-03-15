<?php
$sc=array(_ISBEST=>"isbest",_ISPROMO=>"ispromo",_ISNEW=>"isnew",_ISSOLD=>"issold");
$default_value = "isbest:1;ispromo:1;isnew:1;issold";
if($action=='edit')
{
$q=mysql_query("select isi from widget where id=$pid");
list($isi)=mysql_fetch_array($q);
$r_isi=explode(";",$isi);
	foreach($r_isi as $val)
	{	
		$t=explode(":",$val);
		$hasil[$t[0]]=$t[1];
	}
}
if (substr($jenis,0,8)=='featured') 
{
foreach($sc as $label => $tname)
	{
	list($name,$default)=explode(":",$tname);
	if($action=='edit')
	{
	$checked=$hasil[$name]==1?"checked='checked'":"";
	}
	else
	{
	$checked=$default==1?"checked='checked'":"";
	}
	//$modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">".$label."</label><div class=\"controls\">";
	$modulurasi .= "<div class=\"ceki\"><span class=\"labelprodukwidget\">".$label."</span><input type='checkbox' class=\"widgetproduk\" name='widgetconfig[]' value='$name:1' $checked /></div>";
	}

	
}
if ($jenis=='featured') {

/*
	$modulurasi = "<tr><td align=\"right\">"._SPECIALCASE.":</td><td><select name=\"widgetconfig[]\">";
	if ($action=="edit" && $isi==1) {
		$modulurasi .= "<option value=\"1\" selected>"._BESTSELLER."</option>\r\n";
	} else {
		$modulurasi .= "<option value=\"1\">"._BESTSELLER."</option>\r\n";
	}
	if ($action=="edit" && $isi==2) {
		$modulurasi .= "<option value=\"2\" selected>"._SPECIALPRICE."</option>\r\n";
	} else {
		$modulurasi .= "<option value=\"2\">"._SPECIALPRICE."</option>\r\n";
	}
	if ($action=="edit" && $isi==3) {
		$modulurasi .= "<option value=\"3\" selected>"._NEWARRIVAL."</option>\r\n";
	} else {
		$modulurasi .= "<option value=\"3\">"._NEWARRIVAL."</option>\r\n";
	}
	$modulurasi .= "</select></td></tr>\r\n";
*/	
}
?>
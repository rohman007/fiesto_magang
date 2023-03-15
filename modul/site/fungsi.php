<?php
function loop_child($parent)
{
	global $r_parent, $mysql;
	$sql="SELECT id from catalogcat WHERE parent=$parent";
	$q=$mysql->query($sql);
	if($q and $mysql->num_rows($q)>0)
	{
		while(list($id)=$mysql->fetch_row($q))
		{
		$r_parent[]=$id;
		loop_child($id);
		}
	}
}
?>
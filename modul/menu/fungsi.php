<?php

function catstructure1($sql) {
	global $mysql;
	$cats = new categories();
	$mycats = array();
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'judul'=>$row['judul'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);

	$currlevel = 1;
	$catcontent .= "<ul>\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$selisihlevel=$cats->cats[$i]['level']-$currlevel;
		if ($selisihlevel>0) {
			$catcontent .= "<ul>\n";
			$catcontent .= "<li>".$cats->cats[$i]['judul']." <a href=\"?p=menu&action=edit&pid=".$cats->cats[$i]['id']."\"><img alt=\""._EDIT."\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=menu&action=del&pid=".$cats->cats[$i]['id']."\"><img alt=\""._DEL."\" border=\"0\" src=\"../images/delete.gif\"></a></li>\n";
		}
		if ($selisihlevel==0) {
			$catcontent .= "<li>".$cats->cats[$i]['judul']." <a href=\"?p=menu&action=edit&pid=".$cats->cats[$i]['id']."\"><img alt=\""._EDIT."\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=menu&action=del&pid=".$cats->cats[$i]['id']."\"><img alt=\""._DEL."\" border=\"0\" src=\"../images/delete.gif\"></a></li>\n";
		}
		if ($selisihlevel<0) {
			for ($j=0; $j<-$selisihlevel; $j++) {
				$catcontent .= "</ul>\n";
			}
			$catcontent .= "<li>".$cats->cats[$i]['judul']." <a href=\"?p=menu&action=edit&pid=".$cats->cats[$i]['id']."\"><img alt=\""._EDIT."\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=menu&action=del&pid=".$cats->cats[$i]['id']."\"><img alt=\""._DEL."\" border=\"0\" src=\"../images/delete.gif\"></a></li>\n";
		}
		$currlevel=$cats->cats[$i]['level'];
	}	
	$catcontent .= "</ul>\n";
	return $catcontent;
}
function catstructure($sql)
{
	global $mysql;
	global $urlfunc,$cats;
	$cats = new categories();
	$mycats = array();
	$result = $mysql->query($sql);
	while ($row = $mysql->fetch_array($result)) {
	$mycats[] = array('id' => $row['id'], 'judul' => $row['judul'], 'parent' => $row['parent'], 'level' => 0,'type'=>$row['type']);
	}
	$cats->get_cats($mycats);
	ob_start();
	echo "<ol class=\"sortable ui-sortable\"class=\"sortable ui-sortable\">";
	 for ($i = 0; $i < count($cats->cats); $i++) 
	 {
		if($cats->cats[$i]['parent']==0)
		{
			$cat_id=$cats->cats[$i]['id'];
			$class="class='nosub'";
			if($cats->cats[$i]['type']==1) {$class="class='subok'";}
			echo "<li id='list_$cat_id' data-catid='$cat_id'  $class data-type='".$cats->cats[$i]['type']."'>";
			echo "<div class=\"kat_item ui-state-default ui-draggable\"><div class=\"kat_judul\">".$cats->cats[$i]['judul']."</div>";
			   echo "<div class=\"kat_action\"><a href=\"?p=menu&action=edit&pid=".$cats->cats[$i]['id']."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=menu&action=del&pid=" . $cats->cats[$i]['id'] . "\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a>
				</div></div>";
			child($cats->cats[$i]['id']);
			echo "</li>";
		}
	 }
	echo "</ol>";

	return ob_get_clean();
}
function child($id)
{
	global $mysql;
	global $urlfunc,$cats;
	echo "<ol>";
	for ($i = 0; $i < count($cats->cats); $i++) 
 	{
		if($cats->cats[$i]['parent']==$id)
		{
		$class="class='nosub'";
		//if($cats->cats[$i]['type']==1 or $cats->cats[$i]['type']==2) {$class="";}
		if($cats->cats[$i]['type']==1) {$class="class='subok'";}
		$pid=$cats->cats[$i]['id'];
		echo "<li id='list_$pid' data-catid='$pid' $class data-type='".$cats->cats[$i]['type']."'>";
		echo "<div class=\"kat_item ui-state-default ui-draggable\"><div class=\"kat_judul\">".$cats->cats[$i]['judul'] ."</div> ";
           echo "<div class=\"kat_action\"><a href=\"?p=menu&action=edit&pid=".$cats->cats[$i]['id']."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a> <a href=\"?p=menu&action=del&pid=" . $cats->cats[$i]['id'] . "\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a>
			</div></div>";
		child($cats->cats[$i]['id']);
		echo "</li>";
		}
		
	}
	echo "</ol>";

}
?>
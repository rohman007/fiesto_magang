<?php

// if($jenismenu=="cattree")
// {	
	// $sql_catalog = "SELECT id, nama, parent FROM filecat ORDER BY urutan ";
	// $result_catalog = mysql_query($sql_catalog);
	// while($row_catalog = mysql_fetch_array($result_catalog)) 
	// {	$titleurl = array();
		// $titleurl["cat_id"] = $row_catalog['nama'];
		// $url = "";
		
		// $sqlchild = "SELECT id, nama, parent FROM filecat 
			// WHERE parent='".$row_catalog['id']."' ";
		// $resultchild = mysql_query($sqlchild);
		// if(mysql_num_rows($resultchild)==0)
		// {	$url = "?p=download&action=images&cat_id=".$row_catalog['id'];
		// }
	
		// if(strlen($url)>0)
		// {	$url = $urlfunc->makePretty($url, $titleurl);
		// }
		
		// if($row_catalog['parent']==0)
		// {	$row_catalog['parent'] = $row['id'];
		// }
		// else
		// {	$row_catalog['parent'] = $row['id']."-".$row_catalog['parent'];
		// }
		// $row_catalog['id'] = $row['id']."-".$row_catalog['id'];
		
		// $mycats[] = array('id'=>$row_catalog['id'],
			// 'parent'=>$row_catalog['parent'], 'type'=>$row['type'], 
			// 'judul'=>$row_catalog['nama'], 'url'=>$url, 'level'=>0);
	// }
	
// }

// if($jenismenu=="producttree")
// {	
	// $sql_catalog = "SELECT id, nama, parent FROM filecat ORDER BY urutan ";
	// $result_catalog = mysql_query($sql_catalog);
	// while($row_catalog = mysql_fetch_array($result_catalog)) 
	// {	$titleurl = array();
		// $titleurl["cat_id"] = $row_catalog['nama'];
		// $url = "";
		
		// $sqlchild = "SELECT id, nama, parent FROM filecat 
			// WHERE parent='".$row_catalog['id']."' ";
		// $resultchild = mysql_query($sqlchild);
		
		// $sqlproduct = "SELECT id, title, cat_id FROM filedata 
			// WHERE cat_id='".$row_catalog['id']."' AND publish='1' 
			// ORDER BY title ";

		// $resultproduct = mysql_query($sqlproduct);
		// if(mysql_num_rows($resultchild)==0 and mysql_num_rows($resultproduct)==0)
		// {	$url = "?p=download&action=images&cat_id=".$row_catalog['id'];
		// }
	
		// if(strlen($url)>0)
		// {	$url = $urlfunc->makePretty($url, $titleurl);
		// }
		
		// if($row_catalog['parent']==0)
		// {	$row_catalog['parent'] = $row['id'];
		// }
		// else
		// {	$row_catalog['parent'] = $row['id']."-".$row_catalog['parent'];
		// }
		// $row_catalog['id'] = $row['id']."-".$row_catalog['id'];
		
		// $mycats[] = array('id'=>$row_catalog['id'],
			// 'parent'=>$row_catalog['parent'], 'type'=>$row['type'], 
			// 'judul'=>$row_catalog['nama'], 'url'=>$url, 'level'=>0);
		
		// if(mysql_num_rows($resultproduct)>0)
		// {	while($row_product = mysql_fetch_array($resultproduct)) 
			// {	$titleurl = array();
				// $titleurl["cat_id"] = $row_catalog['nama'];
				// $titleurl["pid"] = $row_product['title'];
				// $url = "?p=download&action=detail&pid=".$row_product['id'];
				// $url .= "&cat_id=".$row_product['cat_id'];
				// if(strlen($url)>0)
				// {	$url = $urlfunc->makePretty($url, $titleurl);
				// }
		
				// $row_product['id'] = $row_catalog['id']."-".$row_product['id'];
				// $mycats[] = array('id'=>$row_product['id'],
					// 'parent'=>$row_catalog['id'], 'type'=>$row['type'], 
					// 'judul'=>$row_product['title'], 'url'=>$url, 'level'=>0);
			// }
		// }
		
	// }
	
// }

// if($jenismenu=="singletree")
// {	
	// list($menucatid)=explode(';',$row['isi']);
	// // echo "ha$menucatid-";
	
	// $sql = "SELECT id, nama, parent FROM filecat WHERE parent = '$menucatid'";
	
	// $result_catalog = mysql_query($sql);
	// while($row_catalog = mysql_fetch_array($result_catalog)) {	
		// $titleurl = array();
		// $titleurl["cat_id"] = $row_catalog['nama'];
		// $url = "";
		
		// $url = "?p=download&action=images&cat_id=".$row_catalog['id'];
		
		// if(strlen($url)>0) {	
			// $url = $urlfunc->makePretty($url, $titleurl);
		// }
		
		// $mycats[] = array(
			// 'id'	=> $row_catalog['id'],
			// 'parent'=> $row['id'], 
			// 'type'	=> $row['type'], 
			// 'judul'	=> $row_catalog['nama'], 
			// 'url'	=> $url,
			// 'level'	=> 0
		// );	

	// }	
// }
?>
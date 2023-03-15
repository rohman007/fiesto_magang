<?php

if($jenismenu=="cattree")
{	
	$sql_catalog = "SELECT id, nama, parent FROM catalogcat ORDER BY urutan ";
	$result_catalog = $mysql->query($sql_catalog);
	while($row_catalog = $mysql->fetch_array($result_catalog)) 
	{	$titleurl = array();
		$titleurl["cat_id"] = $row_catalog['nama'];
		$url = "";
		
		$sqlchild = "SELECT id, nama, parent FROM catalogcat 
			WHERE parent='".$row_catalog['id']."' ";
		$resultchild = $mysql->query($sqlchild);
		if($mysql->num_rows($resultchild)==0)
		{	$url = "?p=catalog&action=images&cat_id=".$row_catalog['id'];
		}
	
		if(strlen($url)>0)
		{	$url = $urlfunc->makePretty($url, $titleurl);
		}
		
		if($row_catalog['parent']==0)
		{	$row_catalog['parent'] = $row['id'];
		}
		else
		{	$row_catalog['parent'] = $row['id']."-".$row_catalog['parent'];
		}
		$row_catalog['id'] = $row['id']."-".$row_catalog['id'];
		
		$mycats[] = array('id'=>$row_catalog['id'],
			'parent'=>$row_catalog['parent'], 'type'=>$row['type'], 
			'judul'=>$row_catalog['nama'], 'url'=>$url, 'level'=>0);
	}
	
}

if($jenismenu=="producttree")
{	
	$sql_catalog = "SELECT id, nama, parent FROM catalogcat ORDER BY urutan ";
	$result_catalog = $mysql->query($sql_catalog);
	while($row_catalog = $mysql->fetch_array($result_catalog)) 
	{	$titleurl = array();
		$titleurl["cat_id"] = $row_catalog['nama'];
		$url = "";
		
		$sqlchild = "SELECT id, nama, parent FROM catalogcat 
			WHERE parent='".$row_catalog['id']."' ";
		$resultchild = $mysql->query($sqlchild);
		
		$sqlproduct = "SELECT id, title, cat_id FROM catalogdata 
			WHERE cat_id='".$row_catalog['id']."' AND publish='1' 
			ORDER BY title ";

		$resultproduct = $mysql->query($sqlproduct);
		if($mysql->num_rows($resultchild)==0 and $mysql->num_rows($resultproduct)==0)
		{	$url = "?p=catalog&action=images&cat_id=".$row_catalog['id'];
		}
	
		if(strlen($url)>0)
		{	$url = $urlfunc->makePretty($url, $titleurl);
		}
		
		if($row_catalog['parent']==0)
		{	$row_catalog['parent'] = $row['id'];
		}
		else
		{	$row_catalog['parent'] = $row['id']."-".$row_catalog['parent'];
		}
		$row_catalog['id'] = $row['id']."-".$row_catalog['id'];
		
		$mycats[] = array('id'=>$row_catalog['id'],
			'parent'=>$row_catalog['parent'], 'type'=>$row['type'], 
			'judul'=>$row_catalog['nama'], 'url'=>$url, 'level'=>0);
		
		if($mysql->num_rows($resultproduct)>0)
		{	while($row_product = $mysql->fetch_array($resultproduct)) 
			{	$titleurl = array();
				$titleurl["cat_id"] = $row_catalog['nama'];
				$titleurl["pid"] = $row_product['title'];
				$url = "?p=catalog&action=detail&pid=".$row_product['id'];
				$url .= "&cat_id=".$row_product['cat_id'];
				if(strlen($url)>0)
				{	$url = $urlfunc->makePretty($url, $titleurl);
				}
		
				$row_product['id'] = $row_catalog['id']."-".$row_product['id'];
				$mycats[] = array('id'=>$row_product['id'],
					'parent'=>$row_catalog['id'], 'type'=>$row['type'], 
					'judul'=>$row_product['title'], 'url'=>$url, 'level'=>0);
			}
		}
		
	}
	
}

if($jenismenu=="singletree")
{	
	list($menucatid)=explode(';',$row['isi']);
	// echo "ha$menucatid-";
	
	$sql = "SELECT id, nama, parent FROM catalogcat WHERE parent = '$menucatid'";
	
	$result_catalog = $mysql->query($sql);
	while($row_catalog = $mysql->fetch_array($result_catalog)) {	
		$titleurl = array();
		$titleurl["cat_id"] = $row_catalog['nama'];
		$url = "";
		
		$url = "?p=catalog&action=images&cat_id=".$row_catalog['id'];
		
		if(strlen($url)>0) {	
			$url = $urlfunc->makePretty($url, $titleurl);
		}
		
		$mycats[] = array(
			'id'	=> $row_catalog['id'],
			'parent'=> $row['id'], 
			'type'	=> $row['type'], 
			'judul'	=> $row_catalog['nama'], 
			'url'	=> $url,
			'level'	=> 0
		);	

	}	
}
?>
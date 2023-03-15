<?php

if($jenismenu=="newstree")
{	
	
	$sql = "SELECT id, cat_id, judulberita, tglberita FROM newsdata GROUP BY YEAR(tglberita) ORDER BY tglberita DESC";
	$result = $mysql->query($sql);
	if($mysql->num_rows($result)>0)
	{	
		$content = "<ul>\r\n";
		while($row_news = $mysql->fetch_assoc($result)) {
			$titleurl["cat_id"] = $row_news['judulberita'];
			$url = "";	
			$mycats[] = array('id'=>$row_news['id'],
					'parent'=>$row_news['id'], 'type'=>$row['type'], 
					'judul'=>$row_news['judulberita'], 'url'=>$url, 'level'=>0);
			if (($timestamp = strtotime($row_news['tglberita'])) !== -1) {
				$i=getdate($timestamp);
				$tahun = $i[year];
				if ($row_news['cat_id']==0) {
					$content .= "<li><a href=\"".$urlfunc->makePretty("?p=news&action=archive&tahun=$tahun&bulan=$i[mon]")."\">$tahun</a>\r\n";
				} else {
					$titleurl = array();
					$titleurl["cat_id"] = $row_news['judulberita'];
					$sql_news = "SELECT id,tglmuat,judulberita,summary,tglberita FROM newsdata WHERE YEAR(tglberita) = '$tahun'";
					$res_news = $mysql->query($sql_news);

					// $content .= "<li>$tahun\r\n";
					// $content .= "<ul>";
					while(list($id,$tglmuat,$judulberita,$summary,$tglberita) = $mysql->fetch_assoc($res_news)) {
						// $content .= "<li><a href=\"".$urlfunc->makePretty("?p=news&action=shownews&pid=$id", $titleurl)."\">$judulberita</a></li>";
						$mycats[] = array('id'=>$id,
							'parent'=>$row_news['id'], 'type'=>$row['type'], 
							'judul'=>$judulberita, 'url'=>$url, 'level'=>0);
					}
					// $content .= "</ul>";
					// $content .= "</li>";
				}
			}
		}
		$content .= "</ul>\r\n";
		
	}
	
}

if($jenismenu=="producttree")
{	
	$sql_catalog = "SELECT id, nama, parent FROM catalogcat ORDER BY urutan ";
	$result_catalog = $mysql->query($sql_catalog);
	while($row_catalog = $mysql->fetch_assoc($result_catalog)) 
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
		{	while($row_product = $mysql->fetch_assoc($resultproduct)) 
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

if($jenismenu=="cattree")
{	
	$sql_catalog = "SELECT id, nama FROM newscat ORDER BY urutan ";
	$result_catalog = $mysql->query($sql_catalog);
	while($row_catalog = $mysql->fetch_assoc($result_catalog)) 
	{	$titleurl = array();
		$titleurl["cat_id"] = $row_catalog['nama'];
		$url = "";
		$url = $urlfunc->makePretty("?p=news&action=images&cat_id=".$row_catalog['id']);
		
		$mycats[] = array('id'=>$row_catalog['id'],
			'parent'=>$row['id'], 'type'=>$row['type'], 
			'judul'=>$row_catalog['nama'], 'url'=>$url, 'level'=>0);
	}
}
?>

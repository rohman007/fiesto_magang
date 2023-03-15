<?php 
/**
* @author Moh Aly Shodiqin (felixprogressive@gmail.com)
* @return Generate Schema 
*/

class Schema
{
	const SCHEMA_HEADER = '<script type="application/ld+json">';
	const SCHEMA_FOOTER = '</script>';
	const SCHEMA_CONTEXT = 'http://schema.org';
	
	const SCHEMA_AVAILABILITY = 'http://schema.org/InStock';
	
	/* const FRAMEWORK = array(
		'version' => 2,
		'name'	=> 'IWD'
	); */
	
	public function ProductDetail($sql) 
	{
		global $mysqli, $cfg_fullsizepics_path, $cfg_fullsizepics_url, $urlfunc;
		
		if ($result = $mysqli->query($sql)) {
			$total_record = $result->num_rows;
			
			$row = $result->fetch_assoc();
			
			$pid = $row['id'];
			$cat_id = $row['cat_id'];
			
			if ($row['thumb'] != '') {
				
				$thumbs = explode(':', $row['thumb']);
				$thumbnail = array();
				foreach($thumbs as $image) {
					if (file_exists("$cfg_fullsizepics_path/$image")) {
						$thumbnail[] = "$cfg_fullsizepics_url/$image";
					}
				}
			}
			
			if (!empty($row['diskon'])) {
				$markup_offer = array(
					'@type'			=> 'AggregateOffer',
					'lowPrice'		=> round($row['hargadiskon']),
					'highPrice'		=> round($row['harganormal']),
					'offerCount'	=> $total_record,
					'priceCurrency'	=> 'IDR',
					'availability'	=> self::SCHEMA_AVAILABILITY
				);
			} else {
				$markup_offer = array(
					'@type'		=> 'Offer',
					'price'		=> round($row['harganormal']),
					'priceCurrency'		=> 'IDR',
					'availability'		=> self::SCHEMA_AVAILABILITY
				);
			}
			
			$urltitle = array();
			$urltitle['pid'] = $row['title'];
			$urltitle['cat_id'] = get_cat_name($cat_id);
			
			$markup = array(
				'@context' 	=> self::SCHEMA_CONTEXT,
				'@type' 	=> 'Product',
				// '@id'   	=> $pid,
				'image'		=> $thumbnail,
				'url'		=> $urlfunc->makePretty("?p=catalog&action=detail&pid=$pid&cat_id=$cat_id", $urltitle),
				'name'  	=> $row['title'],
				'description' => strip_tags($row['keterangan']),
			);
			$markup['offers'] = $markup_offer;
			
			if ($row['idmerek'] > 0) {
				$brand_name = get_brand_name($row['idmerek']);
				$markup_brand = array(
					'@type' => 'Thing',
					'name' => $brand_name
				);
				
				$markup['brand'] = $markup_brand;
			}
			
			$result->close();
			
			$schema = self::SCHEMA_HEADER;
			$schema .= json_encode($markup);
			$schema .= self::SCHEMA_FOOTER;
			
			return $schema;
		}
	}
	
	public function ProductBreadcrumbs($productId)
	{
		global $mysqli, $urlfunc, $topcatnamenav;
		
		$sql = "SELECT cat_id FROM catalogdata WHERE id='$productId'";
		$result = $mysqli->query($sql);
		list($cat_id) = $result->fetch_row();

		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent, description FROM catalogcat';
		$result = $mysqli->query($sql);
		while ($row = $result->fetch_array()) {
			$mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'description' => $row['description'], 'level' => 0);
		}
		
		$cats->get_cats($mycats);
		$titleurl = array();
		
		$position = 1;
		$list_item[] = array(
			'@type' => 'ListItem',
			'position' => $position,
			'name' => $topcatnamenav,
			'item' => $urlfunc->makePretty("?p=catalog")
		);
		
		for ($i = 0; $i < count($cats->cats); $i++) {
			$cats->cat_map($cats->cats[$i]['id'], $mycats);
			if ($cats->cats[$i]['id'] == $cat_id) {
				for ($a = 0; $a < count($cats->cat_map); $a++) {
					$cat_parent_id = $cats->cat_map[$a]['id'];
					$cat_parent_name = $cats->cat_map[$a]['nama'];
					$titleurl['cat_id']=$cat_parent_name;
					$position++;
					$list_item[] = array(
						'@type' => 'ListItem',
						'position' => $position,
						'name' => $cat_parent_name,
						'item' => $urlfunc->makePretty("?p=catalog&action=images&cat_id=$cat_parent_id",$titleurl)
					);
				}
				$titleurl['cat_id']=$cats->cats[$i]['nama'];
				$position++;
				$list_item[] = array(
					'@type' => 'ListItem',
					'position' => $position,
					'name' => $cats->cats[$i]['nama'],
					'item' => $urlfunc->makePretty("?p=catalog&action=images&cat_id=".$cats->cats[$i]['id'],$titleurl)
				);
			}
		}
		
		$markup = array(
			'@context' => self::SCHEMA_CONTEXT,
			'@type' => 'BreadcrumbList'
		);
		
		$markup['itemListElement'] = $list_item;
		
		$schema = self::SCHEMA_HEADER;
		$schema .= json_encode($markup);
		$schema .= self::SCHEMA_FOOTER;
		
		return $schema;
	}
	
	public function SinglePage($sql)
	{
		global $mysqli, $cfg_fullsizepics_path, $cfg_fullsizepics_url, $urlfunc, $cfg_app_url;
		
		if ($result = $mysqli->query($sql)) {
			$total_record = $result->num_rows;
			
			$row = $result->fetch_assoc();
			
			$pid = $row['id'];
			$cat_id = $row['cat_id'];
			
			$markup = array(
				'@context' 	=> self::SCHEMA_CONTEXT,
				'@type' 	=> 'WebPage',
				'name'  	=> $row['judul'],
				'description'  	=> strip_tags($row['isi'])
			);
			
			$titleurl = array();
			$titleurl['pid'] = $row['judul'];
			$url = $urlfunc->makePretty("?p=page&action=view&pid=$pid", $titleurl);
			$markup['url'] = $url;
			
			$publishers = array(
				'@type'	=> 'Organization',
				'name'	=> $cfg_app_url
			);
			$markup['publisher'] = $publishers;
			
			$result->close();
			
			$schema = self::SCHEMA_HEADER;
			$schema .= json_encode($markup);
			$schema .= self::SCHEMA_FOOTER;
			
			return $schema;
		}
	}
	
	public function SingleNews($sql)
	{
		global $mysqli, $cfg_fullsizepics_path, $cfg_fullsizepics_url, $urlfunc, $schema_person_organization, $cfg_app_url;
		
		if ($result = $mysqli->query($sql)) {
			$total_record = $result->num_rows;
			
			$row = $result->fetch_assoc();
  
			$markup = array(
				'@context' => self::SCHEMA_CONTEXT,
				'@type' => 'NewsArticle'
			);
			
			// Protokol http harus https, rekomen dari Google
			$markup_main_entity_of_page = array(
				"@type" => "WebPage",
				"@id" => "https://google.com/article"
			);
			$markup['mainEntityOfPage'] = $markup_main_entity_of_page;
			
			$markup['headline'] = $row['judulberita'];
			$markup['datePublished'] = date('Y-m-d\TH:i:sO', $row['tglmuat']);
			$markup['dateModified'] = date('Y-m-d\TH:i:sO', $row['tglberita']);
			$markup['description'] = strip_tags($row['summary']);
			
			$author_name = $_SERVER['HTTP_HOST'];
			$markup_authors = array(
				"@type" => "Person",
				"name" => $author_name
			);
			$markup['author'] = $markup_authors;
			
			$sql_logo = "SELECT basename, extension FROM decoration WHERE basename='headerlogo'";
			if ($result_logo = $mysqli->query($sql_logo)) {
				list($basename, $extension) = $result_logo->fetch_row();
				$image_logo = "$cfg_app_url/file/dekorasi/$basename.$extension";
				$result_logo->close();
			}
			$markup_publisher_logo = array(
				"@type" => "ImageObject",
				"url" => $image_logo,
				"width" => "178px",
				"height" => "26px"
			);
			
			$markup_publisher = array(
				"@type" => "Organization",
				"name" => $author_name,
				"logo" => $markup_publisher_logo
			);
			
			$markup['publisher'] = $markup_publisher;
			
			
			$thumbnail = array();
			if ($row['thumb'] != '') {
				
				$thumbs = explode(':', $row['thumb']);
				foreach($thumbs as $thumb) {
					if ($thumb != '' && file_exists("$cfg_fullsizepics_path/$thumb")) {
						$thumbnail[] = "$cfg_fullsizepics_url/$thumb";
					}
				}
				$markup_images = array(
					'@type' 	=> 'ImageObject',
					"url" => $thumbnail,
					"width" => "200px",
					"height" => "120px"
				);
				$markup['image'] = $markup_images;
			}
			
			$result->close();
			
			$schema = self::SCHEMA_HEADER;
			$schema .= json_encode($markup);
			$schema .= self::SCHEMA_FOOTER;
			
			return $schema;
		}
		
	}
	
	public function SingleEvent($sql)
	{
		global $mysqli, $cfg_fullsizepics_path, $cfg_fullsizepics_url, $urlfunc, $cfg_app_url;
	
		if ($result = $mysqli->query($sql)) {
			$total_record = $result->num_rows;
			
			$row = $result->fetch_assoc();
			
			$pid = $row['id'];
			
			/**
			* Event standard
			*/
			
			$markup = array(
				'@context' 	=> self::SCHEMA_CONTEXT,
				'@type' 	=> 'Event'
			);
			
			$markup_location_address = array(
				'@type'	=> 'PostalAddress',
				'addressLocality'	=> $row['kota'],
				'streetAddress'		=> $row['lokasi']
			);
			
			$markup_location = array(
				'@type'	=> 'Place',
				'name'	=> $row['lokasi'],
				'address'	=> $markup_location_address
			);
			$markup['location'] = $markup_location;
			$markup['name'] = $row['judul'];
			
			$titleurl = array();
			$titleurl['pid'] = $row['judul'];
			$url = $urlfunc->makePretty("?p=event&action=view&pid=$pid", $titleurl);
			$markup['url'] = $url;
			
			if ($row['filename'] != '' && file_exists("$cfg_fullsizepics_path/{$row['filename']}")) {
				$image_url = "$cfg_fullsizepics_url/{$row['filename']}";
				$markup_images = array(
					'@type' 	=> 'ImageObject',
					'url' 		=> $image_url,
				);
				
				$markup['image'] = $markup_images;
			}
			$markup['startDate'] = $row['tglmulai'];
			$markup['endDate'] = $row['tglselesai'];
			$markup['description'] = strip_tags($row['deskripsi']);
			
			
			/**
			* Event pakai offers dan performer
			*/
			
			/* $markup_event_type = array(
				"Event",
				"TouristAttraction"
			);
			
			$markup = array(
				"@context" => self::SCHEMA_CONTEXT,
				"@type" => $markup_event_type,
				"name" => $row['judul'],
				"description" => strip_tags($row['deskripsi'])
			);
			
			$markup['startDate'] = $row['tglmulai'];
			$markup['endDate'] = $row['tglselesai'];
			
			$markup_location_address = array(
				'@type'	=> 'PostalAddress',
				'addressLocality'	=> $row['kota'],
				'streetAddress'		=> $row['lokasi'],
				'addressCountry'	=> 'IDN'
			);
			
			$markup_location = array(
				'@type'	=> 'Place',
				'name'	=> $row['lokasi'],
				'address'	=> $markup_location_address
			);
			
			$titleurl = array();
			$titleurl['pid'] = $row['judul'];
			$url = $urlfunc->makePretty("?p=event&action=view&pid=$pid", $titleurl);
			$markup['url'] = $url;
			
			if ($row['filename'] != '' && file_exists("$cfg_fullsizepics_path/{$row['filename']}")) {
				$image_url = "$cfg_fullsizepics_url/{$row['filename']}";
				$markup_images = array(
					'@type' 	=> 'ImageObject',
					'url' 		=> $image_url,
				);
				
				$markup['image'] = $markup_images;
			}
			
			$markup['location'] = $markup_location;
			$markup['publicAccess'] = true;
			$markup['isAccessibleForFree'] = true;
			
			$markup_offer = array(
				"@type" => "Offer",
				"url" => $url,
				"availability" => self::SCHEMA_AVAILABILITY,
				"price" => 0,
				"priceCurrency" => "IDR",
				"validFrom" =>  $row['tglselesai']
			);
			
			$markup['offers'] = $markup_offer;
			
			$markup_performer = array(
				"@type" => "PerformingGroup",
				"name" => $cfg_app_url
			);
			
			$markup['performer'] = $markup_performer; */
			
			$schema = self::SCHEMA_HEADER;
			$schema .= json_encode($markup);
			$schema .= self::SCHEMA_FOOTER;
			
			return $schema;
		}
	}
	
	
}
	
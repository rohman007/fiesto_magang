<?php
function archive($tahun,$bulan) {
	global $lang, $cfg_thumb_url, $mysql;
	$bulandepan=$bulan+1;

	$sql = "SELECT id, judul, deskripsi, tglmulai, tglselesai, filename, kota, lokasi FROM event WHERE tglselesai<CURDATE() AND tglmulai>='$tahun-$bulan-01 00:00:00' AND tglmulai<'$tahun-$bulandepan-01 00:00:00' ORDER BY tglmulai DESC";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) == "0") {
		$content .= "<p>Maaf, arsip bulan ".$namabulan[$bulan-1]." $tahun tidak ada.</p>\n";
	} else {
		$content .= "<ul>\r\n";
		while (list($id, $judul, $deskripsi, $tglmulai, $tglselesai, $filename, $kota, $lokasi) = $mysql->fetch_row($result)) {
			$content .= "<li>";
						
			if($filename!="")
			{	$content .= "<span class=\"gambarevent\">";
				$content .= '<img src="'.$cfg_thumb_url.'/'.$filename.'" alt="'.$judul.'">';
				$content .= "</span><br />\r\n";
			}
			
			$content .= "<a href=\"?p=event&action=view&pid=$id\"><span class=\"judulevent\">$judul</span></a><br />\r\n";
			
			$content .= "<span class=\"tglevent\">";
			$tglmulai = tglformat($tglmulai);
			$tglselesai = tglformat($tglselesai);
			$mulai = explode(" ",$tglmulai);
			$selesai = explode(" ",$tglselesai);
			if ($mulai[2] == $selesai[2]) {
				if ($mulai[1] == $selesai[1]) {
					if ($mulai[0] == $selesai[0]) {
						$content .= "$mulai[0] $mulai[1] $mulai[2]";
					} else {
						$content .= "$mulai[0]-$selesai[0] $mulai[1] $mulai[2]";
					}
				} else {
					$content .= "$mulai[0] $mulai[1] - $selesai[0] $selesai[1] $mulai[2]";
				}
			} else {
				$content .= "$mulai[0] $mulai[1] $mulai[2] - $selesai[0] $selesai[1] $selesai[2]";
			}
			$content .= "</span>";
			$content .= "<span class=\"lokasievent\">$lokasi</span><br />\r\n";
			$content .= "<span class=\"kotaevent\">$kota</span><br />\r\n";
			$content .= "</li>\r\n";
		}
		$content .= "</ul>\r\n";
	}
	return $content;
}

/* By Aly */

/*
return JSON Schema Single Product
*/
// function generate_schema_single_event($sql) {
	// global $mysql, $cfg_fullsizepics_path, $cfg_fullsizepics_url, $urlfunc, $schema_person_organization, $cfg_app_url;
	
	// define('SCHEMA_HEADER', '<script type="application/ld+json">');
	// define('SCHEMA_FOOTER', '</script>');
	// define('SCHEMA_CONTEXT', 'http://schema.org');
	
	// $markup = array();
	// if ($result = $mysql->query($sql)) {
		// $total_record = $mysql->num_rows($result);
		
		// $row = $result->fetch_assoc();
		
		// $pid = $row['id'];
		
		// $markup = array(
			// '@context' 	=> SCHEMA_CONTEXT,
			// '@type' 	=> 'Event'
		// );
		
		// $markup_location_address = array(
			// '@type'	=> 'PostalAddress',
			// 'addressLocality'	=> $row['kota'],
			// 'streetAddress'		=> $row['lokasi']
		// );
		
		// $markup_location = array(
			// '@type'	=> 'Place',
			// 'name'	=> $row['lokasi'],
			// 'address'	=> $markup_location_address
		// );
		// $markup['location'] = $markup_location;
		// $markup['name'] = $row['judul'];
		
		// $titleurl = array();
		// $titleurl['pid'] = $row['judul'];
		// $url = $urlfunc->makePretty("?p=event&action=view&pid=$pid", $titleurl);
		// $markup['url'] = $url;
		
		// if ($row['filename'] != '' && file_exists("$cfg_fullsizepics_path/{$row['filename']}")) {
			// $image_url = "$cfg_fullsizepics_url/{$row['filename']}";
			// $markup_images = array(
				// '@type' 	=> 'ImageObject',
				// 'url' 		=> $image_url,
			// );
			
			// $markup['image'] = $markup_images;
		// }
		// $markup['startDate'] = $row['tglmulai'];
		// $markup['endDate'] = $row['tglselesai'];
		// $markup['description'] = $row['deskripsi'];
		
		// // Warning :: Structured Data Testing Tool
		// // $markup_offers = array(
			// // '@type'	=> 'Offer',
			// // 'url'	=> $url
		// // );
		// // $markup['offers'] =, false)

// const encSmallNumber = (buf, off, size, number) =>
  // buf.write(octalString(number, size), off, size, '
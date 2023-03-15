<?php 
/**
* @author Aly
* Generate Schema 
*/
class Schema
{
	const SCHEMA_HEADER = '<script type="application/ld+json">';
	const SCHEMA_FOOTER = '</script>';
	const SCHEMA_CONTEXT = 'http://schema.org';
	
	const SCHEMA_AVAILABILITY = 'http://schema.org/InStock';
	
	const FRAMEWORK = array(
		'version' => 2,
		'name'	=> 'IWD'
	);
	
	public function generateSingleProduct($sql) {
		global $mysqli, $cfg_fullsizepics_path, $cfg_fullsizepics_url, $urlfunc;
		
		if ($result = $mysqli->query($sql)) {
			$total_record = $result->num_rows;
			
			$row = $result->fetch_assoc();
			
			$pid = $row['id'];
			$cat_id = $row['cat_id'];
			
			$markup_offers = array(
				'@type'		=> 'Offer',
				'price'		=> 4400000,	
				'priceCurrency'		=> 'IDR',
				'availability'		=> self::SCHEMA_AVAILABILITY
			);
			
			$markup = array(
				'@context' 	=> self::SCHEMA_CONTEXT,
				'@type' 	=> 'Product',
				'@id'   	=> $pid,
				'image'		=> array(
					'http://www.sunrace.co.id/file/catalog/large/IMG_9574.JPG',
					'http://www.sunrace.co.id/file/catalog/large/IMG_9577.JPG',
					'http://www.sunrace.co.id/file/catalog/large/IMG_9582.JPG',
					'http://www.sunrace.co.id/file/catalog/large/IMG_9589.JPG'
				),
				'url'		=> 'http://www.sunrace.co.id/catalog/detail/3_sunrace_universal_20/1_sunrace',
				'name'  	=> 'SUNRACE UNIVERSAL 20',
				'description' => 'TYRES : BRAKE : MAX SPEED : DISTANCE : BATTERY : WEIGHT CAPACITY : MOTOR : Garansi Garansi yang diberikan adalah: Garansi aki – 6 bulan. Garansi hub motor - 1 tahun. Garansi electrik part - 3 bulan. Garansi frame - 5 tahun.',
			);
			$markup['offers'] = $markup_offers;
			
			$schema = self::SCHEMA_HEADER;
			$schema .= json_encode($markup);
			$schema .= self::SCHEMA_FOOTER;
			
			return $schema;
		}
	}
}
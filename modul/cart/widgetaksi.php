<?php
if ($jenis=='active') {
	
	if ($pakaicart) {
		if (!empty($_SESSION['cart'])) 
		{
			
			foreach ($_SESSION['cart'] as $product_id => $quantity)
			{
				
				list($product_id, $product_attr_id, $product_color, $product_price, $product_price_plus, $product_size) = explode('|', $product_id);
			$widget .="<table id='widget_list_order' cellspacing='0' cellpadding='0'>";
				//get the name, description and price from the database - this will depend on your database implementation.
				//use sprintf to make sure that $product_id is inserted into the query as a number - to prevent SQL injection
			if(!empty($product_id) and strlen($product_id)>0)
			{	
				$sql = "SELECT id, keterangan, title, harganormal-(if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal),
							diskon)) as hargadiskon, harganormal,
							cat_id, filename, publish, ishot, ketsingkat, diskon, matauang,
							if(LOCATE('%', diskon)>0, (TRIM(REPLACE(diskon, '%', ''))/100*harganormal), diskon)
							as nilaidiskon
							FROM catalogdata WHERE id=$product_id;
							";
				
				$result = $mysql->query($sql);
				
				//Only display the row if there is a product (though there should always be as we have already checked)
				if ($mysql->num_rows($result) > 0){
					list($id, $description, $title, $price, $normalprice, $discountedprice) = $mysql->fetch_row($result);
					$line_cost = $price * $quantity;  //work out the line cost
					$total = $total + $line_cost;   //add to the total cost
					$widget .="<tr>";
					//show this information in table cells
					$widget .="<td align=\"left\">
								  <a href=\"".$urlfunc->makePretty("?p=cart&action=removeproduct&pid=$product_id")."\">
								  <img class=\"fixed-size\" src=\"$cfg_app_url/images/delete.gif\" border=\"0\" alt=\""._DELETEFROMCART."\" /></a>
								<td align=\"left\">$title  ($quantity".x.")</td>
								";
					//along with a 'remove' link next to the quantity - which links to this page, but with an action of remove, and the id of the current product
					$widget .="</tr>";
					$counter++;
				}
			}
			$widget .="</table>";
			}
			//$widget .= "<div id=\"cartwidget\"><a href=\"".$urlfunc->makePretty("?p=cart")."\"><input type=\"submit\" value="._CART." class=\"order_submit\"></a></div>";
			$widget .= "<div id=\"cartwidget\"><a class=\"order_submit\" href=\"".$urlfunc->makePretty("?p=cart")."\"><i class=\"fa fa-shopping-cart\"></i>"._CART."</a></div>";
			$widget .= "<div id=\"cowidget\"><a href=\"".$urlfunc->makePretty("?p=cart&action=checkout")."\"><input type=\"submit\" value="._CHECKOUT." class=\"order_submit\"></a></div>";
		} else {
			//otherwise tell the user they have no items in their cart
			$widget .= _NOPRODINCART." ";
			$widget .= "<div id=\"cartwidget\"><a id=\"widget_cart\" class=\"order_submit\" href=\"".$urlfunc->makePretty("?p=cart")."\"><i class=\"fa fa-shopping-cart\"></i>"._CART."</a></div>";
		}
	}
}
if ($jenis=='kurs') {
	$widget .= "<div id=\"kurswidget\" align=\"center\">1 USD = Rp ".number_format($kursusdidr,0,',','.')."</div>\r\n";
}
?>

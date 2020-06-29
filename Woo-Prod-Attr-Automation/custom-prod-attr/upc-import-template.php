<?php

/*
	Simple script used to automate adding "GTIN" or "UPC" to every WooCommerce product

	Uses a CSV file containing sku and the field you'd like to enter (See examples below)

	Meta field/tag can be changed to what every your system needs


*/

	$file = getcwd()."/Path/to/csv/SKU-UPC.csv";
	$csv = array_map('str_getcsv', file($file));

	//CSV file heading: SKU,UPC (Don't include)
	//Example:
					//sku1,upc1
					//sku2,upc2

	// wpfoof-gtin-name   =  meta key to update

	//$innerArray[0] = sku or column 1
	//$innerArray[1] = upc or colum 2
	foreach ($csv as $index => $innerArray) {
		$prodID  = wc_get_product_id_by_sku($innerArray[0]);
		//Run the code only if there's a product with that sku number
		if($prodID != 0){
			$product = wc_get_product($prodID);
			$postID = $product->get_id();
			update_post_meta($postID, "wpfoof-gtin-name",$innerArray[1]);
		}
	}

 ?>

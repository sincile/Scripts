<!---Change the API Key for this to work
			API Keys on Line(s):
								40
	-->
<!--- Also requires the cookie to be set inorder for the functions to run  -->

<script>
	var pixleeObj = {
		'cart_contents': [],
		'order_id': 0,
		'cart_total_quantity': 0,
		'cart_total': '0.00',
		'currency': 'USD'
	};

		pixleeObj.cart_total = <?php echo $order->get_total();?>;
		pixleeObj.cart_total = pixleeObj.cart_total.toString();
		pixleeObj.order_id = <?php echo $order->get_id();?>;
</script>
<?php
	$totalQuant = 0;
	$cartItems = array();

	foreach ($order->get_items() as $item) {
		$product = wc_get_product($item->get_product_id());
		$price = $item->get_total();
		$price = strval($price);
		$itemArr = array(
			'sku'=>$product->get_sku(),
			'quantity'=>$item->get_quantity(),
			'price'=>$price
		);
		$totalQuant +=$item->get_quantity();
		echo "<script>pixleeObj.cart_contents.push(".json_encode($itemArr).")</script>";
  }
	print_r($itemArr);
	echo "<script>pixleeObj.cart_total_quantity=",$totalQuant,"</script>";
?>

<script id='pixlee_script' src="https://assets.pixlee.com/assets/pixlee_events.js"></script>
<script type="text/javascript">
    if (typeof Pixlee_Analytics !== 'undefined') {
        pixlee_analytics = new Pixlee_Analytics("Your API Key");
    }
</script>

<script>
jQuery(document).ready(function( $ ) {
		function getCookie(cname) {
	  var name = cname + "=";
	  var decodedCookie = decodeURIComponent(document.cookie);
	  var ca = decodedCookie.split(';');
	  for(var i = 0; i <ca.length; i++) {
	    var c = ca[i];
	    while (c.charAt(0) == ' ') {
	      c = c.substring(1);
	    }
	    if (c.indexOf(name) == 0) {
	      return c.substring(name.length, c.length);
	    }
	  }
	  return "";
	}


	if(getCookie('pixlee')!= "" && typeof pixlee_analytics !== 'undefined'){
		console.log('Pixlee Activated');
		pixlee_analytics.events.trigger('converted:photo',pixleeObj);
	}
	else{
		console.log('Failed');
	}
});

</script>

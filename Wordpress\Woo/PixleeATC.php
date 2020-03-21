
<script id='pixlee_script' src="https://assets.pixlee.com/assets/pixlee_events.js"></script>
<script type="text/javascript">
    if (typeof Pixlee_Analytics !== 'undefined') {
        pixlee_analytics = new Pixlee_Analytics("LkaXfizWC6Ttd3tv231L");
				console.log('Pixlee obj created');
    }
</script>
<!-- END Pixlee -->

<script>
		function receiveMessage(event) {
			if (event.data) {
					try {
							var message = JSON.parse(event.data);
					} catch (error) {
							return;
					}
			} else {
					return;
			}
			if (message.eventName && message.eventName === 'photoOpened') {
					document.cookie ="pixlee=true";
			}
		}
		window.addEventListener("message", receiveMessage, false);

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

		console.log ("P-events");
		var urlParam  = new URLSearchParams(window.location.search);
		 if(urlParam.has('pixlee') || getCookie('pixlee') != "") {
			 var pixleeDebug = false;

			 if (pixleeDebug){
			 	console.log('pixlee caught');
		 	}
			 var that = jQuery(this);

			 const url = window.location.pathname.split('/')[1];

			 if(url != "" && url == "product") {


				 jQuery('.single_add_to_cart_button.button.alt').on('click',function() {

					var pixleeDebug = false;
					var activeTab =  jQuery('.js-tab-link.is-active');
					 if (activeTab.text() === "Individual") {

						 var quant = jQuery('.quantity').find('input').val();
						 if (quant === undefined){
							 quant = jQuery('.qtywrap .quantity').find('input[type=number]').val();
						 }

						 var price = jQuery('.price').text().split(' ')[0].split('$')[1];
						 var sku = jQuery('.sku_wrapper').text().split(': ')[1];

						 if (typeof pixlee_analytics !== 'undefined') {
								pixlee_analytics.events.trigger('add:to:cart', {
									'product_sku': sku,
									'price': price,
									'quantity': quant,
									'currency': 'USD'
								});
								document.cookie ="pixlee=true";

								if (pixleeDebug){
									console.log('Individual');
									console.log(sku + ' ' + price + ' ' + quant);
								}
						 }
						 else{
							 if(pixleeDebug) {
							 	console.log("Failed Individual");
							}
						 }
					 }
					 else if(activeTab.text()  === "Packs") {

						 var packQuant = jQuery( this ).prevAll().eq(1).children('label').children('input').val();
						 var packPrice = jQuery( this ).parent().prev().text().split(' ')[0].replace('\n','').replace('$','');
						 var packSku = jQuery('.sku_wrapper').text().split(': ')[1];

						 if (typeof pixlee_analytics !== 'undefined') {
								pixlee_analytics.events.trigger('add:to:cart', {
									'product_sku': packSku,
									'price': packPrice,
									'quantity': packQuant,
									'currency': 'USD'
								});
								document.cookie ="pixlee=true";
								if(pixleeDebug){
									console.log('Individual');
									console.log(packSku + ' ' + packPrice + ' ' + packQuant);
								}
						 }
						 else {
							 	if(pixleeDebug){
							 		console.log("Failed Packs");
								}
						 }

					 }
					 else if(activeTab.text()  === "Refills") {
						 var refillQuant = jQuery( this ).prevAll().eq(1).children('label').children('input').val();
						 var refillPrice = jQuery( this ).parent().prev().text().split(' ')[0].replace('\n','').replace('$','');
						 var refillSku = jQuery('.sku_wrapper').text().split(': ')[1];

						 if (typeof pixlee_analytics !== 'undefined') {
								pixlee_analytics.events.trigger('add:to:cart', {
									'product_sku': refillSku,
									'price': refillPrice,
									'quantity': refillQuant,
									'currency': 'USD'
								});
								document.cookie ="pixlee=true";
								if(pixleeDebug) {
									console.log(refillSku + ' ' + refillPrice + ' ' + refillQuant);
									console.log('Refills');
								}
						 }
						 else {
							 if(pixleeDebug) {
							 	console.log("Failed Refills");
						 	}
						 }
					 }
				 });
		 		}
		 	}
		});
</script>

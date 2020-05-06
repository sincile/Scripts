<?php
/**
 * Template Name: UPC Import Test
 *
 *  A custom page template for generic Zen pages.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header();
?>

<div id="primary" class="site-content" style="margin-top:-62px;">

	<div class="ink-background">

		<div class="zen-content-wrap">
			<h3 id="undefined" class="pt-section" data-name="Top of Page"></h3>
			<style>
			.zen-content{
				padding-top: 28px !important;
			}
			</style>
			<div class="zen-content landing-page">

				<div>
					<img class="zen-graphic" src="<?php echo get_stylesheet_directory_uri(); ?>/images/zen-headers/ZensOnlineAdventures_Header2.png" alt="Zebra">
				</div>


			</div>
		</div>

	</div>
	<div id="content" role="main">

		<?php
			echo "Hello World\n";

			$file = getcwd()."/wp-content/UPC/zebraSKU-UPC.csv";
			$csv = array_map('str_getcsv', file($file));

			//File heading SKU,UPC (Don't include)
			//$innerArray[0] = sku
			//$innerArray[1] = upc

			// wpfoof-gtin-name   =  meta key to update
			//Working example on stage = SKU: 27111

			foreach ($csv as $index => $innerArray) {
				$prodID  = wc_get_product_id_by_sku($innerArray[0]);
				//Run the code only if there's a product with that sku number
				if($prodID != 0){
					echo "Got ".$prodID ."-----";
					$product = wc_get_product($prodID);
					$postID = $product->get_id();
					update_post_meta($postID, "wpfoof-gtin-name",$innerArray[1]);
				}
			}

			// $prodID  = wc_get_product_id_by_sku("27111");
			// $product = wc_get_product($prodID);
			// $postID = $product->get_id(); //Post ID needed for meta
			// print_r (get_post_meta($postID, "wpfoof-gtin-name"));
			
		 ?>





		<div class="addthis_toolbox addthis_default_style">
			<a class="addthis_counter addthis_pill_style"></a>
		</div>

		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-530399555bd9c21d"></script>

<div style="padding-top:60px;">

		<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'page' ); ?>
		<?php comments_template( '', true ); ?>
		<?php endwhile; // end of the loop. ?>

		</div>

	</div>
	<!-- #content -->
</div> <!-- #primary -->




<?php get_footer(); ?>

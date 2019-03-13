<?php
/**
 * The Template for displaying all store products.
 *
 * @package WCfM Markeplace Views Store/products
 *
 * For edit coping this to yourtheme/wcfm/store 
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp;

$counter = 0;
?>

<?php do_action( 'wcfmmp_store_before_products', $store_user->get_id() ); ?>

<div class="" id="products">
	<div class="product_area">
	
		<?php do_action( 'wcfmmp_before_store_product', $store_user->get_id(), $store_info ); ?>
	
		<?php if ( woocommerce_product_loop() ) { ?>
			
			<?php do_action( 'wcfmmp_woocommerce_before_shop_loop_before', $store_user->get_id(), $store_info ); ?>
			<?php do_action( 'woocommerce_before_shop_loop' ); ?>
			<?php do_action( 'wcfmmp_woocommerce_before_shop_loop_after', $store_user->get_id(), $store_info ); ?>
			
			<?php do_action( 'flatsome_category_title_alt'); // Flatsome Catalog support ?>
			<?php do_action( 'wcfmmp_before_store_product_loop', $store_user->get_id(), $store_info ); ?>
			
			<?php if( function_exists( 'martfury_is_vendor_page' ) ) { echo '<div class="mf-toolbar-empty-space"></div><div id="mf-shop-content" class="mf-shop-content">'; } // Martfury catalog support ?>
	
			<?php woocommerce_product_loop_start(); ?>
			
			  <?php if ( wc_get_loop_prop( 'total' ) ) { ?>
			  	
			  	<?php do_action( 'wcfmmp_after_store_product_loop_start', $store_user->get_id(), $store_info ); ?>
			  	
					<?php while ( have_posts() ) { the_post(); ?>
						
						<?php do_action( 'wcfmmp_store_product_loop_in_before', $store_user->get_id(), $store_info, $counter ); ?>
		
						<?php wc_get_template_part( 'content', 'product' ); ?>
						
						<?php do_action( 'wcfmmp_store_product_loop_in_after', $store_user->get_id(), $store_info, $counter ); ?>
						
						<?php $counter++; ?>
		
					<?php }  ?>
					
					<?php do_action( 'wcfmmp_before_store_product_loop_end', $store_user->get_id(), $store_info ); ?>
					
				<?php } ?>
				
			<?php if( function_exists( 'listify_php_compat_notice') ) { ?>
				</div>
			<?php } else { ?>
			  <?php woocommerce_product_loop_end(); ?>
			<?php } ?>
			
			<?php if( function_exists( 'martfury_is_vendor_page' ) ) { echo '</div>'; } // Martfury catalog support ?>
			
			<?php do_action( 'wcfmmp_after_store_product_loop', $store_user->get_id(), $store_info ); ?>
			
			<?php do_action( 'wcfmmp_woocommerce_after_shop_loop_before', $store_user->get_id(), $store_info ); ?>
			<?php do_action( 'woocommerce_after_shop_loop' ); ?>
			<?php do_action( 'wcfmmp_woocommerce_after_shop_loop_after', $store_user->get_id(), $store_info ); ?>
			
			<?php //wcfmmp_content_nav( 'nav-below' ); ?>
	
		<?php } else { ?>
			<?php do_action( 'woocommerce_no_products_found' ); ?>
		<?php } ?>
		
		<?php do_action( 'wcfmmp_after_store_product', $store_user->get_id(), $store_info ); ?>
		
	</div><!-- #products -->
</div><!-- .product_area -->

<?php do_action( 'wcfmmp_store_after_products', $store_user->get_id() ); ?>
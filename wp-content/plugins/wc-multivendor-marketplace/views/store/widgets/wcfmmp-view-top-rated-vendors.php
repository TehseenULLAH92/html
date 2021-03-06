<?php
/**
 * The Template for displaying store sidebar top rated vendors.
 *
 * @package WCfM Markeplace Views Top Rated Vendors
 *
 * For edit coping this to yourtheme/wcfm/store/widgets
 *
 */
global $WCFM, $WCFMmp;
//print_r($vendor_id); 
echo wp_kses_post( apply_filters( 'wcfmmp_before_widget_top_rated_vendor_list', '<ul class="product_list_widget">' ) );

  foreach ( $vendors as $key => $value ) {
    $vendor_id = absint($key);
    
    $is_store_offline = get_user_meta( $vendor_id, '_wcfm_store_offline', true );
		if ( $is_store_offline ) continue;
		
		$is_disable_vendor = get_user_meta( $vendor_id, '_disable_vendor', true );
		if ( $is_disable_vendor ) continue;
    
    $store_name = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($vendor_id) );

    $store_logo = $WCFM->wcfm_vendor_support->wcfm_get_vendor_logo_by_vendor( $vendor_id );
    if( !$store_logo ) {
      $store_logo = $WCFMmp->plugin_url . 'assets/images/wcfmmp.png';
    } ?>
    <li>
	
      <a href="<?php echo wcfmmp_get_store_url( $vendor_id ); ?>">
        <img width="250" height="250" src="<?php echo $store_logo; ?>">
        <span class="product-title"><?php echo $store_name; ?></span>
      </a>
      <span class="vendor_rating">
        <?php $WCFMmp->wcfmmp_reviews->show_star_rating( 0, $vendor_id ); ?>
      </span>
      <span class="vendor_badges">
        <?php do_action('after_wcfmmp_store_list_rating', $vendor_id ); ?>
      </span>
    </li>
    
    
    
  <?php } 
echo wp_kses_post( apply_filters( 'wcfmmp_after_widget_top_rated_vendor_list', '</ul>' ) );

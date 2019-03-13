<?php

/**
 * Class for Dokan Vendor template modification
 *
 * @since 1.0
 */
class Marketo_Dokan {

	/**
	 * Construction function
	 *
	 * @since  1.0
	 *
	 */
	function __construct() {
		// Check if Woocomerce plugin is actived
		if ( ! in_array( 'marketo/dokan.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return;
		}

		// Define all hook
		add_action( 'woocommerce_single_product_summary', array( $this, 'template_loop_sold_by' ) );
		add_filter( 'dokan_dashboard_nav_common_link', array( $this, 'dashboard_nav_common_link' ) );

	}


	/**
	 * Add sold by
	 */
	public function template_loop_sold_by() {
		get_template_part( 'template-parts/vendor/loop', 'sold-by' );
	}

	/**
	 * dashboard_nav_common_link
	 * @Param link
	 */
	public function dashboard_nav_common_link( $common_links ) {
		if ( ! function_exists( 'dokan_get_store_url' ) && ! function_exists( 'dokan_get_navigation_url' ) ) {
			return $common_links;
		}


		$common_links = sprintf(
			'<li class="dokan-common-links dokan-clearfix">' .
			'<a href="%s" >%s</a >' .
			'<a href="%s" >%s</a >' .
			'<a href="%s" >%s</a >' .
			'</li>',
			esc_url( dokan_get_store_url( get_current_user_id() ) ),
			esc_html__( 'Visit Store', 'marketo' ),
			esc_url( dokan_get_navigation_url( 'edit-account' ) ),
			esc_html__( 'Edit Account', 'marketo' ),
			esc_url( wp_logout_url( home_url() ) ),
			esc_html__( 'Log out', 'marketo' )

		);

		return $common_links;
	}

}
$marketo_dokan = new marketo_dokan();
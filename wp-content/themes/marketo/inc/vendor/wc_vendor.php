<?php

/**
 * Class for Wc Vendor template modification
 *
 * @since 1.0
 */
class Marketo_Wc_Vendor {

    /**
     * Construction function
     *
     * @since  1.0
     *
     */
    function __construct() {
        if ( ! in_array( 'wc-vendors/class-wc-vendors.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            return;
        }
        add_action( 'wcvendors_before_dashboard', array( $this, 'xs_vendors_before_dashboard' ) );
        add_action( 'wcvendors_after_dashboard', array( $this, 'xs_vendors_after_dashboard' ) );

    }

    /**
     * vendors_before_dashboard
     */
    public function xs_vendors_before_dashboard() {
        echo '<div class="marketo-wcvendors-dashboard">';
    }

    /**
     * vendors_after_dashboard
     */
    public function xs_vendors_after_dashboard() {
        echo '</div>';
    }


}
$Marketo_Wc_Vendor = new Marketo_Wc_Vendor();
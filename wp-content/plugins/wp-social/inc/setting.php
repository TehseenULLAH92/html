<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */

if ( !class_exists('Wp_Social_Menu_Settings' ) ):
    class Wp_Social_Menu_Settings {
        private $settings_api;
        function __construct() {
            $this->settings_api = new WeDevs_Settings_API;
            add_action( 'admin_init', array($this, 'admin_init') );
            add_action( 'admin_menu', array($this, 'admin_menu') );
        }
        function admin_init() {
            //set the settings
            $this->settings_api->set_sections( $this->get_settings_sections() );
            $this->settings_api->set_fields( $this->get_settings_fields() );
            //initialize settings
            $this->settings_api->admin_init();
        }
        function admin_menu() {
            add_menu_page( 'WP Social', 'Wp Social', 'edit_theme_options', 'wp-social', array($this, 'plugin_page') );
        }
        function get_settings_sections() {
            $sections = array(
                array(
                    'id'    => 'facebook',
                    'title' => __( 'Facebook', 'wp-fundraising' )
                ),
                array(
                    'id'    => 'google',
                    'title' => __( 'Google Plus', 'wp-fundraising' )
                ),

            );
            return $sections;
        }
        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        function get_settings_fields() {
            $settings_fields = array(
                'facebook' => array(
                    array(
                        'name'  => 'app_id',
                        'label' => __( 'App ID', 'wp-fundraising' ),
                        'desc'  => __( 'Enter Client ID', 'wp-fundraising' ),
                        'type'  => 'text'
                    ),
                    array(
                        'name'  => 'app_secret',
                        'label' => __( 'App Secret', 'wp-fundraising' ),
                        'desc'  => __( 'Enter App Secret', 'wp-fundraising' ),
                        'type'  => 'text'
                    ),
                ),

                'google' => array(
                    array(
                        'name'  => 'google_client_id',
                        'label' => __( 'Client ID', 'wp-fundraising' ),
                        'desc'  => __( 'Enter Client ID', 'wp-fundraising' ),
                        'type'  => 'text'
                    ),
                    array(
                        'name'  => 'google_secret',
                        'label' => __( 'Client secret', 'wp-fundraising' ),
                        'desc'  => __( 'Enter Client ID', 'wp-fundraising' ),
                        'type'  => 'text'
                    ),
                ),
            );
            return $settings_fields;
        }
        function plugin_page() {
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        }
        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages() {
            $pages = get_pages();
            $pages_options = array();
            if ( $pages ) {
                foreach ($pages as $page) {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }
            return $pages_options;
        }
    }
endif;


new Wp_Social_Menu_Settings();
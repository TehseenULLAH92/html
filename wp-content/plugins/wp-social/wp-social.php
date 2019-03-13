<?php
/**
Plugin Name: Wp Social
Plugin URI:http://xpeedstudio.com
Description: Wp Social Features is a plugin.
Author: xpeedstudio
Author URI: http://xpeedstudio.com
Version:1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
define( 'XS_SOCIAL_DIR', plugin_dir_path( __FILE__ ) );
class Wp_Social{

    /**
     * Holds the class object.
     *
     * @since 1.0.0
     *
     */

    public static $_instance;

    /**
     * Plugin Name
     *
     * @since 1.0.0
     *
     */

    public $plugin_name = 'Wp Social';

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     */

    public $plugin_version = '1.0.0';

    /**
     * Plugin File
     *
     * @since 1.0.0
     *
     */

    public $file = __FILE__;

    /**
     * Load Construct
     *
     * @since 1.0.0
     */

    public function __construct(){
        $this->wp_social_plugin_init();
    }

    /**
     * Plugin Initialization
     *
     * @since 1.0.0
     *
     */

    public function wp_social_plugin_init(){

        require_once (plugin_dir_path($this->file). 'vendor/autoload.php');
        require_once (plugin_dir_path($this->file). 'inc/auth.php');
        require_once (plugin_dir_path($this->file). 'inc/setting-class.php');
        require_once (plugin_dir_path($this->file). 'inc/setting.php');

    }
    public static function wp_social_get_instance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new Wp_Social();
        }
        return self::$_instance;
    }

}
$Xs_Main = Wp_Social::wp_social_get_instance();
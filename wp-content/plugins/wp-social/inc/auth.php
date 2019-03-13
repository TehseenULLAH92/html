<?php
if (!defined('ABSPATH')) exit;

class Xs_Authitication
{

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

    private $current_url;

    private $available_networks = array('facebook', 'twitter' ,'google');

    public function __construct()
    {
        $this->xs_plugin_init();
    }

    /**
     * Plugin Initialization
     *
     * @since 1.0.0
     *
     */

    public function xs_plugin_init()
    {
        $this->current_url = $this->xs_check_http() . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        add_action('init', array($this, 'xs_auth'), 20);
        add_action('init', array($this, 'xs_auth_callback'), 30);
    }


    public function xs_auth()
    {
        if (empty($_GET['social_auth']) && empty($_GET['code'])) {
            return;
        }
        $network = (empty($_GET['social_auth'])) ? $this->get_current_callback_network() : sanitize_key($_GET['social_auth']);

        if (!in_array($network, $this->available_networks)) return;

        $account_url = $this->get_account_url();
        $security_salt  = '2NlBUibcszrVtNmDnxqDbwCOpLWq91eatIz6O1O';

        $strategy = array();
        switch ($network) {
            case 'google':
                $app_id = xs_get_option('google_client_id', 'google');
                $app_secret = xs_get_option('google_secret', 'google');

                if (empty($app_secret) || empty($app_id)) return;

                $strategy = array(
                    'Google' => array(
                        'client_id' => $app_id,
                        'client_secret' => $app_secret,
                        #'scope' => 'email'
                    ),
                );

                $callback_param = 'oauth2callback';

                break;

            case 'facebook':
                $app_id = xs_get_option('app_id', 'facebook');
                $app_secret = xs_get_option('app_secret', 'facebook');

                if (empty($app_secret) || empty($app_id)) return;

                $strategy = array(
                    'Facebook' => array(
                        'app_id' => $app_id,
                        'app_secret' => $app_secret,
                        'scope' => 'email'
                    ),
                );
                $callback_param = 'int_callback';
                break;

            case 'twitter':
                $app_id = '1701330970141515';
                $app_secret = '8e628235b09301f9f9ece9e7166d6670';

                if (empty($app_secret) || empty($app_id)) return;

                $strategy = array(
                    'Twitter' => array(
                        'key' => '7xFeBSrmgvTDE9l6aHKX3XF4u',
                        'secret' => '75TG5bCmJqJUCUVDGTxW8pHd915KAFgA8py8YrB7j5hUPz80qw',
                    ),
                );
                $callback_param = 'oauth_callback';
                break;
        }

        $config = array(
            'security_salt' => $security_salt,
            'host' => $account_url,
            'path' => '/',
            'callback_url' => $account_url,
            'callback_transport' => 'get',
            'strategy_dir' => XS_SOCIAL_DIR . 'api/',
            'Strategy' => $strategy
        );
        if (empty($_GET['code'])) {
            $config['request_uri'] = '/' . $network;
        } else {
            $config['request_uri'] = '/' . $network . '/' . $callback_param . '?code=' . $_GET['code'];
        }

        new Opauth($config);
    }

    public function get_current_callback_network()
    {
        $account_url = $this->get_account_url();

        foreach ($this->available_networks as $network) {
            if (strstr($this->current_url, trailingslashit($account_url) . $network)) {
                return $network;
            }
        }
        return false;
    }

    public function get_account_url()
    {
        return untrailingslashit( wc_get_page_permalink('myaccount') );
    }

    public function xs_check_http(){
        if (!is_ssl()) {
            return 'http';
        } else {
            return 'https';
        }
    }
    public static function xs_get_instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Xs_Authitication();
        }
        return self::$_instance;
    }

    public function xs_auth_callback()
    {
        if (isset($_GET['error_reason']) && $_GET['error_reason'] == 'user_denied') {
            wp_redirect($this->get_account_url());
            exit;
        }
        if (empty($_GET['opauth'])) return;

        $opauth = json_decode(base64_decode($_GET['opauth']));
        
        switch ($opauth->auth->provider) {

            case 'Google':

                if (empty($opauth->auth->info)) {
                    wc_add_notice(__('Can\'t login with Google. Please, try again later.', 'marketo'), 'error');
                    return;
                }

                $email = $opauth->auth->info->email;

                if (empty($email)) {
                    wc_add_notice(__('Google doesn\'t provide your email. Try to register manually.', 'marketo'), 'error');
                    return;
                }
                $this->xs_register($email);
                break;

            case 'Facebook':
                if (empty($opauth->auth->info)) {
                    wc_add_notice(__('Can\'t login with Facebook. Please, try again later.', 'marketo'), 'error');
                    return;
                }

                $email = $opauth->auth->info->email;

                if (empty($email)) {
                    wc_add_notice(__('Facebook doesn\'t provide your email. Try to register manually.', 'marketo'), 'error');
                    return;
                }
                $this->xs_register($email);
                break;

            case 'Twitter':
                if (empty($opauth->auth->info)) {
                    wc_add_notice(__('Can\'t login with Facebook. Please, try again later.', 'marketo'), 'error');
                    return;
                }

                $email = $opauth->auth->info->email;

                if (empty($email)) {
                    wc_add_notice(__('Facebook doesn\'t provide your email. Try to register manually.', 'marketo'), 'error');
                    return;
                }
                $this->xs_register($email);
                break;

        }
    }

    public function xs_register($email)
    {

        $password = wp_generate_password();
        $customer = wc_create_new_customer($email, '', $password);

        $user = get_user_by('email', $email);

        if (is_wp_error($customer)) {
            if (isset($customer->errors['registration-error-email-exists'])) {
                wc_set_customer_auth_cookie($user->ID);
            }
        } else {

            wc_set_customer_auth_cookie($customer);
        }
        wc_add_notice(sprintf(__('You are now logged in as <strong>%s</strong>', 'marketo'), $user->display_name));
    }

}

$Xs_Main = Xs_Authitication::xs_get_instance();
function xs_get_option($option, $section, $default = ''){

    $options = get_option($section);

    if (isset($options[$option])) {
        return $options[$option];
    }

    return $default;
}
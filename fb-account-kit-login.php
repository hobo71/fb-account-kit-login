<?php
/**
 * Plugin Name: Facebook Account Kit Login
 * Plugin URI: https://wordpress.org/plugins/fb-account-kit-login/
 * Description: Facebook Account Kit Login integration for WordPress. It helps to easily login or register to wordpress by using SMS or Email Verification without any password.
 * Version: 1.0.4
 * Author: Sayan Datta
 * Author URI: https://sayandatta.com/
 * License: GPLv3
 * Text Domain: fb-account-kit-login
 * Domain Path: /languages
 * 
 * Facebook Account Kit Login is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Facebook Account Kit Login is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Facebook Account Kit Login. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category Admin
 * @package  Facebook Account Kit Login
 * @author   Sayan Datta
 * @license  http://www.gnu.org/licenses/ GNU General Public License
 * @link     https://wordpress.org/plugins/fb-account-kit-login/
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define ( 'FBAK_PLUGIN_VERSION', '1.0.4' );

// debug scripts
//define ( 'FBAK_PLUGIN_ENABLE_DEBUG', 'true' );

// Internationalization
add_action( 'plugins_loaded', 'fbak_plugin_load_textdomain' );
/**
 * Load plugin textdomain.
 * 
 * @since 1.0.0
 */
function fbak_plugin_load_textdomain() {
    load_plugin_textdomain( 'fb-account-kit-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}

// register activation hook
register_activation_hook( __FILE__, 'fbak_plugin_activation' );
// register deactivation hook
register_deactivation_hook( __FILE__, 'fbak_plugin_deactivation' );

function fbak_plugin_activation() {
    
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }
    set_transient( 'fbak-admin-notice-on-activation', true, 5 );
}

function fbak_plugin_deactivation() {

    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }
    delete_option( 'fbak_plugin_dismiss_rating_notice' );
    delete_option( 'fbak_plugin_no_thanks_rating_notice' );
    delete_option( 'fbak_plugin_installed_time' );
}

function fbak_plugin_install_notice() { 

    if( get_transient( 'fbak-admin-notice-on-activation' ) ) { ?>
        <div class="notice notice-success">
            <p><strong><?php printf( __( 'Thanks for installing %1$s v%2$s plugin. Click <a href="%3$s">here</a> to configure plugin settings.', 'fb-account-kit-login' ), 'Facebook Account Kit Login', FBAK_PLUGIN_VERSION, admin_url( 'admin.php?page=fb-account-kit-login' ) ); ?></strong></p>
        </div> <?php
        delete_transient( 'fbak-admin-notice-on-activation' );
    }
}

add_action( 'admin_notices', 'fbak_plugin_install_notice' ); 

function fbak_plugin_register_scripts() {

    $fbak_settings = get_option('fbak_plugin_settings');
    $ver = FBAK_PLUGIN_VERSION;
    if( defined( 'FBAK_PLUGIN_ENABLE_DEBUG' ) ) {
        $ver = time();
    }

    wp_register_style( 'fbak-admin', plugins_url( 'admin/css/admin.min.css', __FILE__ ), array(), $ver );
    wp_register_style( 'fbak-lightbox', plugins_url( 'admin/css/jquery.fancybox.min.css', __FILE__ ), array(), $ver );
    wp_register_style( 'fbak-frontend', plugins_url( 'includes/public/css/frontend.min.css', __FILE__ ), array(), $ver );
    wp_register_style( 'fbak-login', plugins_url( 'includes/public/css/wp-login.min.css', __FILE__ ), array(), $ver );

    wp_register_script( 'fbak-admin-js', plugins_url( 'admin/js/admin.min.js', __FILE__ ), array( 'jquery' ), $ver );
    wp_register_script( 'fbak-lightbox-js', plugins_url( 'admin/js/jquery.fancybox.min.js', __FILE__ ), array( 'jquery' ), $ver );
    wp_register_script( 'fbak-fb-account-kit', 'https://sdk.accountkit.com/' . fbak_get_account_kit_locale() . '/sdk.js', array(), null, true );
    wp_register_script( 'fbak-fb-account-kit-admin', plugins_url( 'includes/js/profile.min.js', __FILE__ ), array( 'jquery', 'fbak-fb-account-kit' ), $ver );
    wp_register_script( 'fbak-fb-account-kit-js', plugins_url( 'includes/public/js/account-kit.min.js', __FILE__ ), array( 'jquery', 'fbak-fb-account-kit' ), $ver, true );
    wp_register_script( 'fbak-fb-account-kit-login', plugins_url( 'includes/public/js/wp-login.min.js', __FILE__ ), array( 'jquery', 'fbak-fb-account-kit' ), $ver, true );
    wp_localize_script( 'fbak-fb-account-kit', 'FBAccountKitLogin', array(
        'ajaxurl'  => admin_url( 'admin-ajax.php' ),
        'app_id'   => $fbak_settings['fbak_app_id'],
        'version'  => fbak_get_fb_app_api_version(),
        'display'  => $fbak_settings['fbak_login_form_type'],
        'nonce'    => wp_create_nonce( 'fbak_fb_account_kit' ),
        'redirect' => fbak_get_email_login_redirect_url(),
    ) );
}

add_action( 'wp_enqueue_scripts', 'fbak_plugin_register_scripts' );
add_action( 'admin_enqueue_scripts', 'fbak_plugin_register_scripts' );
add_action( 'login_enqueue_scripts', 'fbak_plugin_register_scripts' );

function fbak_load_admin_assets( $hook ) {
    // get current screen
    $current_screen = get_current_screen();
    if ( strpos( $current_screen->base, 'fb-account-kit-login') !== false ) {
        wp_enqueue_style( 'fbak-admin' );
        wp_enqueue_style( 'fbak-lightbox' );

        wp_enqueue_script( 'fbak-admin-js' );
        wp_enqueue_script( 'fbak-lightbox-js' );
    }
    if ( 'profile.php' === $hook || 'user-edit.php' === $hook ) {
        wp_enqueue_script( 'fbak-fb-account-kit' );
        wp_enqueue_script( 'fbak-fb-account-kit-admin' );
    }
}

function fbak_add_account_kit_scripts() {
    wp_enqueue_style( 'fbak-frontend' );
    
    wp_enqueue_script( 'fbak-fb-account-kit' );
    wp_enqueue_script( 'fbak-fb-account-kit-js' );
}

function fbak_add_async_defer_attribute( $tag, $handle ) {
    // if the unique handle/name of the registered script has 'async' in it
    if ( $handle === 'fbak-fb-account-kit' ) {
        return str_replace( '<script ', '<script async defer ', $tag );
    } else {
        return $tag;
    }
}

add_action( 'admin_enqueue_scripts', 'fbak_load_admin_assets' );
add_action( 'wp_enqueue_scripts', 'fbak_add_account_kit_scripts' );
add_filter( 'script_loader_tag', 'fbak_add_async_defer_attribute', 10, 2 );

function fbak_ajax_save_admin_scripts() {
    if ( is_admin() ) { 
        // Embed the Script on our Plugin's Option Page Only
        if ( isset($_GET['page']) && $_GET['page'] == 'fb-account-kit-login' ) {
            wp_enqueue_script('jquery');
            wp_enqueue_script( 'jquery-form' );
        }
    }
}

add_action( 'admin_init', 'fbak_ajax_save_admin_scripts' );

require_once plugin_dir_path( __FILE__ ) . 'admin/settings-loader.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/settings-fields.php';

// register admin menu
add_action( 'admin_menu', 'fbak_admin_menu' );

function fbak_admin_menu() {
    //Add admin menu option
    add_menu_page( __( 'Facebook Account Kit Login', 'fb-account-kit-login' ), __( 'Account Kit', 'fb-account-kit-login' ), 'manage_options', 'fb-account-kit-login', 'fbak_plugin_settings_page' , 'dashicons-facebook', 100 );
}

function fbak_plugin_settings_page() { 
    $fbak_settings = get_option( 'fbak_plugin_settings' ); 
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
}

require_once plugin_dir_path( __FILE__ ) . 'admin/notice.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/donate.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';

// add action links
function fbak_add_action_links ( $links ) {
    $fbaklinks = array(
        '<a href="' . admin_url( 'admin.php?page=fb-account-kit-login' ) . '">' . __( 'Settings', 'fb-account-kit-login' ) . '</a>',
    );
    return array_merge( $fbaklinks, $links );
}

function fbak_plugin_meta_links( $links, $file ) {
    $plugin = plugin_basename(__FILE__);
    if ( $file == $plugin ) // only for this plugin
        return array_merge( $links, 
            array( '<a href="https://wordpress.org/support/plugin/fb-account-kit-login" target="_blank">' . __( 'Support', 'fb-account-kit-login' ) . '</a>' ),
            array( '<a href="http://bit.ly/2I0Gj60" target="_blank">' . __( 'Donate', 'fb-account-kit-login' ) . '</a>' )
        );
    return $links;
}

// plugin action links
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'fbak_add_action_links', 10, 2 );

// plugin row elements
add_filter( 'plugin_row_meta', 'fbak_plugin_meta_links', 10, 2 );

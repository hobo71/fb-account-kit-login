<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    Facebook Account Kit Login
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

if( fbak_enable_on_wp_login_form() ) {
    add_action( 'login_enqueue_scripts', 'fbak_add_login_scripts' );
    add_action( 'login_form', 'fbak_add_custom_login_form' );
    add_action( 'login_head', 'fbak_add_custom_css_to_login_head' );
    add_filter( 'login_message', 'fbak_auth_fail_login_message' );
}

function fbak_add_login_scripts() {
    if ( fbak_enable_sms_login_method() || fbak_enable_email_login_method() ) {
        wp_enqueue_style( 'fbak-login' );
    
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'fbak-fb-account-kit' );
        wp_enqueue_script( 'fbak-fb-account-kit-js' );
        wp_enqueue_script( 'fbak-fb-account-kit-login' );
    }
}

function fbak_add_custom_login_form() {
    if ( fbak_enable_sms_login_method() || fbak_enable_email_login_method() ) {
        require_once plugin_dir_path( __FILE__ ) . 'views/wp-login.php';
    }
}

function fbak_add_custom_css_to_login_head() {
    $fbak_settings = get_option( 'fbak_plugin_settings' );

    $style = '';
    if( !empty( $fbak_settings['fbak_custom_css'] ) ) {
        $style .= '<style type="text/css">' . $fbak_settings['fbak_custom_css'] . '</style>'."\n";
    }

    if ( isset($fbak_settings['fbak_hide_default_login_form']) && $fbak_settings['fbak_hide_default_login_form'] == 'yes' ) {
        $style .= '<style type="text/css">.fb-ackit-form-display p#nav { display: none; }</style>'."\n";
    }
    
    echo $style;
}

function fbak_auth_fail_login_message() {
    $fbak_settings = get_option( 'fbak_plugin_settings' );

    $text = __( 'You are not a registered user of this website.', 'fb-account-kit-login' );
    if( !empty($fbak_settings['fbak_disable_user_reg_message']) ) {
        $text = strip_tags( $fbak_settings['fbak_disable_user_reg_message'] );
    }
    $text = apply_filters( 'fbak/account_kit_login_error_message', $text );

    if ( isset($_GET['fbak_login_error']) && $_GET['fbak_login_error'] === 'true' ) {
        $message = '<div id="login_error">' . $text . '</div>';
        return $message;
    }
}
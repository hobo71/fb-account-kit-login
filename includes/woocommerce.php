<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    Facebook Account Kit Login
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

$fbak_settings = get_option( 'fbak_plugin_settings' );

if ( ( isset($fbak_settings['fbak_enable_sms_login']) && $fbak_settings['fbak_enable_sms_login'] == 1 ) || ( isset($fbak_settings['fbak_enable_email_login']) && $fbak_settings['fbak_enable_email_login'] == 1 ) ) {
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        if ( isset($fbak_settings['fbak_add_woocommerce_support']) && $fbak_settings['fbak_add_woocommerce_support'] == 'woo_login' ) {
    
            add_action( 'woocommerce_login_form_end', 'fbak_add_to_woo_login_form' );
            add_action( 'wp_enqueue_scripts', 'fbak_add_woocommerce_scripts' );
    
        } elseif ( isset($fbak_settings['fbak_add_woocommerce_support']) && $fbak_settings['fbak_add_woocommerce_support'] == 'woo_reg' ) {
    
            add_action( 'woocommerce_register_form_end', 'fbak_add_to_woo_login_form' );
            add_action( 'wp_enqueue_scripts', 'fbak_add_woocommerce_scripts' );
    
        } elseif ( isset($fbak_settings['fbak_add_woocommerce_support']) && $fbak_settings['fbak_add_woocommerce_support'] == 'woo_both' ) {
    
            add_action( 'woocommerce_login_form_end', 'fbak_add_to_woo_login_form' );
            add_action( 'woocommerce_register_form_end', 'fbak_add_to_woo_login_form' );
            add_action( 'wp_enqueue_scripts', 'fbak_add_woocommerce_scripts' );
    
        }
    }
}

function fbak_add_woocommerce_scripts() {
    $myaccount_page_id = wc_get_page_id( 'myaccount' );
    $checkout_page_id = wc_get_page_id( 'checkout' );

    if( is_page( $myaccount_page_id ) || is_page( $checkout_page_id ) ) {
        wp_enqueue_style( 'fbak-frontend-css' );
        
        wp_enqueue_script( 'fbak-fb-account-kit' );
        wp_enqueue_script( 'fbak-fb-account-kit-js' );
    }
}

function fbak_add_to_woo_login_form() {

    $fbak_settings = get_option( 'fbak_plugin_settings' );

    $sms_label = !empty($fbak_settings['fbak_sms_label_text']) ? $fbak_settings['fbak_sms_label_text'] : 'Login with SMS';
    $email_label = !empty($fbak_settings['fbak_email_label_text']) ? $fbak_settings['fbak_email_label_text'] : 'Login with Email';
    
    $sms_label = apply_filters( 'fbak/woocommerce_sms_label', $sms_label );
    $email_label = apply_filters( 'fbak/woocommerce_email_label', $email_label );

    ?>
    <div class="fb-ackit-wrap">
        <div class="fb-ackit-or">
            <span><?php _e( 'Or', 'fb-account-kit' ); ?></span>
        </div>
        <div class="fb-ackit-buttons">
            <?php if ( isset($fbak_settings['fbak_enable_sms_login']) && $fbak_settings['fbak_enable_sms_login'] == 1 ) : ?>
                <button href="#" onclick="smsLogin(); return false;" class="button"><?php echo $sms_label; ?></button>
            <?php endif; ?>
            <?php if ( isset($fbak_settings['fbak_enable_email_login']) && $fbak_settings['fbak_enable_email_login'] == 1 ) : ?>
                <button href="#" onclick="emailLogin(); return false;" class="button"><?php echo $email_label; ?></button>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
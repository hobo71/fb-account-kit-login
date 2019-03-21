<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    Facebook Account Kit Login
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    if ( fbak_enable_sms_login_method() || fbak_enable_email_login_method() ) {
        $fbak_settings = get_option( 'fbak_plugin_settings' );
        
        if ( isset($fbak_settings['fbak_woocommerce_login_element']) && $fbak_settings['fbak_woocommerce_login_element'] == 'woo_login' ) {
    
            add_action( 'woocommerce_login_form_end', 'fbak_add_html_element_to_woo_login_form' );
    
        } elseif ( isset($fbak_settings['fbak_woocommerce_login_element']) && $fbak_settings['fbak_woocommerce_login_element'] == 'woo_reg' ) {
    
            add_action( 'woocommerce_register_form_end', 'fbak_add_html_element_to_woo_login_form' );
    
        } elseif ( isset($fbak_settings['fbak_woocommerce_login_element']) && $fbak_settings['fbak_woocommerce_login_element'] == 'woo_both' ) {
    
            add_action( 'woocommerce_login_form_end', 'fbak_add_html_element_to_woo_login_form' );
            add_action( 'woocommerce_register_form_end', 'fbak_add_html_element_to_woo_login_form' );
    
        }
    }
}

function fbak_add_html_element_to_woo_login_form() {
    $fbak_settings = get_option( 'fbak_plugin_settings' );

    $sms_label = !empty($fbak_settings['fbak_sms_label_text']) ? $fbak_settings['fbak_sms_label_text'] : __( 'Login with SMS', 'fb-account-kit-login' );
    $email_label = !empty($fbak_settings['fbak_email_label_text']) ? $fbak_settings['fbak_email_label_text'] : __( 'Login with Email', 'fb-account-kit-login' );
    $sms_class = !empty($fbak_settings['fbak_sms_btn_class']) ? $fbak_settings['fbak_sms_btn_class'] : 'button btn';
    $email_class = !empty($fbak_settings['fbak_email_btn_class']) ? $fbak_settings['fbak_email_btn_class'] : 'button btn';
    
    $sms_label = apply_filters( 'fbak/woocommerce_sms_label', $sms_label );
    $email_label = apply_filters( 'fbak/woocommerce_email_label', $email_label );
    $sms_class = apply_filters( 'fbak/woocommerce_sms_class', $sms_class );
    $email_class = apply_filters( 'fbak/woocommerce_email_class', $email_class );

    ?>
    <div class="fb-ackit-wrap">
        <div class="fb-ackit-or">
            <span><?php _e( 'Or', 'fb-account-kit' ); ?></span>
        </div>
        <div class="fb-ackit-buttons">
            <?php if ( fbak_enable_sms_login_method() ) : ?>
                <button href="#" onclick="smsLogin(); return false;" class="<?php echo $sms_class; ?>"><?php echo $sms_label; ?></button>
            <?php endif; ?>
            <?php if ( fbak_enable_email_login_method() ) : ?>
                <button href="#" onclick="emailLogin(); return false;" class="<?php echo $email_class; ?>"><?php echo $email_label; ?></button>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    Facebook Account Kit Login
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

add_shortcode( 'fbak-account-kit', 'fbak_login_register_button_shortcode' );
add_action( 'wp_enqueue_scripts', 'fbak_add_shortcode_scripts' );

function fbak_login_register_button_shortcode( $atts ) {

    $fbak_settings = get_option( 'fbak_plugin_settings' );
    $atts = shortcode_atts(
		array(
            'sms_login'    => isset($fbak_settings['fbak_enable_sms_login']) ? $fbak_settings['fbak_enable_sms_login'] : 1,
            'email_login'  => isset($fbak_settings['fbak_enable_email_login']) ? $fbak_settings['fbak_enable_email_login'] : 1,
            'sms_class'    => !empty($fbak_settings['fbak_sms_btn_class']) ? $fbak_settings['fbak_sms_btn_class'] : 'button btn',
            'email_class'  => !empty($fbak_settings['fbak_email_btn_class']) ? $fbak_settings['fbak_email_btn_class'] : 'button btn',
            'sms_label'    => !empty($fbak_settings['fbak_sms_label_text']) ? $fbak_settings['fbak_sms_label_text'] : 'Login with SMS',
            'email_label'  => !empty($fbak_settings['fbak_email_label_text']) ? $fbak_settings['fbak_email_label_text'] : 'Login with Email',
            'description'  => !empty($fbak_settings['fbak_login_description']) ? $fbak_settings['fbak_login_description'] : 'Save time by logging-in with your Phone number or Email address, without password.',
        ), $atts, 'fbak-account-kit' );

    wp_enqueue_script( 'fbak-fb-account-kit' );
    wp_enqueue_script( 'fbak-fb-account-kit-js' ); ?>

    <div class="fb-ackit-wrap ackit-shortcode">
        <div class="fb-ackit-desc"><?php echo $atts['description']; ?></div>
        <div class="fb-ackit-buttons">
            <?php if ( isset($atts['sms_login']) && $atts['sms_login'] == 1 ) : ?>
                <button href="#" onclick="smsLogin(); return false;" class="<?php echo $atts['sms_class']; ?>"><?php echo $atts['sms_label']; ?></button>
            <?php endif; ?>
            <?php if ( isset($atts['email_login']) && $atts['email_login'] == 1 ) : ?>
                <button href="#" onclick="emailLogin(); return false;" class="<?php echo $atts['email_class']; ?>"><?php echo $atts['email_label']; ?></button>
            <?php endif; ?>
        </div>
    </div>

    <?php
}

function fbak_add_shortcode_scripts() {

    if( is_user_logged_in() ) {
        return;
    }

    wp_enqueue_style( 'fbak-frontend-css' );
}
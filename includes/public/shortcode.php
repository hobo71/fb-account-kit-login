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
add_shortcode( 'fbak-account-kit-associate', 'fbak_login_register_associate_shortcode' );

function fbak_login_register_button_shortcode( $atts ) {
    $fbak_settings = get_option( 'fbak_plugin_settings' );

    $atts = shortcode_atts(
		array(
            'sms_login'    => isset($fbak_settings['fbak_enable_sms_login']) ? $fbak_settings['fbak_enable_sms_login'] : 1,
            'email_login'  => isset($fbak_settings['fbak_enable_email_login']) ? $fbak_settings['fbak_enable_email_login'] : 1,
            'sms_class'    => !empty($fbak_settings['fbak_sms_btn_class']) ? $fbak_settings['fbak_sms_btn_class'] : 'button btn',
            'email_class'  => !empty($fbak_settings['fbak_email_btn_class']) ? $fbak_settings['fbak_email_btn_class'] : 'button btn',
            'sms_label'    => !empty($fbak_settings['fbak_sms_label_text']) ? $fbak_settings['fbak_sms_label_text'] : __( 'Login with SMS', 'fb-account-kit-login' ),
            'email_label'  => !empty($fbak_settings['fbak_email_label_text']) ? $fbak_settings['fbak_email_label_text'] : __( 'Login with Email', 'fb-account-kit-login' ),
            'description'  => !empty($fbak_settings['fbak_login_description']) ? $fbak_settings['fbak_login_description'] : __( 'Save time by logging-in with your Phone number or Email address, without password.', 'fb-account-kit-login' ),
            'notice_text'  => !empty($fbak_settings['fbak_auth_waiting_message']) ? $fbak_settings['fbak_auth_waiting_message'] : __( 'Please wait until we authenticate you.', 'fb-account-kit-login' ),
        ), $atts, 'fbak-account-kit' );

    $html = '<div class="fb-ackit-wrap ackit-shortcode">';
        $html .= '<div class="fb-ackit-desc">' . $atts['description'] . '</div>';
        $html .= '<div class="fb-ackit-buttons">';
        if ( isset($atts['sms_login']) && ( $atts['sms_login'] == 1 || $atts['sms_login'] == 'yes' ) ) {
            $html .= '<button href="#" onclick="smsLogin(); return false;" class="' . $atts['sms_class'] . '" style="margin-right: 5px;">' . $atts['sms_label'] . '</button>';
        }
        if ( isset($atts['email_login']) && ( $atts['email_login'] == 1 || $atts['email_login'] == 'yes' ) ) {
            $html .= '<button href="#" onclick="emailLogin(); return false;" class="' . $atts['email_class'] . '">' . $atts['email_label'] . '</button>';
        }
        $html .= '</div>';
    $html .= '</div>';
    $html .= '<div class="fb-ackit-wait" style="text-align: center;display: none;">' . $atts['notice_text'] . '</div>';

    if( ! is_user_logged_in() ) {
        return $html;
    }
}

function fbak_login_register_associate_shortcode( $atts ) {
    $fbak_settings = get_option( 'fbak_plugin_settings' );

    $connected = get_user_meta( get_current_user_id(), '_fb_accountkit_id', true );
    $mode = get_user_meta( get_current_user_id(), '_fb_accountkit_auth_mode', true );

    $success = __( 'Connected', 'fb-account-kit-login' );
    if ( $mode === 'phone' ) {
        $success = __( 'Connected via Phone', 'fb-account-kit-login' );
    } elseif ( $mode === 'email' ) {
        $success = __( 'Connected via Email', 'fb-account-kit-login' );
    }

    $atts = shortcode_atts(
		array(
            'sms_class'    => 'button btn',
            'email_class'  => 'button btn',
            'sms_label'    => __( 'Connect with Phone', 'fb-account-kit-login' ),
            'email_label'  => __( 'Connect with Email', 'fb-account-kit-login' ),
            'description'  => __( 'Save time by logging-in with your Phone number or Email address, without password.', 'fb-account-kit-login' ),
        ), $atts, 'fbak-account-kit-associate' );

    $html = '<div class="fb-ackit-associate">';
        $html .= '<div class="fb-ackit-associate-desc">' . $atts['description'] . '</div>';
        $html .= '<div class="fb-ackit-associate-buttons">';
        if( ! $connected ) {
            if ( fbak_enable_sms_login_method() ) {
                $html .= '<button class="' . $atts['sms_class'] . '" style="margin-right: 5px;" onclick="smsLogin(); return false;">' . $atts['sms_label'] . '</button>';
            }
            if ( fbak_enable_email_login_method() ) {
                $html .= '<button class="' . $atts['email_class'] . '" onclick="emailLogin(); return false;">' . $atts['email_label'] . '</button>';
            }
        } else {
            $html .= '<button class="' . $atts['sms_class'] . '" disabled style="opacity: .5 !important;cursor: not-allowed;margin-right: 5px;">' . $success . '</button>';
            $html .= '<button class="' . $atts['email_class'] . '" onclick="fbAcDisconnect(); return false;">' . __( 'Disconnect', 'fb-account-kit-login' ) . '</button>';
        }
        $html .= '<span id="fbak-user-id" style="display: none;">' . get_current_user_id() . '</span><span id="fbak-check-msg" style="display: none;">' . fbak_get_disconnect_confirm_message() . '</span>';
        $html .= '</div>';
    $html .= '</div>';

    if( is_user_logged_in() ) {
        wp_enqueue_script( 'fbak-fb-account-kit' );
        wp_enqueue_script( 'fbak-fb-account-kit-admin' );

        return $html;
    }
}
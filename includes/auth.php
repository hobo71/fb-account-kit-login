<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    Facebook Account Kit Login
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

add_action( 'wp_ajax_nopriv_fbak_fb_account_kit_auth_login', 'fbak_process_auth_login' );

// admin profile
add_action( 'wp_ajax_fbak_fb_account_kit_associate', 'fbak_associate_phone_number_email' );
add_action( 'wp_ajax_fbak_fb_account_kit_disconnect', 'fbak_disconnect_phone_number_email' );

/**
 * Send a GET request to the API
 *
 * @param  string $url
 *
 * @return array
 */
function fbak_send_remote_request_url( $url ) {
    $response = wp_remote_get( $url );
    $result = wp_remote_retrieve_body( $response );

    return json_decode( $result, true );
}

/**
 * Authorize accountkit with a authorization code
 *
 * @param  string $code
 *
 * @return array
 */
function fbak_authorize_with_account_kit( $code ) {
    $fbak_settings = get_option( 'fbak_plugin_settings' );

    $app_id  = $fbak_settings['fbak_app_id'];
    $secret  = $fbak_settings['fbak_accountkit_secret_key'];
    $version = fbak_get_fb_app_api_version();

    $token_exchange_url = 'https://graph.accountkit.com/' . $version . '/access_token?' .
      'grant_type=authorization_code'.
      '&code=' . $code .
      "&access_token=AA|$app_id|$secret";
    $data = fbak_send_remote_request_url( $token_exchange_url );
    $user_id = $data['id'];
    $access_token = $data['access_token'];
    $refresh_interval = $data['token_refresh_interval_sec'];
    $appsecret_proof = hash_hmac( 'sha256', $access_token, $secret );

    // Get Account Kit information
    $me_endpoint_url = 'https://graph.accountkit.com/'.$version.'/me?'.
      'access_token=' . $access_token . '&appsecret_proof=' . $appsecret_proof;
    $me_data = fbak_send_remote_request_url( $me_endpoint_url );

    return $me_data;
}

/**
 * Process user login
 *
 * @return void
 */
function fbak_process_auth_login() {
    // Check the referrer for the AJAX call.
    check_ajax_referer( 'fbak_fb_account_kit', 'csrf' );

    $fbak_settings = get_option( 'fbak_plugin_settings' );
    
    $me_data = fbak_authorize_with_account_kit( $_POST['code'] );

    $phone = isset($me_data['phone']) ? ( isset($fbak_settings['fbak_sms_country_codes']) && $fbak_settings['fbak_sms_country_codes'] == 'remove' ? $me_data['phone']['national_number'] : $me_data['phone']['number'] ) : '';
    $email = isset($me_data['email']) ? $me_data['email']['address'] : '';
    $id = isset($me_data['id']) ? $me_data['id'] : 0;
    
    $login_error_url = add_query_arg( 'fbak_login_error', 'true', wp_login_url() );
    $login_error_url = apply_filters( 'fbak/account_kit_login_error_url', $login_error_url );

    if ( $email ) {
        $user = fbak_handle_email_login( $email, $id );

        if ( $user ) {
            wp_set_current_user( $user->ID, $user->user_login );
            wp_set_auth_cookie( $user->ID, true );

            if ( apply_filters( 'fbak/account_kit_sync_with_wp_login', true ) ) {
                do_action( 'wp_login', $user->user_login, $user );
            }

            // update the account kit reference
            update_user_meta( $user->ID, '_fb_accountkit_id', $id );
            update_user_meta( $user->ID, '_fb_accountkit_auth_mode', 'email' );

            do_action( 'fbak_user_login_via_email', $user );

            wp_send_json_success( array(
                'redirect' => esc_url( $_POST['email_redir'] )
            ) );
        } else {
            wp_send_json_error( array(
                'redirect' => esc_url( $login_error_url )
            ) );
        }
    }

    if ( $phone ) {
        $user = fbak_handle_phone_login( $phone, $id );

        if ( $user ) {
            wp_set_current_user( $user->ID, $user->user_login );
            wp_set_auth_cookie( $user->ID, true );

            if ( apply_filters( 'fbak/account_kit_sync_with_wp_login', true ) ) {
                do_action( 'wp_login', $user->user_login, $user );
            }

            // update the account kit reference
            update_user_meta( $user->ID, '_fb_accountkit_id', $id );
            update_user_meta( $user->ID, '_fb_accountkit_auth_mode', 'phone' );
            
            do_action( 'fbak_user_login_via_sms', $user );

            wp_send_json_success( array(
                'redirect' => esc_url( $_POST['sms_redir'] )
            ) );
        } else {
            wp_send_json_error( array(
                'redirect' => esc_url( $login_error_url )
            ) );
        }
    }

    die();
}

/**
 * Associate phone number with a account
 *
 * @since 1.0.0
 *
 * @return void
 */
function fbak_associate_phone_number_email() {
    // Check the referrer for the AJAX call.
    check_ajax_referer( 'fbak_fb_account_kit', 'csrf' );

    $fbak_settings = get_option( 'fbak_plugin_settings' );

    $me_data = fbak_authorize_with_account_kit( $_POST['code'] );

    $phone = isset($me_data['phone']) ? ( isset($fbak_settings['fbak_sms_country_codes']) && $fbak_settings['fbak_sms_country_codes'] == 'remove' ? $me_data['phone']['national_number'] : $me_data['phone']['number'] ) : '';
    $email = isset($me_data['email']) ? $me_data['email']['address'] : '';
    $id = isset($me_data['id']) ? $me_data['id'] : 0;

    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : get_current_user_id();

    if ( $id ) {
        update_user_meta( $user_id, '_fb_accountkit_id', $id );
    }

    if ( $phone ) {
        update_user_meta( $user_id, 'phone_number', $phone );
        update_user_meta( $user_id, 'billing_phone', $phone ); // update woocommerce phone number
        update_user_meta( $user_id, '_fb_accountkit_auth_mode', 'phone' );
    }

    if ( $email ) {
        update_user_meta( $user_id, '_fb_accountkit_auth_mode', 'email' );
    }

    wp_send_json_success();

    die();
}

/**
 * Disconnect a phone number
 *
 * @since 1.0.0
 *
 * @return void
 */
function fbak_disconnect_phone_number_email() {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : get_current_user_id();

    delete_user_meta( $user_id, '_fb_accountkit_id' );
    delete_user_meta( $user_id, '_fb_accountkit_auth_mode' );

    wp_send_json_success();

    die();
}

/**
 * Handle the user email response
 *
 * @param  string $email
 *
 * @return $user
 */
function fbak_handle_email_login( $email, $account_id ) {
    $fbak_settings = get_option( 'fbak_plugin_settings' );
    global $wpdb;

    $user = get_user_by( 'email', $email );

    if ( ! $user ) {
        $get_user = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '_fb_accountkit_id' AND meta_value = %s", $account_id ) );
        $user = get_user_by( 'id', $get_user );

        if ( ! $user ) {
            $username = current( explode( '@', $email ) );
            $user = get_user_by( 'login', $username );

            if ( isset($fbak_settings['fbak_email_new_register']) && $fbak_settings['fbak_email_new_register'] == 1 ) {
                if ( ! $user ) {
                    $username = fbak_guess_username_by_email( $email );
                    $user_pass = apply_filters( 'fbak/auto_generated_user_password', true ) ? wp_generate_password( 12, true ) : $username;
            
                    $userdata = array(
                        'user_login'  => $username,
                        'user_pass'   => $user_pass,
                        'user_email'  => $email,
                        'role'        => fbak_get_email_new_user_role(),
                    );

                    if ( isset($fbak_settings['fbak_email_new_user_dn']) && !empty($fbak_settings['fbak_email_new_user_dn']) ) {
                        $userdata['display_name'] = apply_filters( 'fbak/email_login_new_user_register', esc_html( $fbak_settings['fbak_email_new_user_dn'] ), $username, $email );
                    }
        
                    $user_id = wp_insert_user( $userdata );
                    $user = get_user_by( 'id', $user_id );

                    update_user_meta( $user->ID, 'ackit_woo_passcode', $user_pass );

                    do_action( 'fbak_create_new_user_via_email', $user );
                }
            }
        }
    }

    return $user;
}

/**
 * Handle the phone authentication response
 *
 * @param  string $phone
 * @param  integer $account_id
 *
 * @return $user
 */
function fbak_handle_phone_login( $phone_no, $account_id ) {
    $fbak_settings = get_option( 'fbak_plugin_settings' );
    global $wpdb;

    $get_user = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '_fb_accountkit_id' AND meta_value = %s", $account_id ) );
    $user = get_user_by( 'id', $get_user );

    if ( ! $user ) {
        $phone = str_replace( '+', '', $phone_no ); // remove the '+' sign if exists
        $phone = apply_filters( 'fbak/custom_phone_number_format', $phone );
        $user = get_user_by( 'login', $phone );

        if ( isset($fbak_settings['fbak_sms_new_register']) && $fbak_settings['fbak_sms_new_register'] == 1 ) {
            if ( ! $user ) {
                $username  = $phone;
                $user_pass = apply_filters( 'fbak/auto_generated_user_password', true ) ? wp_generate_password( 12, true ) : $phone;
                $email = str_replace( array( 'https://', 'http://', 'www.' ), '', home_url() );
                $email = current( explode( '/', $email ) );
                $email = $username . '@' . apply_filters( 'fbak/sms_login_email_address', $email ); // generate a fake email address
        
                $userdata = array(
                    'user_login'  => $username,
                    'user_pass'   => $user_pass,
                    'user_email'  => $email,
                    'role'        => fbak_get_sms_new_user_role(),
                );

                if ( isset($fbak_settings['fbak_sms_new_user_dn']) && !empty($fbak_settings['fbak_sms_new_user_dn']) ) {
                    $userdata['display_name'] = apply_filters( 'fbak/sms_login_new_user_register', esc_html( $fbak_settings['fbak_sms_new_user_dn'] ), $username, $phone_no );
                }
    
                $user_id = wp_insert_user( $userdata );
                $user = get_user_by( 'id', $user_id );

                update_user_meta( $user->ID, 'ackit_woo_passcode', $user_pass );

                update_user_meta( $user->ID, 'phone_number', $phone_no );
                update_user_meta( $user->ID, 'billing_phone', $phone_no ); // update woocommerce phone number
                
                do_action( 'fbak_create_new_user_via_sms', $user );
            }
        }
    }

    return $user;
}
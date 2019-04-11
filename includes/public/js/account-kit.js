(function($) {

    AccountKit_OnInteractive = function() {
        AccountKit.init({
            appId: FBAccountKitLogin.app_id,
            state: FBAccountKitLogin.nonce,
            version: FBAccountKitLogin.version,
            redirect: FBAccountKitLogin.redirect,
            display: FBAccountKitLogin.display,
            fbAppEventsEnabled: true,
        });
    };

    // login callback
    function loginCallback(response) {
        if (response.status === "PARTIALLY_AUTHENTICATED") {
            var data = {
                code: response.code,
                csrf: response.state,
                sms_redir: FBAccountKitLogin.sms_redir,
                email_redir: FBAccountKitLogin.email_redir,
                action: 'fbak_fb_account_kit_auth_login'
            };

            $('.fb-ackit-wrap').addClass('loading');
            $('.fb-ackit-wait').show();

            // Send code to server to exchange for access token
            $.post(FBAccountKitLogin.ajaxurl, data, function(response, textStatus, xhr) {
                window.location.href = response.data.redirect;
            });
        }
    }

    // phone form submission handler
    window.smsLogin = function() {
        AccountKit.login('PHONE',{},loginCallback);
    }

    // email form submission handler
    window.emailLogin = function() {
        AccountKit.login('EMAIL',{},loginCallback);
    }

})(jQuery);

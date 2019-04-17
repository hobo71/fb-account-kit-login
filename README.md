![alt text](https://github.com/iamsayan/fb-account-kit-login/raw/master/banner.png "Plugin Banner")

# Facebook Account Kit Login

🔥 The easiest and secure solution for login or register to WordPress by using SMS or Email Verification without any password using Facebook's Secure Authentication.

## Description

The Facebook Account Kit Login plugin brings a lightweight, flexible and easy way to configure Password Less Login to WordPress website. This plugin helps to easily login or register to wordpress by using SMS or Email Verification without any password. You can customize every setting of this plugin in the admin dashboard.

### Features

 * **Login with SMS** (Phone).
 * **Login with WhatsApp**.
 * **Login with Email**.
 * **WooCommerce Support.**
 * Totally **Free of Cost SMS Service**.
 * **Shortcode** Compatible.
 * Dedicated **Widget**.
 * **Compatible with Jetpack**
 * **Compatible with Custom Login URL**
 
![alt text](https://github.com/iamsayan/fb-account-kit-login/raw/master/how-it-works.jpeg "How it Works")

### This is How It Works:

* Instead of asking users for a username and password when they try to log in to your website, it simply asks them for their phone number or email.
* Account Kit servers send an SMS with a confirmation code to the phone number (or WhatsApp Account) or send an email with a confirmation link to the email address to continue the login.
* If users fail to receive the SMS code, it offers two other methods that people can choose from the Phone call or Facebook notification.
* The SDK verifies the SMS confirmation code or monitors the status of the confirmation email. Account Kit may also verify the phone number directly without sending an SMS code.
* After successful verification of that authentication this plugin creates the log in WordPress cookie, successfully authenticating the user if the user alredy exists. Otherwise it will create a new user which depends upon plugin settings.

For more information about Facebook Account Kit please [click here](https://developers.facebook.com/docs/accountkit/overview).

#### Plugin Demo

> For Demo Login: [Click Here](https://demo.sayandatta.com/login)

#### Compatibility

* This plugin is fully compatible with WordPress Version 4.0 and beyond and also compatible with any WordPress theme.

#### Support

* Community support via the [support forums](https://wordpress.org/support/plugin/fb-account-kit-login) at wordpress.org.

Like Facebook Account Kit Login plugin? Consider leaving a [5 star review](https://wordpress.org/support/plugin/fb-account-kit-login/reviews/?rate=5#new-post).

## Installation

### From within WordPress
1. Visit 'Plugins > Add New'.
1. Search for 'Facebook Account Kit Login'.
1. Activate Facebook Account Kit Login from your Plugins page.
1. Go to "after activation" below.

### Manually
1. Upload the `fb-account-kit-login` folder to the `/wp-content/plugins/` directory.
1. Activate Facebook Account Kit Login plugin through the 'Plugins' menu in WordPress.
1. Go to "after activation" below.

### After activation
1. After activation go to 'Account Kit' from Side Panel.
1. Properly configure plugin settings and save changes.

### Frequently Asked Questions

#### What is Facebook Account Kit?

Facebook Account Kit is a quick and easy way to log in to new apps using just your email address or phone number without any password. It helps you avoid creating another new username and password for every app you want to try. In addition, Account Kit doesn't need a Facebook account for you to log in to an app. Even if you have a Facebook account, you won't have to share information directly from your Facebook Profile to log in to apps with Account Kit.

#### Do I need a Facebook account to use Account Kit?

No, you don't need a Facebook account to log in to apps with Account Kit.

#### How much the SMS costs?

Facebook provides it for free.

#### Does Account Kit work in my country?

Account Kit works with [233 country codes](https://developers.facebook.com/docs/accountkit/languagescountries/) and in over [48 languages](https://developers.facebook.com/docs/accountkit/languages).

#### Is there any link between my Facebook Account?

Facebook account and the account kit authentication is fully separated and there is no connection between your facebook account and account kit.

#### How to migrate from DIGITS plugin?

Migration from DIGITS plugin is very easy. If the username of your user is their phone number which is created by DIGITS plugin, then you can migrate from DIGITS to this plugin. Suppose your have 5 users and their country codes are +91, +880, +1, +856 and +86. Then you need to just add this code snippets to the end of your active theme's functions.php file:

`add_filter( 'fbak/custom_phone_number_format', 'fbak_add_digit_phone_support' );
function fbak_add_digit_phone_support( $phone ) {
    return str_replace( array( '91', '1' ), '', $phone ); // country codes without + sign
}`

#### The plugin isn't working or have a bug?

Post detailed information about the issue in the [support forum](https://wordpress.org/support/plugin/fb-account-kit-login) and I will work to fix it.

## Changelog
[View Changelog](CHANGELOG.md)
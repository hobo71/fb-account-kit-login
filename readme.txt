=== Passwordless Login with SMS & Email - Facebook Account Kit ===
Contributors: Infosatech
Tags: login, passwordless login, facebook, passwordless login woocommerce, account kit, register, no password, auto login
Requires at least: 4.7
Tested up to: 5.2
Stable tag: 1.1.3
Requires PHP: 5.6
Donate link: https://www.paypal.me/iamsayan/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

🔥 The easiest and most secure solution for login or register to WordPress by using SMS or Email Verification without any password using Facebook's Secure Authentication. Fully integrated with WooCommerce.

== Description ==

The Facebook Account Kit Login plugin brings a lightweight, secure, flexible, free and easy way to configure Password Less Login to WordPress website. This plugin helps to easily login or register to WordPress by using SMS on Phone or WhatsApp or Email Verification without any password. You can customize every setting of this plugin in the admin dashboard.

> GDPR compliant: does not collect any user data or does not send any data to any 3rd party website

### Features

 * **Login with SMS** (Phone).
 * **Login with WhatsApp**.
 * **Login with Email**.
 * **Full WooCommerce Support**.
 * Totally **Free of Cost SMS Service**.
 * **Shortcode** Compatible.
 * Dedicated **Widget**.
 * **Compatible with Jetpack** Login.
 * **Compatible with Custom Login URL**.
 * **Easy Migration** from **DIGITS** Plugin.
 
### This is how it works:

* Instead of asking users for a username and password when they try to log in to your website, it simply asks them for their phone number or email.
* Account Kit servers send an SMS with a confirmation code to the phone number (or WhatsApp Account) or send an email with a confirmation link to the email address to continue the login.
* If users fail to receive the SMS code, it offers two other methods that people can choose from the Phone call or Facebook notification.
* The SDK verifies the SMS confirmation code or monitors the status of the confirmation email. Account Kit may also verify the phone number directly without sending an SMS code.
* After successful verification of that authentication this plugin creates the log in WordPress cookie, successfully authenticating the user if the user alredy exists. Otherwise it will create a new user which depends upon plugin settings.

For more information about Facebook Account Kit please [click here](https://developers.facebook.com/docs/accountkit/overview).

#### Plugin Demo

> For Demo Login: [Click Here](https://demo.sayandatta.com/login)

#### Compatibility

* This plugin is fully compatible with WordPress Version 4.7 and beyond and also compatible with any WordPress theme.

#### Support

* Community support via the [support forums](https://wordpress.org/support/plugin/fb-account-kit-login) at WordPress.org.

#### Contribute
* Active development of this plugin is handled [on GitHub](https://github.com/iamsayan/fb-account-kit-login/).
* Feel free to [fork the project on GitHub](https://github.com/iamsayan/fb-account-kit-login/) and submit your contributions via pull request.

Like Facebook Account Kit Login plugin? Consider leaving a [5 star review](https://wordpress.org/support/plugin/fb-account-kit-login/reviews/?rate=5#new-post).

== Installation ==

1. Visit 'Plugins > Add New'
1. Search for 'Facebook Account Kit Login' and install it.
1. Or you can upload the `fb-account-kit-login` folder to the `/wp-content/plugins/` directory manually.
1. Activate Facebook Account Kit Login from your Plugins page.
1. After activation go to 'Account Kit' Option from Dashboard Menu.
1. Configure settings according to your need and save changes.

== Frequently Asked Questions ==

= What is Facebook Account Kit? =

Facebook Account Kit is a quick and easy way to log in to new apps using just your email address or phone number without any password. It helps you avoid creating another new username and password for every app you want to try. In addition, Account Kit doesn't need a Facebook account for you to log in to an app. Even if you have a Facebook account, you won't have to share information directly from your Facebook Profile to log in to apps with Account Kit.

= Do I need a Facebook account to use Account Kit? =

No, you don't need a Facebook account to log in to apps with Account Kit.

= How much the SMS costs? =

Facebook provides it for free.

= Does Account Kit work in my country? =

Account Kit works with [233 country codes](https://developers.facebook.com/docs/accountkit/languagescountries/) and in over [48 languages](https://developers.facebook.com/docs/accountkit/languages).

= Is there any link between my Facebook Account? =

Facebook account and the account kit authentication is fully separated and there is no connection between your Facebook account.

= How to migrate from DIGITS plugin? =

Migration from DIGITS plugin is very easy if you were using OTP login in DIGITS. Just go to plugin settings > SMS Login > Handle Country Codes > select remove country codes and save changes.

== Screenshots ==

1. Login Page
2. SMS Login Screen
3. Email Login Screen
4. Default Login Form
5. Connected via Phone or Email with an Account
6. General Settings
7. SMS Login Settings
8. Email Login Settings
9. Display Settings
10. WooCommerce Settings
11. WooCommerce My Account
12. WooCommerce Checkout Page
13. WooCommerce Login Form
14. WooCommerce Profile Section
15. Others Settings
16. How it Works

== Changelog ==

*Thank you to everyone who shares feedback for Facebook Account Kit Login!*

If you like Facebook Account Kit Login, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/fb-account-kit-login/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!

= 1.1.3 =
Release Date: May 22, 2019

* Added: Permanent Notice dismiss link.
* Fixed: WooCommerce Customer Account Password Change problem since previous commit.

= 1.1.2 =
Release Date: May 20, 2019

* Added: An option to show automatically generated password in account details section for WooCommerce Customers.

= 1.1.0 =
Release Date: May 18, 2019

* Added: A filter `fbak/auto_generated_user_password` to disable autogenerated password for newly registered user and password would be same as username.
* Improved: Now this plugin automatically removes the subdirectory path from auto generated fake email address for newly registered users.
* Imporved: The Phone number based username verification system to avoid duplicate account creation.
* Improved: Endpoint Auto Rewrite mechanism.

= Other Versions =

* View the <a href="https://plugins.svn.wordpress.org/fb-account-kit-login/trunk/changelog.txt" target="_blank">Changelog</a> file.

== Upgrade Notice ==

= 1.1.0 =
In this release, Performance of this plugin has been improved.
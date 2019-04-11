# Changelog
All notable changes to this project will be documented in this file.

## 1.0.9
Release Date: April 11, 2019

* Added: A filter `fbak/custom_phone_number_format` to customize the phone number format which was used by **DIGITS** plugin to create WordPress Accounts.
* Added: A message to the users that shows until facebook account kit will authenticate them.
* Tweak: This plugin now automatically regenerate permalinks if any changes made in account kit endpoint url.
* Tweak: Changed some plugin settings label.
* Fixed: Conflict with Bootstrap CSS Class.
* Fixed: Missing HTML Tags in Admin Notice.

## 1.0.8
Release Date: April 8, 2019

* Tweak: Reduced plugin size.
* Removed: Some JS Files.
* Fixed: Some JS errors.

## 1.0.7
Release Date: April 6, 2019

* Tweak: Added a notice if a user account is not linked with Facebook Account Kit.
* Fixed: Some JS errors in Plugin Settings Page.
* Fixed: Delete Account option in user profile does not really disconnect from Account Kit.
* Fixed: CSS issue in WooCommerce register form.

## 1.0.6
Release Date: April 2, 2019

* Added: Complete WooCommerce Support.
* Removed: Some unwanted codes.

## 1.0.5
Release Date: March 21, 2019

* Improved: Admin UI.

## 1.0.4
Release Date: March 15, 2019

* Added: WooCommerce Support.
* Added: Account Kit Login Widget.
* Added: `fbak-sms-login` class for SMS Login and `fbak-email-login` class for Email Login from Nevigation Menu directly.
* Added: An option to disacle Account Kit Login on WordPress Login Page.
* Tweak: Now Account Kit SDK Loaded asyncronously to imporve page loading speed.

## 1.0.3

* Added: An option to redirect on custom page after a successful login.
* Added: An settings to set custom error text if an unregistered user tries to login to website.
* Tweak: Now Administrator can link phone to email to the other existing account from their account.
* Tweak: Now every login via this plugin will be treated with wp_login action.
* Added: Some filters for future releases of this plugin.

## 1.0.2

* Fixed: Shortcode Issue.

## 1.0.1

* Fixed: Image Paths.
* Fixed: Localization strings.

## 1.0.0

* Initial release.

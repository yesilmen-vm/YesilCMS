<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*Notification Title Lang*/
$lang['notification_title_success'] = 'Success';
$lang['notification_title_warning'] = 'Warning';
$lang['notification_title_error']   = 'Error';
$lang['notification_title_info']    = 'Information';

/*Notification Message (Login/Register) Lang*/
$lang['notification_username_empty']                   = 'Username is empty';
$lang['notification_account_not_created']              = 'The account could not be created. Check the data and try again.';
$lang['notification_email_empty']                      = 'Email is empty';
$lang['notification_password_empty']                   = 'Password is empty';
$lang['notification_user_error']                       = 'The username or password is incorrect. please try again!';
$lang['notification_recaptcha_error']                  = 'Error verifying reCAPTCHA, please try again!';
$lang['notification_email_error']                      = 'The email or password is incorrect. please try again!';
$lang['notification_check_email']                      = 'The username or email is incorrect. please try again!';
$lang['notification_checking']                         = 'Checking...';
$lang['notification_redirection']                      = 'Connecting to your account...';
$lang['notification_new_account']                      = 'New account created. redirecting to login...';
$lang['notification_email_sent']                       = 'Email sent. please check your email...';
$lang['notification_account_activation']               = 'Email sent. please check your email for activate your account.';
$lang['notification_captcha_error']                    = 'Please check the captcha';
$lang['notification_password_lenght_error']            = 'Wrong password length. please use a password between 5 and 16 characters';
$lang['notification_account_already_exist']            = 'This account already exists';
$lang['notification_password_not_match']               = 'Passwords do not match';
$lang['notification_same_password']                    = 'The password is the same.';
$lang['notification_currentpass_not_match']            = 'Old Password do not match';
$lang['notification_usernamepass_not_match']           = 'The password do not match for this username';
$lang['notification_used_email']                       = 'Email in use';
$lang['notification_email_not_match']                  = 'Email do not match';
$lang['notification_username_not_match']               = 'Username do not match';
$lang['notification_expansion_not_found']              = 'Expansion not found';
$lang['notification_recovery_email_sent_success']      = 'Information about the password reset process has been sent to your e-mail.';
$lang['notification_recovery_email_sent_fail']         = 'Password reset failed, please try again.';
$lang['notification_recovery_token_valid_success']     = '<strong>Password changed successfully. </strong>Please check your email for new credentials to login.';
$lang['notification_recovery_token_valid_fail']        = 'Invalid email or token is invalid, used or expired.';
$lang['notification_recovery_token_expired_fail']      = 'Your token has expired, please make new request.';
$lang['notification_activation_email_sent_success']    = '<strong>Account created successfully.</strong> Please check your email to activate your account. You can login to your Site account to check your activation status.';
$lang['notification_activation_email_sent_fail']       = 'Account created successfully but there was an error sending Activation Email, please login to your account and request new one.';
$lang['notification_activation_email_resent_success']  = '<strong>New activation email sent successfully.</strong> Please check your email to activate your account.';
$lang['notification_activation_email_resent_fail']     = 'There was a problem sending Activation Email, please request new one below.';
$lang['notification_activation_token_valid_success']   = '<strong>Account Activated.</strong> Now you can sign in with your account.';
$lang['notification_activation_token_valid_fail']      = 'The activation key provided is not valid.';
$lang['notification_activation_not_found_fail']        = 'Something went wrong. Possibly invalid email or token is invalid, used or expired.';
$lang['notification_activation_token_expired_success'] = 'Your activation token <strong>has already been used once</strong>, you should be able to login.';
$lang['notification_activation_token_expired_fail']    = 'Your token has expired, please login to your account and make new request.';

/*Notification Message (General) Lang*/
$lang['notification_email_changed']                  = 'The email has been changed.';
$lang['notification_username_changed']               = 'The username has been changed.';
$lang['notification_password_changed']               = 'The password has been changed.';
$lang['notification_avatar_changed']                 = 'The avatar has been changed.';
$lang['notification_wrong_values']                   = 'The values are wrong';
$lang['notification_select_type']                    = 'Select a type';
$lang['notification_select_priority']                = 'Select a priority';
$lang['notification_select_category']                = 'Select a Category';
$lang['notification_select_realm']                   = 'Select a Realm';
$lang['notification_select_character']               = 'Select a Character';
$lang['notification_select_item']                    = 'Select a Item';
$lang['notification_report_created']                 = 'The report has been created.';
$lang['notification_title_empty']                    = 'Title is Empty';
$lang['notification_description_empty']              = 'Description is empty';
$lang['notification_name_empty']                     = 'Name is empty';
$lang['notification_id_empty']                       = 'id is empty';
$lang['notification_reply_empty']                    = 'Reply is empty';
$lang['notification_general_error']                  = 'Something went wrong.';
$lang['notification_reply_created']                  = 'Reply has been sended.';
$lang['notification_reply_updated']                  = 'Reply has been updated.';
$lang['notification_reply_deleted']                  = 'Reply has been deleted.';
$lang['notification_topic_created']                  = 'The topic has been created.';
$lang['notification_donation_successful']            = 'The donation has been successfully completed, check your donor points in your account.';
$lang['notification_donation_canceled']              = 'The donation has been canceled.';
$lang['notification_donation_error']                 = 'The information provided in the transaction does not match.';
$lang['notification_store_chars_error']              = 'Select your character in each item.';
$lang['notification_store_item_insufficient_points'] = 'You do not have enough points to buy.';
$lang['notification_store_item_purchased']           = 'The items has been purchased, please check your mail in-game.';
$lang['notification_store_item_added']               = 'The selected item has been added to your cart.';
$lang['notification_store_item_removed']             = 'The selected item has been removed from your cart.';
$lang['notification_store_cart_error']               = 'The cart update failed, please try again.';

/*Notification Message (Admin) Lang*/
$lang['notification_changelog_created'] = 'The changelog has been created.';
$lang['notification_changelog_edited']  = 'The changelog has been edited.';
$lang['notification_changelog_deleted'] = 'The changelog has been deleted.';
$lang['notification_forum_created']     = 'The forum has been created.';
$lang['notification_forum_edited']      = 'The forum has been edited.';
$lang['notification_forum_deleted']     = 'The forum has been deleted.';
$lang['notification_category_created']  = 'The category has been created.';
$lang['notification_category_edited']   = 'The category has been edited.';
$lang['notification_category_deleted']  = 'The category has been deleted.';
$lang['notification_menu_created']      = 'The menu has been created.';
$lang['notification_menu_edited']       = 'The menu has been edited.';
$lang['notification_menu_deleted']      = 'The menu has been deleted.';
$lang['notification_news_deleted']      = 'The news has been deleted.';
$lang['notification_page_created']      = 'The page has been created.';
$lang['notification_page_edited']       = 'The page has been edited.';
$lang['notification_page_deleted']      = 'The page has been deleted.';
$lang['notification_realm_created']     = 'The realm has been created.';
$lang['notification_realm_edited']      = 'The realm has been edited.';
$lang['notification_realm_deleted']     = 'The realm has been deleted.';
$lang['notification_slide_created']     = 'The slide has been created.';
$lang['notification_slide_edited']      = 'The slide has been edited.';
$lang['notification_slide_deleted']     = 'The slide has been deleted.';
$lang['notification_item_created']      = 'The item has been created.';
$lang['notification_item_edited']       = 'The item has been edited.';
$lang['notification_item_deleted']      = 'The item has been deleted.';
$lang['notification_top_created']       = 'The top item has been created.';
$lang['notification_top_edited']        = 'The top item has been edited.';
$lang['notification_top_deleted']       = 'The top item has been deleted.';
$lang['notification_topsite_created']   = 'The topsite has been created.';
$lang['notification_topsite_edited']    = 'The topsite has been edited.';
$lang['notification_topsite_deleted']   = 'The topsite has been deleted.';
$lang['notification_timeline_created']  = 'The Timeline item has been created.';
$lang['notification_timeline_edited']   = 'The Timeline item has been edited.';
$lang['notification_timeline_deleted']  = 'The Timeline item has been deleted.';
$lang['notification_settings_updated'] = 'The settings have been updated.<br>Page will reload now.';
$lang['notification_module_enabled']   = 'The module has been enabled.';
$lang['notification_module_disabled']  = 'The module has been disabled.';
$lang['notification_migration']        = 'The settings have been set.';

$lang['notification_donation_added']   = 'Added donation';
$lang['notification_donation_deleted'] = 'Deleted donation';
$lang['notification_donation_updated'] = 'Updated donation';
$lang['notification_points_empty']     = 'Points is empty';
$lang['notification_tax_empty']        = 'Tax is empty';
$lang['notification_price_empty']      = 'Price is empty';
$lang['notification_incorrect_update'] = 'Unexpected update';

$lang['notification_route_inuse'] = 'The route is already in use please choose another one.';

$lang['notification_account_updated']    = 'The account has been updated.';
$lang['notification_dp_vp_empty']        = 'DP/VP is empty';
$lang['notification_account_banned']     = 'The account has been banned.';
$lang['notification_reason_empty']       = 'Reason is empty';
$lang['notification_account_ban_remove'] = 'The ban in the account has been removed.';
$lang['notification_rank_empty']         = 'Rank is empty';
$lang['notification_rank_granted']       = 'The rank has been granted.';
$lang['notification_rank_removed']       = 'The rank has been deleted.';

$lang['notification_cms_updated']         = 'The CMS has been updated';
$lang['notification_cms_update_error']    = 'The CMS could not be updated';
$lang['notification_cms_not_updated']     = 'A new version has not been found to update';
$lang['notification_cms_update_disabled'] = 'This feature has been temporarily disabled.';

$lang['notification_select_category'] = 'It is not subcategory';
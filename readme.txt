=== Smart User Slug Hider ===
Contributors: petersplugins
Tags: author, authors, user, users, url, link, security, secure, login, permalink, authorlink, author link, userlink, user link, authorpage, author page, classicpress
Requires at least: 4.0
Tested up to: 6.3
Stable tag: 4.0.6
Requires PHP: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hide usernames in Author Pages URLs to enhance Security

== Description ==

The Smart User Slug Hider Plugin hides usernames in Author Pages URLs to enhance Security

== Retired Plugin ==

Development, maintenance and support of this plugin has been retired in october 2023. You can use this plugin as long as is works for you. 

There will be no more updates and I won't answer any support questions. Thanks for your understanding.

Feel free to fork this plugin.

== Usage ==

The plugin automatically replaces user names with 16 digits coded strings. There are no settings. Just install and forget. Deactivating the Plugin restores the default WordPress behavior.

== Why use this plugin? ==

WordPress uses the pattern `example.com/author/name` for author page URLs where `name` represents the users login name. 

This means that the <strong>login names from all your users are publicly visible</strong> which is a serious security flaw. 

The Smart User Slug Hider Plugin changes all author page URLs from e.g. `example.com/author/john` to something like `example.com/author/e9e716def73f76ac`. 

The codes are generated automatically and its impossible to make conclusions about the user names. The WordPress default URLs will cause a 404 (not found) error. 

== Shortcodes ==

The plugin adds three shortcodes you can use in your posts:

* `[smart_user_slug]` - the user slug of the post author - e.g. e9e716def73f76ac
* `[smart_user_url]` - the url of the post author's profile page - e.g. example.com/author/e9e716def73f76ac
* `[smart_user_link]` - adds a link to the post author's profile page

== Theme Functions ==

The plugin adds two functions that can be used in theme files:

* `get_smart_user_slug( $author_id )` to **get** the user slug for the author - the parameter $author_id is optional, if omitted the author`s ID of the current post is used
* `the_smart_user_slug( $author_id )` to **display** the user slug for the author - the parameter $author_id is optional, if omitted the author`s ID of the current post is used

== Plugin Privacy Information ==

* This plugin does not set cookies
* This plugin does not collect or store any data
* This plugin does not send any data to external servers

== Changelog ==

= 4.0.6 (2024-04-17) URGENT BUGFIX =
* Bugfix after Cleanup

= 4.0.5 (2024-04-16) CLEANUP =
* Cleanup

= 4.0.4 (2022-10-05) FINAL VERSION =
* removed all links to webiste
* removed request for rating

= 4.0.3 (2023-03-26) =
* do not rely on SERVER_ADDR only (see [support ticket](https://wordpress.org/support/topic/warning-undefined-array-key-server_addr/))

= 4.0.2 (2022-05-28) =
* just cosmetics
* Plugin Foundation updated to PPF08

= 4.0.1 (2020-08-23) =
* minor UI adjustments

= 4.0.0 (2019-12-29) =
* remove user class from body tag ([See here](https://wordpress.org/support/topic/author-still-in-source-code/))
* always use Future Proof Mode, old method removed
* rewritten based on my Plugin Foundation PPF03

= 3 (2018-12-31) =
* Future Safe Mode renamed to Future Proof Mode
* automatically activate Future Proof Mode if mcrypt is not availabe
* code improvement
* UI improvements
* changed capability to manage_options to display admin page

= 2 (2018-03-14) =
* Future Safe Mode 

= 1.5 (2017-11-16) =
* faulty display in WP 4.9 fixed

= 1.4 (2017-07-15) =
* fix for BuddyExtender plugin

= 1.3 (2017-07-17) =
* BuddyPress compatibility 
* redesigned admin interface
* code improvement

= 1.2 (2016-10-04) =
* Shortcodes added
* Theme Functions added

= 1.1 (2016-06-30) =
* Code optimization
* Plugin info page added

= 1.0 (2014-10-02) =
* Initial Release

== Upgrade Notice ==

= 4.0.2 =
do not rely on SERVER_ADDR only

= 4.0.2 =
just cosmetics

= 4.0.1 =
minor UI adjustments

= 4.0.0 =
always use Future Proof Mode, old method removed

= 3 =
prepared for PHP 7.2+ without mcrypt extension

= 2 =
Future Safe Mode

= 1.5 =
faulty display in WP 4.9 fixed

= 1.4 =
fix for BuddyExtender plugin

= 1.3 =
BuddyPress compatibility

= 1.2 =
Shortcodes and Theme Functions added

= 1.1 =
Code optimization, Plugin info page added, no functional changes
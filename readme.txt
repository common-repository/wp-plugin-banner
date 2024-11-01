=== WP Plugin Banner ===
Contributors: cklosows
Tags: plugin, banner, wordpress.org
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 1.0.2
Donate link: https://chrisk.io/i/donate
License: GPLv2 or later

Easily display the banner image and title of a plugin on the WordPress.org Directory within your WordPress posts.

== Description ==

This plugin adds the shortcode `[plugin_banner]`, so you can quickly and easily display the banner image from the WordPress.org repository for the given plugin slug.

For example, to display the banner image of this plugin you would use:
`[plugin_banner slug="wp-plugin-banner"]`

By default images are not linked but if you add `link=true` into the argument list the image will be clickable and take the visitor the plugin page in the WordPress.org plugin directory.

The image status and plugin title is all pulled dynamically from the WordPress.org API, this data is cached for 1 week to help with performance.

List of supported attributes:
`slug - The slug to the WordPress plugin (found in the URL in the WordPress.org plugin directory)`
`link - If the image should be linked to the plugin in the WordPress.org directory. Options: true|false (default)`
`title_wrapper - What HTML element to wrap the title of the plugin with. Supported: h2 (default), h3, h4, h5, em, strong, span, p`

== Changelog ==
= 1.0.2 =
* Fix: Fixing CSS for browers that don't fix markup

= 1.0.1 =
* Fix: Output buffer issue causing banners to all stack at the top of the content

= 1.0 =
* New: Initial release

== Screenshots ==
None at this time

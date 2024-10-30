=== Plugin Name ===
MetaShortcode

Contributors:      codecide
Plugin Name:       MetaRedirect
Plugin URI:        https://plugins.codecide.net/mts
Tags:              shortcode, custom fields, custom-fields
Author URI:        https://plugins.codecide.net/
Author:            codecide
Donate link:       https://www.redcross.org/donate/donation
Requires PHP:      5.4
Requires at least: 4.5
Tested up to:      5.3.1
Stable tag:        1.0.1
Version:           1.0.1

== Description ==
MetaShortcode is a lightweight module that allows users to display the value any custom (meta) field on the front-end using a simple shortcode.

The module can be disabled from the settings page. *Important:* The module is inactive by default. If you leave the _Enabled_ box unchecked, the plugin will have no effect at all. 

Pattern: [msc custom_field_name]
Shortcodes with the pattern above will display the value stored in _custom field name_ in the post.

Alternative pattern: {msc custom_field_name}
An alternative shortcode pattern, which replaces square brackets with curly braces, can be activated if needed. This can be helpful if you need to use a custom field value as an attribute inside an existing shortcode definition. For example:

*invalid*: [shortcode name="example" url="[msc custom_url_field]"]...[/shortcode]
*valid*: [shortcode name="example" url="{msc custom_url_field}"]...[/shortcode]

Note that unless you have a specific need for nesting shortcodes as attributes, you should leave this option disabled in the settings to avoid the slight (and unnecessary) performance degradation that the additional content filter involves.

== Installation ==

Install and configure the plugin as needed in the MetaShortcode settings page: /wp-admin/admin.php?page=metashortcode_settings. 

_Note_ that the plugin is not enabled by default. Check the _Enabled_ box in the settings page to turn it on.

General information about installing WordPress plugins can be found [here](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== Upgrade Notice ==
= 1.0.1 =
Critical bug fix to enable compatibility with visual editors.

== Screenshots ==
1. The configuration screen.

== Changelog ==

= 1.0 =
* Initial release.
= 1.0.1 =
* Fixed critical bug: replaced metashortcode pattern attribute, changed from '*' to 'msc'

== Roadmap ==

* Allow customizing the shortcode identifier

== Frequently Asked Questions ==
= Is it really free? =
Yes, this plugin is completely free. 

== Donations ==
None needed. 

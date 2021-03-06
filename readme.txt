=== PageBatcher ===
Contributors: kevinfreitas
Donate link: http://frayd.us/wordpress-plugins/pagebatcher/
Tags: productivity, wordpress management, pages, menus, bulk
Requires at least: 3.0.1
Tested up to: 4.2
Stable tag: 1.0.20150724
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create a hierarchy of pages in bulk along with an accompanying menu.

== Description ==

Populating a WordPress website with dozens of pages can be a pain but that's where PageBatcher comes in! Create pages 
in bulk with a simple text list. You can choose to publish those pages or leave them as drafts and also have
your new page hierarchy automatically create a nav menu with the same structure. Here's how it works:

* In your WordPress admin, under **Pages**, choose **Add New Batch**
* Enter or paste in a list of page titles *(one page title per line | use dashes to create a page hierarchy | blank lines are ignored)*
* Choose whether or not to publish your new pages
* Create a new menu that will include all your new pages in their hierarchy
* Click **Save Changes**

== Installation ==

1. Upload `frayd-pagebatcher` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Find PageBatcher in your WordPress admin, under **Pages** > **Add New Batch**

== Frequently Asked Questions ==

= How many pages can I create? =

As many as you want. Though, if you get up into the hundreds you *may* run into server timeout issues depending on your hosting environment.

= Do blank lines matter in my list of new pages? =

Nope, they'll be ignored.

= Can I specify templates for my new pages? =

Not yet. Currently, the `default` template will be used for each of your new pages.

= What's next for PageBatcher features? =

We'll add ways to assign different templates, set per-page publish status, assign the front page, and more soon. Stay tuned!

== Screenshots ==

1. Form used to create your new batch of pages.

== Changelog ==

= 1.0.20150723 =
No changes -- version 1.0!

== Upgrade Notice ==

= 1.0.20150723 =
No upgrade necessary -- we're only just now 1.0!
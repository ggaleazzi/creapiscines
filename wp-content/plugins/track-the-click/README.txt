=== Track The Click ===
Contributors: danfoster, gastronomicslc
Tags: referral, link, seo, track outbound link, click tracking, affiliate
Tested up to: 6.6
Stable tag: 0.4.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Track how many clicks your links get.

== Description ==

= Tracking clicks =

Track The Click helps website owners better understand how site visitors interact with links on their site.  The main function of the plugin is to track clicks as they occur on a WordPress website in real time.  Track The Click uses client side Javascript to record all outbound click events and record them to a local database.  Once the data is recorded, Track The Click offers a number of views, to interpret and analyze the click behavior.

= Why track outbound clicks? =

**Improve affiliate link performance**

By tracking and recording where and how your visitors click and leave your site, you can optimize your website and pages for increased affiliate revenue.

**Demonstrate advertising value**

If you have website sponsors or advertisers, the external click data can be invaluable.  Your clients might not be sophisticated enough to check their own website analytics, so you can provide them with the data yourself; how well did the ad perform, how many users left your sites for theirs through the ad in question?  From that they can develop a Return On Investment number for the cost of your campaign.

**See where your site visitors go next**

If the bulk of your visitors are leaving your website for a specific destination, maybe there's room for extra content on your own site.  Why send your traffic elsewhere if you can meet your visitors' needs yourself?

**See which pages are driving external traffic**

Using the Track The Click plugin you can review which of your website's posts and pages are sending the most external traffic. These may be potential sources of new affiliate revenue.

= Why Track The Click? =

**Simple. Easy. Quick.**

Once you install the plugin you can begin tracking clicks within 60 seconds.  You don't need to subscribe to our site, our newsletter, or any other signup form.  Download, install, start.

**Made by bloggers for bloggers**

We made the plugin because we were also frustrated by the lack of ways of track clicks on our sites.  Track The Click is the result of that frustration.

**Free and lightweight**

The basics of the plugin are 100% free.  The plugin is also made with site speed first and foremost in mind. We won't add 1000s of lines of bloated code to your website.

**Data insights**

We're constantly working to show you as much data around your link click as possible.  What time of the day was the click?  What page did the click leave on, what was the anchor of that click?  We're always looking to add more and more data.

= Core features =

The basic functions of the plugin are free, these include:

* Unlimited link click tracking
* Local tracking and reporting of clicks
* No limit on storage of link click data
* Track outbound link clicks in all areas of your site: header, body, widgets and footer
* Two standard views

The free version comes with two views.  Each view represents a different way to see your outbound link profile.  The two free views are the link and domain view as follows.  The standard free view is the link view.  For every outbound click on your WordPress website this shows:

* Link URL
* Anchor text of the link clicked
* Post or page on your website where the link occurred
* Total of link clicks in specified time period

== Installation ==

1. Log into your WordPress admin
1. Click Plugins
1. Click Add New Plugin
1. Search for Track The Click
1. Click Install Now under "Track The Click"
1. Activate the plugin

== Changelog ==

= 0.4.0 =
* *Pro* Correct logged in user option label
* Correct image URL in CSS
* Update API to v3 and change /hit to /click
* Remove frontend click count display on uninstall
* Remove Google Analytics integration

= 0.3.18 =
* Track middle button clicks
* Add data-no-optimize to scripts for compatibility with [LiteSpeed Cache](https://wordpress.org/plugins/litespeed-cache/)

= 0.3.17 =
* *Pro* Exclude clicks on elements with class no-ttc

= 0.3.16 = 
* Track middle button clicks, too

= 0.3.15 =
* Prevent error when money parameter missing from result

= 0.3.14 =
* *Pro* CSV download of reports

= 0.3.13 =
* Add data-noptimize to scripts for compatibility with [Autoptimize](https://en-gb.wordpress.org/plugins/autoptimize/)

= 0.3.12 =
* Last part of SQL injection vulnerability fix

= 0.3.11 =
* Fix SQL injection vulnerability

= 0.3.10 =
* *Pro* Fix updating from EDD
* *Pro* Move EDD functionality into pro files

= 0.3.9 =
* *Pro* Pro licensing
* *Pro* Optionally don't track clicks from admin users
* *Pro* Optionally don't track clicks from logged in users

= 0.3.8 =
* Check for existence of Pro plugin files before including them

= 0.3.7 =
* Click graph design refresh - thanks [Pep](https://www.yosoypep.com/)!

= 0.3.6 =
* Allow setting of data retention period, delete data older than setting

= 0.3.5 =
* Include Google Analytics JavaScript file only if any GA functionality is enabled

= 0.3.4 =
* Handle empty date format

= 0.3.3 =
* Don't exclude home URL by default

= 0.3.2 =
* Add display of click counts by links to frontend

= 0.3.1 =
* Move Google Analytics click listener to DOM-wide
* Include Google Analytics functions if any GA functionality is enabled

= 0.3.0 =
* Listen for clicks across the DOM rather than on each individual link

= 0.2.19 =
* Set explicit default timestamp for hits

= 0.2.18 =
* *Pro* Tag money links based on patterns

= 0.2.17 =
* *Pro* Viewing of money link data

= 0.2.16 =
* Stop JS to do with graph generation from running if not on graph screen
* *Pro* Money link tagging

= 0.2.15 =
* Move graph settings into dropdowns

= 0.2.14 =
* Put site address in exclusion instead of hardcoding

= 0.2.13 =
* Add by-page data view

= 0.2.12 =
* Remove plugin data on uninstallation

= 0.2.11 =
* First public version
